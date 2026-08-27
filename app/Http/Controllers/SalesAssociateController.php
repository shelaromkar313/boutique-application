<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ReferralSale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesAssociateController extends Controller
{
    /**
     * Get current active sales associate or null.
     */
    protected function getAssociate()
    {
        $user = Auth::user();
        if ($user && $user->isSalesAssociate()) {
            return $user;
        }
        return null;
    }

    /**
     * 5.2 Sales Associate Dashboard
     */
    public function dashboard(Request $request)
    {
        $associate = $this->getAssociate();
        if (!$associate) {
            return redirect('/login?role=sales_associate')->with('info', 'Please sign in to access your Sales Partner Dashboard.');
        }

        $products = Product::all();
        $sales = ReferralSale::where('associate_id', $associate->id)->latest()->take(10)->get();

        $totalSalesAmount = ReferralSale::where('associate_id', $associate->id)->sum('sale_amount');
        $totalCommission = ReferralSale::where('associate_id', $associate->id)->sum('commission_earned');
        $pendingPayout = ReferralSale::where('associate_id', $associate->id)->where('status', 'approved')->sum('commission_earned');
        $paidPayout = ReferralSale::where('associate_id', $associate->id)->where('status', 'paid')->sum('commission_earned');

        return view('sales.dashboard', compact(
            'associate',
            'products',
            'sales',
            'totalSalesAmount',
            'totalCommission',
            'pendingPayout',
            'paidPayout'
        ));
    }

    /**
     * 5.3 Earnings & Monthly Reports
     */
    public function earnings(Request $request)
    {
        $associate = $this->getAssociate();
        if (!$associate) {
            return redirect('/login?role=sales_associate')->with('info', 'Please sign in to access your Earnings Reports.');
        }

        $allSales = ReferralSale::where('associate_id', $associate->id)->latest()->get();

        $monthlyBreakdown = ReferralSale::where('associate_id', $associate->id)
            ->selectRaw('DATE_FORMAT(created_at, "%M %Y") as month, COUNT(*) as orders_count, SUM(sale_amount) as total_sales, SUM(commission_earned) as total_commission')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) DESC')
            ->get();

        $totalSalesAmount = $allSales->sum('sale_amount');
        $totalCommission = $allSales->sum('commission_earned');

        return view('sales.earnings', compact(
            'associate',
            'allSales',
            'monthlyBreakdown',
            'totalSalesAmount',
            'totalCommission'
        ));
    }

    /**
     * Update Associate Profile / Payment UPI Info
     */
    public function updateProfile(Request $request)
    {
        $associate = $this->getAssociate();
        if (!$associate) {
            return redirect('/login?role=sales_associate');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'upi_id' => 'nullable|string|max:100',
        ]);

        $associate->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'upi_id' => $request->upi_id,
        ]);

        return back()->with('success', 'Profile and payment settings updated successfully!');
    }

    /**
     * Request Commission Payout
     */
    public function requestPayout(Request $request)
    {
        $associate = $this->getAssociate();
        if (!$associate) {
            return redirect('/login?role=sales_associate');
        }

        $amount = (float) $request->input('amount', $associate->balance);

        return back()->with('success', "✨ Payout request of ₹" . number_format($amount, 2) . " submitted for UPI: " . ($associate->upi_id ?: 'Registered Account') . ". Processing within 24 business hours.");
    }
}
