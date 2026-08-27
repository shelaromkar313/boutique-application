@extends('layouts.app')

@section('title', 'ESTILO WEAR | Slay Every Look')

@section('content')

<div class="pb-16 bg-[var(--color-offwhite)]">

    {{-- ══ 1. LARGE FUNCTIONAL SEARCH BAR ══ --}}
    <section class="bg-white/90 backdrop-blur-sm border-b border-[var(--color-bisque)]/30 py-3 sm:py-5 px-3 sm:px-6 shadow-sm">
        <div class="max-w-4xl mx-auto">
            <form action="/shop" method="GET" class="relative group" role="search" aria-label="Search Boutique Products">
                <svg class="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-[var(--color-ebony)]/40 group-focus-within:text-[var(--color-rose-antique)] transition-colors duration-300 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text"
                       name="search"
                       placeholder="Search kurtis, sarees, festive wear, fabrics..."
                       class="w-full pl-10 sm:pl-14 pr-24 sm:pr-28 py-3 sm:py-4 rounded-full border border-[var(--color-bisque)]/70 bg-white shadow-[var(--shadow-soft)] text-xs sm:text-sm font-sans text-[var(--color-ebony)] placeholder-[var(--color-ebony)]/40 tracking-wide focus:outline-none focus:border-[var(--color-rose-antique)] focus:shadow-[var(--shadow-luxury)] transition-all duration-300" />
                <button type="submit"
                        class="absolute right-2 sm:right-3 top-1/2 -translate-y-1/2 bg-[var(--color-rose-antique)] hover:bg-[var(--color-ebony)] text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-full text-xs font-sans font-bold tracking-wider uppercase shadow-md transition-all duration-300 flex items-center gap-1.5 cursor-pointer">
                    <span>Search</span>
                </button>
            </form>
        </div>
    </section>

    {{-- ══ 2. CIRCULAR CATEGORY SECTION (HORIZONTAL SLIDER) ══ --}}
    <section class="bg-white border-b border-[var(--color-bisque)]/30 py-5 sm:py-7 relative group"
             x-data="{
                 scroll(dir) {
                     const el = this.$refs.circleSlider;
                     const amt = el.clientWidth * 0.6;
                     el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                 }
             }">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 relative">
            <!-- Left Arrow -->
            <button @click="scroll('left')"
                    class="hidden md:flex absolute -left-2 lg:-left-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-white/95 text-[var(--color-ebony)] shadow-md border border-[var(--color-bisque)]/60 items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110 cursor-pointer"
                    aria-label="Previous categories">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <!-- Slider Container -->
            <div x-ref="circleSlider" class="flex gap-4 sm:gap-7 overflow-x-auto pb-2 pt-1 scroll-smooth snap-x" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($circleCategories as $cat)
                <div class="flex-none snap-start">
                    <a href="{{ $cat['path'] }}" class="flex flex-col items-center gap-2 group/cat">
                        <div class="w-[62px] h-[62px] sm:w-[86px] sm:h-[86px] lg:w-[96px] lg:h-[96px] rounded-full overflow-hidden border-2 border-[var(--color-bisque)]/60 p-[3px] sm:p-1 group-hover/cat:border-[var(--color-rose-antique)] group-hover/cat:shadow-[var(--shadow-floating)] transition-all duration-300">
                            <div class="w-full h-full rounded-full overflow-hidden">
                                <img src="{{ $cat['image'] }}" alt="{{ $cat['name'] }}" class="w-full h-full object-cover group-hover/cat:scale-110 transition-transform duration-500" loading="lazy" />
                            </div>
                        </div>
                        <span class="text-[10px] sm:text-xs font-sans font-semibold text-[var(--color-ebony)]/80 text-center tracking-wide group-hover/cat:text-[var(--color-rose-antique)] transition-colors duration-300 max-w-[68px] sm:max-w-[100px] leading-tight">{{ $cat['name'] }}</span>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Right Arrow -->
            <button @click="scroll('right')"
                    class="hidden md:flex absolute -right-2 lg:-right-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-white/95 text-[var(--color-ebony)] shadow-md border border-[var(--color-bisque)]/60 items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110 cursor-pointer"
                    aria-label="Next categories">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </section>

    {{-- ══ 3. HERO BANNER ══ --}}
    <section class="relative w-full overflow-hidden bg-[var(--color-ebony)]" style="height: clamp(480px, 80vh, 900px);" aria-label="Hero Banner — New Collection">
        <img src="/storage/hero/hero-main.jpg" alt="Estilo Wear — Royal Traditional Saree Collection" class="absolute inset-0 w-full h-full object-cover object-[78%_top] sm:object-[82%_top] md:object-[right_top]" fetchpriority="high" />
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

        {{-- ── 1. Featured Categories Horizontal Slider ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.catSlider;
                         const amt = el.clientWidth * 0.75;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-6 sm:mb-10 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Curated Collections</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Explore Boutique Categories</h2>
                </div>
                <!-- Controls -->
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <button @click="scroll('left')"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                            aria-label="Previous Categories">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="scroll('right')"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                            aria-label="Next Categories">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <div x-ref="catSlider" class="flex gap-4 sm:gap-6 overflow-x-auto pb-4 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($categoriesData as $category)
                @php
                    $catName    = is_object($category) ? $category->name    : $category['name'];
                    $catTagline = is_object($category) ? $category->tagline  : ($category['tagline'] ?? '');
                    $catImage   = is_object($category) ? $category->image    : $category['image'];
                @endphp
                <div class="flex-none snap-start w-[200px] sm:w-[240px] lg:w-[260px]">
                    <a href="/shop?category={{ urlencode(explode(' ', $catName)[0]) }}" class="group relative block rounded-2xl overflow-hidden shadow-sm hover:shadow-[var(--shadow-floating)] transition-all duration-500 aspect-[3/4] border border-[var(--color-bisque)]/40">
                        <img src="{{ $catImage }}" alt="{{ $catName }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy" />
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ebony)]/90 via-[var(--color-ebony)]/30 to-transparent flex flex-col justify-end p-4 sm:p-5">
                            <span class="text-[9px] sm:text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-blush)]">{{ $catTagline }}</span>
                            <h3 class="font-serif text-base sm:text-xl font-bold text-white group-hover:text-[var(--color-champagne)] transition-colors">{{ $catName }}</h3>
                            <span class="text-xs font-sans text-white/80 flex items-center gap-1 mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                Discover Now
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </span>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── 2. Trending Collection Horizontal Slider ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-[var(--color-champagne-light)]/30 py-10 sm:py-16 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/40 relative group"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.trendingSlider;
                         const amt = el.clientWidth * 0.75;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-10 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Handpicked Styles</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Trending This Season</h2>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="/shop?filter=trending" class="inline-flex items-center gap-1.5 text-xs font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors border-b border-[var(--color-ebony)] pb-1">
                        View All
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <!-- Arrow Controls -->
                    <div class="flex items-center gap-1.5">
                        <button @click="scroll('left')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Previous Trending">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="scroll('right')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Next Trending">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Horizontal Products Slider -->
            <div x-ref="trendingSlider" class="flex gap-4 sm:gap-6 overflow-x-auto pb-4 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($trendingProducts as $product)
                <div class="flex-none snap-start w-[220px] sm:w-[260px] lg:w-[280px]">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── Chikankari Editorial Banner ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden bg-[var(--color-ebony)] text-white shadow-2xl">
                <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[380px] sm:min-h-[480px]">
                    <div class="relative h-52 sm:h-72 lg:h-full">
                        <img src="/storage/editorial/chikankari-banner.jpg" alt="Lucknowi Chikankari Artistry" class="w-full h-full object-cover object-top brightness-95" loading="lazy" />
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

        {{-- ── 3. Luxury Sarees Spotlight Horizontal Slider ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.sareeSlider;
                         const amt = el.clientWidth * 0.75;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-10 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Six Yards of Royalty</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Luxury Banarasi & Silk Sarees</h2>
                    <p class="text-xs text-[var(--color-ebony)]/60 font-sans mt-1">Pure silk mark certified sarees featuring gold zari brocade Kadwa weaving.</p>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="/shop?category=Sarees" class="inline-flex items-center gap-1.5 text-xs font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors border-b border-[var(--color-ebony)] pb-1">
                        View All Sarees
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <!-- Arrow Controls -->
                    <div class="flex items-center gap-1.5">
                        <button @click="scroll('left')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Previous Sarees">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="scroll('right')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Next Sarees">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Horizontal Sarees Slider -->
            <div x-ref="sareeSlider" class="flex gap-4 sm:gap-6 overflow-x-auto pb-4 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($sareeSpotlight as $product)
                <div class="flex-none snap-start w-[220px] sm:w-[260px] lg:w-[280px]">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── 4. Shop By Heritage Fabric Horizontal Slider ── --}}
        <section class="bg-[var(--color-bisque)]/20 py-10 sm:py-16"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.fabricSlider;
                         const amt = el.clientWidth * 0.6;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-7 sm:mb-10 gap-3">
                    <div>
                        <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Tactile Luxury</span>
                        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)] mt-1">Shop By Heritage Fabric</h2>
                    </div>
                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <button @click="scroll('left')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Previous Fabrics">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="scroll('right')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Next Fabrics">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <div x-ref="fabricSlider" class="flex gap-3 sm:gap-4 overflow-x-auto pb-3 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                    @foreach($fabricsData as $fabric)
                    <div class="flex-none snap-start w-[145px] sm:w-[185px] lg:w-[200px]">
                        <a href="/shop?fabric={{ urlencode($fabric['name']) }}" class="block bg-white hover:bg-[var(--color-rose-antique)] hover:text-white p-4 sm:p-5 rounded-2xl border border-[var(--color-bisque)]/60 text-center shadow-sm hover:shadow-lg transition-all duration-300 group h-full">
                            <h4 class="font-serif text-sm sm:text-base font-bold text-[var(--color-ebony)] group-hover:text-white transition-colors truncate">{{ $fabric['name'] }}</h4>
                            <span class="text-[10px] sm:text-[11px] font-sans text-[var(--color-ebony)]/60 group-hover:text-white/80 transition-colors block mt-1">{{ $fabric['count'] }}</span>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ── 5. Shop By Occasion Horizontal Slider ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.occasionSlider;
                         const amt = el.clientWidth * 0.7;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-10 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Style For Every Event</span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)] mt-1">Shop By Occasion</h2>
                </div>
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <button @click="scroll('left')"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                            aria-label="Previous Occasion">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="scroll('right')"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                            aria-label="Next Occasion">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <div x-ref="occasionSlider" class="flex gap-4 sm:gap-5 overflow-x-auto pb-3 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($occasionsData as $occ)
                <div class="flex-none snap-start w-[180px] sm:w-[220px] lg:w-[250px]">
                    <a href="/shop?occasion={{ urlencode($occ['name']) }}" class="group relative block rounded-2xl overflow-hidden aspect-[4/5] shadow-sm hover:shadow-[var(--shadow-floating)] transition-all duration-500 border border-[var(--color-bisque)]/40">
                        <img src="{{ $occ['image'] }}" alt="{{ $occ['name'] }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
                        <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-ebony)]/90 via-[var(--color-ebony)]/20 to-transparent flex flex-col justify-end p-3 sm:p-4 text-center">
                            <h3 class="font-serif text-sm sm:text-lg font-bold text-white group-hover:text-[var(--color-blush)] transition-colors">{{ $occ['name'] }}</h3>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── 6. New Arrivals Collection Horizontal Slider ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.newArrivalsSlider;
                         const amt = el.clientWidth * 0.75;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-10 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Fresh Off The Looms</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">New Arrivals Collection</h2>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="/shop?filter=new" class="inline-flex items-center gap-1.5 text-xs font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors border-b border-[var(--color-ebony)] pb-1">
                        Explore All
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <!-- Arrow Controls -->
                    <div class="flex items-center gap-1.5">
                        <button @click="scroll('left')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Previous New Arrivals">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="scroll('right')"
                                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Next New Arrivals">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Horizontal Product Slider -->
            <div x-ref="newArrivalsSlider" class="flex gap-4 sm:gap-6 overflow-x-auto pb-4 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($newArrivals as $product)
                <div class="flex-none snap-start w-[220px] sm:w-[260px] lg:w-[280px]">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </section>

        {{-- ── 7. Client Testimonials & Reviews (Enhanced Horizontal Slider) ── --}}
        <section class="bg-[var(--color-champagne-light)]/50 py-10 sm:py-16 border-y border-[var(--color-bisque)]/50"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.reviewsSlider;
                         const amt = el.clientWidth * 0.8;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-8 sm:mb-12 gap-3">
                    <div>
                        <div class="inline-flex items-center gap-2 text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">
                            <span>★ 4.9 / 5.0 Rating</span>
                            <span>•</span>
                            <span>Over 2,400+ Happy Patrons</span>
                        </div>
                        <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Words From Our Boutique Patrons</h2>
                        <p class="text-xs sm:text-sm text-[var(--color-ebony)]/60 font-sans mt-1">Slide to read authentic reviews from verified Estilo shoppers across India.</p>
                    </div>
                    <!-- Slider Arrows -->
                    <div class="flex items-center gap-2 self-end sm:self-auto">
                        <button @click="scroll('left')"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Previous Review">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="scroll('right')"
                                class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                                aria-label="Next Review">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Reviews Horizontal Slider -->
                <div x-ref="reviewsSlider" class="flex gap-5 sm:gap-6 overflow-x-auto pb-4 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                    @foreach($testimonialsData as $t)
                    <div class="flex-none snap-start w-[285px] sm:w-[360px] lg:w-[390px] bg-white p-6 sm:p-8 rounded-3xl shadow-sm border border-[var(--color-bisque)]/60 flex flex-col justify-between space-y-5 hover:shadow-[var(--shadow-floating)] transition-all duration-300">
                        <div class="space-y-3.5">
                            <!-- Star Rating & Verified Badge -->
                            <div class="flex items-center justify-between">
                                <div class="flex text-amber-500 gap-1 text-sm">
                                    @for($i = 0; $i < $t['rating']; $i++)
                                        <svg class="w-4 h-4 fill-amber-500" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    @endfor
                                </div>
                                @if(!empty($t['verified']))
                                <span class="inline-flex items-center gap-1 text-[10px] font-sans font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                    <svg class="w-3 h-3 fill-emerald-600" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Verified Buyer
                                </span>
                                @endif
                            </div>

                            <!-- Product Purchased Tag -->
                            @if(!empty($t['product']))
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/50 text-[10px] sm:text-xs font-sans text-[var(--color-ebony)]/70">
                                <svg class="w-3 h-3 text-[var(--color-rose-antique)] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span class="truncate font-medium">{{ $t['product'] }}</span>
                            </div>
                            @endif

                            <!-- Comment -->
                            <p class="text-xs sm:text-[13px] font-sans text-[var(--color-ebony)]/80 leading-relaxed italic">
                                "{{ $t['comment'] }}"
                            </p>
                        </div>

                        <!-- Reviewer Info Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-[var(--color-bisque)]/30">
                            <div class="flex items-center gap-3">
                                <img src="{{ $t['avatar'] }}" alt="{{ $t['name'] }}" class="w-10 h-10 rounded-full object-cover border-2 border-[var(--color-rose-antique)]" loading="lazy" />
                                <div>
                                    <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)]">{{ $t['name'] }}</h4>
                                    <span class="text-[11px] font-sans text-[var(--color-ebony)]/50 block">{{ $t['role'] }} • {{ $t['city'] }}</span>
                                </div>
                            </div>
                            @if(!empty($t['date']))
                            <span class="text-[10px] font-sans text-[var(--color-ebony)]/40">{{ $t['date'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ── 8. Instagram Lookbook Horizontal Slider ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.instaSlider;
                         const amt = el.clientWidth * 0.7;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-7 sm:mb-10 gap-3">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"/></svg>
                        #SlayEveryLook
                    </div>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Follow Us On Instagram @EstiloWear</h2>
                </div>
                <div class="flex items-center gap-2 self-end sm:self-auto">
                    <button @click="scroll('left')"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                            aria-label="Previous Instagram Posts">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="scroll('right')"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full border border-[var(--color-bisque)]/80 bg-white text-[var(--color-ebony)] shadow-sm flex items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all cursor-pointer"
                            aria-label="Next Instagram Posts">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>

            <!-- Horizontal Instagram Slider -->
            <div x-ref="instaSlider" class="flex gap-3 sm:gap-4 overflow-x-auto pb-3 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($instagramPosts as $post)
                <div class="flex-none snap-start w-[180px] sm:w-[240px] lg:w-[270px]">
                    <div class="group relative rounded-xl sm:rounded-2xl overflow-hidden aspect-square border border-[var(--color-bisque)]/40">
                        <img src="{{ $post['image'] }}" alt="Instagram Look" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" />
                        <div class="absolute inset-0 bg-[var(--color-ebony)]/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white space-y-1 sm:space-y-2">
                            <svg class="w-6 h-6 text-[var(--color-blush)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"/></svg>
                            <span class="text-[10px] sm:text-xs font-sans font-bold">{{ $post['tag'] }}</span>
                            <span class="text-[9px] sm:text-[10px] font-sans text-white/80">{{ $post['likes'] }} Likes</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

    </div>
</div>

@endsection

