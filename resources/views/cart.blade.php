@extends('layouts.app')

@section('title', 'Shopping Bag | ESTILO WEAR')

@section('content')

<div class="pb-16 sm:pb-24 pt-4 sm:pt-8" x-data="{
    promoCode: '',
    appliedDiscount: 0,
    promoApplied: false,
    
    applyPromo() {
        if (this.promoCode.trim().toUpperCase() === 'BOUTIQUE10') {
            this.appliedDiscount = Math.round($store.shop.cartSubtotal * 0.10);
            this.promoApplied = true;
            $store.shop.showToast('10% Promo Code BOUTIQUE10 applied successfully!');
        } else {
            $store.shop.showToast('Invalid promo code. Use BOUTIQUE10');
        }
    },

    get shipping() {
        return $store.shop.cartSubtotal >= 1999 || $store.shop.cart.length === 0 ? 0 : 199;
    },

    get finalTotal() {
        return Math.max(0, $store.shop.cartSubtotal - this.appliedDiscount + this.shipping);
    }
}">

    {{-- Page Header --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 sm:mb-10 text-center">
        <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">
            Review & Order
        </span>
        <h1 class="font-serif text-2xl sm:text-5xl font-bold text-[var(--color-ebony)] mt-2">
            Your Shopping Bag
        </h1>
        <p class="text-xs font-sans text-[var(--color-ebony)]/60 mt-2">
            <span x-text="$store.shop.cartCount"></span> items in your bag
        </p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <template x-if="$store.shop.cart.length > 0">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-10">

                {{-- Cart Items Column --}}
                <div class="lg:col-span-2 space-y-4">
                    <template x-for="(item, index) in $store.shop.cart" :key="index">
                        <div class="bg-white p-4 sm:p-6 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                            <img :src="item.image" :alt="item.name" class="w-20 h-24 sm:w-24 sm:h-32 object-cover object-top rounded-xl sm:rounded-2xl flex-shrink-0" />

                            <div class="flex-1 space-y-2 text-center sm:text-left w-full">
                                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-rose-antique)]" x-text="item.category || 'Atelier Collection'"></span>
                                <h3 class="font-serif text-sm sm:text-lg font-bold text-[var(--color-ebony)]" x-text="item.name"></h3>
                                <div class="text-xs font-sans text-[var(--color-ebony)]/60 space-x-3">
                                    <span>Color: <strong class="text-[var(--color-ebony)]" x-text="item.color"></strong></span>
                                    <span>|</span>
                                    <span>Size: <strong class="text-[var(--color-ebony)]" x-text="item.size"></strong></span>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    {{-- Quantity control --}}
                                    <div class="flex items-center border border-[var(--color-bisque)] rounded-full px-3 py-1 bg-[var(--color-offwhite)]">
                                        <button @click="$store.shop.updateQty(index, item.qty - 1)" class="text-xs font-bold px-2 hover:text-[var(--color-rose-antique)]">-</button>
                                        <span class="text-xs font-sans font-bold px-2" x-text="item.qty"></span>
                                        <button @click="$store.shop.updateQty(index, item.qty + 1)" class="text-xs font-bold px-2 hover:text-[var(--color-rose-antique)]">+</button>
                                    </div>

                                    {{-- Price & Remove --}}
                                    <div class="flex items-center gap-4">
                                        <span class="font-serif text-base sm:text-lg font-bold text-[var(--color-ebony)]" x-text="'₹' + (item.price * item.qty).toLocaleString('en-IN')"></span>
                                        <button @click="$store.shop.removeFromCart(index)" class="text-[var(--color-ebony)]/40 hover:text-[var(--color-rose-antique)] transition-colors p-2" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Order Summary Column --}}
                <div class="space-y-6">
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-[var(--color-bisque)]/60 shadow-[var(--shadow-floating)] space-y-6">
                        <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)] border-b border-[var(--color-bisque)] pb-3 uppercase tracking-wider">
                            Order Summary
                        </h3>

                        {{-- Promo form --}}
                        <form @submit.prevent="applyPromo()" class="flex gap-2">
                            <div class="relative flex-1">
                                <input type="text"
                                    x-model="promoCode"
                                    placeholder="Promo (BOUTIQUE10)"
                                    class="w-full px-4 py-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full text-xs font-sans uppercase focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>
                            <button type="submit" class="px-4 py-2.5 bg-[var(--color-rose-antique)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider rounded-full transition-colors">
                                Apply
                            </button>
                        </form>

                        {{-- Cost Breakdown --}}
                        <div class="space-y-3 text-xs font-sans text-[var(--color-ebony)]/80 pt-2 border-t border-[var(--color-bisque)]/30">
                            <div class="flex justify-between">
                                <span>Bag Subtotal</span>
                                <span class="font-bold" x-text="'₹' + $store.shop.cartSubtotal.toLocaleString('en-IN')"></span>
                            </div>
                            <template x-if="appliedDiscount > 0">
                                <div class="flex justify-between text-[var(--color-rose-antique)] font-bold">
                                    <span>Promo Discount</span>
                                    <span x-text="'-₹' + appliedDiscount.toLocaleString('en-IN')"></span>
                                </div>
                            </template>
                            <div class="flex justify-between">
                                <span>Shipping Charges</span>
                                <span x-html="shipping === 0 ? '<strong class=\'text-[var(--color-thyme)]\'>FREE</strong>' : '₹' + shipping"></span>
                            </div>
                            <div class="flex justify-between text-base font-serif font-bold text-[var(--color-ebony)] pt-3 border-t border-[var(--color-bisque)]">
                                <span>Total Amount</span>
                                <span x-text="'₹' + finalTotal.toLocaleString('en-IN')"></span>
                            </div>
                        </div>

                        <a href="/checkout" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-full flex items-center justify-center gap-2 shadow-lg transition-all hover:scale-[1.02]">
                            Proceed to Checkout →
                        </a>

                        <div class="flex items-center justify-center gap-2 text-[10px] font-sans text-[var(--color-ebony)]/60 text-center">
                            <svg class="w-4 h-4 text-[var(--color-thyme)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>100% Encrypted Transactions & Authenticity Guaranteed</span>
                        </div>
                    </div>
                </div>

            </div>
        </template>

        <template x-if="$store.shop.cart.length === 0">
            <div class="text-center py-20 bg-white rounded-3xl border border-[var(--color-bisque)]/50 max-w-md mx-auto p-8 space-y-4 shadow-sm">
                <div class="w-16 h-16 rounded-full bg-[var(--color-champagne)] flex items-center justify-center text-[var(--color-rose-antique)] text-2xl mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Your Bag is Empty</h3>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">
                    Explore our handcrafted designer kurtis, traditional sarees, and boutique outfits.
                </p>
                <a href="/shop" class="inline-flex items-center gap-2 bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest px-8 py-3.5 rounded-full hover:bg-[var(--color-rose-deep)] transition-colors">
                    Start Shopping →
                </a>
            </div>
        </template>
    </div>

</div>

@endsection
