<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReferralSale;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminApiController extends Controller
{
    /**
     * Get Overview KPI Stats & Top Metrics
     */
    public function dashboardStats()
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        $ordersCount = Order::count();
        $productsCount = Product::count();
        $inStockCount = Product::where('in_stock', true)->count();
        $customersCount = User::where('role', 'customer')->orWhereNull('role')->count();
        $associatesCount = User::whereIn('role', ['sales_associate', 'sales_executive', 'associate'])->count();
        $totalCommissionPaid = ReferralSale::where('status', 'paid')->sum('commission_earned');

        $recentOrders = Order::latest()->take(5)->get();

        return response()->json([
            'status' => 'success',
            'metrics' => [
                'gross_revenue' => (float) $totalRevenue,
                'total_orders' => $ordersCount,
                'total_products' => $productsCount,
                'in_stock_products' => $inStockCount,
                'total_customers' => $customersCount,
                'total_associates' => $associatesCount,
                'total_commission_paid' => (float) $totalCommissionPaid,
            ],
            'recent_orders' => $recentOrders,
        ]);
    }

    /**
     * Orders List & Details
     */
    public function getOrders(Request $request)
    {
        $query = Order::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate($request->input('per_page', 20));

        return response()->json($orders);
    }

    /**
     * Update Order Status
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|string|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order
        ]);
    }

    /**
     * Customers List
     */
    public function getCustomers(Request $request)
    {
        $customers = User::where('role', 'customer')
            ->orWhereNull('role')
            ->latest()
            ->paginate($request->input('per_page', 20));

        return response()->json($customers);
    }

    /**
     * Sales Associates List & Performance
     */
    public function getAssociates()
    {
        $associates = User::whereIn('role', ['sales_associate', 'sales_executive', 'associate'])
            ->with(['referralSales'])
            ->latest()
            ->get();

        return response()->json($associates);
    }

    /**
     * Update Associate Details & Commission Rate
     */
    public function updateAssociate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'balance'         => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $associate = User::findOrFail($id);
        $associate->update($request->only(['commission_rate', 'balance']));

        return response()->json([
            'message' => 'Associate updated successfully',
            'associate' => $associate
        ]);
    }

    /**
     * Process / Approve Payout for Associate
     */
    public function approvePayout(Request $request, $id)
    {
        $associate = User::findOrFail($id);
        $amount = (float) $request->input('amount', $associate->balance);

        if ($amount <= 0 || $amount > $associate->balance) {
            return response()->json(['error' => 'Invalid payout amount'], 422);
        }

        $associate->decrement('balance', $amount);

        // Update referral sales records as paid
        ReferralSale::where('associate_id', $associate->id)
            ->where('status', 'pending')
            ->update(['status' => 'paid']);

        return response()->json([
            'message' => "Payout of ₹{$amount} approved and processed for {$associate->name}.",
            'current_balance' => $associate->balance
        ]);
    }

    /**
     * Coupons List
     */
    public function getCoupons()
    {
        return response()->json(Coupon::latest()->get());
    }

    /**
     * Create Coupon
     */
    public function storeCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'           => 'required|string|unique:coupons,code',
            'title'          => 'required|string|max:255',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_order_value'=> 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $coupon = Coupon::create([
            'code'            => strtoupper($request->code),
            'title'           => $request->title,
            'discount_type'   => $request->discount_type,
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->input('min_order_value', 0),
            'campaign_type'   => $request->input('campaign_type', 'festival'),
            'valid_until'     => $request->input('valid_until', now()->addMonths(3)),
            'is_active'       => true,
        ]);

        return response()->json([
            'message' => 'Coupon created successfully',
            'coupon' => $coupon
        ], 201);
    }

    /**
     * Toggle Coupon Status
     */
    public function toggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);

        return response()->json([
            'message' => 'Coupon status updated',
            'is_active' => $coupon->is_active
        ]);
    }

    /**
     * Delete Coupon
     */
    public function deleteCoupon($id)
    {
        Coupon::findOrFail($id)->delete();
        return response()->json(['message' => 'Coupon deleted successfully']);
    }

    /**
     * Reviews List
     */
    public function getReviews()
    {
        return response()->json(Review::latest()->get());
    }

    /**
     * Moderate / Update Review
     */
    public function updateReview(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->update($request->only(['rating', 'comment', 'is_approved']));

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review
        ]);
    }

    /**
     * Delete Review
     */
    public function deleteReview($id)
    {
        Review::findOrFail($id)->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }

    /**
     * Monthly Analytics Report
     */
    public function getMonthlyReports()
    {
        $reports = ReferralSale::selectRaw('DATE_FORMAT(created_at, "%M %Y") as month, COUNT(*) as orders_count, SUM(sale_amount) as total_sales, SUM(commission_earned) as total_commission')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) DESC')
            ->get();

        return response()->json($reports);
    }
}
