@extends('layouts.app')

@section('title', 'My Wishlist | ESTILO WEAR')

@section('content')
<div class="pb-16 sm:pb-24 pt-8 sm:pt-12" x-data>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-8 sm:mb-12">
            <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Your Saved Pieces</span>
            <h1 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-2">Boutique Wishlist</h1>
            <p class="text-xs font-sans text-[var(--color-ebony)]/60 mt-1" x-text="$store.shop.wishlist.length + ' item(s) saved in your atelier wishlist'"></p>
        </div>

        {{-- Wishlist Items Grid --}}
        <template x-if="$store.shop.wishlist.length > 0">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                <template x-for="(item, index) in $store.shop.wishlist" :key="index">
                    <div class="bg-white rounded-2xl border border-[var(--color-bisque)]/60 overflow-hidden shadow-sm hover:shadow-[var(--shadow-floating)] transition-all flex flex-col justify-between">
                        <div class="relative aspect-[3/4] bg-[var(--color-champagne-light)]">
                            <img :src="item.image" :alt="item.name" class="w-full h-full object-cover object-top" />
                            <button @click="$store.shop.toggleWishlist(item)" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center text-[var(--color-rose-antique)] hover:scale-110 transition-transform">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                        </div>

                        <div class="p-4 space-y-3 flex-1 flex flex-col justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-[var(--color-rose-antique)]" x-text="item.category"></span>
                                <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)] truncate" x-text="item.name"></h4>
                                <span class="font-serif text-base font-bold text-[var(--color-ebony)] mt-1 block" x-text="'₹' + item.price.toLocaleString('en-IN')"></span>
                            </div>

                            <button @click="$store.shop.addToCart({ id: item.id, name: item.name, price: item.price, image: item.image, color: 'Standard', size: 'Free Size' }); $store.shop.toggleWishlist(item)" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider py-2.5 rounded-full transition-colors flex items-center justify-center gap-1.5 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg> Move to Bag
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </template>

        {{-- Empty Wishlist State --}}
        <template x-if="$store.shop.wishlist.length === 0">
            <div class="text-center py-20 bg-white rounded-3xl border border-[var(--color-bisque)]/50 max-w-md mx-auto p-8 space-y-4 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-[var(--color-blush)]/30 flex items-center justify-center mx-auto text-[var(--color-rose-antique)]">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </div>
                <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">Your Wishlist is Empty</h3>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60 max-w-md mx-auto">Browse our traditional boutique collection and tap the heart icon on any outfit to save it here.</p>
                <a href="/shop" class="inline-flex items-center gap-2 bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest px-8 py-3.5 rounded-full hover:bg-[var(--color-rose-deep)] transition-colors">
                    Explore Collections →
                </a>
            </div>
        </template>

    </div>
</div>
@endsection
