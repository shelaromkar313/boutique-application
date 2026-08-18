{{-- Slide-out Luxury Cart Drawer --}}
<div x-data
    x-show="$store.shop.isCartOpen"
    style="display: none;"
    class="fixed inset-0 z-50 overflow-hidden"
    aria-labelledby="slide-over-title"
    role="dialog"
    aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="$store.shop.isCartOpen"
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.shop.isCartOpen = false"
        class="fixed inset-0 bg-[var(--color-ebony)]/70 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div x-show="$store.shop.isCartOpen"
            x-transition:enter="transform transition ease-in-out duration-500"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-500"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="w-screen max-w-md bg-[var(--color-offwhite)] shadow-2xl flex flex-col justify-between">

            {{-- Header --}}
            <div class="p-5 sm:p-6 border-b border-[var(--color-bisque)]/60 bg-[var(--color-champagne-light)]/50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-[var(--color-rose-antique)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <h2 class="font-serif text-lg sm:text-xl font-bold text-[var(--color-ebony)]">Your Shopping Bag</h2>
                    <span class="text-xs font-sans font-bold bg-[var(--color-ebony)] text-white px-2 py-0.5 rounded-full ml-1" x-text="$store.shop.cartCount"></span>
                </div>
                <button @click="$store.shop.isCartOpen = false" class="p-2 text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Free Shipping Progress Indicator --}}
            <div class="bg-[var(--color-bisque)]/20 px-5 sm:px-6 py-3 border-b border-[var(--color-bisque)]/40 text-xs font-sans">
                <div class="flex items-center justify-between text-[var(--color-ebony)] font-medium mb-1.5">
                    <span class="flex items-center gap-1.5 text-[11px] sm:text-xs">
                        <svg class="w-4 h-4 text-[var(--color-thyme)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        <span x-text="$store.shop.freeShippingRemaining === 0 ? '🎉 You unlocked FREE Express Shipping!' : 'Add ₹' + $store.shop.freeShippingRemaining.toLocaleString('en-IN') + ' more for FREE Shipping'"></span>
                    </span>
                    <span class="font-bold text-[var(--color-thyme)]" x-text="$store.shop.freeShippingProgress + '%'"></span>
                </div>
                <div class="w-full bg-[var(--color-bisque)]/60 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-[var(--color-thyme)] h-full transition-all duration-500 rounded-full" :style="'width: ' + $store.shop.freeShippingProgress + '%'"></div>
                </div>
            </div>

            {{-- Cart Items List --}}
            <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-3">
                <template x-if="$store.shop.cart.length > 0">
                    <div class="space-y-3">
                        <template x-for="(item, index) in $store.shop.cart" :key="index">
                            <div class="flex gap-3 p-3 bg-white rounded-2xl border border-[var(--color-bisque)]/50 shadow-sm relative group">
                                <img :src="item.image" :alt="item.name" class="w-16 h-20 sm:w-20 sm:h-24 object-cover object-top rounded-xl flex-shrink-0" />
                                <div class="flex-1 flex flex-col justify-between min-w-0">
                                    <div>
                                        <div class="flex items-start justify-between gap-2">
                                            <h4 class="font-serif text-xs sm:text-sm font-bold text-[var(--color-ebony)] truncate" x-text="item.name"></h4>
                                            <button @click="$store.shop.removeFromCart(index)" class="text-[var(--color-ebony)]/40 hover:text-[var(--color-rose-antique)] transition-colors p-1" title="Remove">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                        <div class="text-[10px] sm:text-[11px] font-sans text-[var(--color-ebony)]/60 space-x-2 mt-0.5">
                                            <span>Color: <strong class="text-[var(--color-ebony)]" x-text="item.color"></strong></span>
                                            <span>|</span>
                                            <span>Size: <strong class="text-[var(--color-ebony)]" x-text="item.size"></strong></span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-2">
                                        <div class="flex items-center border border-[var(--color-bisque)] rounded-full px-2 py-0.5 bg-[var(--color-offwhite)]">
                                            <button @click="$store.shop.updateQty(index, item.qty - 1)" class="text-xs font-bold px-1.5 hover:text-[var(--color-rose-antique)]">-</button>
                                            <span class="text-xs font-sans font-bold px-2" x-text="item.qty"></span>
                                            <button @click="$store.shop.updateQty(index, item.qty + 1)" class="text-xs font-bold px-1.5 hover:text-[var(--color-rose-antique)]">+</button>
                                        </div>
                                        <span class="font-serif text-xs sm:text-sm font-bold text-[var(--color-ebony)]" x-text="'₹' + (item.price * item.qty).toLocaleString('en-IN')"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <template x-if="$store.shop.cart.length === 0">
                    <div class="text-center py-16 space-y-4">
                        <div class="w-16 h-16 rounded-full bg-[var(--color-champagne)] flex items-center justify-center text-[var(--color-rose-antique)] text-2xl mx-auto">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </div>
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Your Bag is Empty</h3>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60 max-w-xs mx-auto">
                            Explore our handcrafted designer kurtis, traditional sarees, and boutique dresses.
                        </p>
                        <a href="/shop" @click="$store.shop.isCartOpen = false" class="inline-block bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full hover:bg-[var(--color-rose-deep)] transition-colors">
                            Start Shopping
                        </a>
                    </div>
                </template>
            </div>

            {{-- Footer Summary --}}
            <template x-if="$store.shop.cart.length > 0">
                <div class="p-5 sm:p-6 border-t border-[var(--color-bisque)]/60 bg-white space-y-3 shadow-lg">
                    <div class="space-y-1.5 text-xs font-sans text-[var(--color-ebony)]/80">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="font-bold text-[var(--color-ebony)]" x-text="'₹' + $store.shop.cartSubtotal.toLocaleString('en-IN')"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping</span>
                            <span class="text-[var(--color-thyme)] font-bold" x-text="$store.shop.cartSubtotal >= 1999 ? 'FREE' : '₹199'"></span>
                        </div>
                        <div class="flex justify-between text-sm font-serif font-bold text-[var(--color-ebony)] pt-2 border-t border-[var(--color-bisque)]/50">
                            <span>Total</span>
                            <span x-text="'₹' + ($store.shop.cartSubtotal + ($store.shop.cartSubtotal >= 1999 ? 0 : 199)).toLocaleString('en-IN')"></span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="/cart" @click="$store.shop.isCartOpen = false" class="text-center border border-[var(--color-ebony)] hover:bg-[var(--color-ebony)] hover:text-white text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-wider py-3 rounded-full transition-colors">
                            View Bag
                        </a>
                        <a href="/checkout" @click="$store.shop.isCartOpen = false" class="text-center bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-wider py-3 rounded-full shadow-lg transition-colors">
                            Checkout
                        </a>
                    </div>
                </div>
            </template>

        </div>
    </div>
</div>
