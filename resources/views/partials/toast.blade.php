{{-- Global Toast Notification --}}
<div x-data
    x-show="$store.shop.toast.show"
    x-transition:enter="transition ease-out duration-300 transform"
    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-200 transform"
    x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-4"
    style="display: none;"
    class="fixed bottom-6 right-6 z-[100] max-w-sm w-full bg-[var(--color-ebony)] text-white p-4 rounded-2xl shadow-2xl border border-[var(--color-rose-antique)]/40 flex items-center justify-between gap-3 pointer-events-auto">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-[var(--color-rose-antique)]/20 text-[var(--color-blush)] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="text-xs font-sans font-medium text-white/90" x-text="$store.shop.toast.message"></p>
    </div>
    <button @click="$store.shop.toast.show = false" class="text-white/40 hover:text-white transition-colors p-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
