<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api as RazorpayApi;

class PaymentController extends Controller
{
    /**
     * Create a Razorpay order for the given cart.
     *
     * @param  array  $cartItems  [{est_id, quantity, selectedColor, selectedSize}]
     * @param  float  $subtotal   subtotal in rupees (server-calculated from DB)
     * @param  float  $shipping   shipping cost in rupees
     * @param  float  $discount   discount in rupees
     * @param  float  $total      final total in rupees
     */
    public function createOrder(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array',
            ]);

            // Compute exact total from server-side (items looked up from DB)
            $subtotal = $request->input('subtotal', 0);
            $shipping = $request->input('shipping', 199);
            $discount = $request->input('discount', 0);
            $total = $subtotal + $shipping - $discount;

            $razorpay = new RazorpayApi(env('RAZORPAY_KEY_ID', ''), env('RAZORPAY_KEY_SECRET', ''));

            $order = $razorpay->order->create([
                'receipt'     => 'ORD-' . strtoupper(substr(uniqid(), 0, 10)),
                'amount'      => (int) round($total * 100),
                'currency'    => 'INR',
                'payment_capture' => 1,
                'notes'       => [
                    'order_type' => 'boutique',
                ],
            ]);

            return response()->json([
                'success' => true,
                'order_id' => $order['id'],
                'amount'   => (int) round($total * 100),
                'currency' => 'INR',
                'receipt'  => $order['receipt'],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Razorpay payment and persist the order to the DB.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'razorpay_order_id' => 'required|string',
            'razorpay_payment_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $keyId = env('RAZORPAY_KEY_ID', '');
        $keySecret = env('RAZORPAY_KEY_SECRET', '');

        // Verify Razorpay signature:
        // HMAC-SHA256(order_id + "|" + payment_id, key_secret) == signature
        $payload = $request->input('razorpay_order_id') . '|' . $request->input('razorpay_payment_id');
        $expectedSignature = hash_hmac('sha256', $payload, $keySecret);

        if (! hash_equals($expectedSignature, $request->input('razorpay_signature'))) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Resolve user_id from active session OR by matching registered user email / phone
        $userId = auth()->id();
        if (!$userId && $request->filled('email')) {
            $existingUser = \App\Models\User::where('email', trim($request->input('email')))->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            }
        }

        // Create order record
        $order = Order::create([
            'order_no' => 'EST-PAY-' . time(),
            'user_id' => $userId,
            'full_name' => $request->input('name', $request->input('full_name', 'Customer')),
            'email' => $request->input('email', ''),
            'phone' => $request->input('phone', ''),
            'address' => $request->input('address', ''),
            'city' => $request->input('city') ?: 'Metropolitan',
            'state' => $request->input('state') ?: 'India',
            'pincode' => $request->input('pincode', ''),
            'subtotal' => $request->input('subtotal', 0),
            'shipping' => $request->input('shipping', 199),
            'discount' => $request->input('discount', 0),
            'total' => $request->input('total', 0),
            'currency' => 'INR',
            'payment_id' => $request->input('razorpay_payment_id'),
            'razorpay_order_id' => $request->input('razorpay_order_id'),
            'signature' => $request->input('razorpay_signature'),
            'status' => 'paid',
            'items' => json_encode($request->input('items', [])),
            'note' => 'Payment via Razorpay',
        ]);

        $referralCode = session('referral_code') ?? $request->input('referral_code');
        $paidItems = $request->input('items', []);
        if (is_string($paidItems)) {
            $decoded = json_decode($paidItems, true);
            $paidItems = is_array($decoded) ? $decoded : [];
        }
        try {
            $this->decrementStock($paidItems);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Stock decrement error: ' . $e->getMessage());
        }
        if (!empty($referralCode)) {
            $this->creditReferralAssociate($order->order_no, $paidItems, $referralCode, $order->full_name);
            session()->forget('referral_code');
        }

        return response()->json([
            'success'    => true,
            'order_id'   => $order->order_no,
            'payment_id' => $request->input('razorpay_payment_id'),
        ], 201);
    }

    /**
     * Place order directly via COD, UPI, or Direct Transfer with Referral Tracking
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string',
            'pincode'        => 'required|string|max:10',
            'items'          => 'required|array|min:1',
            'payment_method' => 'required|string',
        ]);

        $orderNo = 'EST-' . rand(100000, 999999);
        $items = $request->input('items', []);
        // Normalize: frontend may send JSON string -> avoid double-encoding "\"[...]\""
        if (is_string($items)) {
            $decoded = json_decode($items, true);
            $itemsForDb = (json_last_error() === JSON_ERROR_NONE) ? json_encode($decoded) : $items;
            $itemsForReferral = is_array($decoded) ? $decoded : [];
        } else {
            $itemsForDb = json_encode(is_array($items) ? $items : []);
            $itemsForReferral = is_array($items) ? $items : [];
        }
        $subtotal = (float) $request->input('subtotal', 0);
        $shipping = (float) $request->input('shipping', 0);
        $discount = (float) $request->input('discount', 0);
        $total = (float) $request->input('total', $subtotal + $shipping - $discount);

        $referralCode = session('referral_code') ?? $request->input('referral_code');

        // Resolve user_id from active session OR by matching registered user email / phone
        $userId = auth()->id();
        if (!$userId && !empty($request->email)) {
            $existingUser = \App\Models\User::where('email', trim($request->email))
                ->orWhere(function ($q) use ($request) {
                    if ($request->filled('phone')) {
                        $q->where('phone', trim($request->phone));
                    }
                })->first();
            if ($existingUser) {
                $userId = $existingUser->id;
            }
        }

        $order = Order::create([
            'order_no'    => $orderNo,
            'user_id'     => $userId,
            'full_name'   => trim($request->name),
            'email'       => trim($request->email),
            'phone'       => trim($request->phone),
            'address'     => trim($request->address),
            'city'        => trim($request->input('city')) ?: 'Metropolitan',
            'state'       => trim($request->input('state')) ?: 'India',
            'pincode'     => trim($request->pincode),
            'subtotal'    => $subtotal,
            'shipping'    => $shipping,
            'discount'    => $discount,
            'total'       => $total,
            'currency'    => 'INR',
            'payment_id'  => $request->payment_method === 'cod' ? 'COD-PENDING' : 'UPI-CONFIRMED',
            'status'      => 'confirmed',
            'items'       => $itemsForDb,
            'note'        => 'Payment Mode: ' . strtoupper($request->payment_method) . ($referralCode ? ' • Referred by: ' . $referralCode : ''),
        ]);

        // Decrement stock so admin inventory drops by ordered qty (never blocks order)
        try {
            $this->decrementStock($itemsForReferral);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Stock decrement error: ' . $e->getMessage());
        }

        // Credit referral earnings to the sales associate (wrapped in try-catch so order is never blocked)
        if (!empty($referralCode)) {
            try {
                $this->creditReferralAssociate($orderNo, $itemsForReferral, $referralCode, $request->name);
                session()->forget('referral_code');
            } catch (\Throwable $e) {
                // Log exception silently without failing the customer order
                \Illuminate\Support\Facades\Log::error('Referral credit error: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success'  => true,
            'order_id' => $orderNo,
            'message'  => 'Order placed and confirmed successfully!',
        ], 201);
    }

    /**
     * Decrement product size_stock by ordered quantity so admin sees stock drop.
     */
    private function decrementStock(array $items): void
    {
        foreach ($items as $item) {
            if (!is_array($item)) continue;
            $estId = $item['id'] ?? ($item['est_id'] ?? '');
            if (empty($estId)) continue;
            $qty = max(1, (int) ($item['qty'] ?? ($item['quantity'] ?? 1)));
            $size = trim((string) ($item['size'] ?? ''));

            $product = \App\Models\Product::where('est_id', $estId)->orWhere('id', $estId)->first();
            if (!$product) continue;

            $stock = is_array($product->size_stock) ? $product->size_stock : [];
            if (empty($stock)) continue;

            // Match ordered size to a stock key (case-insensitive); else use first available
            $targetKey = null;
            if ($size !== '' && strtolower($size) !== 'standard') {
                foreach ($stock as $k => $v) {
                    if (strtolower(trim((string) $k)) === strtolower($size)) {
                        $targetKey = $k;
                        break;
                    }
                }
            }
            if ($targetKey === null) {
                foreach ($stock as $k => $v) {
                    if ((int) $v > 0) {
                        $targetKey = $k;
                        break;
                    }
                }
                if ($targetKey === null) continue;
            }

            $stock[$targetKey] = max(0, (int) $stock[$targetKey] - $qty);
            $total = array_sum($stock);
            $product->size_stock = $stock;
            $product->sizes = array_keys($stock);
            $product->in_stock = $total > 0;
            $product->save();
        }
    }

    /**
     * Customer requests Exchange for a delivered order (7-day window).
     * Sets status to exchange_requested for admin approval. (Return disabled)
     */
    public function requestReturn(Request $request, $id)
    {
        $request->validate([
            'type'   => 'required|string|in:exchange',
            'reason' => 'required|string|max:50',
            'notes'  => 'nullable|string|max:500',
        ]);

        $order = Order::findOrFail($id);
        $user = $request->user();

        $owns = $user && (
            ($order->user_id && (int) $order->user_id === (int) $user->id) ||
            ($user->email && strtolower(trim((string) $order->email)) === strtolower(trim((string) $user->email))) ||
            ($user->phone && trim((string) $order->phone) === trim((string) $user->phone))
        );
        if (!$owns) {
            return back()->withErrors(['order' => 'This order does not belong to your account.']);
        }

        if (in_array($order->status, ['exchange_requested', 'exchanged', 'cancelled'])) {
            return back()->withErrors(['order' => 'This order has already been requested for exchange or cancelled.']);
        }

        if (!in_array($order->status, ['delivered', 'confirmed', 'shipped', 'paid', 'processing', 'dispatched'])) {
            return back()->withErrors(['order' => 'Exchange can be requested for confirmed, shipped or delivered orders (7-day window).']);
        }

        if ($order->created_at && $order->created_at->lt(now()->subDays(7))) {
            return back()->withErrors(['order' => '7-day exchange window has expired for this order.']);
        }

        $typeLabel = 'Exchange';
        $order->status = 'exchange_requested';
        $note = trim($request->input('notes', ''));
        $order->note = trim(($order->note ? $order->note . ' • ' : '') . "{$typeLabel} requested: {$request->reason}" . ($note ? " ({$note})" : ''));
        $order->save();

        return redirect('/profile')->with('success', "🔄 {$typeLabel} request submitted for Order #{$order->order_no}! Doorstep pickup will be scheduled within 24-48 hours.");
    }

    /**
     * Restore product size_stock when an order is returned / exchanged.
     */
    public static function restoreStockForOrder(Order $order): void
    {
        $items = $order->items;
        for ($i = 0; $i < 3 && is_string($items); $i++) {
            $decoded = json_decode($items, true);
            if (json_last_error() !== JSON_ERROR_NONE) break;
            $items = $decoded;
        }
        if (!is_array($items)) return;

        foreach ($items as $item) {
            if (!is_array($item)) continue;
            $estId = $item['id'] ?? ($item['est_id'] ?? '');
            if (empty($estId)) continue;
            $qty = max(1, (int) ($item['qty'] ?? ($item['quantity'] ?? 1)));
            $size = trim((string) ($item['selectedSize'] ?? ($item['size'] ?? '')));

            $product = \App\Models\Product::where('est_id', $estId)->orWhere('id', $estId)->first();
            if (!$product) continue;

            $stock = is_array($product->size_stock) ? $product->size_stock : [];
            if (empty($stock)) continue;

            $targetKey = null;
            if ($size !== '' && strtolower($size) !== 'standard') {
                foreach ($stock as $k => $v) {
                    if (strtolower(trim((string) $k)) === strtolower($size)) {
                        $targetKey = $k;
                        break;
                    }
                }
            }
            if ($targetKey === null) {
                $targetKey = array_key_first($stock);
            }

            $stock[$targetKey] = (int) $stock[$targetKey] + $qty;
            $product->size_stock = $stock;
            $product->sizes = array_keys($stock);
            $product->in_stock = true;
            $product->save();
        }
    }

    /**
     * Credit associate with referral profit/commission
     */
    private function creditReferralAssociate(string $orderNo, array $items, string $referralCode, string $customerName): void
    {
        $associate = \App\Models\User::where('referral_code', strtoupper($referralCode))->first();
        if (!$associate) return;

        $totalCommission = 0;
        foreach ($items as $item) {
            $estId = $item['id'] ?? ($item['est_id'] ?? '');
            $itemName = $item['name'] ?? 'Handcrafted Garment';
            $itemQty = max(1, (int) ($item['qty'] ?? ($item['quantity'] ?? 1)));
            $itemPrice = (float) ($item['price'] ?? 0);
            $itemTotal = $itemPrice * $itemQty;

            $dbProd = \App\Models\Product::where('est_id', $estId)->orWhere('id', $estId)->first();

            // Option B: Profit margin is difference between Link selling price and base price
            if ($dbProd && $dbProd->price && $itemPrice > (float) $dbProd->price) {
                $commissionEarned = round(($itemPrice - (float) $dbProd->price) * $itemQty, 2);
                $commissionRate = round((($itemPrice - (float) $dbProd->price) / $itemPrice) * 100, 2);
            } else {
                // Fallback to associate's commission rate percentage
                $commissionRate = (float) ($associate->commission_rate ?: 10.00);
                $commissionEarned = round($itemTotal * ($commissionRate / 100), 2);
            }

            \App\Models\ReferralSale::create([
                'associate_id'      => $associate->id,
                'order_no'          => $orderNo,
                'product_name'      => $itemName . ($itemQty > 1 ? " (x{$itemQty})" : ''),
                'sale_amount'       => $itemTotal,
                'commission_rate'   => $commissionRate,
                'commission_earned' => $commissionEarned,
                'customer_name'     => $customerName,
                'status'            => 'approved',
            ]);

            $totalCommission += $commissionEarned;
        }

        if ($totalCommission > 0) {
            $associate->increment('balance', $totalCommission);
            $associate->increment('earnings', $totalCommission);
        }
    }

    /**
     * Get the latest order for a user.
     */
    public function getOrder(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['order' => null], 200);
        }

        $order = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json(['order' => $order], 200);
    }

    /**
     * Validate a coupon code against the MySQL database and calculate dynamic discount.
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code'     => 'required|string',
            'subtotal' => 'nullable|numeric|min:0',
        ]);

        $code     = strtoupper(trim($request->input('code')));
        $subtotal = (float) $request->input('subtotal', 0);

        $coupon = \App\Models\Coupon::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => "Invalid or expired coupon code '{$code}'.",
            ], 422);
        }

        // Check validity date
        if ($coupon->valid_until && $coupon->valid_until->isPast()) {
            return response()->json([
                'success' => false,
                'message' => "Coupon '{$code}' has expired.",
            ], 422);
        }

        // Check minimum order value
        if ($coupon->min_order_value > 0 && $subtotal > 0 && $subtotal < $coupon->min_order_value) {
            return response()->json([
                'success' => false,
                'message' => "Coupon '{$code}' requires a minimum order of ₹" . number_format($coupon->min_order_value) . " (Current cart: ₹" . number_format($subtotal) . ").",
            ], 422);
        }

        // Calculate discount
        $discountAmount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discountAmount = ($subtotal > 0) ? round(($subtotal * (float) $coupon->discount_value) / 100) : 0;
            $discountText   = "{$coupon->discount_value}% OFF";
        } else {
            $discountAmount = min($subtotal > 0 ? $subtotal : (float) $coupon->discount_value, (float) $coupon->discount_value);
            $discountText   = "₹" . number_format($coupon->discount_value) . " FLAT OFF";
        }

        // Increment usage count
        $coupon->increment('usage_count');

        return response()->json([
            'success'         => true,
            'code'            => $coupon->code,
            'title'           => $coupon->title,
            'discount_type'   => $coupon->discount_type,
            'discount_value'  => (float) $coupon->discount_value,
            'discount_amount' => (float) $discountAmount,
            'discount_text'   => $discountText,
            'min_order_value' => (float) $coupon->min_order_value,
            'message'         => "✨ Coupon '{$coupon->code}' applied successfully ({$discountText})!",
        ]);
    }
}