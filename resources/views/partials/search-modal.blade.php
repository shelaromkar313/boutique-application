{{-- Global Luxury Search Popup Modal --}}
<div x-data
    x-show="$store.shop.isSearchOpen"
    @keydown.escape.window="$store.shop.isSearchOpen = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-start justify-center pt-16 sm:pt-24 px-4 overflow-y-auto"
    role="dialog"
    aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="$store.shop.isSearchOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.shop.isSearchOpen = false"
        class="fixed inset-0 bg-[var(--color-ebony)]/75 backdrop-blur-md transition-opacity"></div>

    {{-- Search Container --}}
    <div x-show="$store.shop.isSearchOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:scale-95"
        class="relative w-full max-w-2xl bg-[var(--color-offwhite)] rounded-3xl shadow-2xl border border-[var(--color-bisque)]/80 p-5 sm:p-6 z-10">

        {{-- Search Input Form --}}
        <form action="/shop" method="GET" class="relative flex items-center mb-5">
            <svg class="absolute left-4 w-5 h-5 text-[var(--color-rose-antique)] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text"
                name="search"
                x-ref="searchInput"
                x-model="$store.shop.searchQuery"
                placeholder="Search traditional sarees, chikankari kurtis, anarkalis..."
                class="w-full pl-12 pr-12 py-3.5 bg-white border border-[var(--color-bisque)] text-[var(--color-ebony)] placeholder-[var(--color-ebony)]/40 rounded-full font-sans text-xs sm:text-sm focus:outline-none focus:border-[var(--color-rose-antique)] shadow-inner transition-colors" />
            <button type="button" @click="$store.shop.isSearchOpen = false" class="absolute right-3 p-2 text-[var(--color-ebony)]/70 hover:text-[var(--color-rose-antique)] rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </form>

        {{-- Trending Searches --}}
        <div class="mb-4">
            <span class="text-[10px] sm:text-xs font-sans font-bold text-[var(--color-ebony)]/50 uppercase tracking-wider block mb-2.5">
                Trending Searches
            </span>
            <div class="flex flex-wrap gap-2">
                <a href="/shop?category=Chikankari+Kurtis" @click="$store.shop.isSearchOpen = false" class="px-3 py-1 bg-white hover:bg-[var(--color-rose-antique)] hover:text-white border border-[var(--color-bisque)]/60 rounded-full text-xs font-sans text-[var(--color-ebony)]/80 transition-colors">✦ Chikankari Anarkali</a>
                <a href="/shop?category=Banarasi+Sarees" @click="$store.shop.isSearchOpen = false" class="px-3 py-1 bg-white hover:bg-[var(--color-rose-antique)] hover:text-white border border-[var(--color-bisque)]/60 rounded-full text-xs font-sans text-[var(--color-ebony)]/80 transition-colors">✦ Banarasi Silk</a>
                <a href="/shop?category=Silk+Sarees" @click="$store.shop.isSearchOpen = false" class="px-3 py-1 bg-white hover:bg-[var(--color-rose-antique)] hover:text-white border border-[var(--color-bisque)]/60 rounded-full text-xs font-sans text-[var(--color-ebony)]/80 transition-colors">✦ Kanjivaram Sarees</a>
                <a href="/shop?category=Organza+Sarees" @click="$store.shop.isSearchOpen = false" class="px-3 py-1 bg-white hover:bg-[var(--color-rose-antique)] hover:text-white border border-[var(--color-bisque)]/60 rounded-full text-xs font-sans text-[var(--color-ebony)]/80 transition-colors">✦ Organza Sarees</a>
                <a href="/shop?category=Co-Ord+Sets" @click="$store.shop.isSearchOpen = false" class="px-3 py-1 bg-white hover:bg-[var(--color-rose-antique)] hover:text-white border border-[var(--color-bisque)]/60 rounded-full text-xs font-sans text-[var(--color-ebony)]/80 transition-colors">✦ Co-Ord Sets</a>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="pt-3 border-t border-[var(--color-bisque)]/40 flex items-center justify-between text-xs font-sans">
            <span class="text-[var(--color-ebony)]/60">Press <kbd class="px-1.5 py-0.5 bg-white border border-[var(--color-bisque)] rounded text-[10px]">ESC</kbd> to close</span>
            <a href="/shop" @click="$store.shop.isSearchOpen = false" class="font-bold text-[var(--color-rose-antique)] hover:underline">Explore All Collections →</a>
        </div>
    </div>
</div>
