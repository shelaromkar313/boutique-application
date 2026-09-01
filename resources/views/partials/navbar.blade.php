<div x-data="{ 
        isScrolled: false, 
        mobileMenuOpen: false, 
        activeMegaMenu: null 
    }" 
    @scroll.window="isScrolled = (window.pageYOffset > 40) ? true : false"
    class="sticky top-0 z-50">
    
    @if(!request()->is('estilo-hq-console*'))
    {{-- Pull live announced coupons and announcements from DB --}}
    @php
        $activeAnnouncements = \App\Models\Announcement::active()->get();
        $tickerAnnouncements = $activeAnnouncements->where('show_in_ticker', true);
        $tickerCoupons = \App\Models\Coupon::where('is_announced', true)
            ->where('is_active', true)
            ->where(function($q) { $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()); })
            ->get();
    @endphp

    <!-- 1. Continuous Right-to-Left Announcement Ticker -->
    <div class="bg-white text-[var(--color-ebony)] text-[11px] font-sans tracking-[0.22em] uppercase py-2 border-b border-[var(--color-bisque)]/40 overflow-hidden relative select-none z-40 group/ticker">
        <div class="flex whitespace-nowrap gap-12 items-center w-max group-hover/ticker:[animation-play-state:paused]" style="animation: marquee 75s linear infinite;">
            <style>
                @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
            </style>

            {{-- Render twice for seamless loop --}}
            @foreach([1,2] as $loop)
            <div class="flex items-center gap-8 shrink-0">

                {{-- Dynamic Custom Announcements --}}
                @if($tickerAnnouncements->isNotEmpty())
                    @foreach($tickerAnnouncements as $tAnn)
                        <span class="flex items-center gap-2">
                            <span class="text-amber-500 text-xs animate-pulse">{{ $tAnn->icon ?? '📢' }}</span>
                            <span class="font-bold text-amber-900">{{ strtoupper($tAnn->title) }}:</span>
                            <span>{{ strtoupper($tAnn->message) }}</span>
                        </span>
                        <span class="text-[var(--color-bisque)] font-bold">|</span>
                    @endforeach
                @endif

                {{-- Dynamic DB-driven coupon announcements --}}
                @if($tickerCoupons->isNotEmpty())
                    @foreach($tickerCoupons as $tc)
                        <span class="flex items-center gap-2">
                            <span class="text-amber-500 text-xs animate-pulse">🎟️</span>
                            <span>{{ strtoupper($tc->announcement_text ?? ('USE CODE ' . $tc->code . ' FOR ' . $tc->discount_value . '% OFF')) }}</span>
                        </span>
                        <span class="text-[var(--color-bisque)] font-bold">|</span>
                    @endforeach
                @endif

                {{-- Fallback messages only if database has no active announcements --}}
                @if($tickerAnnouncements->isEmpty() && $tickerCoupons->isEmpty())
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-rose-antique)] text-xs animate-pulse">✨</span>
                    <span>COMPLIMENTARY EXPRESS SHIPPING ON ORDERS OVER ₹1,499</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-thyme)] text-xs animate-pulse">✨</span>
                    <span>AUTHENTIC HANDLOOM BOUTIQUE COUTURE</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-rose-antique)] text-xs animate-pulse">✨</span>
                    <span>EASY RETURNS · SECURE PAYMENTS · COD AVAILABLE</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- 3. Premium Fixed Header -->
    <header :class="isScrolled ? 'bg-[#181818]/98 backdrop-blur-md shadow-2xl py-2.5 border-b border-white/10' : 'bg-[#1a1a1a] py-3.5 border-b border-white/5'" class="w-full transition-all duration-500 text-white">
        <div class="max-w-[1520px] mx-auto pl-3 pr-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-2 lg:gap-4">
                
                @if(request()->is('estilo-hq-console*'))
                {{-- ══════════════════════════════════════════════════════════════════ --}}
                {{-- ADMIN PORTAL NAVIGATION HEADER                                    --}}
                {{-- ══════════════════════════════════════════════════════════════════ --}}
                
                <!-- Left: Admin Brand Lockup -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-1.5 text-white hover:text-[#FBEAD6] transition-colors rounded-full focus:outline-none shrink-0" aria-label="Open Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>

                    <a href="/estilo-hq-console" class="flex items-center gap-1.5 group shrink-0">
                        <div class="w-7 h-7 overflow-hidden shrink-0 flex items-center justify-center bg-transparent rounded-full border-[0.5px] border-white/30 group-hover:border-[#FBEAD6]/50 transition-colors">
                            <img src="/storage/logo.jpg" alt="Estilo Wear" class="w-[160%] max-w-none mix-blend-screen -mt-[25%]" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <span class="text-[11px] font-serif font-bold tracking-[0.18em] text-[#FBEAD6] leading-none uppercase">Estilo</span>
                            <span class="text-[7px] font-sans tracking-[0.20em] text-emerald-400 mt-0.5 uppercase font-bold">Admin</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Admin Features Navigation Tabs -->
                @php $curTab = request('tab', 'overview'); @endphp
                <nav class="hidden lg:flex flex-1 justify-center items-center gap-0.5 px-1 min-w-0 overflow-x-auto scrollbar-none">
                    <a href="/estilo-hq-console?tab=overview"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'overview' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Overview
                    </a>

                    <a href="/estilo-hq-console?tab=inventory"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'inventory' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Inventory
                    </a>

                    <a href="/estilo-hq-console?tab=orders"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'orders' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Orders
                    </a>

                    <a href="/estilo-hq-console?tab=customers"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'customers' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Customers
                    </a>

                    <a href="/estilo-hq-console?tab=associates"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'associates' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Associates
                    </a>

                    <a href="/estilo-hq-console?tab=reports"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'reports' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Reports
                    </a>

                    <a href="/estilo-hq-console?tab=offers"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'offers' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Coupons
                    </a>

                    <a href="/estilo-hq-console?tab=announcements"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap flex items-center gap-1 {{ $curTab === 'announcements' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        <span>📢 Announcements</span>
                    </a>

                    <a href="/estilo-hq-console?tab=reviews"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ $curTab === 'reviews' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Reviews
                    </a>

                    <a href="/estilo-hq-console/profile"
                       class="px-2.5 py-1.5 rounded-full text-[9.5px] font-sans font-semibold uppercase tracking-wide transition-all whitespace-nowrap {{ request()->is('estilo-hq-console/profile*') || $curTab === 'profile' ? 'bg-[#FBEAD6] text-[#1A1818] font-bold shadow-md' : 'text-white/75 hover:text-white hover:bg-white/10' }}">
                        Profile
                    </a>
                </nav>


                <!-- Right: Admin Actions -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <a href="/shop" target="_blank" class="inline-flex items-center gap-1 bg-white/10 hover:bg-white/20 border border-white/20 text-[#FBEAD6] text-xs font-sans font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition-all" title="View Customer Storefront">
                        <span>Storefront ↗</span>
                    </a>
                    
                    <a href="/estilo-hq-console/profile" 
                       class="hidden sm:flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/15 rounded-full px-3 py-1 text-xs transition-all hover:scale-105 active:scale-95 cursor-pointer" title="Admin Profile & Security">
                        <span class="w-6 h-6 rounded-full bg-[var(--color-ebony)] text-amber-200 flex items-center justify-center font-bold text-[11px]">
                            A
                        </span>
                        <span class="font-bold text-[11px] text-[#FBEAD6] hidden xl:inline">Admin</span>
                    </a>

                    <form action="{{ route('admin.logout') }}" method="POST" class="inline m-0">
                        @csrf
                        <button type="submit" class="text-[11px] bg-rose-500/20 hover:bg-rose-500/30 border border-rose-400/40 text-rose-200 font-bold px-3 py-1.5 rounded-full transition-colors" title="Log Out">
                            Sign Out
                        </button>
                    </form>
                </div>

                @else
                {{-- ══════════════════════════════════════════════════════════════════ --}}
                {{-- CUSTOMER STOREFRONT NAVIGATION HEADER                             --}}
                {{-- ══════════════════════════════════════════════════════════════════ --}}
                
                <!-- Left: Logo Lockup + Mobile Menu -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-1.5 text-white hover:text-[#FBEAD6] transition-colors rounded-full focus:outline-none shrink-0" aria-label="Open Menu">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <a href="/" class="flex items-center gap-2 group shrink-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 overflow-hidden shrink-0 flex items-center justify-center bg-transparent rounded-full border-[0.5px] border-white/30 group-hover:border-[#FBEAD6]/50 transition-colors">
                            <img src="/storage/logo.jpg" alt="Estilo Wear" class="w-[160%] max-w-none mix-blend-screen -mt-[25%]" />
                        </div>
                        <div class="flex flex-col justify-center">
                            <span class="text-sm sm:text-lg lg:text-xl font-serif font-bold tracking-[0.24em] text-[#FBEAD6] leading-none uppercase" style="text-shadow: 0 0 1px rgba(251,234,214,0.3);">Estilo Wear</span>
                            <span class="text-[6px] sm:text-[8px] font-sans tracking-[0.26em] text-white/80 mt-1 uppercase pl-0.5">Slay Every Look</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Customer Storefront Navigation Links -->
                <nav class="hidden lg:flex flex-1 justify-center items-center gap-3 xl:gap-5 2xl:gap-6 px-2 min-w-0">
                    <a href="/" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Home</a>
                    
                    <a href="/shop" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">New</a>
                    
                    <a href="/shop" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Shop</a>
                    
                    {{-- 1. Kurtis Hover Dropdown --}}
                    <div class="relative group cursor-pointer py-2 flex items-center">
                        <a href="/shop?category=Kurtis" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white group-hover:text-[#FBEAD6] uppercase transition-colors flex items-center gap-1 whitespace-nowrap">
                            Kurtis <svg class="w-3 h-3 text-white/50 group-hover:text-[#FBEAD6] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>

                        {{-- Mega Dropdown Menu --}}
                        <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 w-[480px] hidden group-hover:block transition-all duration-300 z-50">
                            <div class="bg-[#1C1A1A] border border-white/10 rounded-2xl p-5 shadow-2xl backdrop-blur-xl text-left grid grid-cols-5 gap-4 ring-1 ring-white/5">
                                
                                {{-- Subcategories list --}}
                                <div class="col-span-3 space-y-1">
                                    <span class="text-[9px] font-sans font-bold tracking-[0.25em] text-[#FBEAD6] uppercase block mb-2 pb-1.5 border-b border-white/10">Kurtis & Suits Types</span>
                                    
                                    <a href="/shop?category=Chikankari+Kurtis" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Chikankari Kurtis</span>
                                            <span class="text-[9px] font-sans text-white/50">Lucknowi shadow handwork</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Designer+Kurtis" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Designer Kurtis</span>
                                            <span class="text-[9px] font-sans text-white/50">Contemporary party cuts</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Anarkali+Suits" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Anarkali Suits & Sets</span>
                                            <span class="text-[9px] font-sans text-white/50">Royal flared silhouette</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Cotton+Kurtis" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Cotton Mulmul Kurtis</span>
                                            <span class="text-[9px] font-sans text-white/50">Everyday breathable comfort</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Straight+Kurtis" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Straight Cut Kurtis</span>
                                            <span class="text-[9px] font-sans text-white/50">Office & smart casual</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <div class="pt-2 border-t border-white/10 mt-1">
                                        <a href="/shop?category=Kurtis" class="text-[10px] font-sans font-bold text-[#FBEAD6] hover:underline uppercase tracking-wider flex items-center gap-1">
                                            View All Kurtis Collection <span>✦</span>
                                        </a>
                                    </div>
                                </div>

                                {{-- Featured Visual Card --}}
                                <div class="col-span-2 bg-[#252222] rounded-xl p-3 flex flex-col justify-between border border-white/5">
                                    <div>
                                        <span class="text-[9px] font-sans font-bold uppercase tracking-wider text-[#E5BCA9] bg-[#E5BCA9]/10 px-2 py-0.5 rounded-full inline-block mb-2">Artisan Pick</span>
                                        <h4 class="font-serif text-sm font-bold text-[#FBEAD6] leading-tight">Chikankari & Silk Kurtis</h4>
                                        <p class="text-[10px] font-sans text-white/60 mt-1">Hand-embroidered by master craftswomen.</p>
                                    </div>
                                    <div class="pt-3">
                                        <a href="/shop?category=Chikankari+Kurtis" class="inline-block w-full text-center bg-[#FBEAD6] hover:bg-white text-[#1A1818] font-sans font-bold text-[10px] uppercase tracking-wider py-2 rounded-lg transition-colors">
                                            Explore
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- 2. Sarees Hover Dropdown --}}
                    <div class="relative group cursor-pointer py-2 flex items-center">
                        <a href="/shop?category=Sarees" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white group-hover:text-[#FBEAD6] uppercase transition-colors flex items-center gap-1 whitespace-nowrap">
                            Sarees <svg class="w-3 h-3 text-white/50 group-hover:text-[#FBEAD6] transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>

                        {{-- Mega Dropdown Menu --}}
                        <div class="absolute top-full left-1/2 -translate-x-1/2 pt-2 w-[480px] hidden group-hover:block transition-all duration-300 z-50">
                            <div class="bg-[#1C1A1A] border border-white/10 rounded-2xl p-5 shadow-2xl backdrop-blur-xl text-left grid grid-cols-5 gap-4 ring-1 ring-white/5">
                                
                                {{-- Subcategories list --}}
                                <div class="col-span-3 space-y-1">
                                    <span class="text-[9px] font-sans font-bold tracking-[0.25em] text-[#FBEAD6] uppercase block mb-2 pb-1.5 border-b border-white/10">Luxury Saree Drapes</span>
                                    
                                    <a href="/shop?category=Banarasi+Sarees" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Banarasi Silk Sarees</span>
                                            <span class="text-[9px] font-sans text-white/50">Varanasi royal zari weave</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Silk+Sarees" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Pure Kanjivaram Silk</span>
                                            <span class="text-[9px] font-sans text-white/50">Golden zari border heritage</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Organza+Sarees" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Organza Floral Sarees</span>
                                            <span class="text-[9px] font-sans text-white/50">Hand-painted sheer elegance</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Linen+Sarees" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Organic Linen Sarees</span>
                                            <span class="text-[9px] font-sans text-white/50">Modern artisanal drape</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <a href="/shop?category=Cotton+Sarees" class="group/item flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-white/5 transition-all">
                                        <div>
                                            <span class="text-xs font-serif font-bold text-white group-hover/item:text-[#FBEAD6] block">Handloom Jamdani Cotton</span>
                                            <span class="text-[9px] font-sans text-white/50">Airy traditional motifs</span>
                                        </div>
                                        <span class="text-[#FBEAD6] text-xs opacity-0 group-hover/item:opacity-100 transition-opacity">→</span>
                                    </a>

                                    <div class="pt-2 border-t border-white/10 mt-1">
                                        <a href="/shop?category=Sarees" class="text-[10px] font-sans font-bold text-[#FBEAD6] hover:underline uppercase tracking-wider flex items-center gap-1">
                                            View All 6-Yard Drapes <span>✦</span>
                                        </a>
                                    </div>
                                </div>

                                {{-- Featured Visual Card --}}
                                <div class="col-span-2 bg-[#252222] rounded-xl p-3 flex flex-col justify-between border border-white/5">
                                    <div>
                                        <span class="text-[9px] font-sans font-bold uppercase tracking-wider text-[#F0C4CB] bg-[#F0C4CB]/10 px-2 py-0.5 rounded-full inline-block mb-2">Royal Heritage</span>
                                        <h4 class="font-serif text-sm font-bold text-[#FBEAD6] leading-tight">Banarasi & Kanjivaram</h4>
                                        <p class="text-[10px] font-sans text-white/60 mt-1">Woven with real golden zari threads.</p>
                                    </div>
                                    <div class="pt-3">
                                        <a href="/shop?category=Banarasi+Sarees" class="inline-block w-full text-center bg-[#FBEAD6] hover:bg-white text-[#1A1818] font-sans font-bold text-[10px] uppercase tracking-wider py-2 rounded-lg transition-colors">
                                            Shop Sarees
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    
                    <a href="/shop" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Collections</a>
                    
                    <a href="/shop?occasion=Festive" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Festive</a>
                    
                    <a href="/shop?sale=true" class="text-[11px] font-sans font-bold tracking-[0.12em] text-[#E5BCA9] hover:text-white uppercase transition-colors flex items-center gap-0.5 whitespace-nowrap">
                        <span class="text-[#E5BCA9] opacity-80">%</span> Sale
                    </a>
                    
                    <a href="/about" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">About</a>
                    <a href="/contact" class="text-[11px] font-sans font-bold tracking-[0.12em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Contact</a>
                </nav>

                {{-- Right action icons for Customer Storefront --}}
                <div class="flex items-center gap-1 sm:gap-2 lg:gap-3 shrink-0 mr-1">

                    <!-- AI Try-On (desktop only) -->
                    <button type="button"
                            @click="$dispatch('open-tryon', { id: 'est-001', name: 'Gulzar Chikankari Anarkali Set', price: 1899, image: '/storage/products/est-001-chikankari-anarkali.jpg', category: 'dresses' })"
                            class="hidden md:flex items-center gap-1.5 bg-gradient-to-r from-[var(--color-rose-antique)]/25 to-[var(--color-rose-deep)]/25 hover:from-[var(--color-rose-antique)]/40 hover:to-[var(--color-rose-deep)]/40 border border-[var(--color-rose-antique)]/50 text-[#FBEAD6] text-[10px] font-sans font-bold uppercase tracking-wider px-2.5 sm:px-3 py-1.5 rounded-full transition-all hover:scale-105 shadow-sm whitespace-nowrap">
                        <span class="text-amber-300 animate-pulse">✨</span>
                        <span class="hidden xl:inline">AI Fitting Room</span>
                        <span class="xl:hidden">Try-On</span>
                    </button>

                    <!-- Search -->
                    <button @click="$store.shop.isSearchOpen = true"
                            class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-white/90 hover:text-[#FBEAD6] hover:bg-white/10 transition-colors"
                            aria-label="Search" title="Search catalog">
                        <svg class="w-4 h-4 sm:w-[18px] sm:h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                    <!-- Wishlist -->
                    <a href="/wishlist"
                       class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-white/90 hover:text-[#FBEAD6] hover:bg-white/10 transition-colors relative"
                       aria-label="Wishlist" title="View Wishlist">
                        <svg class="w-4 h-4 sm:w-[18px] sm:h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <span x-show="$store.shop.wishlist.length > 0"
                              class="absolute -top-1 -right-1 w-4 h-4 bg-[#F0C4CB] text-[#1A1818] text-[9px] font-bold rounded-full flex items-center justify-center ring-2 ring-[#1a1a1a]"
                              x-text="$store.shop.wishlist.length"></span>
                    </a>

                    <!-- Profile / Account -->
                    <a href="{{ Auth::check() ? (Auth::user()->isAdmin() ? '/estilo-hq-console?tab=profile' : (in_array(Auth::user()->role, ['sales_associate','associate','sales_executive']) ? '/sales/dashboard' : '/profile')) : '/login' }}"
                       class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-full text-white/90 hover:text-[#FBEAD6] hover:bg-white/10 transition-colors border border-white/10"
                       aria-label="My Account" title="{{ Auth::check() ? (Auth::user()->name . ' (My Account)') : 'My Account / Login' }}">
                        <svg class="w-4 h-4 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        @if(Auth::check())
                            @php
                                $userName = Auth::user()->name;
                                if (Auth::user()->isAdmin() || str_starts_with(strtolower($userName), 'boutique admin')) {
                                    $displayName = 'Admin';
                                } elseif (str_starts_with(strtolower($userName), 'boutique')) {
                                    $displayName = trim(substr($userName, 8)) ?: 'Account';
                                } else {
                                    $displayName = explode(' ', $userName)[0];
                                }
                            @endphp
                            <span class="text-[11px] font-sans font-bold tracking-wider hidden xl:inline text-[#FBEAD6] max-w-[90px] truncate">{{ $displayName }}</span>
                        @else
                            <span class="text-[11px] font-sans font-bold tracking-wider hidden xl:inline text-white/80">Sign In</span>
                        @endif
                    </a>

                    <!-- Cart -->
                    <button @click="$store.shop.isCartOpen = true"
                            class="relative shrink-0 w-9 h-9 rounded-full flex items-center justify-center
                                   bg-[var(--color-rose-antique)] hover:bg-[var(--color-rose-deep)]
                                   text-white shadow-lg
                                   transition-all duration-200 hover:scale-105 active:scale-95"
                            aria-label="Shopping Cart" title="View Shopping Cart">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <span x-show="$store.shop.cartCount > 0"
                              class="absolute -top-1.5 -right-1.5 min-w-[18px] h-[18px] px-1
                                     bg-[#FBEAD6] text-[#1A1818] text-[9px] font-bold
                                     rounded-full flex items-center justify-center
                                     ring-2 ring-[#1a1a1a] shadow-sm pointer-events-none"
                              x-text="$store.shop.cartCount"></span>
                    </button>

                </div>
                @endif
            </div>
        </div>
    </header>

    <!-- Mobile Drawer -->
    <div x-show="mobileMenuOpen" style="display: none;">
        <div x-show="mobileMenuOpen" x-transition.opacity @click="mobileMenuOpen = false" class="fixed inset-0 bg-[var(--color-ebony)]/60 backdrop-blur-sm z-50"></div>
        
        <aside x-show="mobileMenuOpen" 
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               class="fixed top-0 left-0 bottom-0 w-[85%] max-w-md bg-[var(--color-offwhite)] z-50 shadow-2xl flex flex-col justify-between overflow-y-auto"
               x-data="{ mobileKurtisOpen: false, mobileSareesOpen: false }">
            
            <div>
                <div class="p-5 flex items-center justify-between border-b border-[var(--color-bisque)]/60 bg-[var(--color-champagne-light)]">
                    <a href="/" class="inline-block py-2">
                        <img src="/storage/logo.jpg" alt="Estilo Wear" class="h-8 sm:h-10 w-auto object-contain invert mix-blend-multiply transform scale-[1.8] origin-left" />
                    </a>
                    <button @click="mobileMenuOpen = false" class="p-2 text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-3">
                    @if(request()->is('estilo-hq-console*'))
                    <div class="pb-2 border-b border-[var(--color-bisque)]/40 mb-2">
                        <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded-full inline-block">
                            Admin Navigation Suite
                        </span>
                    </div>

                    <a href="/estilo-hq-console?tab=overview" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        📊 Dashboard Overview
                    </a>
                    <a href="/estilo-hq-console?tab=inventory" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        👗 Inventory & Products
                    </a>
                    <a href="/estilo-hq-console?tab=orders" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        📦 Orders & Fulfillment
                    </a>
                    <a href="/estilo-hq-console?tab=customers" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        👥 Customers
                    </a>
                    <a href="/estilo-hq-console?tab=associates" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        🤝 Sales Associates & Sellers
                    </a>
                    <a href="/estilo-hq-console?tab=reports" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        📈 Monthly Reports & Billing
                    </a>
                    <a href="/estilo-hq-console?tab=offers" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        🎟️ Offers & Coupons
                    </a>
                    <a href="/estilo-hq-console?tab=announcements" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        📢 Storefront Announcements
                    </a>
                    <a href="/estilo-hq-console?tab=reviews" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        ⭐ Ratings & Reviews
                    </a>
                    <a href="/estilo-hq-console/profile" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        🛡️ Admin Profile & Security
                    </a>
                    <div class="pt-3 border-t border-[var(--color-bisque)]/40 mt-3 space-y-2">
                        <a href="/shop" target="_blank" class="block text-xs font-sans font-bold text-gray-600 hover:text-black">
                            Storefront ↗
                        </a>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left text-xs font-bold text-rose-600 hover:underline">
                                Sign Out
                            </button>
                        </form>
                    </div>
                    @else
                    <a href="/" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Home</a>
                    <a href="/shop" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Shop All Collections</a>

                    {{-- Mobile Kurtis Accordion --}}
                    <div class="border-b border-[var(--color-bisque)]/40 pb-2">
                        <div class="flex items-center justify-between cursor-pointer py-1.5" @click="mobileKurtisOpen = !mobileKurtisOpen">
                            <span class="text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider">Kurtis & Suits</span>
                            <svg class="w-4 h-4 text-[var(--color-rose-antique)] transition-transform duration-200" :class="mobileKurtisOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="mobileKurtisOpen" x-transition class="pl-4 pt-2 space-y-2 text-xs font-sans text-[var(--color-ebony)]/80">
                            <a href="/shop?category=Kurtis" class="block py-1 font-bold text-[var(--color-rose-antique)]">✦ All Kurtis</a>
                            <a href="/shop?category=Chikankari+Kurtis" class="block py-1 hover:text-[var(--color-rose-antique)]">• Chikankari Kurtis</a>
                            <a href="/shop?category=Designer+Kurtis" class="block py-1 hover:text-[var(--color-rose-antique)]">• Designer Kurtis</a>
                            <a href="/shop?category=Anarkali+Suits" class="block py-1 hover:text-[var(--color-rose-antique)]">• Anarkali Suits & Sets</a>
                            <a href="/shop?category=Cotton+Kurtis" class="block py-1 hover:text-[var(--color-rose-antique)]">• Cotton Mulmul Kurtis</a>
                            <a href="/shop?category=Straight+Kurtis" class="block py-1 hover:text-[var(--color-rose-antique)]">• Straight Cut Kurtis</a>
                        </div>
                    </div>

                    {{-- Mobile Sarees Accordion --}}
                    <div class="border-b border-[var(--color-bisque)]/40 pb-2">
                        <div class="flex items-center justify-between cursor-pointer py-1.5" @click="mobileSareesOpen = !mobileSareesOpen">
                            <span class="text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider">Luxury Sarees</span>
                            <svg class="w-4 h-4 text-[var(--color-rose-antique)] transition-transform duration-200" :class="mobileSareesOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="mobileSareesOpen" x-transition class="pl-4 pt-2 space-y-2 text-xs font-sans text-[var(--color-ebony)]/80">
                            <a href="/shop?category=Sarees" class="block py-1 font-bold text-[var(--color-rose-antique)]">✦ All Luxury Sarees</a>
                            <a href="/shop?category=Banarasi+Sarees" class="block py-1 hover:text-[var(--color-rose-antique)]">• Banarasi Silk Sarees</a>
                            <a href="/shop?category=Silk+Sarees" class="block py-1 hover:text-[var(--color-rose-antique)]">• Pure Kanjivaram Silk</a>
                            <a href="/shop?category=Organza+Sarees" class="block py-1 hover:text-[var(--color-rose-antique)]">• Organza Floral Sarees</a>
                            <a href="/shop?category=Linen+Sarees" class="block py-1 hover:text-[var(--color-rose-antique)]">• Organic Linen Sarees</a>
                            <a href="/shop?category=Cotton+Sarees" class="block py-1 hover:text-[var(--color-rose-antique)]">• Handloom Jamdani Cotton</a>
                        </div>
                    </div>

                    <a href="/shop?occasion=Festive" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Festive Wear</a>
                    <a href="/about" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">About</a>
                    <a href="/contact" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Contact</a>
                    
                    <a href="{{ Auth::check() ? (Auth::user()->isAdmin() ? '/estilo-hq-console?tab=profile' : (in_array(Auth::user()->role, ['sales_associate','associate','sales_executive']) ? '/sales/dashboard' : '/profile')) : '/login' }}" class="flex items-center justify-between text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        <span>{{ Auth::check() ? ('My Account (' . Auth::user()->name . ')') : 'Account / Sign In' }}</span>
                        @if(Auth::check())
                            <span class="text-[10px] bg-[var(--color-champagne-light)] text-[var(--color-ebony)] px-2 py-0.5 rounded-full font-bold">
                                {{ Auth::user()->isAdmin() ? 'Admin' : (in_array(Auth::user()->role, ['sales_associate','associate','sales_executive']) ? 'Partner' : 'Member') }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="/wishlist" class="flex items-center justify-between text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        <span>Wishlist</span>
                        <span x-show="$store.shop.wishlist.length > 0" class="bg-[var(--color-rose-antique)] text-white text-[10px] px-2 py-0.5 rounded-full" x-text="$store.shop.wishlist.length"></span>
                    </a>
                    
                    <a href="#" @click.prevent="$store.shop.isCartOpen = true; mobileMenuOpen = false" class="flex items-center justify-between text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">
                        <span>Cart</span>
                        <span x-show="$store.shop.cartCount > 0" class="bg-[var(--color-rose-antique)] text-white text-[10px] px-2 py-0.5 rounded-full" x-text="$store.shop.cartCount"></span>
                    </a>
                    @endif
                </div>
            </div>
        </aside>
    </div>
</div>
