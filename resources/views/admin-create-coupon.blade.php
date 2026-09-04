@extends('layouts.app')

@section('title', 'Estilo HQ — Generate Discount Coupon')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Navigation Breadcrumb --}}
        <div class="flex items-center justify-between">
            <a href="/estilo-hq-console?tab=offers" class="inline-flex items-center gap-2 text-xs font-sans font-bold text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Offers & Coupons
            </a>
            <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-amber-800 bg-amber-100 px-3 py-1 rounded-full">
                ✨ Promotions Suite
            </span>
        </div>

        {{-- Main Creation Card --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-xl p-6 sm:p-10 space-y-8"
             x-data="{
                coupon: {
                    code: 'DIWALI30',
                    title: 'Diwali Royal Festive 30% Off',
                    discount_type: 'percentage',
                    discount_value: 30,
                    min_order_value: 1999,
                    campaign_type: 'festival',
                    valid_until: '2027-12-31'
                },
                applyPreset(code, title, discount, type, minOrder, campaign) {
                    this.coupon.code = code;
                    this.coupon.title = title;
                    this.coupon.discount_value = discount;
                    this.coupon.discount_type = type;
                    this.coupon.min_order_value = minOrder;
                    this.coupon.campaign_type = campaign;
                }
             }">

            {{-- Header --}}
            <div class="border-b border-[var(--color-bisque)]/60 pb-5">
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Generate Discount Coupon</h1>
                <p class="text-xs font-sans text-gray-500 mt-1">Create promotional voucher codes for boutique customers and festive campaigns. The coupon is immediately active in checkout validation.</p>
            </div>

            {{-- Quick 1-Click Presets --}}
            <div class="space-y-2">
                <label class="block text-[11px] font-sans font-bold text-[var(--color-ebony)]/70 uppercase tracking-wider">
                    ⚡ Quick Presets (Click to Auto-Fill):
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" @click="applyPreset('DIWALI30', 'Diwali Royal Festive 30% Off', 30, 'percentage', 1999, 'festival')"
                            class="p-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-left transition-all hover:scale-105 active:scale-95 group">
                        <span class="block text-xs font-mono font-bold text-amber-950 group-hover:text-amber-700">🎉 DIWALI30</span>
                        <span class="text-[10px] text-amber-800">30% Off • Min ₹1,999</span>
                    </button>
                    <button type="button" @click="applyPreset('ROYAL500', 'Flat ₹500 Off Luxury Collection', 500, 'fixed', 3500, 'festival')"
                            class="p-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 border border-rose-200 text-left transition-all hover:scale-105 active:scale-95 group">
                        <span class="block text-xs font-mono font-bold text-rose-950 group-hover:text-rose-700">👑 ROYAL500</span>
                        <span class="text-[10px] text-rose-800">Flat ₹500 • Min ₹3,500</span>
                    </button>
                    <button type="button" @click="applyPreset('FESTIVE25', 'Festive Season 25% Off', 25, 'percentage', 1499, 'festival')"
                            class="p-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-left transition-all hover:scale-105 active:scale-95 group">
                        <span class="block text-xs font-mono font-bold text-emerald-950 group-hover:text-emerald-700">✨ FESTIVE25</span>
                        <span class="text-[10px] text-emerald-800">25% Off • Min ₹1,499</span>
                    </button>
                    <button type="button" @click="applyPreset('WELCOME10', '10% Welcome Discount For New Customers', 10, 'percentage', 999, 'welcome')"
                            class="p-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-left transition-all hover:scale-105 active:scale-95 group">
                        <span class="block text-xs font-mono font-bold text-indigo-950 group-hover:text-indigo-700">👗 WELCOME10</span>
                        <span class="text-[10px] text-indigo-800">10% Off • Min ₹999</span>
                    </button>
                </div>
            </div>

            {{-- Live Interactive Voucher Card Preview --}}
            <div class="bg-gradient-to-br from-[#FBEAD6] via-[#F6DEC7] to-[#ECC8A8] border-2 border-amber-300 rounded-2xl p-5 shadow-md relative overflow-hidden space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider px-3 py-1 rounded-full bg-white/90 text-[var(--color-ebony)] shadow-xs">
                        <span x-text="coupon.campaign_type.toUpperCase()"></span> CAMPAIGN
                    </span>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100/90 px-2.5 py-0.5 rounded-full border border-emerald-300">● Live Customer Card Preview</span>
                </div>
                <div class="flex items-baseline justify-between pt-1">
                    <span class="font-mono text-3xl font-black text-[var(--color-ebony)] tracking-wider" x-text="coupon.code || 'COUPON_CODE'"></span>
                    <span class="text-base font-black text-amber-900 bg-white/95 px-3 py-1 rounded-xl shadow-xs" x-text="coupon.discount_type === 'percentage' ? (coupon.discount_value + '% OFF') : ('₹' + coupon.discount_value + ' OFF')"></span>
                </div>
                <p class="font-serif text-sm font-bold text-[var(--color-ebony)]" x-text="coupon.title || 'Campaign Title Preview'"></p>
                <div class="text-xs text-[var(--color-ebony)]/80 flex items-center justify-between pt-2 border-t border-amber-400/50 font-sans">
                    <span>Min Cart Subtotal: <strong>₹<span x-text="Number(coupon.min_order_value || 0).toLocaleString()"></span></strong></span>
                    <span>Valid until: <strong x-text="coupon.valid_until || '2027-12-31'"></strong></span>
                </div>
            </div>

            {{-- Form Fields --}}
            <form action="/estilo-hq-console/coupons" method="POST" class="space-y-5">
                @csrf

                {{-- Code & Title --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Coupon Code *</label>
                        <input type="text" name="code" x-model="coupon.code" @input="coupon.code = coupon.code.toUpperCase()" placeholder="DIWALI30" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-mono font-bold uppercase focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Campaign Title *</label>
                        <input type="text" name="title" x-model="coupon.title" placeholder="Diwali Royal Festive 30% Off" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                </div>

                {{-- Discount Type & Value & Min Order --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Discount Type</label>
                        <select name="discount_type" x-model="coupon.discount_type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-3 text-xs font-bold focus:outline-none focus:border-amber-400">
                            <option value="percentage">% Percentage Discount</option>
                            <option value="fixed">₹ Flat INR Amount</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Discount Value *</label>
                        <input type="number" name="discount_value" x-model="coupon.discount_value" required min="1"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Min Order Value (₹)</label>
                        <input type="number" name="min_order_value" x-model="coupon.min_order_value" min="0" step="100" placeholder="0"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-bold focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                </div>

                {{-- Campaign Category & Expiry Date --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Campaign Category</label>
                        <select name="campaign_type" x-model="coupon.campaign_type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-amber-400">
                            <option value="festival">Festival Campaign (Diwali, Navratri, Eid)</option>
                            <option value="monthly">Monthly Special</option>
                            <option value="welcome">Welcome First-Order Discount</option>
                            <option value="seasonal">Seasonal Summer/Winter Couture</option>
                            <option value="clearance">Atelier Clearance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Validity Expiry Date</label>
                        <input type="date" name="valid_until" x-model="coupon.valid_until"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-amber-400" />
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4 pt-4 border-t border-[var(--color-bisque)]/60">
                    <a href="/estilo-hq-console?tab=offers"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-3.5 rounded-xl transition-colors">
                        Cancel & Return
                    </a>
                    <button type="submit"
                            class="flex-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-widest py-3.5 px-8 rounded-xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                        ✨ Publish Coupon to Database
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
