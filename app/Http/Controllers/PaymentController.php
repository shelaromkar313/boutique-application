<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Razorpay\Razorpay;
use Razorpay\Crypto\Hash;

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
        $request->validate([
            'items' => 'required|array',
            'items.*.est_id' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Compute exact total from server-side (items looked up from DB)
        $subtotal = $request->input('subtotal', 0);
        $shipping = $request->input('shipping', 199);
        $discount = $request->input('discount', 0);
        $total = $subtotal + $shipping - $discount;

        $razorpay = new Razorpay(env('RAZORPAY_KEY_ID', ''), env('RAZORPAY_KEY_SECRET', ''));

        $order = $razorpay->orders->create([
            'receipt'     => 'ORD-' . strtoupper(substr(uniqid(), 0, 10)),
            'amount'      => (int) round($total * 100),
            'currency'    => 'INR',
            'payment_capture' => 1,
            'notes'       => [
                'order_type' => 'boutique',
                'items'      => json_encode($request->input('items', [])),
            ],
        ]);

        return response()->json([
            'success' => true,
            'order_id' => $order['id'],
            'amount'   => (int) round($total * 100),
            'currency' => 'INR',
            'receipt'  => $order['receipt'],
        ], 201);
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
        $expectedSignature = Hash::make($payload, $keySecret);

        if (! hash_equals($expectedSignature, $request->input('razorpay_signature'))) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Create order record
        $order = Order::create([
            'order_no' => 'EST-PAY-' . time(),
            'full_name' => $request->input('full_name', 'Customer'),
            'email' => $request->input('email', ''),
            'phone' => $request->input('phone', ''),
            'address' => $request->input('address', ''),
            'city' => $request->input('city', ''),
            'state' => $request->input('state', ''),
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

        return response()->json([
            'success' => true,
            'order_id' => $order->order_no,
            'payment_id' => $request->input('razorpay_payment_id'),
        ], 201);
    }

    /**
     * Get the latest order for a user.
     */
    public function getOrder(Request $request)
    {
        $user = $request->user();
        $order = Order::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$order) {
            return response()->json(['order' => null], 200);
        }

        return response()->json(['order' => $order], 200);
    }
}