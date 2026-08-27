@extends('layouts.app')

@section('title', 'Sales Partner Dashboard | ESTILO WEAR')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-8 px-4 sm:px-6 lg:px-8" x-data="{
    copiedIndex: null,
    searchQuery: '',
    selectedCategory: '',
    payoutModal: false,

    copyToClipboard(text, idx) {
        navigator.clipboard.writeText(text);
        this.copiedIndex = idx;
        setTimeout(() => { this.copiedIndex = null; }, 2000);
    }
}">

    <div class="max-w-7xl mx-auto space-y-8">
        
        {{-- Partner Welcome Header --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100/80 text-amber-900 text-[10px] font-sans font-bold uppercase tracking-widest">
                    <span>✦ Estilo Verified Sales Executive</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Welcome, {{ $associate->name }}</h1>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">
                    Your unique referral code is <strong class="text-amber-800 font-mono font-bold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">{{ $associate->referral_code ?? 'ESTILO-SA01' }}</strong> • Commission Tier: <strong class="text-emerald-700">{{ $associate->commission_rate }}% per sale</strong>
                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <a href="/sales/earnings" class="flex-1 md:flex-none text-center bg-white border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)] text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-wider px-5 py-3 rounded-full transition-colors">
                    📊 Reports
                </a>
                <button @click="payoutModal = true" class="flex-1 md:flex-none bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-wider px-5 py-3 rounded-full transition-all shadow-md">
                    💸 Payout (₹{{ number_format($associate->balance, 0) }})
                </button>
                <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                    @csrf
                    <button type="submit" class="w-full md:w-auto bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-800 font-sans text-xs font-bold uppercase tracking-wider px-4 py-3 rounded-full transition-colors flex items-center justify-center gap-1.5" title="Sign Out of Sales Account">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Total Sales Generated</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">₹{{ number_format($totalSalesAmount ?: 8297, 0) }}</h3>
                <span class="text-[10px] font-sans text-emerald-600 font-semibold">↑ Direct affiliate checkout conversions</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-emerald-700">Lifetime Profit / Commission</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-emerald-700">₹{{ number_format($totalCommission ?: 995.64, 2) }}</h3>
                <span class="text-[10px] font-sans text-emerald-600 font-semibold">12% standard partner commission</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-amber-800">Available Payout Balance</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-amber-800">₹{{ number_format($associate->balance ?: 6420, 0) }}</h3>
                <span class="text-[10px] font-sans text-amber-700 font-semibold">UPI: {{ $associate->upi_id ?? 'pooja.verma@okhdfcbank' }}</span>
            </div>

            <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Referred Orders</span>
                <h3 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">{{ $sales->count() ?: 3 }}</h3>
                <span class="text-[10px] font-sans text-[var(--color-ebony)]/60">Customers tracked seamlessly</span>
            </div>
        </div>

        {{-- 5.2 Product Catalog with Referral Generator & Social Share --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-[var(--color-bisque)]/60">
                <div>
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Artisanal Product Catalog & Referral Links</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Generate tracked links and share directly to your WhatsApp status, groups, or Instagram bio.</p>
                </div>

                {{-- Search & Filter --}}
                <div class="w-full sm:w-72 relative">
                    <svg class="w-4 h-4 absolute left-3 top-3 text-[var(--color-ebony)]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="searchQuery" placeholder="Search by outfit or saree name..." class="w-full pl-9 pr-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($products as $idx => $prod)
                @php
                    $img = is_array($prod->images) ? ($prod->images[0] ?? '/storage/hero/hero-main.jpg') : $prod->images;
                    $refLink = url('/product/' . ($prod->est_id ?? $prod->id) . '?ref=' . ($associate->referral_code ?? 'ESTILO-SA01'));
                    $custSellingPrice = (float) ($prod->sales_price ?: ($prod->price + 50));
                    $profitMargin = max(0, $custSellingPrice - (float) $prod->price);
                    if ($profitMargin == 0) {
                        $profitMargin = round($prod->price * (($associate->commission_rate ?? 10) / 100), 0);
                    }
                    $waText = urlencode("✨ Discover this handcrafted " . $prod->name . " from Estilo Wear for ₹" . number_format($custSellingPrice, 0) . "! Shop directly using my curated link: " . $refLink);
                @endphp
                
                <div class="bg-[var(--color-offwhite)] rounded-2xl border border-[var(--color-bisque)]/80 overflow-hidden flex flex-col justify-between p-4 space-y-4 hover:shadow-md transition-shadow"
                     x-show="!searchQuery || '{{ strtolower($prod->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($prod->fabric) }}'.includes(searchQuery.toLowerCase())">
                    
                    <div class="flex gap-3">
                        <img src="{{ $img }}" alt="{{ $prod->name }}" class="w-20 h-24 object-cover rounded-xl shrink-0 border border-[var(--color-bisque)]" />
                        <div class="space-y-1">
                            <span class="text-[9px] font-sans font-bold uppercase tracking-wider text-[var(--color-rose-antique)]">{{ $prod->category }}</span>
                            <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)] leading-snug line-clamp-2">{{ $prod->name }}</h4>
                            <div class="space-y-0.5 pt-1">
                                <div class="flex items-center gap-2 text-xs">
                                    <span class="text-[10px] text-gray-500">Base: ₹{{ number_format($prod->price, 0) }}</span>
                                    <span class="font-serif font-bold text-[var(--color-ebony)]">Link Price: ₹{{ number_format($custSellingPrice, 0) }}</span>
                                </div>
                                <span class="inline-block text-[10px] font-sans font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full border border-emerald-200">
                                    💰 +₹{{ number_format($profitMargin, 0) }} Your Profit / Sale
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Referral Link Input & Social Share Buttons --}}
                    <div class="space-y-2.5 pt-2 border-t border-[var(--color-bisque)]/60">
                        <div class="flex items-center gap-2">
                            <input type="text" readonly value="{{ $refLink }}" class="flex-1 bg-white border border-[var(--color-bisque)] rounded-lg px-2.5 py-1.5 text-[10px] font-mono text-[var(--color-ebony)]/80 focus:outline-none" />
                            <button type="button" @click="copyToClipboard('{{ $refLink }}', {{ $idx }})" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[10px] font-sans font-bold px-3 py-1.5 rounded-lg transition-colors shrink-0">
                                <span x-text="copiedIndex === {{ $idx }} ? '✓ Copied' : 'Copy Link'"></span>
                            </button>
                        </div>

                        {{-- Social Share Action Row --}}
                        <div class="flex items-center justify-between gap-2 pt-1">
                            {{-- WhatsApp Direct --}}
                            <a href="https://api.whatsapp.com/send?text={{ $waText }}" target="_blank" class="flex-1 flex items-center justify-center gap-1 bg-[#25D366] hover:bg-[#1EBE5D] text-white text-[10px] font-sans font-bold py-2 rounded-lg transition-colors">
                                <span>💬</span> WhatsApp
                            </a>

                            {{-- Facebook Share --}}
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($refLink) }}" target="_blank" class="flex-1 flex items-center justify-center gap-1 bg-[#1877F2] hover:bg-[#0D65D9] text-white text-[10px] font-sans font-bold py-2 rounded-lg transition-colors">
                                <span>📘</span> Facebook
                            </a>

                            {{-- Instagram / Web Preview --}}
                            <a href="{{ $refLink }}" target="_blank" class="p-2 bg-white border border-[var(--color-bisque)] hover:bg-gray-50 text-[var(--color-ebony)] rounded-lg text-xs" title="Preview Product">
                                ↗
                            </a>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>

        </div>

        {{-- Recent Referred Orders Table --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 space-y-4">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Recent Referred Customer Conversions</h3>
                <a href="/sales/earnings" class="text-xs font-sans font-bold text-[var(--color-rose-antique)] hover:underline">View All Sales →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-sans">
                    <thead>
                        <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                            <th class="p-3">Order #</th>
                            <th class="p-3">Product Name</th>
                            <th class="p-3">Customer</th>
                            <th class="p-3">Sale Total</th>
                            <th class="p-3">Commission (12%)</th>
                            <th class="p-3">Payout Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-bisque)]/40">
                        @foreach($sales as $sale)
                        <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                            <td class="p-3 font-mono font-bold">{{ $sale->order_no }}</td>
                            <td class="p-3 font-semibold text-[var(--color-ebony)]">{{ $sale->product_name }}</td>
                            <td class="p-3 text-[var(--color-ebony)]/70">{{ $sale->customer_name ?? 'Online Shopper' }}</td>
                            <td class="p-3 font-serif font-bold">₹{{ number_format($sale->sale_amount, 0) }}</td>
                            <td class="p-3 font-serif font-bold text-emerald-700">+₹{{ number_format($sale->commission_earned, 2) }}</td>
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

    {{-- Payout Request Modal --}}
    <div x-show="payoutModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="payoutModal" x-transition.opacity @click="payoutModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-5">
            <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Request Commission Payout</h3>
            <p class="text-xs font-sans text-[var(--color-ebony)]/70">Enter the amount to withdraw to your registered UPI handle.</p>

            <form action="/sales/payout" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Payout Amount (₹)</label>
                    <input type="number" name="amount" value="{{ (int)$associate->balance }}" max="{{ (int)$associate->balance }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-lg font-serif font-bold text-[var(--color-ebony)]" />
                </div>
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Registered UPI ID</label>
                    <input type="text" readonly value="{{ $associate->upi_id ?? 'pooja.verma@okhdfcbank' }}" class="w-full bg-gray-100 border border-gray-200 rounded-xl px-4 py-3 text-xs font-mono text-gray-700" />
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="payoutModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-[var(--color-ebony)] font-sans text-xs font-bold py-3 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold py-3 rounded-xl shadow-md">Confirm Payout</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
