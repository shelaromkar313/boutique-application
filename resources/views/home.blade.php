@extends('layouts.app')

@section('title', 'ESTILO WEAR | Slay Every Look')

@section('content')


<div class="pb-16 bg-[var(--color-offwhite)]">

    {{-- ══ 1. LARGE SEARCH BAR ══ --}}
    <section class="bg-white/90 backdrop-blur-sm border-b border-[var(--color-bisque)]/30 py-3 sm:py-5 px-3 sm:px-6 shadow-sm">
        <div class="max-w-4xl mx-auto">
            <div class="relative cursor-pointer group" role="search" aria-label="Open search">
                <svg class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-[var(--color-ebony)]/40 group-hover:text-[var(--color-rose-antique)] transition-colors duration-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <div class="w-full pl-10 sm:pl-14 pr-4 sm:pr-24 py-3 sm:py-4 rounded-full border border-[var(--color-bisque)]/70 bg-white shadow-[var(--shadow-soft)] text-xs sm:text-sm font-sans text-[var(--color-ebony)]/50 tracking-wide select-none group-hover:border-[var(--color-rose-antique)] group-hover:shadow-[var(--shadow-luxury)] transition-all duration-300 flex items-center">
                    Search kurtis, sarees, festive wear...
                </div>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 bg-[var(--color-ebony)] text-white group-hover:bg-[var(--color-rose-antique)] px-4 sm:px-5 py-2 rounded-full text-xs font-sans font-semibold tracking-wider uppercase transition-colors duration-300 hidden sm:flex items-center gap-1.5">
                    <span>Search</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ 2. CIRCULAR CATEGORY SECTION ══ --}}
    <section class="bg-white border-b border-[var(--color-bisque)]/30 py-5 sm:py-9">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex gap-3 sm:gap-7 overflow-x-auto pb-3 pt-1" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($circleCategories as $cat)
                <div class="flex-none">
                    <a href="{{ $cat['path'] }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-[60px] h-[60px] sm:w-[86px] sm:h-[86px] lg:w-[98px] lg:h-[98px] rounded-full overflow-hidden border-2 border-[var(--color-bisque)]/60 p-[3px] sm:p-1 group-hover:border-[var(--color-rose-antique)] group-hover:shadow-[var(--shadow-floating)] transition-all duration-300">
                            <div class="w-full h-full rounded-full overflow-hidden">
                                <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" />
                            </div>
                        </div>
                        <span class="text-[10px] sm:text-xs font-sans font-semibold text-[var(--color-ebony)]/80 text-center tracking-wide group-hover:text-[var(--color-rose-antique)] transition-colors duration-300 max-w-[68px] sm:max-w-[100px] leading-tight">{{ $cat['name'] }}</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ══ 3. HERO BANNER ══ --}}
    <section class="relative w-full overflow-hidden bg-[var(--color-ebony)]" style="height: clamp(480px, 80vh, 900px);" aria-label="Hero Banner — New Collection">
        <img src="/images/hero/hero-main.jpg" alt="Estilo Wear — Royal Traditional Saree Collection" class="absolute inset-0 w-full h-full object-cover object-[78%_top] sm:object-[82%_top] md:object-[right_top]" fetchpriority="high" />
        <div class="absolute inset-0 bg-gradient-to-r from-black/90 via-black/50 to-transparent lg:to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/20"></div>

        <div class="relative h-full flex items-center">
            <div class="max-w-7xl mx-auto px-5 sm:px-10 lg:px-16 w-full">
                <div class="max-w-[580px] space-y-4 sm:space-y-6 text-white">
                    <div class="flex items-center gap-2 sm:gap-3 animate-[fadeIn_0.75s_ease-out]">
                        <span class="block w-7 sm:w-10 h-px bg-[var(--color-champagne)] flex-none"></span>
                        <span class="text-[10px] sm:text-xs font-sans font-bold tracking-[0.25em] sm:tracking-[0.35em] text-[var(--color-champagne)] uppercase">NEW COLLECTION — 2025</span>
                    </div>

                    <h1 class="font-serif text-4xl sm:text-6xl lg:text-7xl font-bold leading-[1.05] tracking-tight animate-[fadeIn_0.85s_ease-out]">
                        Timeless<br />
                        <em class="not-italic text-[var(--color-blush)]">Indian</em><br />
                        Elegance
                    </h1>

                    <p class="text-xs sm:text-base font-sans text-white/80 leading-relaxed font-light max-w-[340px] sm:max-w-[440px] animate-[fadeIn_0.75s_ease-out]">
                        Handcrafted Indian fashion for the modern woman — curated from artisan weavers across India.
                    </p>

                    <div class="flex flex-wrap items-center gap-4 sm:gap-6 pt-2 sm:pt-3 animate-[fadeIn_0.75s_ease-out]">
                        <a href="/shop" class="inline-flex items-center gap-2 sm:gap-3 bg-white text-[var(--color-ebony)] hover:bg-[var(--color-blush)] font-sans text-xs font-bold uppercase tracking-[0.15em] sm:tracking-[0.2em] px-6 sm:px-8 py-3 sm:py-4 rounded-full shadow-2xl transition-all duration-300 hover:scale-[1.03] group">
                            Shop Now
                            <svg class="w-4 h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="/shop?filter=new" class="text-xs font-sans font-semibold text-white/85 hover:text-white uppercase tracking-[0.15em] sm:tracking-[0.2em] border-b border-white/40 hover:border-white pb-1 transition-all duration-200">
                            View New Arrivals
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-[var(--color-offwhite)]/30 to-transparent pointer-events-none"></div>
    </section>

    {{-- ══ MAIN CONTENT SECTIONS ══ --}}
    <div class="space-y-12 sm:space-y-20 pt-10 sm:pt-20">

        {{-- ── Featured Categories Grid ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12 space-y-2">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Curated Collections</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)]">Explore Boutique Categories</h2>
                <div class="w-12 h-0.5 bg-[var(--color-rose-antique)] mx-auto rounded-full mt-3"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-6">
                @foreach($categoriesData as $category)
                @php
                    $catName    = is_object($category) ? $category->name    : $category['name'];
                    $catTagline = is_object($category) ? $category->tagline  : ($category['tagline'] ?? '');
                    $catImage   = is_object($category) ? $category->image    : $category['image'];
                    $catSlug    = is_object($category) ? $category->slug     : ($category['id'] ?? '');
                @endphp
                <a href="/shop?category={{ urlencode(explode(' ', $catName)[0]) }}" class="group relative block rounded-2xl overflow-hidden shadow-sm hover:shadow-[var(--shadow-floating)] transition-all duration-500 aspect-[3/4] border border-[var(--color-bisque)]/40">
                    <img src="{{ $catImage }}" alt="{{ $catName }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ebony)]/90 via-[var(--color-ebony)]/30 to-transparent flex flex-col justify-end p-3 sm:p-5">
                        <span class="text-[9px] sm:text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-blush)]">{{ $catTagline }}</span>
                        <h3 class="font-serif text-base sm:text-xl font-bold text-white group-hover:text-[var(--color-champagne)] transition-colors">{{ $catName }}</h3>
                        <span class="text-xs font-sans text-white/80 flex items-center gap-1 mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            Discover Now
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        {{-- ── Trending Collection ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-[var(--color-champagne-light)]/30 py-10 sm:py-16 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/40">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-12 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Handpicked Styles</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Trending This Season</h2>
                </div>
                <a href="/shop?filter=trending" class="inline-flex items-center gap-2 text-xs font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors border-b border-[var(--color-ebony)] pb-1">
                    View All Trending
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($trendingProducts as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>

        {{-- ── Chikankari Editorial Banner ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden bg-[var(--color-ebony)] text-white shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[380px] sm:min-h-[480px]">
                    <div class="relative h-52 sm:h-72 lg:h-full">
                        <img src="/images/editorial/chikankari-banner.jpg" alt="Lucknowi Chikankari Artistry" class="w-full h-full object-cover object-top brightness-95" />
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-transparent to-[var(--color-ebony)] hidden lg:block"></div>
                    </div>
                    <div class="p-6 sm:p-12 lg:p-16 flex flex-col justify-center space-y-4 sm:space-y-6">
                        <div class="inline-flex items-center gap-2 text-[var(--color-blush)] text-xs font-sans font-bold uppercase tracking-widest">
                            ✦ Royal Heritage Craftsmanship
                        </div>
                        <h2 class="font-serif text-2xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
                            The Lucknowi Chikankari & Mukaish Symphony
                        </h2>
                        <p class="text-xs sm:text-sm font-sans text-white/80 leading-relaxed font-light">
                            Each stitch tells a century-old story. Hand-embroidered by women artisans in Lucknow, our Chikankari collection blends airy cotton mulmul with delicate silver Mukaish sequins for effortless festive regalness.
                        </p>
                        <div class="pt-2">
                            <a href="/shop?category=Chikankari+Kurtis" class="inline-flex items-center gap-3 bg-[var(--color-blush)] hover:bg-white text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-widest px-6 sm:px-8 py-3 sm:py-4 rounded-full shadow-lg transition-all">
                                Explore Chikankari Kurtis
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ── Luxury Sarees Spotlight ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12 space-y-2">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Six Yards of Royalty</span>
                <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)]">Luxury Banarasi & Silk Sarees</h2>
                <p class="text-xs text-[var(--color-ebony)]/60 font-sans">Pure silk mark certified sarees featuring gold zari brocade Kadwa weaving.</p>
                <div class="w-12 h-0.5 bg-[var(--color-rose-antique)] mx-auto rounded-full mt-3"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($sareeSpotlight as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>

        {{-- ── Shop By Heritage Fabric ── --}}
        <section class="bg-[var(--color-bisque)]/20 py-10 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto mb-7 sm:mb-10">
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Tactile Luxury</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)] mt-1">Shop By Heritage Fabric</h2>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                    @foreach($fabricsData as $fabric)
                    <a href="/shop?fabric={{ urlencode($fabric['name']) }}" class="bg-white hover:bg-[var(--color-rose-antique)] hover:text-white p-3 sm:p-5 rounded-2xl border border-[var(--color-bisque)]/60 text-center shadow-sm hover:shadow-lg transition-all duration-300 group">
                        <h4 class="font-serif text-sm sm:text-base font-bold text-[var(--color-ebony)] group-hover:text-white transition-colors">{{ $fabric['name'] }}</h4>
                        <span class="text-[10px] sm:text-[11px] font-sans text-[var(--color-ebony)]/60 group-hover:text-white/80 transition-colors block mt-1">{{ $fabric['count'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ── Shop By Occasion ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-8 sm:mb-12 space-y-2">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Style For Every Event</span>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Shop By Occasion</h2>
                <div class="w-12 h-0.5 bg-[var(--color-rose-antique)] mx-auto rounded-full mt-3"></div>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($occasionsData as $occ)
                <a href="/shop?occasion={{ urlencode($occ['name']) }}" class="group relative rounded-2xl overflow-hidden aspect-[4/5] shadow-sm hover:shadow-[var(--shadow-floating)] transition-all duration-500">
                    <img src="{{ $occ['image'] }}" alt="{{ $occ['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ebony)]/90 via-[var(--color-ebony)]/20 to-transparent flex flex-col justify-end p-3 sm:p-4 text-center">
                        <h3 class="font-serif text-sm sm:text-lg font-bold text-white group-hover:text-[var(--color-blush)] transition-colors">{{ $occ['name'] }}</h3>
                    </div>
                </a>
                @endforeach
            </div>
        </section>

        {{-- ── New Arrivals Collection ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-12 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Fresh Off The Looms</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">New Arrivals Collection</h2>
                </div>
                <a href="/shop?filter=new" class="inline-flex items-center gap-2 text-xs font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors border-b border-[var(--color-ebony)] pb-1">
                    Explore All New Arrivals
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @foreach($newArrivals as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>

        {{-- ── Client Testimonials ── --}}
        <section class="bg-[var(--color-champagne-light)]/50 py-10 sm:py-16 border-y border-[var(--color-bisque)]/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-xl mx-auto mb-8 sm:mb-12">
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Real Estilo Women</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)] mt-1">Words From Our Boutique Patrons</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 sm:gap-8">
                    @foreach($testimonialsData as $t)
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-[var(--color-bisque)]/60 space-y-4 flex flex-col justify-between">
                        <div class="space-y-3">
                            <div class="flex text-amber-500 gap-1 text-sm">
                                @for($i = 0; $i < $t['rating']; $i++)
                                    <svg class="w-4 h-4 fill-amber-500" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                @endfor
                            </div>
                            <p class="text-xs font-sans text-[var(--color-ebony)]/80 leading-relaxed italic">"{{ $t['comment'] }}"</p>
                        </div>
                        <div class="flex items-center gap-3 pt-4 border-t border-[var(--color-bisque)]/30">
                            <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="w-10 h-10 rounded-full object-cover border border-[var(--color-rose-antique)]" />
                            <div>
                                <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)]">{{ $t['name'] }}</h4>
                                <span class="text-[11px] font-sans text-[var(--color-ebony)]/50">{{ $t['role'] }} • {{ $t['city'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ── Instagram Lookbook Grid ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-xl mx-auto mb-7 sm:mb-10 space-y-2">
                <div class="inline-flex items-center gap-1.5 text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"/></svg>
                    #SlayEveryLook
                </div>
                <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Follow Us On Instagram @EstiloWear</h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4">
                @foreach($instagramPosts as $post)
                <div class="group relative rounded-xl sm:rounded-2xl overflow-hidden aspect-square">
                    <img src="{{ $post['image'] }}" alt="Instagram Look" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    <div class="absolute inset-0 bg-[var(--color-ebony)]/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white space-y-1 sm:space-y-2">
                        <svg class="w-6 h-6 text-[var(--color-blush)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"/></svg>
                        <span class="text-[10px] sm:text-xs font-sans font-bold">{{ $post['tag'] }}</span>
                        <span class="text-[9px] sm:text-[10px] font-sans text-white/80">{{ $post['likes'] }} Likes</span>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

    </div>
</div>

@endsection
