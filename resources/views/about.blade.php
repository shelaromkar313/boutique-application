@extends('layouts.app')

@section('title', 'About Us | ESTILO WEAR')

@section('content')

<div class="pb-16 sm:pb-24 pt-4 sm:pt-6 space-y-12 sm:space-y-20">
    
    <!-- Banner -->
    <div class="relative bg-[var(--color-ebony)] text-white py-16 sm:py-24 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-[var(--color-ebony)] via-[var(--color-ebony)]/70 to-transparent z-10"></div>
        <img src="/hero-kurti-model.png" alt="Estilo Wear Heritage" class="absolute inset-0 w-full h-full object-cover object-top opacity-30" />

        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-4 animate-[fadeIn_0.5s_ease-out]">
            <span class="text-xs font-sans font-bold text-[var(--color-blush)] uppercase tracking-[0.35em]">Estilo Wear Story</span>
            <h1 class="font-serif text-2xl sm:text-6xl font-bold text-white max-w-3xl mx-auto leading-tight">
                Crafting Timeless Indian Couture for the Modern Connoisseur
            </h1>
            <p class="text-xs sm:text-sm font-sans text-white/80 max-w-2xl mx-auto font-light leading-relaxed">
                Founded with a vision to preserve centuries of royal weaving traditions while designing chic silhouettes for contemporary celebrations.
            </p>
        </div>
    </div>

    <!-- Brand Ethos -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="space-y-6">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Our Heritage & Soul</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] leading-tight">
                    Where Royal Tradition Meets Everyday Grace
                </h2>
                <p class="text-xs sm:text-sm font-sans text-[var(--color-ebony)]/80 leading-relaxed font-light">
                    At <strong>Estilo Wear</strong>, we believe every piece of clothing is a canvas of living art. From the intricate shadow work of Lucknowi Chikankari to the grand gold brocades of Varanasi Kadwa looms, our boutique curates outfits that make women feel regal, confident, and effortlessly beautiful.
                </p>
                <p class="text-xs sm:text-sm font-sans text-[var(--color-ebony)]/80 leading-relaxed font-light">
                    We collaborate directly with over 300 women weavers and master artisans across Uttar Pradesh, West Bengal, Rajasthan, and Tamil Nadu, ensuring fair trade practices and preserving authentic handloom legacies.
                </p>
            </div>

            <div class="relative rounded-3xl overflow-hidden shadow-[var(--shadow-floating)] aspect-[4/3] border border-[var(--color-bisque)]/60">
                <img src="https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=1000&q=80" alt="Handloom Weaving" class="w-full h-full object-cover" />
            </div>
        </div>
    </section>

    <!-- Pillars -->
    <section class="bg-[var(--color-champagne-light)]/50 py-10 sm:py-16 border-y border-[var(--color-bisque)]/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-12">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">The Estilo Standard</span>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)] mt-1">Our Core Promises</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 sm:gap-8">
                <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/60 space-y-3 sm:space-y-4 text-center hover:shadow-[var(--shadow-soft)] transition-shadow">
                    <div class="w-14 h-14 rounded-full bg-[var(--color-rose-antique)]/10 flex items-center justify-center text-[var(--color-rose-antique)] text-2xl mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h3 class="font-serif text-lg sm:text-xl font-bold text-[var(--color-ebony)]">100% Certified Pure Silk</h3>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/70 leading-relaxed">
                        Every saree and silk outfit carries the Silk Mark of authenticity, woven with tested electro-plated zari threads.
                    </p>
                </div>

                <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/60 space-y-3 sm:space-y-4 text-center hover:shadow-[var(--shadow-soft)] transition-shadow">
                    <div class="w-14 h-14 rounded-full bg-[var(--color-rose-antique)]/10 flex items-center justify-center text-[var(--color-rose-antique)] text-2xl mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="font-serif text-lg sm:text-xl font-bold text-[var(--color-ebony)]">Artisan Empowerment</h3>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/70 leading-relaxed">
                        We directly empower female Chikankari artisans in Lucknow, providing sustainable livelihoods and education funds.
                    </p>
                </div>

                <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/60 space-y-3 sm:space-y-4 text-center hover:shadow-[var(--shadow-soft)] transition-shadow">
                    <div class="w-14 h-14 rounded-full bg-[var(--color-rose-antique)]/10 flex items-center justify-center text-[var(--color-rose-antique)] text-2xl mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="font-serif text-lg sm:text-xl font-bold text-[var(--color-ebony)]">Bespoke Fitting Service</h3>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/70 leading-relaxed">
                        Our in-house boutique tailors offer custom adjustments and neck modifications to ensure your outfit fits like a dream.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-[var(--color-ebony)] text-white p-8 sm:p-16 rounded-2xl sm:rounded-3xl shadow-[var(--shadow-floating)] space-y-4 sm:space-y-6">
            <span class="text-xs font-sans font-bold text-[var(--color-blush)] uppercase tracking-[0.3em]">Step Into Royalty</span>
            <h2 class="font-serif text-2xl sm:text-5xl font-bold max-w-2xl mx-auto leading-tight">
                Ready to Find Your Signature Look?
            </h2>
            <div>
                <a href="/shop" class="inline-flex items-center gap-2 sm:gap-3 bg-[var(--color-blush)] hover:bg-white text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-widest px-6 sm:px-8 py-3 sm:py-4 rounded-full shadow-lg transition-all group">
                    Explore Full Collection 
                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

</div>

@endsection
