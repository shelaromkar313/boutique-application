{{-- ═══════════════════════════════════════════════════════
     ESTILO WEAR — Mobile Bottom Navigation Bar
     Fixed sticky bottom navigation bar with active states & live badges
     ═══════════════════════════════════════════════════════ --}}

@php
    $profileUrl = '/login';
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            $profileUrl = '/estilo-hq-console/dashboard';
        } elseif (Auth::user()->role === 'sales_associate') {
            $profileUrl = '/sales/dashboard';
        }
    }
    $isProfileActive = request()->is('login*') || request()->is('register*') || request()->is('sales*') || request()->is('estilo-hq-console*');
@endphp

@if(!request()->is('estilo-hq-console*'))
<nav class="fixed bottom-0 left-0 right-0 z-40 lg:hidden bg-white/95 backdrop-blur-lg border-t border-[var(--color-bisque)]/70 shadow-[0_-4px_25px_rgba(26,24,24,0.08)] px-2 pt-2 pb-[calc(env(safe-area-inset-bottom,0px)+0.5rem)] transition-all duration-300 select-none"
     aria-label="Mobile Bottom Navigation">
    <div class="max-w-md mx-auto grid grid-cols-5 items-center justify-around text-center">

        {{-- 1. Home Link --}}
        <a href="/" 
           class="flex flex-col items-center justify-center gap-1 py-1 rounded-xl transition-all duration-200 {{ request()->is('/') ? 'text-[var(--color-rose-antique)] font-bold' : 'text-[var(--color-ebony)]/70 hover:text-[var(--color-rose-antique)]' }}">
            <div class="relative">
                <svg class="w-5 h-5 {{ request()->is('/') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                @if(request()->is('/'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-[var(--color-rose-antique)] rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-sans tracking-wide leading-tight">Home</span>
        </a>

        {{-- 2. Shop Collections Link --}}
        <a href="/shop" 
           class="flex flex-col items-center justify-center gap-1 py-1 rounded-xl transition-all duration-200 {{ request()->is('shop*') ? 'text-[var(--color-rose-antique)] font-bold' : 'text-[var(--color-ebony)]/70 hover:text-[var(--color-rose-antique)]' }}">
            <div class="relative">
                <svg class="w-5 h-5 {{ request()->is('shop*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                @if(request()->is('shop*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-[var(--color-rose-antique)] rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-sans tracking-wide leading-tight">Shop</span>
        </a>

        {{-- 3. Profile / Account Link --}}
        <a href="{{ $profileUrl }}" 
           class="flex flex-col items-center justify-center gap-1 py-1 rounded-xl transition-all duration-200 relative {{ $isProfileActive ? 'text-[var(--color-rose-antique)] font-bold' : 'text-[var(--color-ebony)]/70 hover:text-[var(--color-rose-antique)]' }}"
           aria-label="Profile Account">
            <div class="relative">
                <svg class="w-5 h-5 {{ $isProfileActive ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                @auth
                    <span class="absolute -top-0.5 -right-1 w-2 h-2 bg-emerald-500 rounded-full ring-2 ring-white"></span>
                @endauth
                @if($isProfileActive)
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-[var(--color-rose-antique)] rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-sans tracking-wide leading-tight">{{ Auth::check() ? 'Profile' : 'Account' }}</span>
        </a>

        {{-- 4. Wishlist Link --}}
        <a href="/wishlist" 
           class="flex flex-col items-center justify-center gap-1 py-1 rounded-xl transition-all duration-200 relative {{ request()->is('wishlist*') ? 'text-[var(--color-rose-antique)] font-bold' : 'text-[var(--color-ebony)]/70 hover:text-[var(--color-rose-antique)]' }}">
            <div class="relative">
                <svg class="w-5 h-5 {{ request()->is('wishlist*') ? 'stroke-[2.5]' : 'stroke-2' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span x-show="$store.shop.wishlist.length > 0" 
                      x-text="$store.shop.wishlist.length" 
                      class="absolute -top-1.5 -right-2.5 bg-[var(--color-rose-antique)] text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm"
                      style="display: none;">
                </span>
                @if(request()->is('wishlist*'))
                    <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-[var(--color-rose-antique)] rounded-full"></span>
                @endif
            </div>
            <span class="text-[10px] font-sans tracking-wide leading-tight">Wishlist</span>
        </a>

        {{-- 5. Bag / Cart Drawer Trigger --}}
        <button @click="$store.shop.isCartOpen = true" 
                class="flex flex-col items-center justify-center gap-1 py-1 rounded-xl text-[var(--color-ebony)]/70 hover:text-[var(--color-rose-antique)] transition-all duration-200 relative cursor-pointer"
                aria-label="Open Bag">
            <div class="relative">
                <svg class="w-5 h-5 stroke-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span x-show="$store.shop.cartCount > 0" 
                      x-text="$store.shop.cartCount" 
                      class="absolute -top-1.5 -right-2.5 bg-[var(--color-ebony)] text-[var(--color-champagne)] text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm"
                      style="display: none;">
                </span>
            </div>
            <span class="text-[10px] font-sans tracking-wide leading-tight">Bag</span>
        </button>

    </div>
</nav>
@endif
