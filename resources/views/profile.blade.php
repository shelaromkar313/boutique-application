@extends('layouts.app')

@section('title', 'My Account & Orders Hub | ESTILO WEAR')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-6 sm:py-10" x-data="{
    reviewModal: false,
    selectedItemToReview: null,
    rating: 5,
    reviewComment: '',
    reviewSubmitted: false,

    openReview(item) {
        this.selectedItemToReview = item;
        this.rating = 5;
        this.reviewComment = '';
        this.reviewSubmitted = false;
        this.reviewModal = true;
    },

    submitReview() {
        this.reviewSubmitted = true;
        setTimeout(() => {
            this.reviewModal = false;
            $store.shop.showToast('Thank you for reviewing your couture piece! ✨');
        }, 1200);
    }
}">
<script>
// profile vanilla data — no Alpine dependency for tabs/return
window.PROFILE_ORDERS = @json($orders->values());
window.profileReturnOrder = null;
window.profileReturnType = 'exchange';
function setProfileReturnType(t){
    window.profileReturnType = t;
    var ex = document.getElementById('pr-type-exchange');
    var rf = document.getElementById('pr-type-return');
    if(ex) ex.className = (t==='exchange'?'bg-[var(--color-ebony)] text-white':'bg-gray-100 text-gray-800')+' py-2.5 rounded-xl text-xs font-bold transition-all';
    if(rf) rf.className = (t==='return'?'bg-[var(--color-ebony)] text-white':'bg-gray-100 text-gray-800')+' py-2.5 rounded-xl text-xs font-bold transition-all';
}
function openProfileReturn(id){
    var list = window.PROFILE_ORDERS||[];
    var ord=null; for(var i=0;i<list.length;i++){ if(String(list[i].id)===String(id)){ ord=list[i]; break; } }
    if(!ord) return;
    window.profileReturnOrder = ord;
    var e1=document.getElementById('pr-orderno'); if(e1) e1.textContent='Order #'+(ord.order_no||'');
    var e2=document.getElementById('pr-total'); if(e2) e2.textContent='Total Paid: ₹'+Number(ord.total||0).toLocaleString('en-IN');
    setProfileReturnType('exchange');
    var sel=document.getElementById('pr-reason'); if(sel) sel.value='size';
    var nt=document.getElementById('pr-notes'); if(nt) nt.value='';
    var m=document.getElementById('modal-profile-return'); if(m) m.style.display='flex';
    document.body.style.overflow='hidden';
}
function closeProfileReturn(){
    var m=document.getElementById('modal-profile-return'); if(m) m.style.display='none';
    document.body.style.overflow='';
}
function submitProfileReturn(){
    var ord=window.profileReturnOrder; if(!ord) return;
    var form=document.createElement('form'); form.method='POST'; form.action='/orders/'+ord.id+'/return-request';
    var token=document.querySelector('meta[name="csrf-token"]');
    var add=function(n,v){ var i=document.createElement('input'); i.type='hidden'; i.name=n; i.value=v; form.appendChild(i); };
    add('_token', token?token.getAttribute('content'):'');
    add('type', window.profileReturnType==='exchange'?'exchange':'return');
    var r=document.getElementById('pr-reason'); add('reason', r?r.value:'size');
    var n=document.getElementById('pr-notes'); add('notes', n?n.value:'');
    document.body.appendChild(form); form.submit();
}
</script>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 sm:space-y-8">

        {{-- Flash Alerts --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-emerald-600 text-base">✨</span>
                <span class="text-xs font-sans font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold">✕</button>
        </div>
        @endif

        {{-- TOP: User Welcome Banner & Search Bar --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)]/80 p-5 sm:p-7 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[var(--color-ebony)] text-[#FBEAD6] flex items-center justify-center font-serif font-bold text-xl sm:text-2xl shadow-md ring-4 ring-[var(--color-champagne-light)]">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-50 border border-amber-200 text-amber-900 text-[10px] font-sans font-bold uppercase tracking-wider mb-0.5">
                            <span>✦ Estilo Atelier Patron</span>
                        </div>
                        <h1 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Hello, {{ $user->name }}</h1>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">{{ $user->email }} • {{ $user->phone ?? '+91 (Not set)' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end sm:self-center">
                    <a href="/shop" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-4 py-2.5 rounded-full transition-all shadow-sm">
                        🛍️ Shop
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                        @csrf
                        <button type="submit" class="bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-800 text-xs font-sans font-bold uppercase tracking-wider px-3.5 py-2.5 rounded-full transition-colors flex items-center gap-1" title="Sign Out">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            {{-- Amazon-style Quick Search Bar for Catalog --}}
            <div class="pt-2 border-t border-[var(--color-bisque)]/40">
                <div class="relative">
                    <input type="text" @click="$store.shop.isSearchOpen = true" readonly placeholder="Search couture kurtis, silk sarees, anarkalis, or wedding edits..." class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full pl-10 pr-4 py-2.5 text-xs font-sans cursor-pointer focus:outline-none focus:border-[var(--color-rose-antique)]" />
                    <svg class="w-4 h-4 absolute left-3.5 top-3 text-[var(--color-ebony)]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- 1. LISTS & REGISTRIES (Amazon-style Shopping List Card with Luxury Boutique Style) --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)]/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between pb-3 border-b border-[var(--color-bisque)]/40">
                <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.2em]">Lists & Wardrobe Registries</span>
                <a href="/wishlist" class="text-xs font-sans font-bold text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors">View All →</a>
            </div>

            <a href="/wishlist" class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/60 hover:border-[var(--color-rose-antique)] transition-all group">
                <div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-[var(--color-ebony)] group-hover:text-[var(--color-rose-antique)] transition-colors">Curated Shopping List</h3>
                    <p class="text-[11px] font-sans text-[var(--color-ebony)]/60">Private • Default Boutique Wishlist</p>
                </div>

                {{-- Multi-product thumbnail preview container --}}
                <div class="flex items-center gap-2">
                    <template x-if="$store.shop.wishlist.length > 0">
                        <div class="flex items-center gap-2">
                            <template x-for="(item, idx) in $store.shop.wishlist.slice(0, 3)" :key="idx">
                                <img :src="item.image" :alt="item.name" class="w-12 h-14 object-cover object-top rounded-xl border border-[var(--color-bisque)] shadow-xs" />
                            </template>
                            <template x-if="$store.shop.wishlist.length > 3">
                                <div class="w-12 h-14 rounded-xl bg-[var(--color-champagne-light)] border border-[var(--color-bisque)] flex items-center justify-center font-bold text-xs text-[var(--color-ebony)]" x-text="'+' + ($store.shop.wishlist.length - 3)">
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="$store.shop.wishlist.length === 0">
                        <div class="flex items-center gap-2 text-xs font-sans text-[var(--color-ebony)]/50">
                            <span class="text-lg">💖</span>
                            <span>No items saved yet. Tap heart icon on products to curate your list.</span>
                        </div>
                    </template>
                </div>
            </a>
        </div>

        {{-- 2. YOUR ACCOUNT (Amazon-style Horizontal Quick Pills) --}}
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Your Account</h2>
            </div>

            <div class="flex items-center gap-2.5 overflow-x-auto pb-1 no-scrollbar text-xs font-sans" id="profile-pills">
                <button id="pill-orders" onclick="setProfileTab('orders')" class="px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-[var(--color-ebony)] text-white shadow-sm ring-2 ring-[var(--color-ebony)]">
                    <span>📦</span> Your Orders ({{ $orders->whereNotIn('status', ['exchange_requested', 'exchanged'])->count() }})
                </button>

                <button id="pill-returns" onclick="setProfileTab('returns')" class="px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)]">
                    <span>🔄</span> Your Exchange Orders ({{ $orders->whereIn('status', ['exchange_requested', 'exchanged'])->count() }})
                </button>

                <button id="pill-addresses" onclick="setProfileTab('addresses')" class="px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)]">
                    <span>📍</span> Saved Addresses
                </button>

                <button id="pill-rewards" onclick="setProfileTab('rewards')" class="px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)]">
                    <span>💎</span> Your Rewards
                </button>

                <button id="pill-edit" onclick="setProfileTab('edit')" class="px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)]">
                    <span>⚙️</span> Account Settings
                </button>

                <a href="/wishlist" class="px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)] flex items-center gap-1.5 shrink-0">
                    <span>❤️</span> Wishlist
                </a>
            </div>
            <script>
            // Vanilla tab switching (no framework dependency — pills always work)
            function setProfileTab(name) {
                if(name==='rewards'){
                    var card=document.getElementById('rewards-card');
                    if(card) card.scrollIntoView({behavior:'smooth',block:'center'});
                    // highlight rewards pill, dim others
                    ['orders','returns','addresses','edit','rewards'].forEach(function(t){
                        var pill=document.getElementById('pill-'+t);
                        if(!pill) return;
                        var on = (t==='rewards');
                        pill.className = on
                          ? 'px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-[var(--color-ebony)] text-white shadow-sm ring-2 ring-[var(--color-ebony)]'
                          : 'px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)]';
                    });
                    // also hide all tab sections, show orders as fallback behind rewards
                    ['orders','returns','addresses','edit'].forEach(function(t){
                        var sec=document.getElementById('tabsec-'+t);
                        if(sec) sec.style.display='none';
                    });
                    var ord=document.getElementById('tabsec-orders');
                    if(ord) ord.style.display='';
                    return;
                }
                var tabs = ['orders', 'returns', 'addresses', 'edit'];
                var activeCls = 'px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-[var(--color-ebony)] text-white shadow-sm ring-2 ring-[var(--color-ebony)]';
                var idleCls   = 'px-5 py-2.5 rounded-full font-bold whitespace-nowrap transition-all flex items-center gap-1.5 shrink-0 bg-white text-[var(--color-ebony)] border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)]';
                tabs.forEach(function (t) {
                    var sec = document.getElementById('tabsec-' + t);
                    if (sec) sec.style.display = (t === name) ? '' : 'none';
                });
                ['orders','returns','addresses','edit','rewards'].forEach(function(t){
                    var pill=document.getElementById('pill-'+t);
                    if(pill) pill.className = (t===name?activeCls:idleCls);
                });
            }
            </script>
        </div>

        {{-- 3. YOUR REWARDS & WALLET (Amazon-style 3-Column Rewards Card) --}}
        <div id="rewards-card" class="bg-white rounded-3xl border border-[var(--color-bisque)]/80 p-5 sm:p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-[var(--color-bisque)]/40">
                <h3 class="font-serif text-base sm:text-lg font-bold text-[var(--color-ebony)]">Your Rewards & Atelier Perks</h3>
                <span class="text-[10px] font-sans font-bold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-full border border-emerald-200">Active Patron</span>
            </div>

            <div class="grid grid-cols-3 gap-3 sm:gap-4 text-center">
                {{-- 1. Cashback / Store Credit --}}
                <div class="p-3 sm:p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/60 flex flex-col items-center justify-between space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Atelier Credit</span>
                    <div class="flex items-center gap-1">
                        <span class="text-base sm:text-xl font-bold font-serif text-[var(--color-ebony)]">🪙 ₹150</span>
                    </div>
                    <span class="text-[9px] text-emerald-700 font-semibold">Usable on next order</span>
                </div>

                {{-- 2. Collected Offers / Coupons --}}
                <div class="p-3 sm:p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/60 flex flex-col items-center justify-between space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Collected Offers</span>
                    <div class="flex items-center gap-1">
                        <span class="text-base sm:text-xl font-bold font-serif text-[var(--color-ebony)]">🎟️ 2 Active</span>
                    </div>
                    <span class="text-[9px] text-[var(--color-rose-antique)] font-mono font-bold">BOUTIQUE10 (10% OFF)</span>
                </div>

                {{-- 3. Membership / Scratch cards --}}
                <div class="p-3 sm:p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/60 flex flex-col items-center justify-between space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Tier Status</span>
                    <div class="flex items-center gap-1">
                        <span class="text-base sm:text-xl font-bold font-serif text-[var(--color-ebony)]">💎 Gold</span>
                    </div>
                    <span class="text-[9px] text-amber-800 font-semibold">Free Express Shipping</span>
                </div>
            </div>
        </div>

        {{-- 4. YOUR REVIEWS (Amazon-style Review Cards for Recent Purchases) --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)]/80 p-5 sm:p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-2 border-b border-[var(--color-bisque)]/40">
                <div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-[var(--color-ebony)]">Your Reviews & Ratings</h3>
                    <p class="text-[11px] font-sans text-[var(--color-ebony)]/60">Rate garments you received to help fellow couture patrons.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Example Review Prompt Card --}}
                <div class="p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/60 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="/storage/products/est-001-chikankari-anarkali.jpg" alt="Gulzar Anarkali" class="w-14 h-16 object-cover rounded-xl border border-[var(--color-bisque)] shadow-xs" />
                        <div>
                            <h4 class="font-serif text-xs font-bold text-[var(--color-ebony)] line-clamp-1">Gulzar Chikankari Anarkali</h4>
                            <p class="text-[10px] font-sans text-[var(--color-ebony)]/60">What did you think of the craft?</p>
                            <div class="flex items-center gap-1 text-amber-500 pt-1 cursor-pointer" @click="openReview({ name: 'Gulzar Chikankari Anarkali', image: '/storage/products/est-001-chikankari-anarkali.jpg' })">
                                <span>★★★★★</span>
                                <span class="text-[10px] text-gray-500 font-sans ml-1">Write Review</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)]/60 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <img src="/storage/products/est-002-banarasi-saree.jpg" alt="Banarasi Saree" class="w-14 h-16 object-cover rounded-xl border border-[var(--color-bisque)] shadow-xs" />
                        <div>
                            <h4 class="font-serif text-xs font-bold text-[var(--color-ebony)] line-clamp-1">Varanasi Royal Banarasi Silk Saree</h4>
                            <p class="text-[10px] font-sans text-[var(--color-ebony)]/60">How is the silk and zari weave?</p>
                            <div class="flex items-center gap-1 text-amber-500 pt-1 cursor-pointer" @click="openReview({ name: 'Varanasi Royal Banarasi Silk Saree', image: '/storage/products/est-002-banarasi-saree.jpg' })">
                                <span>★★★★★</span>
                                <span class="text-[10px] text-gray-500 font-sans ml-1">Write Review</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 5. YOUR ORDERS (Comprehensive Orders List) --}}
        @php
            $activeOrders = $orders->whereNotIn('status', ['exchange_requested', 'exchanged']);
            $returnOrders = $orders->whereIn('status', ['exchange_requested', 'exchanged']);
        @endphp
        <div id="tabsec-orders" class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Your Recent Orders</h2>
                <span class="text-xs font-sans text-[var(--color-ebony)]/60">{{ $activeOrders->count() }} Total Placed</span>
            </div>

            @forelse($activeOrders as $ord)
            @php
                $items = $ord->items;
                for ($di = 0; $di < 3 && is_string($items); $di++) {
                    $dec = json_decode($items, true);
                    if (json_last_error() !== JSON_ERROR_NONE) break;
                    $items = $dec;
                }
                if (!is_array($items)) $items = [];
            @endphp
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-5 sm:p-7 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[var(--color-bisque)]/60">
                    <div>
                        <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-500">Order Number</span>
                        <h3 class="font-mono text-base font-bold text-[var(--color-ebony)]">{{ $ord->order_no }}</h3>
                        <span class="text-xs text-gray-500">Placed on {{ $ord->created_at ? $ord->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : 'Recently' }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-500 block">Total</span>
                            <span class="font-serif text-lg font-bold text-[var(--color-ebony)]">₹{{ number_format($ord->total, 0) }}</span>
                        </div>

                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $ord->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : '' }}
                            {{ $ord->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $ord->status === 'confirmed' || $ord->status === 'paid' ? 'bg-amber-100 text-amber-800' : '' }}
                            {{ $ord->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}
                            {{ $ord->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                            {{ $ord->status === 'exchange_requested' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $ord->status === 'exchanged' ? 'bg-purple-100 text-purple-800' : '' }}
                        ">
                            ● {{ str_replace('_', ' ', $ord->status) }}
                        </span>
                    </div>
                </div>

                {{-- Garments in this order --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($items as $item)
                    <div class="flex items-center gap-3 p-3 bg-[var(--color-offwhite)] rounded-2xl border border-[var(--color-bisque)]/60">
                        <div class="w-12 h-14 bg-gray-200 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                            <img src="{{ $item['image'] ?? '/storage/hero/hero-main.jpg' }}" alt="{{ $item['name'] ?? 'Garment' }}" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h5 class="font-serif text-xs font-bold text-[var(--color-ebony)] truncate">{{ $item['name'] ?? 'Couture Item' }}</h5>
                            <span class="text-[10px] text-gray-500 font-sans block">Size: {{ $item['selectedSize'] ?? $item['size'] ?? 'Standard' }} • Color: {{ $item['selectedColor'] ?? $item['color'] ?? 'Standard' }} • Qty: {{ $item['quantity'] ?? $item['qty'] ?? 1 }}</span>
                            <span class="font-serif font-bold text-xs text-[var(--color-rose-antique)]">₹{{ number_format($item['price'] ?? 0, 0) }}</span>
                        </div>
                        <button type="button" @click="openReview({{ json_encode($item) }})" class="text-[10px] font-sans font-bold text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] px-2 py-1 bg-white rounded-lg border border-[var(--color-bisque)]">
                            ★ Review
                        </button>
                    </div>
                    @endforeach
                </div>

                {{-- Delivery Destination & 7-Day Exchange Trigger --}}
                <div class="pt-3 border-t border-[var(--color-bisque)]/40 flex flex-col sm:flex-row items-start sm:items-center justify-between text-xs text-gray-600 gap-3">
                    <div>
                        <span class="font-bold text-[var(--color-ebony)]">Destination:</span>
                        {{ $ord->address }}, {{ $ord->city }}, {{ $ord->state }} - {{ $ord->pincode }}
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] text-gray-500 font-bold uppercase"><span class="text-emerald-600">🛡️ 7-Day Exchange Eligible</span> • {{ strtoupper($ord->payment_method ?? 'Online Pre-paid') }}</span>
                        @if(in_array($ord->status, ['delivered', 'confirmed', 'shipped', 'paid', 'processing', 'dispatched']))
                        <button type="button"
                                onclick="openProfileReturn({{ $ord->id }})"
                                class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-800 text-[10px] font-sans font-bold uppercase tracking-wider px-3 py-1.5 rounded-full transition-all cursor-pointer">
                            <span>🔄</span>
                            <span>Exchange (7 Days)</span>
                        </button>
                        @elseif(in_array($ord->status, ['exchange_requested', 'exchanged']))
                        <span class="inline-flex items-center gap-1.5 bg-orange-100 border border-orange-200 text-orange-800 text-[10px] font-sans font-bold uppercase tracking-wider px-3 py-1.5 rounded-full">
                            {{ str_replace('_', ' ', ucfirst($ord->status)) }} ⏳
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-10 text-center shadow-sm space-y-4">
                <div class="text-4xl">🛍️</div>
                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">No Orders Yet</h3>
                <p class="text-xs font-sans text-gray-500 max-w-md mx-auto">You haven't placed any couture orders yet. Discover our artisanal Chikankari, Anarkalis, and Luxury Silk Sarees.</p>
                <a href="/shop" class="inline-block bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-3 rounded-full transition-all shadow-md">
                    Start Shopping
                </a>
            </div>
            @endforelse
        </div>

        {{-- 5b. YOUR EXCHANGE ORDERS (Exchange history only) --}}
        <div id="tabsec-returns" class="space-y-4" style="display:none;">
            <div class="flex items-center justify-between">
                <h2 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Your Exchange Orders</h2>
                <span class="text-xs font-sans text-[var(--color-ebony)]/60">{{ $returnOrders->count() }} Exchange Requests</span>
            </div>

            @forelse($returnOrders as $ord)
            <div class="bg-white rounded-3xl border border-purple-200 p-5 sm:p-7 shadow-sm space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-[var(--color-bisque)]/60">
                    <div>
                        <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-500">Order Number</span>
                        <h3 class="font-mono text-base font-bold text-[var(--color-ebony)]">{{ $ord->order_no }}</h3>
                        <span class="text-xs text-gray-500">Placed on {{ $ord->created_at ? $ord->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : 'Recently' }}</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-500 block">Total</span>
                            <span class="font-serif text-lg font-bold text-[var(--color-ebony)]">₹{{ number_format($ord->total, 0) }}</span>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $ord->status === 'exchange_requested' ? 'bg-orange-100 text-orange-800' : '' }}
                            {{ $ord->status === 'exchanged' ? 'bg-purple-100 text-purple-800' : '' }}
                        ">
                            ● {{ str_replace('_', ' ', $ord->status) }}
                        </span>
                    </div>
                </div>
                @if($ord->note)
                <p class="text-[11px] font-sans text-amber-900 bg-amber-50 border border-amber-200/70 rounded-xl px-3.5 py-2.5">{{ $ord->note }}</p>
                @endif
                <p class="text-[11px] font-sans text-gray-500">Pickup is scheduled within 24-48 hours of your request. Replacement will be shipped after collection.</p>
            </div>
            @empty
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-10 text-center shadow-sm space-y-4">
                <div class="text-4xl">🔄</div>
                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">No Exchanges Yet</h3>
                <p class="text-xs font-sans text-gray-500 max-w-md mx-auto">Delivered orders can be exchanged for a different size/color within 7 days from the Your Orders section.</p>
            </div>
            @endforelse
        </div>

        {{-- 6. SAVED ADDRESSES TAB --}}
        <div id="tabsec-addresses" class="space-y-4" style="display:none;">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-[var(--color-bisque)]/60">
                    <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Your Delivery Addresses</h3>
                    <button onclick="setProfileTab('edit')" class="text-xs font-sans font-bold text-[var(--color-rose-antique)] hover:underline">+ Edit Default Address</button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-2xl bg-[var(--color-offwhite)] border-2 border-[var(--color-ebony)]/80 relative">
                        <span class="absolute top-3 right-3 text-[9px] font-sans font-bold uppercase bg-[var(--color-ebony)] text-white px-2 py-0.5 rounded-full">Default</span>
                        <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)]">{{ $user->name }}</h4>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/70 mt-1 leading-relaxed">
                            {{ $orders->first()?->address ?? 'Flat 402, Royal Palms Residency, MG Road' }}<br>
                            {{ $orders->first()?->city ?? 'Mumbai' }}, {{ $orders->first()?->state ?? 'Maharashtra' }} - {{ $orders->first()?->pincode ?? '400001' }}
                        </p>
                        <p class="text-xs font-sans font-bold text-[var(--color-ebony)] mt-2">Phone: {{ $user->phone ?? '+91 98765 43210' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 7. ACCOUNT SETTINGS TAB --}}
        <div id="tabsec-edit" class="space-y-6" style="display:none;">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6 max-w-2xl">
                <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                    <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Update Profile & Security Details</h3>
                    <p class="text-xs font-sans text-gray-500">Edit your name, contact phone number, and account password.</p>
                </div>

                <form action="/profile" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Email Address (Read-only)</label>
                        <input type="email" value="{{ $user->email }}" readonly class="w-full bg-gray-100 border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans text-gray-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Contact Mobile Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="9876543210" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                    </div>

                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">New Password (Leave empty to keep current)</label>
                        <input type="password" name="password" placeholder="••••••••" minlength="6" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 8. NEED HELP? ATELIER CONCIERGE (Amazon-style Customer Service Card) --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)]/80 p-5 sm:p-6 shadow-sm hover:shadow-md transition-shadow">
            <a href="https://api.whatsapp.com/send?phone=919876543210&text={{ urlencode('Hello Estilo Concierge, I need assistance with my boutique orders.') }}" target="_blank" class="flex items-center justify-between gap-4 group">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-[var(--color-champagne-light)] text-[var(--color-ebony)] flex items-center justify-center text-xl shrink-0 group-hover:scale-105 transition-transform">
                        💬
                    </div>
                    <div>
                        <h4 class="font-serif text-base font-bold text-[var(--color-ebony)] group-hover:text-[var(--color-rose-antique)] transition-colors">Need Help? Contact Atelier Concierge</h4>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Live styling consultation, order tracking, and bespoke tailoring assistance.</p>
                    </div>
                </div>
                <span class="text-lg text-[var(--color-ebony)] group-hover:translate-x-1 transition-transform">→</span>
            </a>
        </div>

    </div>

    {{-- CUSTOMER REVIEW MODAL --}}
    <div x-show="reviewModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="reviewModal" x-transition.opacity @click="reviewModal = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-4">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Rate Your Garment</h3>
                <button @click="reviewModal = false" class="text-gray-400 hover:text-gray-600 text-base">✕</button>
            </div>

            <template x-if="selectedItemToReview">
                <div class="flex items-center gap-3 p-3 bg-[var(--color-offwhite)] rounded-xl border border-[var(--color-bisque)]/60">
                    <img :src="selectedItemToReview.image || '/storage/hero/hero-main.jpg'" class="w-12 h-14 object-cover rounded-lg shrink-0" />
                    <div>
                        <h4 class="font-serif text-xs font-bold text-[var(--color-ebony)] truncate" x-text="selectedItemToReview.name"></h4>
                        <span class="text-[10px] text-gray-500 font-sans">Craft & fit feedback</span>
                    </div>
                </div>
            </template>

            <div>
                <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Your Star Rating</label>
                <div class="flex gap-2 text-2xl text-amber-400 cursor-pointer">
                    <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                        <span @click="rating = star" :class="star <= rating ? 'opacity-100 scale-110' : 'opacity-30'" class="transition-all">★</span>
                    </template>
                </div>
            </div>

            <div>
                <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Review Notes & Fitting Experience</label>
                <textarea x-model="reviewComment" rows="3" placeholder="Tell us how the fabric felt, the embroidery precision, and the silhouette fit..." class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2.5 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]"></textarea>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" @click="reviewModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-[var(--color-ebony)] text-xs font-sans font-bold py-3 rounded-xl">Cancel</button>
                <button type="button" @click="submitReview()" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold py-3 rounded-xl shadow-md">Submit Review ✨</button>
            </div>
        </div>
    </div>

    {{-- 7-DAY EASY EXCHANGE MODAL (vanilla, return removed) --}}
    <div id="modal-profile-return" style="display:none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div onclick="closeProfileReturn()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-4">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div>
                    <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">7-Day Easy Exchange</h3>
                    <p class="text-[10px] text-gray-500 font-sans">Size/color exchange — doorstep pickup within 24-48 hours</p>
                </div>
                <button onclick="closeProfileReturn()" class="text-gray-400 hover:text-gray-600 text-base">✕</button>
            </div>

            <div class="p-3 bg-[var(--color-offwhite)] rounded-2xl border border-[var(--color-bisque)]/60 space-y-1">
                <div class="flex justify-between items-center text-xs font-bold text-[var(--color-ebony)]">
                    <span id="pr-orderno">Order #—</span>
                    <span class="text-emerald-700 text-[10px] font-sans">🛡️ 7-Day Window Active</span>
                </div>
                <p class="text-[10px] text-gray-500" id="pr-total">Total Paid: —</p>
            </div>

            <div class="space-y-3">
                <div class="hidden">
                    <input type="hidden" id="pr-type-exchange" />
                    <input type="hidden" id="pr-type-return" />
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Reason for Exchange</label>
                    <select id="pr-reason" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2.5 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]">
                        <option value="size">Size Fitting Issue (Too tight / Too loose)</option>
                        <option value="fabric">Fabric / Drape Preference</option>
                        <option value="color">Color Mismatch from Image</option>
                        <option value="damaged">Damaged or Defective Item Received</option>
                        <option value="other">Changed Mind / Not Required</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Notes for Pickup Agent</label>
                    <textarea id="pr-notes" rows="2" placeholder="Provide any special fitting notes or preferred pickup address/time..." class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2.5 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]"></textarea>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeProfileReturn()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-[var(--color-ebony)] text-xs font-sans font-bold py-3 rounded-xl">Cancel</button>
                <button type="button" onclick="submitProfileReturn()" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold py-3 rounded-xl shadow-md cursor-pointer">Submit Exchange Request 🚚</button>
            </div>
        </div>
    </div>

</div>
@endsection

