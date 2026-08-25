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
    
    <!-- Google Fonts -->
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
<body class="bg-[var(--color-offwhite)] text-[var(--color-ebony)] font-sans antialiased selection:bg-[var(--color-blush)] selection:text-[var(--color-ebony)] overflow-x-hidden min-h-screen flex flex-col justify-between">
    
    @include('partials.navbar')
    
    <main class="flex-grow">
        @yield('content')
    </main>

    @include('partials.footer')

    {{-- Interactive Global Overlays & Modals --}}
    @include('partials.cart-drawer')
    @include('partials.search-modal')
    @include('partials.size-guide-modal')
    @include('partials.virtual-tryon-modal')
    @include('partials.toast')
    
</body>
</html>
