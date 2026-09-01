@extends('layouts.app')

@section('title', 'ESTILO WEAR | Slay Every Look')

@section('content')

<div class="pb-16 bg-[var(--color-offwhite)]">





    {{-- ══ 2. CIRCULAR CATEGORY SECTION (CONTINUOUS AUTO-SLIDING MARQUEE) ══ --}}
    <section class="bg-white border-b border-[var(--color-bisque)]/30 py-5 sm:py-7 relative group"
             x-data="{
                 timer: null,
                 singleSetWidth: 0,
                 init() {
                     this.$nextTick(() => {
                         const el = this.$refs.circleSlider;
                         if (!el) return;
                         this.singleSetWidth = el.scrollWidth / 2;
                     });
                     this.startAutoScroll();
                 },
                 startAutoScroll() {
                     this.timer = setInterval(() => {
                         const el = this.$refs.circleSlider;
                         if (!el) return;
                         if (el.scrollLeft >= this.singleSetWidth) {
                             el.scrollLeft = 0;
                         } else {
                             el.scrollLeft += 1.5;
                         }
                     }, 20);
                 },
                 stopAutoScroll() {
                     if (this.timer) clearInterval(this.timer);
                 },
                 scroll(dir) {
                     const el = this.$refs.circleSlider;
                     const amt = el.clientWidth * 0.6;
                     el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                 }
             }"
             @mouseenter="stopAutoScroll()"
             @mouseleave="startAutoScroll()">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 relative">
            <!-- Left Arrow -->
            <button @click="scroll('left')"
                    class="hidden md:flex absolute -left-2 lg:-left-3 top-1/2 -translate-y-1/2 z-20 w-8 h-8 rounded-full bg-white/95 text-[var(--color-ebony)] shadow-md border border-[var(--color-bisque)]/60 items-center justify-center hover:bg-[var(--color-rose-antique)] hover:text-white transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110 cursor-pointer"
                    aria-label="Previous categories">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <!-- Slider Container -->
            <div x-ref="circleSlider" class="flex gap-4 sm:gap-7 overflow-x-auto pb-2 pt-1" style="scrollbar-width:none;-ms-overflow-style:none;">
                @php
                    $circleList = is_object($circleCategories) && method_exists($circleCategories, 'all') ? $circleCategories->all() : (array) $circleCategories;
                    $infiniteCircles = array_merge($circleList, $circleList, $circleList);
                @endphp
                @foreach($infiniteCircles as $cat)
                <div class="flex-none">
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

    {{-- ══ 3. HERO SLIDESHOW BANNER ══ --}}
    <section class="relative w-full overflow-hidden bg-[var(--color-ebony)] group"
             style="height: clamp(480px, 80vh, 900px);"
             aria-label="Hero Banner — New Collection"
             x-data="{
                 currentSlide: 0,
                 slides: [
                     {
                         tag: 'NEW COLLECTION — 2025',
                         titleline1: 'Timeless',
                         titleline2: 'Indian',
                         titleline3: 'Elegance',
                         desc: 'Handcrafted Indian fashion for the modern woman — curated from artisan weavers across India.',
                         image: '/storage/hero/hero-main.jpg',
                         btnText: 'Shop Now',
                         btnLink: '/shop',
                         subLinkText: 'View New Arrivals',
                         subLink: '/shop?filter=new'
                     },
                     {
                         tag: 'LUXURY SILK EDIT',
                         titleline1: 'Royal',
                         titleline2: 'Banarasi',
                         titleline3: 'Sarees',
                         desc: 'Pure silk mark certified sarees featuring gold zari brocade & Kadwa weaving from Varanasi.',
                         image: '/storage/hero/hero-slide-2.jpg',
                         btnText: 'Explore Sarees',
                         btnLink: '/shop?category=Sarees',
                         subLinkText: 'View Banarasi Silk',
                         subLink: '/shop?category=Sarees'
                     }
                 ],
                 timer: null,
                 init() {
                     this.startTimer();
                 },
                 startTimer() {
                     this.timer = setInterval(() => {
                         this.nextSlide();
                     }, 3500);
                 },
                 stopTimer() {
                     if (this.timer) clearInterval(this.timer);
                 },
                 nextSlide() {
                     this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                 },
                 prevSlide() {
                     this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
                 },
                 goToSlide(idx) {
                     this.currentSlide = idx;
                 }
             }"
             @mouseenter="stopTimer()"
             @mouseleave="startTimer()"
             x-init="init()">

        {{-- Background Images with Crossfade --}}
        <template x-for="(slide, idx) in slides" :key="idx">
            <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                 :class="currentSlide === idx ? 'opacity-100 z-10' : 'opacity-0 z-0'">
                <img :src="slide.image"
                     :alt="slide.titleline1 + ' ' + slide.titleline2"
                     class="w-full h-full object-cover object-[center_top] sm:object-[78%_top] md:object-[right_top] transition-transform duration-10000 ease-out transform scale-105"
                     :class="currentSlide === idx ? 'scale-100' : 'scale-105'"
                     fetchpriority="high"
                     decoding="async" />
                <div class="absolute inset-0 bg-gradient-to-r from-black/95 via-black/75 to-black/30 sm:via-black/55 sm:to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-black/30"></div>
            </div>
        </template>

        {{-- Content Overlay --}}
        <div class="relative z-20 h-full flex items-center">
            <div class="max-w-7xl mx-auto px-4 sm:px-10 lg:px-16 w-full">
                <template x-for="(slide, idx) in slides" :key="idx">
                    <div x-show="currentSlide === idx"
                         x-transition:enter="transition ease-out duration-700 delay-150"
                         x-transition:enter-start="opacity-0 translate-y-6"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="max-w-[580px] space-y-3.5 sm:space-y-6 text-white">

                        <div class="flex items-center gap-2 sm:gap-3">
                            <span class="block w-6 sm:w-10 h-px bg-[var(--color-champagne)] flex-none"></span>
                            <span class="text-[10px] sm:text-xs font-sans font-bold tracking-[0.2em] sm:tracking-[0.35em] text-[var(--color-champagne)] uppercase" x-text="slide.tag"></span>
                        </div>

                        <h1 class="font-serif text-3xl xs:text-4xl sm:text-6xl lg:text-7xl font-bold leading-[1.08] sm:leading-[1.05] tracking-tight">
                            <span x-text="slide.titleline1"></span><br />
                            <em class="not-italic text-[var(--color-blush)]" x-text="slide.titleline2"></em><br />
                            <span x-text="slide.titleline3"></span>
                        </h1>

                        <p class="text-xs sm:text-base font-sans text-white/90 leading-relaxed font-light max-w-[320px] sm:max-w-[440px]" x-text="slide.desc">
                        </p>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-6 pt-1 sm:pt-3">
                            <a :href="slide.btnLink" class="inline-flex items-center gap-2 sm:gap-3 bg-white text-[var(--color-ebony)] hover:bg-[var(--color-blush)] font-sans text-[11px] sm:text-xs font-bold uppercase tracking-[0.15em] sm:tracking-[0.2em] px-5 sm:px-8 py-2.5 sm:py-4 rounded-full shadow-2xl transition-all duration-300 hover:scale-[1.03] group">
                                <span x-text="slide.btnText"></span>
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a :href="slide.subLink" class="text-[11px] sm:text-xs font-sans font-semibold text-white/90 hover:text-white uppercase tracking-[0.12em] sm:tracking-[0.2em] border-b border-white/40 hover:border-white pb-0.5 sm:pb-1 transition-all duration-200" x-text="slide.subLinkText">
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Left / Right Navigation Arrows --}}
        <button @click="prevSlide()" class="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-black/40 hover:bg-[var(--color-rose-antique)] text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 hover:scale-110 cursor-pointer" aria-label="Previous Slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </button>

        <button @click="nextSlide()" class="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 z-30 w-10 h-10 rounded-full bg-black/40 hover:bg-[var(--color-rose-antique)] text-white backdrop-blur-md border border-white/20 flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 hover:scale-110 cursor-pointer" aria-label="Next Slide">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>

        {{-- Slide Dot Indicators --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2">
            <template x-for="(slide, idx) in slides" :key="idx">
                <button @click="goToSlide(idx)"
                        class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                        :class="currentSlide === idx ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/70'"
                        :aria-label="'Go to slide ' + (idx + 1)">
                </button>
            </template>
        </div>

        <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-[var(--color-offwhite)]/30 to-transparent pointer-events-none z-20"></div>
    </section>

    {{-- ══ MAIN CONTENT SECTIONS ══ --}}
    <div class="space-y-6 sm:space-y-10 pt-6 sm:pt-10">

        {{-- ── 1. Trending Collection Horizontal Slider (Top of page) ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 bg-[var(--color-champagne-light)]/30 py-6 sm:py-10 rounded-2xl sm:rounded-3xl border border-[var(--color-bisque)]/40 relative group"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.trendingSlider;
                         const amt = el.clientWidth * 0.75;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-5 sm:mb-7 gap-3">
                <div>
                    <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Handpicked Styles</span>
                    <h2 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] mt-1">Trending This Season</h2>
                </div>
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="/shop?filter=trending" class="inline-flex items-center gap-1.5 text-xs font-sans font-bold uppercase tracking-widest text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors border-b border-[var(--color-ebony)] pb-1">
                        View All
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
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

            <div x-ref="trendingSlider" class="flex gap-4 sm:gap-6 overflow-x-auto pb-4 pt-1 scroll-smooth snap-x snap-mandatory" style="scrollbar-width:none;-ms-overflow-style:none;">
                @foreach($trendingProducts as $product)
                <div class="flex-none snap-start w-[220px] sm:w-[260px] lg:w-[280px]">
                    @include('partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </section>





        {{-- ── 5. Shop By Occasion Continuous Auto-Sliding Marquee ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative group"
                 x-data="{
                     timer: null,
                     singleSetWidth: 0,
                     init() {
                         this.$nextTick(() => {
                             const el = this.$refs.occasionSlider;
                             if (!el) return;
                             // Calculate single set width for seamless infinite loop
                             this.singleSetWidth = el.scrollWidth / 3;
                         });
                         this.startAutoScroll();
                     },
                     startAutoScroll() {
                         this.timer = setInterval(() => {
                             const el = this.$refs.occasionSlider;
                             if (!el) return;
                             
                             // If we reached or passed the end of single set, reset to 0 silently
                             if (el.scrollLeft >= this.singleSetWidth) {
                                 el.scrollLeft = 0;
                             } else {
                                 el.scrollLeft += 1.5;
                             }
                         }, 20);
                     },
                     stopAutoScroll() {
                         if (this.timer) clearInterval(this.timer);
                     },
                     scroll(dir) {
                         const el = this.$refs.occasionSlider;
                         if (!el) return;
                         if (dir === 'right' && el.scrollLeft >= this.singleSetWidth) {
                             el.scrollLeft = 0;
                         } else if (dir === 'left' && el.scrollLeft <= 5) {
                             el.scrollLeft = this.singleSetWidth;
                         }
                         const amt = el.clientWidth * 0.7;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }"
                 @mouseenter="stopAutoScroll()"
                 @mouseleave="startAutoScroll()">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-5 sm:mb-7 gap-3">
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

            <div x-ref="occasionSlider" class="flex gap-4 sm:gap-5 overflow-x-auto pb-3 pt-1" style="scrollbar-width:none;-ms-overflow-style:none;">
                @php
                    $occList = is_object($occasionsData) && method_exists($occasionsData, 'all') ? $occasionsData->all() : (array) $occasionsData;
                    $infiniteOccasions = array_merge($occList, $occList, $occList);
                @endphp
                @foreach($infiniteOccasions as $occ)
                <div class="flex-none w-[180px] sm:w-[220px] lg:w-[250px]">
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
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-5 sm:mb-7 gap-3">
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
        <section class="bg-[var(--color-champagne-light)]/50 py-6 sm:py-10 border-y border-[var(--color-bisque)]/50"
                 x-data="{
                     scroll(dir) {
                         const el = this.$refs.reviewsSlider;
                         const amt = el.clientWidth * 0.8;
                         el.scrollBy({ left: dir === 'left' ? -amt : amt, behavior: 'smooth' });
                     }
                 }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-5 sm:mb-7 gap-3">
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

        {{-- ── 8. Instagram Lookbook & Continuous Playing Reels ── --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between mb-5 sm:mb-7 gap-3">
                <div class="space-y-1">
                    <div class="inline-flex items-center gap-1.5 text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-widest">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5" ry="5" stroke-width="2"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" stroke-width="2"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5" stroke-width="2"/></svg>
                        #SlayEveryLook
                    </div>
                    <h2 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Boutique Lookbook &amp; Live Reels</h2>
                    <p class="text-xs text-[var(--color-ebony)]/60 font-sans">Watch live artisan craftsmanship &amp; styling reels from @EstiloWear</p>
                </div>
                <a href="https://instagram.com" target="_blank" class="inline-flex items-center gap-2 bg-white hover:bg-[var(--color-rose-antique)] hover:text-white text-[var(--color-ebony)] font-sans text-xs font-bold uppercase tracking-wider px-5 py-2.5 rounded-full border border-[var(--color-bisque)]/80 shadow-xs transition-all">
                    <span>Follow @EstiloWear</span>
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                </a>
            </div>

            <!-- Continuous Playing Video Reels Grid -->
            @php
                $videoUrls = [
                    'https://assets.mixkit.co/videos/preview/mixkit-fashion-model-in-a-red-dress-41584-large.mp4',
                    'https://assets.mixkit.co/videos/preview/mixkit-woman-in-a-traditional-dress-walking-slowly-41586-large.mp4',
                    'https://assets.mixkit.co/videos/preview/mixkit-young-woman-wearing-a-beautiful-traditional-dress-41587-large.mp4',
                    'https://assets.mixkit.co/videos/preview/mixkit-model-posing-in-a-studio-setting-41585-large.mp4',
                ];
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-5">
                @foreach($instagramPosts->take(4) as $index => $post)
                <div class="group relative rounded-2xl overflow-hidden aspect-[4/5] bg-black shadow-md border border-[var(--color-bisque)]/40"
                     x-data="{ isMuted: true }">

                    {{-- HTML5 Video Element playing continuously --}}
                    <video autoplay loop muted playsinline
                           poster="{{ $post['image'] }}"
                           class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <source src="{{ $videoUrls[$index % count($videoUrls)] }}" type="video/mp4">
                        <img src="{{ $post['image'] }}" alt="Fashion Reel" class="w-full h-full object-cover" />
                    </video>

                    {{-- Live REEL Badge Overlay --}}
                    <div class="absolute top-3 left-3 z-10 flex items-center gap-1.5 bg-black/60 backdrop-blur-md px-2.5 py-1 rounded-full text-white text-[10px] font-sans font-bold tracking-wider uppercase border border-white/20">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        <span>REEL</span>
                    </div>

                    {{-- Sound Toggle Button --}}
                    <button @click="$el.closest('div').querySelector('video').muted = !$el.closest('div').querySelector('video').muted; isMuted = !isMuted"
                            class="absolute top-3 right-3 z-10 w-7 h-7 rounded-full bg-black/50 backdrop-blur-md text-white flex items-center justify-center text-xs hover:bg-[var(--color-rose-antique)] transition-colors border border-white/20"
                            aria-label="Toggle Sound">
                        <span x-text="isMuted ? '🔇' : '🔊'"></span>
                    </button>

                    {{-- Bottom Caption Overlay --}}
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent p-3.5 sm:p-4 text-white flex flex-col justify-end space-y-1">
                        <div class="flex items-center justify-between text-xs font-sans font-bold">
                            <span class="text-[var(--color-blush)] truncate">{{ $post['tag'] }}</span>
                            <span class="text-[10px] text-white/80 font-normal">❤️ {{ $post['likes'] }}</span>
                        </div>
                        <p class="text-[10px] font-sans text-white/70 truncate">Estilo Atelier Handloom Collection ✦</p>
                    </div>

                </div>
                @endforeach
            </div>
        </section>

    </div>
</div>

@endsection

