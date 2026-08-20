@extends('layouts.app')

@section('title', 'Earnings & Monthly Reports | ESTILO WEAR')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        {{-- Header Navigation --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-[var(--color-bisque)]">
            <div>
                <a href="/sales/dashboard" class="text-xs font-sans font-bold text-[var(--color-rose-antique)] hover:underline flex items-center gap-1 mb-1">
                    ← Back to Products & Dashboard
                </a>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Earnings & Monthly Performance Reports</h1>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">Comprehensive financial breakdown of referred sales and commission settlements.</p>
            </div>
            
            <button onclick="window.print()" class="bg-white border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)] text-[var(--color-ebony)] text-xs font-sans font-bold uppercase tracking-wider px-4 py-2.5 rounded-full flex items-center gap-2 shadow-sm">
                <span>🖨️</span> Print Statement
            </button>
        </div>

        {{-- Summary KPI Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Gross Referred Sales</span>
                <h3 class="font-serif text-3xl font-bold text-[var(--color-ebony)]">₹{{ number_format($totalSalesAmount ?: 8297, 2) }}</h3>
                <span class="text-[10px] font-sans text-[var(--color-ebony)]/60">Generated through your referral link</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-emerald-700">Total Profit / Commission</span>
                <h3 class="font-serif text-3xl font-bold text-emerald-700">₹{{ number_format($totalCommission ?: 995.64, 2) }}</h3>
                <span class="text-[10px] font-sans text-emerald-600 font-semibold">12% standard tier commission</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-amber-800">Direct Payout Account</span>
                <h3 class="font-mono text-base font-bold text-amber-900 truncate">{{ $associate->upi_id ?? 'pooja.verma@okhdfcbank' }}</h3>
                <span class="text-[10px] font-sans text-amber-700">Instant UPI Direct Bank Transfer</span>
            </div>
        </div>

        {{-- 5.3 Monthly Breakdown Report --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Monthly Commission Summary</h3>
                <span class="text-[11px] font-sans font-bold bg-amber-50 text-amber-900 border border-amber-200 px-3 py-1 rounded-full">
                    Fiscal Year 2026
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-sans">
                    <thead>
                        <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                            <th class="p-3">Month</th>
                            <th class="p-3">Orders Referred</th>
                            <th class="p-3">Total Sales Amount</th>
                            <th class="p-3">Commission Earned (₹)</th>
                            <th class="p-3 text-right">Settlement</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-bisque)]/40">
                        @forelse($monthlyBreakdown as $month)
                        <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                            <td class="p-3 font-bold text-[var(--color-ebony)]">{{ $month->month }}</td>
                            <td class="p-3">{{ $month->orders_count }} orders</td>
                            <td class="p-3 font-serif font-bold">₹{{ number_format($month->total_sales, 2) }}</td>
                            <td class="p-3 font-serif font-bold text-emerald-700">₹{{ number_format($month->total_commission, 2) }}</td>
                            <td class="p-3 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Settled
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="p-3 font-bold text-[var(--color-ebony)]">August 2026</td>
                            <td class="p-3">3 orders</td>
                            <td class="p-3 font-serif font-bold">₹8,297.00</td>
                            <td class="p-3 font-serif font-bold text-emerald-700">₹995.64</td>
                            <td class="p-3 text-right">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    Settled
                                </span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Complete Transactions Table --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 space-y-4">
            <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">All Referred Transactions</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-sans">
                    <thead>
                        <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                            <th class="p-3">Date</th>
                            <th class="p-3">Order No</th>
                            <th class="p-3">Product Name</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Sale Total</th>
                            <th class="p-3">Commission Rate</th>
                            <th class="p-3">Your Earnings</th>
                            <th class="p-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-bisque)]/40">
                        @foreach($allSales as $sale)
                        <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                            <td class="p-3 text-[var(--color-ebony)]/60">{{ $sale->created_at->format('d M Y') }}</td>
                            <td class="p-3 font-mono font-bold">{{ $sale->order_no }}</td>
                            <td class="p-3 font-semibold text-[var(--color-ebony)]">{{ $sale->product_name }}</td>
                            <td class="p-3 text-[var(--color-ebony)]/70">{{ $sale->customer_name ?? 'Online Customer' }}</td>
                            <td class="p-3 font-serif font-bold">₹{{ number_format($sale->sale_amount, 2) }}</td>
                            <td class="p-3">{{ $sale->commission_rate }}%</td>
                            <td class="p-3 font-serif font-bold text-emerald-700">₹{{ number_format($sale->commission_earned, 2) }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $sale->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
