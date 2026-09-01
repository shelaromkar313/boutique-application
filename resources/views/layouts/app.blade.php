<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="description" content="Estilo Wear - Slay Every Look. Luxury Women's Boutique Collection featuring Designer Kurtis, Chikankari, Anarkali Suits, Silk & Organza Sarees, and Wedding Couture." />
    <meta name="theme-color" content="#FBEAD6" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ESTILO WEAR | Slay Every Look')</title>
    
    <!-- Preload Hero Image for Instant Render -->
    <link rel="preload" as="image" href="/storage/hero/hero-main.jpg" fetchpriority="high">

    <!-- Google Fonts with display swap for fast text rendering -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AlpineJS Global Store Definition -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('shop', {
                cart: JSON.parse(localStorage.getItem('estilo_cart') || '[]'),
                wishlist: JSON.parse(localStorage.getItem('estilo_wishlist') || '[]'),
                isCartOpen: false,
                isSearchOpen: false,
                isSizeGuideOpen: false,
                searchQuery: '',
                toast: { show: false, message: '' },
                couponCode: localStorage.getItem('estilo_coupon') || '',
                discountPercent: parseInt(localStorage.getItem('estilo_discount') || '0'),

                get cartCount() {
                    return this.cart.reduce((sum, item) => sum + (item.qty || 1), 0);
                },

                get cartSubtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * (item.qty || 1)), 0);
                },

                get discountAmount() {
                    return Math.round(this.cartSubtotal * (this.discountPercent / 100));
                },

                get cartTotal() {
                    let total = this.cartSubtotal - this.discountAmount;
                    if (this.cartSubtotal > 0 && this.cartSubtotal < 1999) {
                        total += 199; // shipping
                    }
                    return total;
                },

                get freeShippingRemaining() {
                    return Math.max(0, 1999 - this.cartSubtotal);
                },

                get freeShippingProgress() {
                    return Math.min(100, Math.round((this.cartSubtotal / 1999) * 100));
                },

                saveCart() {
                    localStorage.setItem('estilo_cart', JSON.stringify(this.cart));
                },

                saveWishlist() {
                    localStorage.setItem('estilo_wishlist', JSON.stringify(this.wishlist));
                },

                showToast(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3500);
                },

                applyCoupon(code) {
                    if (code.toUpperCase() === 'BOUTIQUE10') {
                        this.couponCode = code.toUpperCase();
                        this.discountPercent = 10;
                        localStorage.setItem('estilo_coupon', this.couponCode);
                        localStorage.setItem('estilo_discount', '10');
                        this.showToast('Promo code applied: 10% OFF');
                        return true;
                    } else {
                        this.showToast('Invalid or expired promo code.');
                        return false;
                    }
                },

                removeCoupon() {
                    this.couponCode = '';
                    this.discountPercent = 0;
                    localStorage.removeItem('estilo_coupon');
                    localStorage.removeItem('estilo_discount');
                    this.showToast('Promo code removed.');
                },

                addToCart(product) {
                    const existing = this.cart.find(i => 
                        i.id === product.id && 
                        i.color === (product.color || 'Standard') && 
                        i.size === (product.size || 'Free Size')
                    );
                    if (existing) {
                        existing.qty += (product.qty || 1);
                    } else {
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            image: product.image || product.images?.[0] || '/storage/hero/hero-main.jpg',
                            color: product.color || 'Standard',
                            size: product.size || 'Free Size',
                            qty: product.qty || 1,
                            category: product.category || 'Atelier'
                        });
                    }
                    this.saveCart();
                    this.showToast(`"${product.name}" added to bag!`);
                    this.isCartOpen = true;
                },

                removeFromCart(index) {
                    const removed = this.cart.splice(index, 1);
                    this.saveCart();
                    if (removed.length) {
                        this.showToast(`Removed from bag.`);
                    }
                },

                updateQty(index, qty) {
                    if (qty <= 0) {
                        this.removeFromCart(index);
                    } else {
                        this.cart[index].qty = qty;
                        this.saveCart();
                    }
                },

                toggleWishlist(product) {
                    const idx = this.wishlist.findIndex(i => i.id == product.id);
                    if (idx > -1) {
                        this.wishlist.splice(idx, 1);
                        this.saveWishlist();
                        this.showToast(`Removed "${product.name}" from wishlist.`);
                    } else {
                        this.wishlist.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            image: product.image || product.images?.[0] || '/storage/hero/hero-main.jpg',
                            category: product.category || 'Atelier'
                        });
                        this.saveWishlist();
                        this.showToast(`Saved "${product.name}" to wishlist!`);
                    }
                },

                isInWishlist(id) {
                    return this.wishlist.some(i => i.id == id);
                }
            });

            if (window.virtualTryOn) {
                Alpine.data('virtualTryOn', window.virtualTryOn);
            }
        });
    </script>

    <!-- Alpine x-cloak: MUST be defined before Alpine loads to prevent flash of modal on every page -->
    <style>[x-cloak] { display: none !important; }</style>

    <!-- AlpineJS & Plugins -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Design Tokens & Utilities -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<body class="bg-[var(--color-offwhite)] text-[var(--color-ebony)] font-sans antialiased selection:bg-[var(--color-blush)] selection:text-[var(--color-ebony)] overflow-x-hidden min-h-screen flex flex-col justify-between pb-16 lg:pb-0">
    
    {{-- Partner Mode Active Banner for Logged-In Sales Associates --}}
    @if(auth()->check() && auth()->user()->isSalesAssociate())
    <div class="bg-[var(--color-ebony)] text-white text-xs py-2 px-4 border-b border-amber-400/30 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto w-full flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-amber-400/20 text-amber-300 text-[10px] font-bold uppercase tracking-wider border border-amber-400/40">
                    ✦ Partner Mode Active
                </span>
                <span class="text-xs">
                    Welcome, <strong>{{ auth()->user()->name }}</strong> (Code: <code class="font-mono bg-black/40 px-1.5 py-0.5 rounded text-amber-300">{{ auth()->user()->referral_code ?? 'ESTILO-SA01' }}</code>)
                </span>
            </div>
            <div class="flex items-center gap-3 text-xs font-sans">
                <span class="text-gray-300 hidden md:inline">Balance: <strong class="text-emerald-400">₹{{ number_format(auth()->user()->balance, 0) }}</strong></span>
                <span class="text-gray-300 hidden md:inline">Total Profit: <strong class="text-amber-300">₹{{ number_format(auth()->user()->earnings, 0) }}</strong></span>
                <a href="/sales/dashboard" class="bg-amber-400 hover:bg-amber-500 text-[var(--color-ebony)] font-bold text-[10px] uppercase tracking-wider px-3 py-1 rounded-full transition-all shadow-sm">
                    Sales Dashboard →
                </a>
                <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                    @csrf
                    <button type="submit" class="text-rose-300 hover:text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded hover:bg-white/10 transition-colors" title="Sign Out">
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    @include('partials.navbar')
    
    <main class="flex-grow">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Interactive Global Overlays & Modals --}}
    @include('partials.mobile-bottom-nav')
    @include('partials.cart-drawer')
    @include('partials.search-modal')
    @include('partials.size-guide-modal')
    @include('partials.toast')

    <a href="https://wa.me/919876543210?text={{ urlencode('Hello Estilo Wear, I want to connect for a boutique order.') }}"
       target="_blank"
       rel="noreferrer"
       aria-label="Chat on WhatsApp"
       class="fixed right-4 bottom-20 z-50 md:hidden flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] shadow-[0_10px_22px_rgba(37,211,102,0.35)] transition-transform duration-200 hover:scale-105 active:scale-95 border-2 border-white/80">
        <svg class="h-6 w-6 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20.52 3.48A11.86 11.86 0 0 0 12.1 0C5.54 0 .1 5.42.1 12.05c0 2.13.56 4.2 1.63 6.02L0 24l6.12-1.6a11.96 11.96 0 0 0 5.98 1.53h.01c6.56 0 11.9-5.42 11.9-12.05 0-3.21-1.25-6.24-3.49-8.4ZM12.1 21.9h-.01c-1.93 0-3.82-.52-5.46-1.5l-.39-.23-3.63.95.97-3.54-.25-.38A9.87 9.87 0 0 1 2.12 12.1c0-5.46 4.45-9.9 9.92-9.9a9.88 9.88 0 0 1 7.02 3.02 9.85 9.85 0 0 1 2.9 7.04c0 5.46-4.45 9.9-9.92 9.9Zm5.45-7.38c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.27-.47-2.42-1.52-.9-.8-1.5-1.78-1.68-2.08-.17-.3-.03-.46.13-.61.13-.14.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.06-.37-.02-.52-.08-.15-.67-1.62-.92-2.21-.24-.58-.48-.5-.67-.51-.17-.01-.37-.01-.57-.01-.2 0-.52.07-.8.35-.27.28-1.03 1-1.03 2.46 0 1.46 1.05 2.85 1.2 3.04.15.2 2.06 3.15 4.99 4.42.7.3 1.25.49 1.68.63.71.23 1.35.2 1.86.12.57-.08 1.77-.72 2.02-1.42.24-.7.24-1.3.17-1.42-.08-.12-.28-.2-.58-.35Z"/>
        </svg>
    </a>
    
</body>
</html>
