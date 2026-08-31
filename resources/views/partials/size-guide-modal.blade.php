{{-- Traditional Indian Size Guide Modal --}}
<div x-data="{ unit: 'in' }"
    x-show="$store.shop.isSizeGuideOpen"
    @keydown.escape.window="$store.shop.isSizeGuideOpen = false"
    style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
    role="dialog"
    aria-modal="true">

    {{-- Backdrop --}}
    <div x-show="$store.shop.isSizeGuideOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="$store.shop.isSizeGuideOpen = false"
        class="fixed inset-0 bg-[var(--color-ebony)]/75 backdrop-blur-md transition-opacity"></div>

    {{-- Modal Box --}}
    <div x-show="$store.shop.isSizeGuideOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative w-full max-w-xl bg-white rounded-3xl shadow-2xl border border-[var(--color-bisque)]/80 p-6 sm:p-8 z-10 space-y-6">

        <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-4">
            <div>
                <span class="text-[10px] font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-widest">Atelier Measurement Chart</span>
                <h3 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Size & Fit Guide</h3>
            </div>
            <button @click="$store.shop.isSizeGuideOpen = false" class="p-2 text-[var(--color-ebony)]/60 hover:text-[var(--color-rose-antique)] transition-colors rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Unit Switcher (Inches / CM) --}}
        <div class="flex items-center justify-between">
            <span class="text-xs font-sans text-[var(--color-ebony)]/70">Standard Indian Kurti & Dress Sizing:</span>
            <div class="inline-flex rounded-full border border-[var(--color-bisque)] p-0.5 bg-[var(--color-offwhite)]">
                <button type="button" @click="unit = 'in'" :class="unit === 'in' ? 'bg-[var(--color-ebony)] text-white' : 'text-[var(--color-ebony)]/70'" class="px-3 py-1 text-xs font-sans font-bold rounded-full transition-colors">Inches (in)</button>
                <button type="button" @click="unit = 'cm'" :class="unit === 'cm' ? 'bg-[var(--color-ebony)] text-white' : 'text-[var(--color-ebony)]/70'" class="px-3 py-1 text-xs font-sans font-bold rounded-full transition-colors">Centimeters (cm)</button>
            </div>
        </div>

        {{-- Sizing Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-xs font-sans text-left border-collapse">
                <thead>
                    <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif text-xs uppercase tracking-wider border-b border-[var(--color-bisque)]">
                        <th class="p-2.5">Size</th>
                        <th class="p-2.5">Bust</th>
                        <th class="p-2.5">Waist</th>
                        <th class="p-2.5">Hip</th>
                        <th class="p-2.5">Length</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--color-bisque)]/40 text-[var(--color-ebony)]/80">
                    <tr><td class="p-2.5 font-bold text-[var(--color-ebony)]">XS (34)</td><td class="p-2.5" x-text="unit === 'in' ? '34 in' : '86 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '28 in' : '71 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '36 in' : '91 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '44 in' : '112 cm'"></td></tr>
                    <tr><td class="p-2.5 font-bold text-[var(--color-ebony)]">S (36)</td><td class="p-2.5" x-text="unit === 'in' ? '36 in' : '91 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '30 in' : '76 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '38 in' : '96 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '44 in' : '112 cm'"></td></tr>
                    <tr><td class="p-2.5 font-bold text-[var(--color-ebony)]">M (38)</td><td class="p-2.5" x-text="unit === 'in' ? '38 in' : '96 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '32 in' : '81 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '40 in' : '101 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '45 in' : '114 cm'"></td></tr>
                    <tr><td class="p-2.5 font-bold text-[var(--color-ebony)]">L (40)</td><td class="p-2.5" x-text="unit === 'in' ? '40 in' : '101 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '34 in' : '86 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '42 in' : '106 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '45 in' : '114 cm'"></td></tr>
                    <tr><td class="p-2.5 font-bold text-[var(--color-ebony)]">XL (42)</td><td class="p-2.5" x-text="unit === 'in' ? '42 in' : '106 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '36 in' : '91 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '44 in' : '112 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '46 in' : '117 cm'"></td></tr>
                    <tr><td class="p-2.5 font-bold text-[var(--color-ebony)]">XXL (44)</td><td class="p-2.5" x-text="unit === 'in' ? '44 in' : '112 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '38 in' : '96 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '46 in' : '117 cm'"></td><td class="p-2.5" x-text="unit === 'in' ? '46 in' : '117 cm'"></td></tr>
                    <tr class="bg-[var(--color-champagne-light)]/40"><td class="p-2.5 font-bold text-[var(--color-ebony)]">Free Size</td><td colspan="4" class="p-2.5 italic text-[var(--color-ebony)]/70">Traditional Sarees measure 5.5m in length + 0.8m unstitched matching blouse piece.</td></tr>
                </tbody>
            </table>
        </div>

        {{-- Note --}}
        <div class="p-3 bg-[var(--color-champagne-light)]/60 rounded-2xl border border-[var(--color-bisque)]/60 text-[11px] font-sans text-[var(--color-ebony)]/80 flex items-start gap-2">
            <span class="text-[var(--color-rose-antique)] font-bold text-sm">✦</span>
            <span>All our outfits include 1.5-inch inner margin allowances on both sides for effortless tailoring adjustments.</span>
        </div>

        <button @click="$store.shop.isSizeGuideOpen = false" class="w-full bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest py-3 rounded-full hover:bg-[var(--color-rose-deep)] transition-colors">
            Close Guide
        </button>
    </div>
</div>
