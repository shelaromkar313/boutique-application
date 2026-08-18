<div x-data="{ 
        isScrolled: false, 
        mobileMenuOpen: false, 
        activeMegaMenu: null 
    }" 
    @scroll.window="isScrolled = (window.pageYOffset > 40) ? true : false"
    class="sticky top-0 z-50">
    
    <!-- 1. Continuous Right-to-Left Announcement Ticker -->
    <div class="bg-white text-[var(--color-ebony)] text-[11px] font-sans tracking-[0.22em] uppercase py-2 border-b border-[var(--color-bisque)]/40 overflow-hidden relative select-none z-40">
        <div class="flex whitespace-nowrap gap-12 items-center w-max" style="animation: marquee 25s linear infinite;">
            <style>
                @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-50%); } }
            </style>
            
            <div class="flex items-center gap-8 shrink-0">
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-rose-antique)] text-xs animate-pulse">✨</span>
                    <span>COMPLIMENTARY EXPRESS SHIPPING ON ORDERS OVER ₹1,499</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-thyme)] text-xs animate-pulse">✨</span>
                    <span>USE CODE <strong class="text-[var(--color-rose-antique)] font-bold">BOUTIQUE10</strong> FOR 10% OFF</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-rose-antique)] text-xs animate-pulse">✨</span>
                    <span>AUTHENTIC HANDLOOM BOUTIQUE COUTURE</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
            </div>
            
            <!-- Duplicate for continuous loop -->
            <div class="flex items-center gap-8 shrink-0">
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-rose-antique)] text-xs animate-pulse">✨</span>
                    <span>COMPLIMENTARY EXPRESS SHIPPING ON ORDERS OVER ₹1,499</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-thyme)] text-xs animate-pulse">✨</span>
                    <span>USE CODE <strong class="text-[var(--color-rose-antique)] font-bold">BOUTIQUE10</strong> FOR 10% OFF</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
                <span class="flex items-center gap-2">
                    <span class="text-[var(--color-rose-antique)] text-xs animate-pulse">✨</span>
                    <span>AUTHENTIC HANDLOOM BOUTIQUE COUTURE</span>
                </span>
                <span class="text-[var(--color-bisque)] font-bold">|</span>
            </div>
        </div>
    </div>

    <!-- 2. Premium Fixed Header -->
    <header :class="isScrolled ? 'bg-[#181818]/98 backdrop-blur-md shadow-2xl py-3 border-b border-white/10' : 'bg-[#1a1a1a] py-4 border-b border-white/5'" class="w-full transition-all duration-500 text-white">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                
                <!-- Left: Logo Lockup + Mobile Menu -->
                <div class="flex items-center gap-3 sm:gap-4 flex-none min-w-0">
                    <button @click="mobileMenuOpen = true" class="lg:hidden p-1 text-white hover:text-[#FBEAD6] transition-colors rounded-full focus:outline-none flex-shrink-0" aria-label="Open Menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    <a href="/" class="flex items-center gap-3 group">
                        <!-- Icon Circle -->
                        <div class="w-10 h-10 sm:w-[42px] sm:h-[42px] overflow-hidden shrink-0 flex items-center justify-center bg-transparent rounded-full border-[0.5px] border-white/30 group-hover:border-[#FBEAD6]/50 transition-colors">
                            <img src="/storage/logo.jpg" alt="Icon" class="w-[160%] max-w-none mix-blend-screen -mt-[25%]" />
                        </div>
                        <!-- Typography -->
                        <div class="flex flex-col justify-center">
                            <span class="text-lg sm:text-[22px] font-serif font-bold tracking-[0.28em] text-[#FBEAD6] leading-none uppercase" style="text-shadow: 0 0 1px rgba(251,234,214,0.3);">Estilo Wear</span>
                            <span class="text-[8px] sm:text-[9px] font-sans tracking-[0.3em] text-white/80 mt-1.5 uppercase pl-0.5">Slay Every Look</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Navigation -->
                <nav class="hidden lg:flex flex-1 justify-center items-center gap-5 xl:gap-8 ml-4">
                    <a href="/" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Home</a>
                    
                    <a href="/shop" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors flex items-center gap-1.5 whitespace-nowrap">
                        New Arrivals <span class="w-1.5 h-1.5 rounded-full bg-white/20"></span>
                    </a>
                    
                    <a href="/shop" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Shop</a>
                    
                    <div class="relative group cursor-pointer flex items-center">
                        <a href="/shop?category=Kurtis" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white group-hover:text-[#FBEAD6] uppercase transition-colors flex items-center gap-1 whitespace-nowrap">
                            Kurtis <svg class="w-3 h-3 text-white/50 group-hover:text-[#FBEAD6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                    </div>

                    <div class="relative group cursor-pointer flex items-center">
                        <a href="/shop?category=Sarees" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white group-hover:text-[#FBEAD6] uppercase transition-colors flex items-center gap-1 whitespace-nowrap">
                            Sarees <svg class="w-3 h-3 text-white/50 group-hover:text-[#FBEAD6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </a>
                    </div>
                    
                    <a href="/shop" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Collections</a>
                    
                    <a href="/shop?occasion=Festive" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Festive</a>
                    
                    <a href="/shop?sale=true" class="text-[11px] font-sans font-bold tracking-[0.15em] text-[#E5BCA9] hover:text-white uppercase transition-colors flex items-center gap-1 whitespace-nowrap">
                        <span class="text-[#E5BCA9] opacity-80">%</span> Sale
                    </a>
                    
                    <a href="/about" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">About</a>
                    <a href="/contact" class="text-[11px] font-sans font-bold tracking-[0.15em] text-white hover:text-[#FBEAD6] uppercase transition-colors whitespace-nowrap">Contact</a>
                </nav>

                <!-- Right: Icons -->
                <div class="flex items-center gap-4 sm:gap-6 flex-none flex-shrink-0">
                    <button @click="$store.shop.isSearchOpen = true" class="text-white hover:text-[#FBEAD6] transition-colors" aria-label="Search">
                        <svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                    
                    <a href="/wishlist" class="text-white hover:text-[#FBEAD6] transition-colors relative" aria-label="Wishlist">
                        <svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        <span x-show="$store.shop.wishlist.length > 0" class="absolute -top-2 -right-2 w-[18px] h-[18px] bg-[#F0C4CB] text-[#1A1818] text-[9px] font-bold rounded-full flex items-center justify-center" x-text="$store.shop.wishlist.length"></span>
                        <!-- Default badge to match screenshot if empty -->
                        <span x-show="$store.shop.wishlist.length === 0" class="absolute -top-2 -right-2 w-[18px] h-[18px] bg-[#F0C4CB] text-[#1A1818] text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#1a1a1a]">2</span>
                    </a>

                    <a href="/login" class="text-white hover:text-[#FBEAD6] transition-colors" aria-label="Profile">
                        <svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </a>

                    <button @click="$store.shop.isCartOpen = true" class="text-white hover:text-[#FBEAD6] transition-colors relative" aria-label="Cart">
                        <svg class="w-[18px] h-[18px] sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span x-show="$store.shop.cartCount > 0" class="absolute -top-2 -right-2 w-[18px] h-[18px] bg-[#F0C4CB] text-[#1A1818] text-[9px] font-bold rounded-full flex items-center justify-center" x-text="$store.shop.cartCount"></span>
                    </button>
                </div>
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
               class="fixed top-0 left-0 bottom-0 w-[85%] max-w-md bg-[var(--color-offwhite)] z-50 shadow-2xl flex flex-col justify-between overflow-y-auto">
            
            <div>
                <div class="p-5 flex items-center justify-between border-b border-[var(--color-bisque)]/60 bg-[var(--color-champagne-light)]">
                    <a href="/" class="inline-block py-2">
                        <img src="/storage/logo.jpg" alt="Estilo Wear" class="h-8 sm:h-10 w-auto object-contain invert mix-blend-multiply transform scale-[1.8] origin-left" />
                    </a>
                    <button @click="mobileMenuOpen = false" class="p-2 text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <a href="/" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Home</a>
                    <a href="/shop" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Shop All</a>
                    <a href="/about" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">About</a>
                    <a href="/contact" class="block text-sm font-sans font-semibold text-[var(--color-ebony)] uppercase tracking-wider hover:text-[var(--color-rose-antique)]">Contact</a>
                </div>
            </div>
        </aside>
    </div>
</div>
