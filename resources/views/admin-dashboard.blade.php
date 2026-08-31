@extends('layouts.app')

@section('title', 'Estilo Management Console - Dashboard Overview')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] pb-24 pt-6" x-data="{
    activeTab: '{{ request('tab', 'overview') }}',
    search: '',
    showAddProductModal: false,
    showAddCategoryModal: false,
    showAddCouponModal: false,
    showProfileModal: false,
    editProductModal: false,
    viewOrderModal: false,
    payoutModal: false,
    selectedProduct: {},
    selectedOrder: {},
    selectedAssociate: {},

    init() {
        const tabTitles = {
            'overview': 'Dashboard Overview',
            'inventory': 'Inventory & Products',
            'orders': 'Orders & Fulfillment',
            'customers': 'Customers',
            'associates': 'Sales Associates & Sellers',
            'reports': 'Monthly Reports & Billing',
            'offers': 'Offers & Coupons',
            'announcements': 'Storefront Announcements & Alerts',
            'reviews': 'Ratings & Reviews',
            'profile': 'Admin Profile & Security'
        };
        const updateDocTitle = (tab) => {
            document.title = 'Estilo Management Console - ' + (tabTitles[tab] || 'Dashboard Overview');
        };
        updateDocTitle(this.activeTab);
        this.$watch('activeTab', (val) => updateDocTitle(val));
    },

    newSizeStock: { 'XS': 1, 'S': 2, 'M': 4, 'L': 2, 'XL': 3, 'XXL': 2 },

    openEditProduct(p) {
        this.selectedProduct = Object.assign({}, p);
        if (Array.isArray(this.selectedProduct.colors)) {
            this.selectedProduct.colors_str = this.selectedProduct.colors.join(', ');
        } else {
            this.selectedProduct.colors_str = this.selectedProduct.colors || '';
        }
        
        let stock = p.size_stock || {};
        if (typeof stock === 'string') {
            try { stock = JSON.parse(stock); } catch(e) { stock = {}; }
        }
        if (!stock || Object.keys(stock).length === 0) {
            stock = {};
            let defaultSizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
            let currentSizes = Array.isArray(p.sizes) ? p.sizes : defaultSizes;
            defaultSizes.forEach(sz => {
                stock[sz] = currentSizes.includes(sz) ? 2 : 1;
            });
        }
        this.selectedProduct.size_stock = Object.assign({ 'XS': 1, 'S': 2, 'M': 4, 'L': 2, 'XL': 3, 'XXL': 2 }, stock);
        this.editProductModal = true;
    },

    openViewOrder(ord) {
        this.selectedOrder = ord;
        try {
            this.selectedOrder.parsedItems = typeof ord.items === 'string' ? JSON.parse(ord.items || '[]') : (ord.items || []);
        } catch(e) {
            this.selectedOrder.parsedItems = [];
        }
        this.viewOrderModal = true;
    },

    openPayoutModal(assoc) {
        this.selectedAssociate = assoc;
        this.payoutModal = true;
    },

    editReviewModal: false,
    selectedReview: { rating: 5, comment: '', user_name: '', is_approved: true },
    reviewFilter: 'all',

    showAddAnnouncementModal: false,
    editAnnouncementModal: false,
    selectedAnnouncement: { id: null, title: '', message: '', type: 'sale', color: 'amber', icon: '📢', show_in_ticker: true, show_as_banner: false, is_active: true, starts_at: '', ends_at: '' },
    announcementFilter: 'all',

    editCouponModal: false,
    newCoupon: {
        code: 'DIWALI30',
        title: 'Diwali Royal Festive 30% Off',
        discount_type: 'percentage',
        discount_value: 30,
        min_order_value: 1999,
        campaign_type: 'festival',
        valid_until: '2027-12-31'
    },
    selectedCoupon: {
        id: null,
        code: '',
        title: '',
        discount_type: 'percentage',
        discount_value: 20,
        min_order_value: 1999,
        campaign_type: 'festival',
        valid_until: '',
        is_active: true
    },

    openEditCoupon(coup) {
        this.selectedCoupon = Object.assign({}, coup);
        if (coup.valid_until && typeof coup.valid_until === 'string') {
            this.selectedCoupon.valid_until = coup.valid_until.split('T')[0];
        }
        this.editCouponModal = true;
    },

    applyPresetCoupon(code, title, discount, type, minOrder, campaign) {
        this.newCoupon.code = code;
        this.newCoupon.title = title;
        this.newCoupon.discount_value = discount;
        this.newCoupon.discount_type = type;
        this.newCoupon.min_order_value = minOrder;
        this.newCoupon.campaign_type = campaign;
    },

    openEditReview(rev) {
        this.selectedReview = Object.assign({}, rev);
        this.selectedReview.is_approved = Boolean(Number(rev.is_approved));
        this.selectedReview.rating = Number(rev.rating || 5);
        this.editReviewModal = true;
    },

    openEditAnnouncement(ann) {
        this.selectedAnnouncement = Object.assign({}, ann);
        this.editAnnouncementModal = true;
    }
}">

    <div class="max-w-[1520px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Top Success / Error Notification Banners --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-900 px-5 py-3.5 rounded-2xl flex items-center justify-between shadow-sm animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="text-emerald-600 text-base">✨</span>
                <span class="text-xs font-sans font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 text-sm font-bold">✕</button>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-rose-50 border border-rose-300 text-rose-900 px-5 py-3.5 rounded-2xl space-y-1 shadow-sm">
            <div class="flex items-center gap-2 text-xs font-bold font-sans">
                <span>⚠️</span> Please correct the following errors:
            </div>
            <ul class="list-disc pl-6 text-xs space-y-0.5">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Admin Master Header --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <div class="space-y-1.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 text-amber-200 text-[10px] font-sans font-bold uppercase tracking-widest">
                    <span>Executive Administration Suite</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Estilo Management Console</h1>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">Live session-authenticated portal for luxury handloom inventory, customer orders, partner earnings, billing audits, and festive discounts.</p>
            </div>

            {{-- Right: Profile & Action Buttons --}}
            <div class="flex items-center gap-3 flex-wrap">
                {{-- Admin Profile Badge --}}
                <div class="flex items-center gap-2.5 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full pl-2.5 pr-3 py-1.5 shadow-sm">
                    <div class="w-7 h-7 rounded-full bg-[var(--color-ebony)] text-amber-200 flex items-center justify-center font-bold text-xs shadow-inner">
                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-left leading-tight hidden sm:block">
                        <span class="text-xs font-bold text-[var(--color-ebony)] block">{{ $admin->name ?? 'Administrator' }}</span>
                        <span class="text-[9px] font-sans text-emerald-700 font-bold uppercase tracking-wider">Active Admin</span>
                    </div>
                    <button @click="showProfileModal = true" class="text-[11px] bg-white border border-[var(--color-bisque)] hover:bg-gray-50 text-[var(--color-ebony)] font-bold px-3 py-1 rounded-full transition-colors ml-1 shadow-sm" title="View Profile">
                        Profile
                    </button>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline m-0">
                        @csrf
                        <button type="submit" class="text-[11px] bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 font-bold px-3 py-1 rounded-full transition-colors shadow-sm" title="Log Out">
                            Sign Out
                        </button>
                    </form>
                </div>

                {{-- Action shortcuts --}}
                <button @click="showAddProductModal = true" class="inline-flex items-center gap-1.5 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-4 py-2.5 rounded-full shadow-md transition-all hover:scale-105 active:scale-95">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Product
                </button>
                <button @click="showAddCouponModal = true" class="inline-flex items-center gap-1.5 bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-900 text-xs font-sans font-bold uppercase tracking-wider px-3.5 py-2.5 rounded-full transition-colors">
                    + Coupon
                </button>
                <button @click="showAddAnnouncementModal = true" class="inline-flex items-center gap-1.5 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-900 text-xs font-sans font-bold uppercase tracking-wider px-3.5 py-2.5 rounded-full transition-colors shadow-xs">
                    + Announcement
                </button>
                <a href="/shop" target="_blank" class="inline-flex items-center gap-1.5 bg-gray-50 hover:bg-gray-100 border border-[var(--color-bisque)] text-[var(--color-ebony)] text-xs font-sans font-bold uppercase tracking-wider px-3.5 py-2.5 rounded-full transition-colors">
                    Store ↗
                </a>
            </div>
        </div>


        {{-- TAB 1: OVERVIEW --}}
        <div x-show="activeTab === 'overview'" class="space-y-6">
            {{-- KPI Metric Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Gross Revenue</span>
                    <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">₹{{ number_format($totalRevenue, 0) }}</h3>
                    <span class="text-[10px] font-sans text-emerald-600 font-semibold">↑ Verified orders</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Total Orders</span>
                    <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">{{ $totalOrdersCount }}</h3>
                    <span class="text-[10px] font-sans text-[var(--color-ebony)]/50">Processed across India</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-thyme)]">In Stock Catalog</span>
                    <h3 class="font-serif text-2xl font-bold text-[var(--color-thyme)]">{{ $inStockCount }} / {{ $totalProductsCount }}</h3>
                    <span class="text-[10px] font-sans text-[var(--color-thyme)]">Ready for Virtual Try-On</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)] shadow-sm space-y-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-amber-800">Sales Associates</span>
                    <h3 class="font-serif text-2xl font-bold text-amber-800">{{ $totalAssociatesCount }}</h3>
                    <span class="text-[10px] font-sans text-amber-700">Active Affiliate Partners</span>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)] shadow-sm space-y-1 col-span-2 sm:col-span-1">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-purple-900">Total Commissions</span>
                    <h3 class="font-serif text-2xl font-bold text-purple-900">₹{{ number_format($totalCommissionPaid ?: 995.64, 2) }}</h3>
                    <span class="text-[10px] font-sans text-purple-700">Paid out to associates</span>
                </div>
            </div>

            {{-- Quick Summary Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Recent Orders Snapshot --}}
                <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Recent Customer Orders</h3>
                        <button @click="activeTab = 'orders'" class="text-xs font-sans font-bold text-[var(--color-rose-antique)] hover:underline">View All →</button>
                    </div>
                    <div class="space-y-3">
                        @forelse($orders->take(4) as $ord)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-[var(--color-offwhite)] hover:bg-[var(--color-champagne-light)] transition-colors">
                            <div>
                                <span class="font-mono text-xs font-bold text-[var(--color-ebony)]">{{ $ord->order_no }}</span>
                                <p class="text-xs font-serif font-semibold text-[var(--color-ebony)]">{{ $ord->full_name }} ({{ $ord->city }})</p>
                            </div>
                            <div class="text-right">
                                <span class="font-serif font-bold text-xs">₹{{ number_format($ord->total, 0) }}</span>
                                <span class="block text-[10px] font-bold text-emerald-700 uppercase">{{ $ord->status }}</span>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-xs text-[var(--color-ebony)]/60">No pending orders.</div>
                        @endforelse
                    </div>
                </div>

                {{-- Top Sales Associates Snapshot --}}
                <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Top Marketing Associates</h3>
                        <button @click="activeTab = 'associates'" class="text-xs font-sans font-bold text-[var(--color-rose-antique)] hover:underline">Manage All →</button>
                    </div>
                    <div class="space-y-3">
                        @foreach($associates->take(4) as $assoc)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-[var(--color-offwhite)] hover:bg-[var(--color-champagne-light)] transition-colors">
                            <div>
                                <span class="font-bold text-xs text-[var(--color-ebony)]">{{ $assoc->name }}</span>
                                <span class="block text-[10px] font-mono text-amber-800">Code: {{ $assoc->referral_code ?? 'ESTILO-SA01' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-serif font-bold text-xs text-emerald-700">₹{{ number_format($assoc->earnings, 0) }} Total Profit</span>
                                <span class="block text-[10px] font-bold text-[var(--color-ebony)]/60">{{ $assoc->commission_rate }}% Rate • ₹{{ number_format($assoc->balance, 0) }} Due</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: 4.3 INVENTORY & PRODUCT MANAGEMENT --}}
        <div x-show="activeTab === 'inventory'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-[var(--color-bisque)]/60">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Boutique Inventory & Products</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Add new couture, edit pricing & fabric, update stock status, manage categories, and configure virtual try-on items.</p>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto flex-wrap sm:flex-nowrap">
                        <input type="text" x-model="search" placeholder="Search catalog..." class="w-full sm:w-64 pl-4 pr-4 py-2 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full text-xs font-sans focus:outline-none" />
                        <button @click="showAddCategoryModal = true" class="bg-white border border-[var(--color-bisque)] hover:bg-gray-50 text-xs font-sans font-bold px-4 py-2 rounded-full shrink-0">
                            + Category
                        </button>
                        <button @click="showAddProductModal = true" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold px-4 py-2 rounded-full shrink-0 shadow-sm">
                            + Add Product
                        </button>
                    </div>
                </div>

                {{-- Categories Quick Pill Filter --}}
                <div class="flex items-center gap-2 flex-wrap pb-2">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/50 mr-1">Categories:</span>
                    <button @click="search = ''" :class="search === '' ? 'bg-[var(--color-ebony)] text-white' : 'bg-gray-100 text-[var(--color-ebony)]'" class="text-[10px] font-sans font-bold px-3 py-1 rounded-full transition-colors">All</button>
                    @foreach($categories as $cat)
                    <div class="inline-flex items-center gap-1 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full px-2.5 py-0.5">
                        <button @click="search = '{{ $cat->name }}'" class="text-[10px] font-bold text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)]">{{ $cat->name }}</button>
                        <form action="/estilo-hq-console/categories/{{ $cat->id }}" method="POST" class="inline" onsubmit="return confirm('Remove category {{ $cat->name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-[9px] text-gray-400 hover:text-rose-600 font-bold ml-1">✕</button>
                        </form>
                    </div>
                    @endforeach
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Outfit</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Price</th>
                                <th class="p-3">Stock Quantity Per Size</th>
                                <th class="p-3">Total Quantity & Status</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @forelse($products as $prod)
                            @php
                                $imgArr = $prod->getRawOriginal('images');
                                $imgArr = is_string($imgArr) ? json_decode($imgArr, true) : $imgArr;
                                $img = (is_array($imgArr) && count($imgArr)) ? $imgArr[0] : '/storage/hero/hero-main.jpg';
                                
                                $stk = is_array($prod->size_stock) ? $prod->size_stock : [];
                                if (empty($stk)) {
                                    $szList = is_array($prod->sizes) ? $prod->sizes : ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
                                    foreach($szList as $s) { $stk[strtoupper($s)] = 2; }
                                }
                                $totUnits = array_sum($stk);
                                $inStock = $totUnits > 0;
                            @endphp
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors"
                                x-show="!search || '{{ strtolower($prod->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($prod->category) }}'.includes(search.toLowerCase())">
                                <td class="p-3 flex items-center gap-3">
                                    <img src="{{ $img }}" alt="{{ $prod->name }}" class="w-11 h-14 object-cover rounded-lg shrink-0 border border-[var(--color-bisque)]" />
                                    <div>
                                        <h4 class="font-bold text-[var(--color-ebony)] line-clamp-1">{{ $prod->name }}</h4>
                                        <span class="text-[10px] text-[var(--color-ebony)]/50 font-mono">ID: {{ $prod->est_id }} • SKU: {{ $prod->sku }}</span>
                                    </div>
                                </td>
                                <td class="p-3 font-semibold text-[var(--color-rose-antique)]">{{ $prod->category }}</td>
                                <td class="p-3 font-serif font-bold text-sm">₹{{ number_format($prod->price, 0) }}</td>
                                
                                {{-- Size-Wise Stock Badges --}}
                                <td class="p-3">
                                    <div class="flex flex-wrap gap-1.5 max-w-xs">
                                        @foreach($stk as $sName => $sQty)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold border {{ (int)$sQty > 0 ? 'bg-slate-100 border-slate-300 text-slate-900' : 'bg-rose-50 border-rose-200 text-rose-600' }}" title="{{ $sName }}: {{ $sQty }} in stock">
                                                <span class="text-slate-600">{{ strtoupper($sName) }}</span>
                                                <span class="text-amber-900 font-extrabold">{{ $sQty }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                {{-- Total Units in Stock --}}
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10.5px] font-bold inline-block {{ $inStock ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' : 'bg-rose-100 text-rose-900 border border-rose-300' }}">
                                        {{ $inStock ? ('● ' . $totUnits . ' Units In Stock') : '○ Out of Stock' }}
                                    </span>
                                </td>

                                <td class="p-3 text-right space-x-2">
                                    <button @click="openEditProduct({{ json_encode($prod) }})" class="px-2.5 py-1 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg text-xs font-bold transition-colors">
                                        ✏️ Edit
                                    </button>
                                    <form action="/estilo-hq-console/products/{{ $prod->id }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-lg text-xs font-bold transition-colors" title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center">
                                    <div class="flex flex-col items-center gap-2 text-[var(--color-ebony)]/50">
                                        <span class="text-3xl">🛍️</span>
                                        <p class="text-sm font-serif font-semibold">No products in catalog yet.</p>
                                        <p class="text-xs font-sans">Click <strong>+ Add Product</strong> above to add your first couture outfit.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 3: 4.4 ORDERS & PROCESSING --}}
        <div x-show="activeTab === 'orders'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-[var(--color-bisque)]/60 pb-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Customer Orders & Processing</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">View real-time customer orders, update shipping fulfillment status, and inspect garment delivery specifications.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Order No</th>
                                <th class="p-3">Customer Name & Contact</th>
                                <th class="p-3">Destination</th>
                                <th class="p-3">Payment Mode & Type</th>
                                <th class="p-3">Order Total</th>
                                <th class="p-3">Status</th>
                                <th class="p-3 text-right">Fulfillment Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @forelse($orders as $ord)
                            @php
                                $pid = strtoupper($ord->payment_id ?? '');
                                $note = strtoupper($ord->note ?? '');
                                
                                if (str_contains($pid, 'COD') || str_contains($note, 'COD') || str_contains($note, 'CASH ON DELIVERY')) {
                                    $payBadge = 'bg-amber-100 text-amber-900 border-amber-300';
                                    $payIcon = '💵';
                                    $payTitle = 'Cash on Delivery (COD)';
                                    $paySub = 'Collect at Doorstep';
                                } elseif (str_contains($pid, 'UPI') || str_contains($note, 'UPI') || str_contains($note, 'GPAY') || str_contains($note, 'PHONEPE')) {
                                    $payBadge = 'bg-purple-100 text-purple-900 border-purple-300';
                                    $payIcon = '⚡';
                                    $payTitle = 'UPI Instant (Verified)';
                                    $paySub = 'Razorpay UPI';
                                } elseif (str_contains($note, 'CARD') || str_contains($note, 'VISA') || str_contains($note, 'MASTERCARD')) {
                                    $payBadge = 'bg-blue-100 text-blue-900 border-blue-300';
                                    $payIcon = '💳';
                                    $payTitle = 'Credit / Debit Card';
                                    $paySub = 'Online Gateway';
                                } elseif (str_contains($note, 'NET BANKING') || str_contains($note, 'HDFC') || str_contains($note, 'ICICI') || str_contains($note, 'SBI')) {
                                    $payBadge = 'bg-indigo-100 text-indigo-900 border-indigo-300';
                                    $payIcon = '🏦';
                                    $payTitle = 'Net Banking';
                                    $paySub = 'Direct Bank Transfer';
                                } else {
                                    $payBadge = 'bg-emerald-100 text-emerald-900 border-emerald-300';
                                    $payIcon = '🛡️';
                                    $payTitle = 'Online Verified (Prepaid)';
                                    $paySub = 'Razorpay Secure';
                                }
                            @endphp
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                                <td class="p-3">
                                    <button @click="openViewOrder({{ json_encode($ord) }})" class="font-mono font-bold text-blue-700 hover:underline block text-left">
                                        {{ $ord->order_no }}
                                    </button>
                                    <span class="text-[10px] text-gray-500">{{ $ord->created_at ? $ord->created_at->format('d M Y, h:i A') : 'Recent' }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="font-bold text-[var(--color-ebony)] block">{{ $ord->full_name }}</span>
                                    <span class="text-[10px] text-[var(--color-ebony)]/60">{{ $ord->phone }} • {{ $ord->email }}</span>
                                </td>
                                <td class="p-3 text-[var(--color-ebony)]/70">{{ $ord->city }}, {{ $ord->state }} ({{ $ord->pincode }})</td>
                                
                                {{-- Payment Type Column --}}
                                <td class="p-3">
                                    <div class="space-y-0.5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10.5px] font-bold border {{ $payBadge }}">
                                            <span>{{ $payIcon }}</span>
                                            <span>{{ $payTitle }}</span>
                                        </span>
                                        <span class="text-[10px] text-gray-500 font-mono block pl-1 truncate max-w-[140px]" title="{{ $ord->payment_id ?: $paySub }}">
                                            {{ $ord->payment_id ?: $paySub }}
                                        </span>
                                    </div>
                                </td>

                                <td class="p-3 font-serif font-bold text-sm text-[var(--color-ebony)]">₹{{ number_format($ord->total, 0) }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $ord->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                        {{ $ord->status === 'shipped' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $ord->status === 'confirmed' || $ord->status === 'paid' ? 'bg-amber-100 text-amber-800' : '' }}
                                        {{ $ord->status === 'cancelled' ? 'bg-rose-100 text-rose-800' : '' }}
                                        {{ $ord->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}
                                    ">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <div class="inline-flex items-center gap-2">
                                        <button @click="openViewOrder({{ json_encode($ord) }})" class="text-[10px] bg-gray-100 hover:bg-gray-200 px-2.5 py-1 rounded-lg font-bold">
                                            🔍 Items
                                        </button>
                                        <form action="/estilo-hq-console/orders/{{ $ord->id }}/status" method="POST" class="inline-flex items-center gap-1">
                                            @csrf
                                            <select name="status" class="bg-[var(--color-offwhite)] border border-[var(--color-bisque)] text-[10px] rounded-lg px-2 py-1 font-bold focus:outline-none">
                                                <option value="pending" {{ $ord->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="confirmed" {{ $ord->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                                <option value="processing" {{ $ord->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                <option value="shipped" {{ $ord->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                <option value="delivered" {{ $ord->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                <option value="cancelled" {{ $ord->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                            <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[10px] font-bold px-2.5 py-1 rounded-lg transition-colors">
                                                Save
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="p-6 text-center text-xs text-[var(--color-ebony)]/60">No orders recorded in system.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 4: 4.5 CUSTOMER MANAGEMENT --}}
        <div x-show="activeTab === 'customers'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Customer Relationship Management</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Directory of registered clientele, authentication logs, contact records, and lifetime orders.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Customer</th>
                                <th class="p-3">Phone Number</th>
                                <th class="p-3">Account Role</th>
                                <th class="p-3">Joined Date</th>
                                <th class="p-3 text-right">Account Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @foreach($customers as $cust)
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                                <td class="p-3">
                                    <span class="font-bold text-[var(--color-ebony)] block">{{ $cust->name }}</span>
                                    <span class="text-[10px] text-[var(--color-ebony)]/60">{{ $cust->email }}</span>
                                </td>
                                <td class="p-3 font-mono font-bold">{{ $cust->phone ?? '9876543212' }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-pink-50 text-[var(--color-rose-antique)] border border-pink-200">
                                        Clientele VIP
                                    </span>
                                </td>
                                <td class="p-3 text-[var(--color-ebony)]/60">{{ $cust->created_at ? $cust->created_at->format('d M Y') : 'Recent' }}</td>
                                <td class="p-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">● Active</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 5: 4.6 MARKETING ASSOCIATES & SELLERS --}}
        <div x-show="activeTab === 'associates'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Marketing Associates & Affiliate Sellers</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Configure partner commission tiers (e.g. 10%, 12%, 15%), review sales volume, verify UPI payout accounts, and approve commission disbursements.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Associate Details</th>
                                <th class="p-3">Referral Code</th>
                                <th class="p-3">Total Earnings</th>
                                <th class="p-3">Unpaid Balance</th>
                                <th class="p-3">Payout UPI</th>
                                <th class="p-3">Commission Rate</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @foreach($associates as $assoc)
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                                <td class="p-3">
                                    <span class="font-bold text-[var(--color-ebony)] block">{{ $assoc->name }}</span>
                                    <span class="text-[10px] text-[var(--color-ebony)]/60">{{ $assoc->phone }} • {{ $assoc->email }}</span>
                                </td>
                                <td class="p-3">
                                    <span class="font-mono font-bold text-amber-900 bg-amber-100/80 px-2 py-1 rounded text-xs">
                                        {{ $assoc->referral_code ?? 'ESTILO-SA01' }}
                                    </span>
                                </td>
                                <td class="p-3 font-serif font-bold text-emerald-700 text-sm">₹{{ number_format($assoc->earnings, 2) }}</td>
                                <td class="p-3 font-serif font-bold text-purple-800 text-sm">₹{{ number_format($assoc->balance, 2) }}</td>
                                <td class="p-3 font-mono text-[11px] text-gray-700">{{ $assoc->upi_id ?: 'Not specified' }}</td>
                                <form action="/estilo-hq-console/associates/{{ $assoc->id }}" method="POST">
                                    @csrf
                                    <td class="p-3">
                                        <div class="flex items-center gap-1">
                                            <input type="number" step="0.5" min="0" max="100" name="commission_rate" value="{{ $assoc->commission_rate }}" class="w-16 px-2 py-1 border border-[var(--color-bisque)] rounded text-xs font-bold" />
                                            <span class="font-bold">%</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right space-x-2">
                                        <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-colors">
                                            Save %
                                        </button>
                                </form>
                                        @if($assoc->balance > 0)
                                        <button type="button" @click="openPayoutModal({{ json_encode($assoc) }})" class="bg-emerald-600 hover:bg-emerald-700 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-colors shadow-sm">
                                            💸 Pay Out
                                        </button>
                                        @endif
                                    </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 6: 4.7 REPORTS & BILLING --}}
        <div x-show="activeTab === 'reports'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-[var(--color-bisque)]/60 pb-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Financial Commission & Billing Reports</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Comprehensive monthly audit of partner payouts, GST billing summaries, and gross couture turnover.</p>
                    </div>
                    <button onclick="window.print()" class="bg-white border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)] text-xs font-sans font-bold px-4 py-2 rounded-full transition-colors">
                        🖨️ Print Statement
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Billing Cycle</th>
                                <th class="p-3">Referred Orders Count</th>
                                <th class="p-3">Gross Sales Turnover (₹)</th>
                                <th class="p-3">Partner Commissions (₹)</th>
                                <th class="p-3 text-right">Audit Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @forelse($monthlyReports as $rep)
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                                <td class="p-3 font-bold">{{ $rep->month }}</td>
                                <td class="p-3">{{ $rep->orders_count }} orders</td>
                                <td class="p-3 font-serif font-bold">₹{{ number_format($rep->total_sales, 2) }}</td>
                                <td class="p-3 font-serif font-bold text-emerald-700">₹{{ number_format($rep->total_commission, 2) }}</td>
                                <td class="p-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">● Reconciled</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="p-3 font-bold">August 2026</td>
                                <td class="p-3">3 orders</td>
                                <td class="p-3 font-serif font-bold">₹8,297.00</td>
                                <td class="p-3 font-serif font-bold text-emerald-700">₹995.64</td>
                                <td class="p-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">● Reconciled</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 7: 4.8 OFFERS & COUPONS --}}
        <div x-show="activeTab === 'offers'" class="space-y-6">

            {{-- LIVE ANNOUNCEMENTS PANEL --}}
            @if($announcedCoupons->count() > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📢</span>
                    <h3 class="font-sans font-bold text-amber-900 text-sm uppercase tracking-wider">Currently Live on Storefront Ticker</h3>
                    <span class="ml-auto bg-amber-200 text-amber-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full">{{ $announcedCoupons->count() }} Active</span>
                </div>
                <div class="space-y-2">
                    @foreach($announcedCoupons as $ann)
                    <div class="flex items-center gap-3 bg-white border border-amber-100 rounded-xl px-4 py-2.5">
                        <span class="text-amber-500 animate-pulse">📢</span>
                        <span class="text-xs font-sans text-amber-900 font-medium flex-1">{{ $ann->announcement_text }}</span>
                        <span class="font-mono text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">{{ $ann->code }}</span>
                        <form action="/estilo-hq-console/coupons/{{ $ann->id }}/announce" method="POST" class="inline m-0">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold text-rose-600 hover:text-rose-800 hover:underline">Remove</button>
                        </form>
                    </div>
                    @endforeach
                </div>
                <p class="text-[10px] text-amber-700 font-sans">These announcements are scrolling in the storefront ticker bar right now. Customers on the shop page can see them live.</p>
            </div>
            @else
            <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-2xl opacity-40">📢</span>
                <p class="text-xs text-gray-400 font-sans">No coupon announcements are currently live. Click <strong>"Announce"</strong> on any active coupon below to broadcast it in the storefront ticker.</p>
            </div>
            @endif

            {{-- COUPONS LIST WITH ANNOUNCE FEATURE --}}
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-[var(--color-bisque)]/60 pb-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Offers, Festival Discounts & Coupons</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Generate promotional discount coupons and 📢 announce them to customers via the storefront ticker.</p>
                    </div>
                    <button @click="showAddCouponModal = true" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-2.5 rounded-full transition-all shadow-md">
                        + New Coupon
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($coupons as $coup)
                    <div x-data="{ showAnnounceForm: false }"
                         class="bg-[var(--color-offwhite)] rounded-2xl border {{ $coup->is_announced ? 'border-amber-300 ring-1 ring-amber-200' : 'border-[var(--color-bisque)]' }} p-5 space-y-3 relative overflow-hidden shadow-sm">

                        {{-- Announced Live Badge --}}
                        @if($coup->is_announced)
                        <div class="absolute top-0 right-0 bg-amber-400 text-amber-950 text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-bl-xl rounded-tr-2xl flex items-center gap-1">
                            <span class="animate-pulse">●</span> Live on Ticker
                        </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-[9px] font-sans font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $coup->campaign_type === 'festival' ? 'bg-amber-100 text-amber-900' : 'bg-pink-100 text-pink-900' }}">
                                {{ ucfirst($coup->campaign_type) }} Campaign
                            </span>
                            <span class="text-[10px] font-bold {{ $coup->is_active ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $coup->is_active ? '● Active' : '○ Inactive' }}
                            </span>
                        </div>

                        <div class="space-y-1">
                            <div class="flex items-baseline gap-2">
                                <span class="font-mono text-xl font-bold text-[var(--color-ebony)] tracking-wider">{{ $coup->code }}</span>
                                <span class="text-xs font-bold text-emerald-700">
                                    {{ $coup->discount_type === 'percentage' ? $coup->discount_value . '% OFF' : '₹' . $coup->discount_value . ' OFF' }}
                                </span>
                            </div>
                            <h4 class="font-serif text-sm font-bold text-[var(--color-ebony)]">{{ $coup->title }}</h4>
                            <p class="text-[11px] text-[var(--color-ebony)]/60">Min Order: ₹{{ number_format($coup->min_order_value, 0) }} • Used: {{ $coup->usage_count }} times</p>
                        </div>

                        {{-- Announcement Text Preview (if announced) --}}
                        @if($coup->is_announced && $coup->announcement_text)
                        <div class="bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 flex items-start gap-2">
                            <span class="text-amber-500 text-xs mt-0.5 shrink-0">📢</span>
                            <p class="text-[10px] text-amber-800 font-medium leading-snug">{{ $coup->announcement_text }}</p>
                        </div>
                        @endif

                        {{-- Announce Custom Text Form (collapsible) --}}
                        <div x-show="showAnnounceForm" x-transition class="bg-white border border-amber-200 rounded-xl p-3 space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-amber-800 block">Custom Announcement Text (optional)</label>
                            <form action="/estilo-hq-console/coupons/{{ $coup->id }}/announce" method="POST">
                                @csrf
                                <textarea name="announcement_text" rows="2" placeholder="🎉 Use code {{ $coup->code }} and get {{ $coup->discount_value }}% OFF! Leave blank to auto-generate." class="w-full text-xs border border-amber-200 rounded-lg px-2.5 py-2 focus:outline-none focus:ring-2 focus:ring-amber-300 font-sans resize-none">{{ $coup->announcement_text }}</textarea>
                                <div class="flex gap-2 mt-2">
                                    <button type="submit" class="flex-1 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-bold uppercase tracking-wider py-1.5 rounded-lg transition-colors">
                                        {{ $coup->is_announced ? '🔄 Update Announcement' : '📢 Announce Now' }}
                                    </button>
                                    <button type="button" @click="showAnnounceForm = false" class="text-[10px] text-gray-500 hover:underline px-2">Cancel</button>
                                </div>
                            </form>
                        </div>

                        {{-- Action Buttons Row --}}
                        <div class="pt-2 border-t border-[var(--color-bisque)]/60 flex items-center justify-between flex-wrap gap-2">
                            <span class="text-[10px] text-[var(--color-ebony)]/50">Expires: {{ $coup->valid_until ? $coup->valid_until->format('d M Y') : 'Never' }}</span>
                            <div class="flex items-center gap-2 flex-wrap">

                                {{-- Announce / Unannounce --}}
                                @if($coup->is_announced)
                                <form action="/estilo-hq-console/coupons/{{ $coup->id }}/announce" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-amber-700 bg-amber-100 hover:bg-amber-200 px-2 py-0.5 rounded-full transition-colors">
                                        🔕 Unannounce
                                    </button>
                                </form>
                                @else
                                <button @click="showAnnounceForm = !showAnnounceForm"
                                        class="text-[10px] font-bold text-amber-700 bg-amber-100 hover:bg-amber-200 px-2 py-0.5 rounded-full transition-colors">
                                    📢 Announce
                                </button>
                                @endif

                                <span class="text-gray-300">|</span>
                                <button type="button" @click="openEditCoupon({{ json_encode($coup) }})" class="text-[10px] font-bold text-amber-800 hover:underline">
                                    ✏️ Edit
                                </button>

                                <span class="text-gray-300">|</span>
                                <form action="/estilo-hq-console/coupons/{{ $coup->id }}/toggle" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-bold text-[var(--color-rose-antique)] hover:underline">
                                        {{ $coup->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <span class="text-gray-300">|</span>
                                <form action="/estilo-hq-console/coupons/{{ $coup->id }}" method="POST" onsubmit="return confirm('Delete coupon {{ $coup->code }}?');" class="inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[10px] font-bold text-rose-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 p-8 text-center bg-[var(--color-offwhite)] rounded-2xl text-xs text-[var(--color-ebony)]/60">
                        No discount coupons created yet. Click "+ New Coupon" to publish your first festive offer.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TAB: STOREFRONT ANNOUNCEMENTS SUITE --}}
        <div x-show="activeTab === 'announcements'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                
                {{-- Header & Subtext --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--color-bisque)]/60 pb-5">
                    <div>
                        <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-rose-antique)]">Promotions & Broadcast Suite</span>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)] flex items-center gap-2">
                            Storefront Announcements & Promotional Banners
                        </h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60 mt-0.5">
                            Broadcast promotional sales, new arrivals, festival discount codes, and express delivery alerts directly to the public storefront ticker and top banners.
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="showAddAnnouncementModal = true" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-2.5 rounded-full transition-all shadow-md flex items-center gap-2">
                            <span>📢</span> + Create Announcement
                        </button>
                    </div>
                </div>

                {{-- Metric KPI Badges --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 rounded-2xl bg-[var(--color-champagne-light)]/40 border border-[var(--color-bisque)]/60 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-xs flex items-center justify-center text-lg">📢</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-[var(--color-ebony)]/60 uppercase tracking-wider block">Total Announcements</span>
                            <span class="font-serif text-xl font-bold text-[var(--color-ebony)]">{{ $announcements->count() }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-lg">⚡</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-amber-900/80 uppercase tracking-wider block">Live on Ticker</span>
                            <span class="font-serif text-xl font-bold text-amber-950">{{ $announcements->where('is_active', true)->where('show_in_ticker', true)->count() }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-lg">📌</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-rose-900/80 uppercase tracking-wider block">Top Banners</span>
                            <span class="font-serif text-xl font-bold text-rose-950">{{ $announcements->where('is_active', true)->where('show_as_banner', true)->count() }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-lg">✓</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-emerald-900/80 uppercase tracking-wider block">Active & Broadcasting</span>
                            <span class="font-serif text-xl font-bold text-emerald-950">{{ $announcements->where('is_active', true)->count() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Filter Pills Bar --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-sans font-bold">
                    <button type="button" @click="announcementFilter = 'all'" :class="announcementFilter === 'all' ? 'bg-[var(--color-ebony)] text-white shadow-sm' : 'bg-gray-100 text-[var(--color-ebony)]/70 hover:bg-gray-200'" class="px-4 py-2 rounded-xl transition-all">
                        All ({{ $announcements->count() }})
                    </button>
                    <button type="button" @click="announcementFilter = 'live'" :class="announcementFilter === 'live' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-emerald-50 text-emerald-800 hover:bg-emerald-100'" class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5">
                        <span>● Live Now</span>
                        <span class="bg-emerald-200 text-emerald-900 text-[10px] px-1.5 py-0.2 rounded-full" :class="announcementFilter === 'live' ? 'bg-white/30 text-white' : ''">{{ $announcements->where('is_active', true)->count() }}</span>
                    </button>
                    <button type="button" @click="announcementFilter = 'ticker'" :class="announcementFilter === 'ticker' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-900 hover:bg-amber-100'" class="px-4 py-2 rounded-xl transition-all">
                        ⚡ Ticker Feed ({{ $announcements->where('show_in_ticker', true)->count() }})
                    </button>
                    <button type="button" @click="announcementFilter = 'banner'" :class="announcementFilter === 'banner' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-900 hover:bg-rose-100'" class="px-4 py-2 rounded-xl transition-all">
                        📌 Top Banners ({{ $announcements->where('show_as_banner', true)->count() }})
                    </button>
                    <button type="button" @click="announcementFilter = 'paused'" :class="announcementFilter === 'paused' ? 'bg-slate-800 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-xl transition-all">
                        Draft / Paused ({{ $announcements->where('is_active', false)->count() }})
                    </button>
                </div>

                {{-- Announcements Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($announcements as $ann)
                    <div x-show="announcementFilter === 'all' || 
                                (announcementFilter === 'live' && {{ $ann->is_active ? 'true' : 'false' }}) ||
                                (announcementFilter === 'ticker' && {{ $ann->show_in_ticker ? 'true' : 'false' }}) ||
                                (announcementFilter === 'banner' && {{ $ann->show_as_banner ? 'true' : 'false' }}) ||
                                (announcementFilter === 'paused' && {{ !$ann->is_active ? 'true' : 'false' }})"
                         class="bg-[var(--color-offwhite)] rounded-2xl border {{ $ann->is_active ? 'border-amber-300 ring-1 ring-amber-200/80 shadow-md' : 'border-[var(--color-bisque)] shadow-xs opacity-85' }} p-5 space-y-4 relative overflow-hidden transition-all hover:shadow-lg">

                        {{-- Status Pill --}}
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5">
                                <span class="text-base">{{ $ann->icon ?? '📢' }}</span>
                                <span class="text-[9px] font-sans font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-slate-900 text-amber-200">
                                    {{ ucfirst($ann->type ?? 'Promo') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                @if($ann->show_in_ticker)
                                    <span class="text-[9px] font-bold bg-amber-100 text-amber-900 px-2 py-0.5 rounded-full" title="Active in Marquee Ticker">⚡ Ticker</span>
                                @endif
                                @if($ann->show_as_banner)
                                    <span class="text-[9px] font-bold bg-rose-100 text-rose-900 px-2 py-0.5 rounded-full" title="Active as Top Banner">📌 Banner</span>
                                @endif
                                <span class="text-[10px] font-bold {{ $ann->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                                    {{ $ann->is_active ? '● Live' : '○ Paused' }}
                                </span>
                            </div>
                        </div>

                        {{-- Announcement Title & Text --}}
                        <div class="space-y-1">
                            <h4 class="font-serif text-base font-bold text-[var(--color-ebony)] leading-snug">{{ $ann->title }}</h4>
                            <p class="text-xs font-sans text-[var(--color-ebony)]/80 leading-relaxed">{{ $ann->message }}</p>
                        </div>

                        {{-- Live Storefront Appearance Preview Box --}}
                        <div class="p-3 bg-white rounded-xl border border-[var(--color-bisque)] space-y-1.5 shadow-inner">
                            <span class="text-[9px] font-sans font-bold uppercase tracking-wider text-gray-400 block">Storefront Display Preview:</span>
                            <div class="bg-gray-900 text-[#FBEAD6] px-3 py-1.5 rounded-lg text-[10px] font-sans flex items-center gap-2 overflow-hidden text-ellipsis whitespace-nowrap">
                                <span class="animate-pulse">{{ $ann->icon ?? '📢' }}</span>
                                <span class="font-bold text-amber-300">{{ strtoupper($ann->title) }}:</span>
                                <span class="text-gray-300 text-ellipsis overflow-hidden">{{ $ann->message }}</span>
                            </div>
                        </div>

                        {{-- Date & Actions Footer --}}
                        <div class="pt-2 border-t border-[var(--color-bisque)]/60 flex items-center justify-between flex-wrap gap-2 text-xs">
                            <span class="text-[10px] text-[var(--color-ebony)]/50 font-sans">
                                Added {{ $ann->created_at->diffForHumans() }}
                            </span>

                            <div class="flex items-center gap-2">
                                {{-- Quick Edit Button --}}
                                <button type="button" @click="openEditAnnouncement({{ json_encode($ann) }})" class="text-[11px] font-bold text-slate-800 bg-white hover:bg-slate-50 border border-slate-200 px-2.5 py-1 rounded-lg transition-colors">
                                    ✏️ Edit
                                </button>

                                {{-- Toggle Active Status --}}
                                <form action="/estilo-hq-console/announcements/{{ $ann->id }}/toggle" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="text-[11px] font-bold {{ $ann->is_active ? 'text-amber-700 hover:text-amber-900 bg-amber-50' : 'text-emerald-700 hover:text-emerald-900 bg-emerald-50' }} border border-transparent hover:border-current px-2.5 py-1 rounded-lg transition-colors">
                                        {{ $ann->is_active ? '⏸ Pause' : '▶ Publish' }}
                                    </button>
                                </form>

                                {{-- Delete Announcement --}}
                                <form action="/estilo-hq-console/announcements/{{ $ann->id }}" method="POST" onsubmit="return confirm('Remove announcement: {{ $ann->title }}?');" class="inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] font-bold text-rose-600 hover:text-rose-800 hover:underline p-1">
                                        ✕
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 p-12 text-center bg-[var(--color-offwhite)] rounded-3xl border border-dashed border-[var(--color-bisque)] space-y-3">
                        <div class="text-4xl">📢</div>
                        <h4 class="font-serif text-lg font-bold text-[var(--color-ebony)]">No Storefront Announcements Yet</h4>
                        <p class="text-xs text-[var(--color-ebony)]/60 max-w-md mx-auto">
                            Broadcast promotional sales, festive coupons, or shipping notices to customers by creating your first announcement.
                        </p>
                        <button type="button" @click="showAddAnnouncementModal = true" class="inline-flex items-center gap-1.5 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-2.5 rounded-full transition-all shadow-md">
                            <span>📢</span> Create First Announcement
                        </button>
                    </div>
                    @endforelse
                </div>

                {{-- Synchronized Announced Coupons Feed --}}
                @if(isset($announcedCoupons) && $announcedCoupons->isNotEmpty())
                <div class="pt-6 border-t border-[var(--color-bisque)]/60 space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-serif text-base font-bold text-[var(--color-ebony)] flex items-center gap-2">
                                <span>🎟️</span>
                                <span>Active Coupon Broadcasts (Live in Marquee Ticker)</span>
                            </h3>
                            <p class="text-[11px] font-sans text-[var(--color-ebony)]/60">Coupons marked as 'Announced' that are automatically streaming into the storefront ticker.</p>
                        </div>
                        <a href="/estilo-hq-console?tab=coupons" class="text-xs font-sans font-bold text-amber-800 hover:text-amber-950 bg-amber-50 border border-amber-200 px-3 py-1.5 rounded-xl transition-colors">
                            Manage All Coupons →
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($announcedCoupons as $ac)
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50/40 p-4 rounded-2xl border border-amber-200/80 flex items-center justify-between gap-3">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono text-xs font-bold px-2 py-0.5 rounded-lg bg-amber-900 text-amber-100 uppercase tracking-wider">{{ $ac->code }}</span>
                                    <span class="text-[10px] font-sans font-bold text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full">● Broadcasting</span>
                                </div>
                                <p class="text-xs font-sans text-amber-950 font-medium">
                                    {{ $ac->announcement_text ?? ('USE CODE ' . $ac->code . ' FOR ' . $ac->discount_value . '% OFF') }}
                                </p>
                            </div>
                            <form action="/estilo-hq-console/coupons/{{ $ac->id }}/announce" method="POST" class="inline m-0">
                                @csrf
                                <button type="submit" class="text-[11px] font-bold text-rose-700 hover:text-rose-900 bg-white border border-rose-200 px-3 py-1.5 rounded-xl transition-all shadow-xs" title="Remove from Ticker Broadcast">
                                    ✕ Stop Ticker
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- TAB 8: RATINGS & REVIEWS MODERATION (Edit 1-5 Stars & Modify Bad Reviews) --}}
        <div x-show="activeTab === 'reviews'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                
                {{-- Header & Subtext --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--color-bisque)]/60 pb-5">
                    <div>
                        <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-rose-antique)]">Feedback Management Suite</span>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)] flex items-center gap-2">
                            Customer Ratings & Reviews Moderation
                        </h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60 mt-0.5">
                            Modify customer ratings (1 to 5 Stars), edit/rewrite negative feedback, and control public visibility on product pages.
                        </p>
                    </div>
                </div>

                {{-- Review Metric KPI Badges --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="p-4 rounded-2xl bg-[var(--color-champagne-light)]/40 border border-[var(--color-bisque)]/60 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white shadow-xs flex items-center justify-center text-lg">📝</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-[var(--color-ebony)]/60 uppercase tracking-wider block">Total Reviews</span>
                            <span class="font-serif text-xl font-bold text-[var(--color-ebony)]">{{ $reviews->count() }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200/80 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-lg">⭐</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-amber-900/80 uppercase tracking-wider block">5-Star Royal Reviews</span>
                            <span class="font-serif text-xl font-bold text-amber-950">{{ $reviews->where('rating', 5)->count() }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200/80 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center text-lg">⚠️</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-rose-900/80 uppercase tracking-wider block">Low Ratings (≤ 3★)</span>
                            <span class="font-serif text-xl font-bold text-rose-950">{{ $reviews->where('rating', '<=', 3)->count() }}</span>
                        </div>
                    </div>

                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center text-lg">✓</div>
                        <div>
                            <span class="text-[10px] font-sans font-bold text-emerald-900/80 uppercase tracking-wider block">Approved & Live</span>
                            <span class="font-serif text-xl font-bold text-emerald-950">{{ $reviews->where('is_approved', true)->count() }}</span>
                        </div>
                    </div>
                </div>

                {{-- Filter Pills Bar --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs font-sans font-bold">
                    <button type="button" @click="reviewFilter = 'all'" :class="reviewFilter === 'all' ? 'bg-[var(--color-ebony)] text-white shadow-sm' : 'bg-gray-100 text-[var(--color-ebony)]/70 hover:bg-gray-200'" class="px-4 py-2 rounded-xl transition-all">
                        All Reviews ({{ $reviews->count() }})
                    </button>
                    <button type="button" @click="reviewFilter = 'low'" :class="reviewFilter === 'low' ? 'bg-rose-600 text-white shadow-sm' : 'bg-rose-50 text-rose-700 hover:bg-rose-100'" class="px-4 py-2 rounded-xl transition-all flex items-center gap-1.5">
                        <span>⚠️ Low Ratings (1-3★)</span>
                        <span class="bg-rose-200 text-rose-900 text-[10px] px-1.5 py-0.2 rounded-full" :class="reviewFilter === 'low' ? 'bg-white/30 text-white' : ''">{{ $reviews->where('rating', '<=', 3)->count() }}</span>
                    </button>
                    <button type="button" @click="reviewFilter = '5star'" :class="reviewFilter === '5star' ? 'bg-amber-500 text-white shadow-sm' : 'bg-amber-50 text-amber-800 hover:bg-amber-100'" class="px-4 py-2 rounded-xl transition-all">
                        ⭐ 5 Stars Only ({{ $reviews->where('rating', 5)->count() }})
                    </button>
                    <button type="button" @click="reviewFilter = 'pending'" :class="reviewFilter === 'pending' ? 'bg-slate-800 text-white shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-4 py-2 rounded-xl transition-all">
                        Hidden / Unapproved ({{ $reviews->where('is_approved', false)->count() }})
                    </button>
                </div>

                {{-- Reviews List / Table --}}
                <div class="space-y-4">
                    @forelse($reviews as $rev)
                    @php
                        $isLow = $rev->rating <= 3;
                    @endphp
                    <div class="p-5 rounded-2xl border transition-all duration-200 space-y-3 {{ $isLow ? 'bg-rose-50/40 border-rose-200' : 'bg-[var(--color-offwhite)]/60 border-[var(--color-bisque)]' }}"
                         x-show="reviewFilter === 'all' || (reviewFilter === 'low' && {{ $rev->rating }} <= 3) || (reviewFilter === '5star' && {{ $rev->rating }} === 5) || (reviewFilter === 'pending' && !{{ $rev->is_approved ? 'true' : 'false' }})">
                        
                        {{-- Top Meta Row --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-black/5 pb-3">
                            <div class="flex items-center gap-2.5 flex-wrap">
                                <div class="w-7 h-7 rounded-full bg-[var(--color-ebony)] text-white flex items-center justify-center font-bold text-xs">
                                    {{ strtoupper(substr($rev->user_name ?? 'C', 0, 1)) }}
                                </div>
                                <span class="font-bold text-xs text-[var(--color-ebony)]">{{ $rev->user_name }}</span>
                                <span class="text-[10px] font-mono bg-white px-2 py-0.5 rounded-md border border-[var(--color-bisque)] text-[var(--color-rose-antique)] font-semibold">
                                    Product: {{ $rev->product_est_id }}
                                </span>
                                @if($isLow)
                                <span class="text-[9px] bg-rose-100 text-rose-800 font-bold uppercase tracking-wider px-2 py-0.5 rounded-full flex items-center gap-1">
                                    ⚠️ Low Rating Detected ({{ $rev->rating }}★)
                                </span>
                                @endif
                                <span class="text-[10px] text-gray-400 font-sans">{{ $rev->created_at ? $rev->created_at->format('d M Y, h:i A') : 'Recent' }}</span>
                            </div>

                            {{-- Star Rating Display --}}
                            <div class="flex items-center gap-1.5">
                                <div class="flex text-amber-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rev->rating)
                                            <span class="text-amber-400">★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                                <span class="font-bold text-xs text-[var(--color-ebony)] font-mono">({{ $rev->rating }}/5)</span>
                            </div>
                        </div>

                        {{-- Review Comment Body --}}
                        <div class="bg-white/80 p-3.5 rounded-xl border border-black/5">
                            <p class="text-xs font-sans text-[var(--color-ebony)]/90 leading-relaxed italic">
                                "{{ $rev->comment }}"
                            </p>
                        </div>

                        {{-- Bottom Action Row --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $rev->is_approved ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ $rev->is_approved ? '✓ Approved & Live on Store' : '○ Hidden from Store' }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Quick 1-Click Boost to 5 Stars (Useful for bad ratings) --}}
                                @if($rev->rating < 5)
                                <form action="/estilo-hq-console/reviews/{{ $rev->id }}/boost" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 bg-amber-100 hover:bg-amber-200 text-amber-900 border border-amber-300 rounded-lg text-xs font-bold transition-all flex items-center gap-1" title="Instantly change to 5 Stars & Approve">
                                        <span>⭐ Boost to 5★</span>
                                    </button>
                                </form>
                                @endif

                                {{-- Edit Modal Trigger (Edit Stars 1-5 and Rewrite Comment) --}}
                                <button type="button" @click="openEditReview({{ json_encode($rev) }})" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold transition-colors flex items-center gap-1">
                                    <span>✏️ Edit Rating & Review</span>
                                </button>

                                {{-- Toggle Live / Hide --}}
                                <form action="/estilo-hq-console/reviews/{{ $rev->id }}/toggle" method="POST" class="inline m-0">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-bold transition-colors">
                                        {{ $rev->is_approved ? 'Hide' : 'Approve' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form action="/estilo-hq-console/reviews/{{ $rev->id }}" method="POST" class="inline m-0" onsubmit="return confirm('Permanently delete review #{{ $rev->id }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-bold transition-colors" title="Delete Review">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                    @empty
                    <div class="p-12 text-center text-xs text-[var(--color-ebony)]/60 bg-[var(--color-offwhite)] rounded-2xl">
                        <span class="text-3xl block mb-2">⭐</span>
                        No customer reviews submitted yet.
                    </div>
                    @endforelse
                </div>

            </div>
        </div>

        {{-- TAB 9: ADMINISTRATOR PROFILE & SECURITY --}}
        <div x-show="activeTab === 'profile'" class="space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Left: Profile Overview & Logout --}}
                <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6 flex flex-col justify-between">
                    <div class="space-y-5 text-center">
                        <div class="w-20 h-20 mx-auto rounded-full bg-[var(--color-ebony)] text-amber-200 flex items-center justify-center font-serif font-bold text-3xl shadow-lg ring-4 ring-amber-100">
                            {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">{{ $admin->name ?? 'Boutique Administrator' }}</h3>
                            <span class="inline-block px-3 py-1 bg-slate-900 text-amber-200 text-[10px] font-sans font-bold uppercase tracking-widest rounded-full mt-1.5">
                                Super Administrator
                            </span>
                        </div>

                        <div class="bg-[var(--color-offwhite)] rounded-2xl p-4 text-left text-xs font-sans space-y-2 border border-[var(--color-bisque)]">
                            <div class="flex justify-between py-1 border-b border-gray-200/60">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-bold text-[var(--color-ebony)]">{{ $admin->email }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200/60">
                                <span class="text-gray-500">Phone:</span>
                                <span class="font-bold text-[var(--color-ebony)]">{{ $admin->phone ?? '9000000001' }}</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-gray-200/60">
                                <span class="text-gray-500">Auth Method:</span>
                                <span class="font-bold text-emerald-700">Session Cookie</span>
                            </div>
                            <div class="flex justify-between py-1">
                                <span class="text-gray-500">Account Role:</span>
                                <span class="font-mono font-bold text-purple-800 uppercase">{{ $admin->role }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Logout Action inside Profile --}}
                    <div class="pt-4 border-t border-[var(--color-bisque)]/60">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-sans text-xs font-bold uppercase tracking-wider py-3.5 rounded-2xl transition-all shadow-md flex items-center justify-center gap-2">
                                <span>🚪</span> Log Out of Admin Session
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Right: Edit Profile Settings Form --}}
                <div class="lg:col-span-2 bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                    <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                        <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Update Administrator Credentials</h3>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Modify admin name, email, phone number, and password.</p>
                    </div>

                    <form action="/estilo-hq-console/profile" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Administrator Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name) }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Email Address (Login Username)</label>
                                <input type="email" name="email" value="{{ old('email', $admin->email) }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                            </div>
                            <div>
                                <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Mobile / Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone', $admin->phone) }}" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">New Password (Leave blank to keep existing)</label>
                            <input type="password" name="password" placeholder="••••••••" minlength="6" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                        </div>

                        <div class="pt-3">
                            <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-3 rounded-xl transition-all shadow-md">
                                Save Profile Settings
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>

    {{-- MODAL 1: ADD NEW PRODUCT --}}
    <div x-show="showAddProductModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="showAddProductModal" x-transition.opacity @click="showAddProductModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 max-h-[90vh] overflow-y-auto space-y-5">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div>
                    <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">Add New Couture Outfit</h3>
                    <p class="text-xs font-sans text-gray-500">Configure product details, customer price, and size-specific stock inventory.</p>
                </div>
                <button @click="showAddProductModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form action="/estilo-hq-console/products" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Product Title / Name</label>
                    <input type="text" name="name" placeholder="e.g. Royal Banarasi Zari Silk Anarkali Set" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Category</label>
                        <select name="category" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans">
                            <option value="Kurtis & Suits">Kurtis & Suits</option>
                            <option value="Chikankari Kurtis">Chikankari Kurtis</option>
                            <option value="Anarkali Suits">Anarkali Suits & Sets</option>
                            <option value="Luxury Sarees">Luxury Sarees</option>
                            <option value="Banarasi Sarees">Banarasi Silk Sarees</option>
                            <option value="Silk Sarees">Pure Kanjivaram Silk</option>
                            <option value="Co-Ord Sets">Co-Ord Sets</option>
                            <option value="Festive Wear">Festive Wear</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Customer Price (₹)</label>
                        <input type="number" name="price" placeholder="1499" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                    </div>
                </div>

                {{-- Size-Wise Stock Inventory Configuration --}}
                <div class="space-y-2 p-4 bg-[var(--color-champagne-light)]/40 rounded-2xl border border-[var(--color-bisque)]">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">
                            Stock Quantity Per Size (XS, S, M, L, XL, XXL)
                        </label>
                        <span class="text-[10.5px] font-bold text-emerald-900 bg-emerald-100 border border-emerald-300 px-2.5 py-0.5 rounded-full"
                              x-text="'Total: ' + (Number(newSizeStock['XS']||0) + Number(newSizeStock['S']||0) + Number(newSizeStock['M']||0) + Number(newSizeStock['L']||0) + Number(newSizeStock['XL']||0) + Number(newSizeStock['XXL']||0)) + ' Units In Stock'">
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500 font-sans">Set initial available stock count for each size:</p>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5 pt-1">
                        <template x-for="sz in ['XS', 'S', 'M', 'L', 'XL', 'XXL']" :key="sz">
                            <div class="bg-white p-2 rounded-xl border border-[var(--color-bisque)] text-center space-y-1 shadow-xs">
                                <span class="block text-[11px] font-bold font-mono text-[var(--color-ebony)]" x-text="sz"></span>
                                <input type="number" min="0" :name="'size_stock[' + sz + ']'" x-model="newSizeStock[sz]" required class="w-full bg-[var(--color-offwhite)] border border-gray-200 rounded-lg py-1.5 text-center text-xs font-bold font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Color Palette (Comma Separated)</label>
                    <input type="text" name="colors" value="Rose Blush, Royal Navy, Golden Ochre" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Upload Product Photograph</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs font-sans file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[var(--color-ebony)] file:text-white hover:file:bg-[var(--color-rose-deep)]" />
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Description & Craft Notes</label>
                    <textarea name="description" rows="3" placeholder="Handcrafted with delicate Lucknowi shadow work and genuine golden zari borders..." required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans"></textarea>
                </div>

                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">
                        <input type="checkbox" name="is_featured" class="accent-[var(--color-rose-antique)]" /> Feature on Homepage
                    </label>
                </div>

                <div class="flex gap-3 pt-4 border-t border-[var(--color-bisque)]/60">
                    <button type="button" @click="showAddProductModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-[var(--color-ebony)] font-sans text-xs font-bold py-3 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold py-3 rounded-xl shadow-md">Publish to Atelier</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 2: EDIT PRODUCT --}}
    <div x-show="editProductModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="editProductModal" x-transition.opacity @click="editProductModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 max-h-[90vh] overflow-y-auto space-y-5">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div>
                    <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">Edit Couture Outfit & Inventory</h3>
                    <p class="text-xs font-sans text-gray-500">Update product name, category, price, and adjust stock counts per size.</p>
                </div>
                <button @click="editProductModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form :action="'/estilo-hq-console/products/' + selectedProduct.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Product Title / Name</label>
                    <input type="text" name="name" x-model="selectedProduct.name" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Category</label>
                        <input type="text" name="category" x-model="selectedProduct.category" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Customer Price (₹)</label>
                        <input type="number" name="price" x-model="selectedProduct.price" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                    </div>
                </div>

                {{-- Size-Wise Stock Inventory Configuration --}}
                <div class="space-y-2 p-4 bg-[var(--color-champagne-light)]/40 rounded-2xl border border-[var(--color-bisque)]">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">
                            Stock Quantity Per Size (XS, S, M, L, XL, XXL)
                        </label>
                        <span class="text-[10.5px] font-bold text-emerald-900 bg-emerald-100 border border-emerald-300 px-2.5 py-0.5 rounded-full"
                              x-text="'Total: ' + (Object.values(selectedProduct.size_stock || {}).reduce((acc, val) => Number(acc) + Number(val || 0), 0)) + ' Units In Stock'">
                        </span>
                    </div>
                    <p class="text-[10px] text-gray-500 font-sans">Adjust quantity for each garment size:</p>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2.5 pt-1">
                        <template x-for="sz in ['XS', 'S', 'M', 'L', 'XL', 'XXL']" :key="sz">
                            <div class="bg-white p-2 rounded-xl border border-[var(--color-bisque)] text-center space-y-1 shadow-xs">
                                <span class="block text-[11px] font-bold font-mono text-[var(--color-ebony)]" x-text="sz"></span>
                                <input type="number" min="0" :name="'size_stock[' + sz + ']'" x-model="selectedProduct.size_stock[sz]" class="w-full bg-[var(--color-offwhite)] border border-gray-200 rounded-lg py-1.5 text-center text-xs font-bold font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Color Palette (Comma Separated)</label>
                    <input type="text" name="colors" x-model="selectedProduct.colors_str" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Replace Photograph (Optional)</label>
                    <input type="file" name="image" accept="image/*" class="w-full text-xs font-sans file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[var(--color-ebony)] file:text-white hover:file:bg-[var(--color-rose-deep)]" />
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Description</label>
                    <textarea name="description" x-model="selectedProduct.description" rows="3" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans"></textarea>
                </div>

                <div class="flex items-center gap-6 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-700">
                        <input type="checkbox" name="is_featured" :checked="selectedProduct.is_featured" class="accent-[var(--color-rose-antique)]" /> Feature on Homepage
                    </label>
                </div>

                <div class="flex gap-3 pt-4 border-t border-[var(--color-bisque)]/60">
                    <button type="button" @click="editProductModal = false" class="flex-1 bg-gray-100 hover:bg-gray-200 text-[var(--color-ebony)] font-sans text-xs font-bold py-3 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold py-3 rounded-xl shadow-md">Update Product</button>
                </div>
            </form>
    </div>

    {{-- MODAL 3: VIEW ORDER DETAILS --}}
    <div x-show="viewOrderModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="viewOrderModal" x-transition.opacity @click="viewOrderModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-xl bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 space-y-5">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div>
                    <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]" x-text="'Order #' + selectedOrder.order_no"></h3>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="text-[10px] font-mono text-gray-500" x-text="'Status: ' + (selectedOrder.status || '').toUpperCase()"></span>
                        <span class="text-gray-300">•</span>
                        <span class="text-[10px] text-gray-500 font-mono" x-text="selectedOrder.payment_id ? ('Payment: ' + selectedOrder.payment_id) : 'Payment: Verified'"></span>
                    </div>
                </div>
                <button @click="viewOrderModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <div class="space-y-3 text-xs font-sans">
                {{-- Delivery Address --}}
                <div class="bg-[var(--color-offwhite)] p-3.5 rounded-xl space-y-1 border border-[var(--color-bisque)]/60">
                    <h4 class="font-bold text-[var(--color-ebony)] uppercase tracking-wider text-[10px]">Customer Delivery Address</h4>
                    <p class="text-gray-800 font-bold" x-text="selectedOrder.full_name"></p>
                    <p class="text-gray-600" x-text="selectedOrder.address"></p>
                    <p class="text-gray-600" x-text="(selectedOrder.city || '') + ', ' + (selectedOrder.state || '') + ' - ' + (selectedOrder.pincode || '')"></p>
                    <p class="text-gray-600" x-text="'Phone: ' + (selectedOrder.phone || '') + ' | Email: ' + (selectedOrder.email || '')"></p>
                </div>

                {{-- Items --}}
                <div class="space-y-2">
                    <h4 class="font-bold text-[var(--color-ebony)] uppercase tracking-wider text-[10px]">Purchased Couture Garments</h4>
                    <div class="max-h-48 overflow-y-auto space-y-2">
                        <template x-for="item in (selectedOrder.parsedItems || [])" :key="item.est_id || item.name">
                            <div class="flex items-center justify-between p-2.5 bg-gray-50 rounded-xl border border-gray-100">
                                <div>
                                    <span class="font-bold text-gray-900 block" x-text="item.name || item.est_id"></span>
                                    <span class="text-[10px] text-gray-500" x-text="'Qty: ' + (item.quantity || item.qty || 1) + ' • Size: ' + (item.selectedSize || item.size || 'Standard') + ' • Color: ' + (item.selectedColor || item.color || 'Standard')"></span>
                                </div>
                                <span class="font-serif font-bold text-xs text-gray-900" x-text="'₹' + Number(item.price || 0).toLocaleString()"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Order Notes & Referral info --}}
                <template x-if="selectedOrder.note">
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200/80 text-[11px] text-amber-900 space-y-0.5">
                        <span class="font-bold uppercase tracking-wider text-[9px] text-amber-800 block">Order Processing Note & Referral:</span>
                        <p x-text="selectedOrder.note"></p>
                    </div>
                </template>

                {{-- Payment Details Box --}}
                <div class="bg-slate-50 p-3.5 rounded-xl space-y-1.5 border border-slate-200">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-[10px] uppercase tracking-wider text-slate-600">Payment Mode & Verification</span>
                        <span class="text-[10.5px] font-bold px-2.5 py-0.5 rounded-full border"
                              :class="(selectedOrder.payment_id || '').includes('COD') ? 'bg-amber-100 text-amber-950 border-amber-300' : 'bg-purple-100 text-purple-950 border-purple-300'"
                              x-text="(selectedOrder.payment_id || '').includes('COD') ? '💵 Cash on Delivery (COD)' : ((selectedOrder.payment_id || '').includes('UPI') ? '⚡ UPI Instant (Verified)' : '💳 Prepaid Online (Verified)')"></span>
                    </div>
                    <div class="text-[11px] text-slate-700 flex justify-between font-mono pt-0.5">
                        <span class="text-slate-500">Gateway Ref ID:</span>
                        <span class="font-bold text-slate-900" x-text="selectedOrder.payment_id || 'RAZORPAY-CONFIRMED'"></span>
                    </div>
                </div>

                {{-- Price Breakdown --}}
                <div class="bg-gray-50 p-3.5 rounded-xl space-y-1.5 border border-gray-200">
                    <div class="flex justify-between text-gray-600 text-[11px]">
                        <span>Subtotal:</span>
                        <span class="font-mono" x-text="'₹' + Number(selectedOrder.subtotal || selectedOrder.total || 0).toLocaleString()"></span>
                    </div>
                    <template x-if="selectedOrder.discount > 0">
                        <div class="flex justify-between text-emerald-700 text-[11px] font-bold">
                            <span>Discount / Coupon Applied:</span>
                            <span class="font-mono" x-text="'-₹' + Number(selectedOrder.discount).toLocaleString()"></span>
                        </div>
                    </template>
                    <div class="flex justify-between text-gray-600 text-[11px]">
                        <span>Shipping & Handling:</span>
                        <span class="font-mono" x-text="selectedOrder.shipping > 0 ? ('₹' + selectedOrder.shipping) : 'FREE (Complimentary)'"></span>
                    </div>
                    <div class="border-t border-gray-200 pt-2 flex justify-between items-center text-sm font-bold text-[var(--color-ebony)]">
                        <span>Total Paid:</span>
                        <span class="font-serif font-bold text-lg text-emerald-800" x-text="'₹' + Number(selectedOrder.total || 0).toLocaleString()"></span>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="button" @click="viewOrderModal = false" class="w-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold py-3 rounded-xl transition-colors">Close Details</button>
            </div>
        </div>
    </div>

    {{-- MODAL 4: APPROVE PAYOUT --}}
    <div x-show="payoutModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="payoutModal" x-transition.opacity @click="payoutModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-4">
            <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Approve Associate Payout</h3>
            <p class="text-xs font-sans text-[var(--color-ebony)]/60">Disburse pending commission balance to registered UPI address.</p>

            <form :action="'/estilo-hq-console/associates/' + selectedAssociate.id + '/payout'" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Associate Name</label>
                    <input type="text" x-model="selectedAssociate.name" readonly class="w-full bg-gray-100 border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">UPI Address</label>
                    <input type="text" x-model="selectedAssociate.upi_id" readonly class="w-full bg-gray-100 border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-mono font-bold" />
                </div>
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Payout Amount (₹)</label>
                    <input type="number" step="0.01" name="amount" x-model="selectedAssociate.balance" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-serif font-bold text-emerald-800" />
                </div>

                <div class="flex gap-3 pt-3">
                    <button type="button" @click="payoutModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-2.5 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 rounded-xl shadow-md">Confirm & Disburse</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 5: GENERATE COUPON (INTERACTIVE WITH LIVE PREVIEW & PRESETS) --}}
    <div x-show="showAddCouponModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="showAddCouponModal" x-transition.opacity @click="showAddCouponModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-5 my-8">
            
            {{-- Modal Header --}}
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)] pb-3">
                <div class="space-y-0.5">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[10px] font-sans font-bold uppercase tracking-wider">
                        <span>✨ Festive Promotions</span>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Generate Discount Coupon</h3>
                </div>
                <button type="button" @click="showAddCouponModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center text-sm font-bold transition-colors">
                    ✕
                </button>
            </div>

            {{-- 1. Quick Presets Bar --}}
            <div class="space-y-1.5">
                <label class="block text-[10px] font-sans font-bold text-[var(--color-ebony)]/60 uppercase tracking-wider">
                    ⚡ Quick Presets (Click to auto-fill):
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5">
                    <button type="button" @click="applyPresetCoupon('DIWALI30', 'Diwali Royal Festive 30% Off', 30, 'percentage', 1999, 'festival')"
                            class="px-2 py-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 border border-amber-200 text-[10px] font-mono font-bold text-amber-900 text-center transition-colors">
                        🎉 DIWALI30
                    </button>
                    <button type="button" @click="applyPresetCoupon('ROYAL500', 'Flat ₹500 Off Luxury Collection', 500, 'fixed', 3500, 'festival')"
                            class="px-2 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 border border-rose-200 text-[10px] font-mono font-bold text-rose-900 text-center transition-colors">
                        👑 ROYAL500
                    </button>
                    <button type="button" @click="applyPresetCoupon('FESTIVE25', 'Festive Season 25% Off', 25, 'percentage', 1499, 'festival')"
                            class="px-2 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-[10px] font-mono font-bold text-emerald-900 text-center transition-colors">
                        ✨ FESTIVE25
                    </button>
                    <button type="button" @click="applyPresetCoupon('WELCOME10', '10% Welcome Discount For New Customers', 10, 'percentage', 999, 'welcome')"
                            class="px-2 py-1.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-[10px] font-mono font-bold text-indigo-900 text-center transition-colors">
                        👗 WELCOME10
                    </button>
                </div>
            </div>

            {{-- 2. Live Interactive Voucher Card Preview --}}
            <div class="bg-gradient-to-br from-[#FBEAD6] to-[#F5DBC3] border border-amber-300 rounded-2xl p-4 shadow-sm relative overflow-hidden space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[9px] font-sans font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-white/80 text-[var(--color-ebony)] shadow-xs">
                        <span x-text="newCoupon.campaign_type.toUpperCase()"></span> CAMPAIGN
                    </span>
                    <span class="text-[10px] font-bold text-emerald-700">● Live Preview</span>
                </div>
                <div class="flex items-baseline justify-between">
                    <span class="font-mono text-2xl font-black text-[var(--color-ebony)] tracking-wider" x-text="newCoupon.code || 'COUPON_CODE'"></span>
                    <span class="text-sm font-black text-amber-900 bg-white/90 px-2 py-0.5 rounded-lg shadow-xs" x-text="newCoupon.discount_type === 'percentage' ? (newCoupon.discount_value + '% OFF') : ('₹' + newCoupon.discount_value + ' OFF')"></span>
                </div>
                <p class="font-serif text-xs font-bold text-[var(--color-ebony)]" x-text="newCoupon.title || 'Campaign Title Preview'"></p>
                <div class="text-[10px] text-[var(--color-ebony)]/70 flex items-center justify-between pt-1 border-t border-amber-300/60 font-sans">
                    <span>Min Order: ₹<span x-text="Number(newCoupon.min_order_value || 0).toLocaleString()"></span></span>
                    <span>Valid until: <span x-text="newCoupon.valid_until || '2027-12-31'"></span></span>
                </div>
            </div>

            {{-- 3. Form Body --}}
            <form action="/estilo-hq-console/coupons" method="POST" class="space-y-3.5">
                @csrf
                
                {{-- Code & Title --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-1">
                        <label class="block text-[11px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Coupon Code</label>
                        <input type="text" name="code" x-model="newCoupon.code" @input="newCoupon.code = newCoupon.code.toUpperCase()" placeholder="DIWALI30" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold uppercase focus:outline-none focus:border-amber-400" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Campaign Title</label>
                        <input type="text" name="title" x-model="newCoupon.title" placeholder="Diwali Royal Festive 30% Off" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2.5 text-xs font-sans focus:outline-none focus:border-amber-400" />
                    </div>
                </div>

                {{-- Discount Type & Value & Min Order --}}
                <div class="grid grid-cols-3 gap-2.5">
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Type</label>
                        <select name="discount_type" x-model="newCoupon.discount_type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-2.5 py-2 text-xs font-bold focus:outline-none">
                            <option value="percentage">% Percentage</option>
                            <option value="fixed">₹ Flat INR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Discount</label>
                        <input type="number" name="discount_value" x-model="newCoupon.discount_value" required min="1"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:border-amber-400" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Min Order (₹)</label>
                        <input type="number" name="min_order_value" x-model="newCoupon.min_order_value" min="0" step="100"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-bold focus:outline-none focus:border-amber-400" />
                    </div>
                </div>

                {{-- Campaign Category & Expiry Date --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Campaign Category</label>
                        <select name="campaign_type" x-model="newCoupon.campaign_type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans focus:outline-none">
                            <option value="festival">Festival Campaign</option>
                            <option value="monthly">Monthly Campaign</option>
                            <option value="welcome">Welcome Discount</option>
                            <option value="seasonal">Seasonal Sale</option>
                            <option value="clearance">Clearance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Expiry Date</label>
                        <input type="date" name="valid_until" x-model="newCoupon.valid_until"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans focus:outline-none" />
                    </div>
                </div>

                {{-- Modal Action Buttons --}}
                <div class="flex gap-3 pt-3 border-t border-[var(--color-bisque)]">
                    <button type="button" @click="showAddCouponModal = false"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-3 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider py-3 rounded-xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                        ✨ Publish Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 5B: EDIT COUPON --}}
    <div x-show="editCouponModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="editCouponModal" x-transition.opacity @click="editCouponModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-4 my-8">
            
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)] pb-3">
                <div>
                    <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Edit Discount Coupon</h3>
                    <p class="text-xs text-[var(--color-ebony)]/60">Update coupon parameters, discount rate, or validity.</p>
                </div>
                <button type="button" @click="editCouponModal = false" class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-500 hover:text-gray-800 flex items-center justify-center text-sm font-bold transition-colors">
                    ✕
                </button>
            </div>

            <form :action="'/estilo-hq-console/coupons/' + selectedCoupon.id" method="POST" class="space-y-3.5">
                @csrf
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="sm:col-span-1">
                        <label class="block text-[11px] font-sans font-bold uppercase tracking-wider mb-1">Coupon Code</label>
                        <input type="text" name="code" x-model="selectedCoupon.code" @input="selectedCoupon.code = selectedCoupon.code.toUpperCase()" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2 text-xs font-mono font-bold uppercase focus:outline-none" />
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-[11px] font-sans font-bold uppercase tracking-wider mb-1">Campaign Title</label>
                        <input type="text" name="title" x-model="selectedCoupon.title" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3.5 py-2 text-xs font-sans focus:outline-none" />
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2.5">
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1">Type</label>
                        <select name="discount_type" x-model="selectedCoupon.discount_type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-2.5 py-2 text-xs font-bold focus:outline-none">
                            <option value="percentage">% Percentage</option>
                            <option value="fixed">₹ Flat INR</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1">Discount</label>
                        <input type="number" name="discount_value" x-model="selectedCoupon.discount_value" required min="1"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-bold focus:outline-none" />
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1">Min Order (₹)</label>
                        <input type="number" name="min_order_value" x-model="selectedCoupon.min_order_value" min="0" step="100"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-bold focus:outline-none" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1">Campaign Category</label>
                        <select name="campaign_type" x-model="selectedCoupon.campaign_type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans focus:outline-none">
                            <option value="festival">Festival Campaign</option>
                            <option value="monthly">Monthly Campaign</option>
                            <option value="welcome">Welcome Discount</option>
                            <option value="seasonal">Seasonal Sale</option>
                            <option value="clearance">Clearance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-sans font-bold uppercase tracking-wider mb-1">Expiry Date</label>
                        <input type="date" name="valid_until" x-model="selectedCoupon.valid_until"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans focus:outline-none" />
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-sans font-bold text-[var(--color-ebony)]">
                        <input type="checkbox" name="is_active" value="1" :checked="Boolean(selectedCoupon.is_active)" class="accent-emerald-600 rounded" />
                        <span>Coupon Active & Usable at Checkout</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-3 border-t border-[var(--color-bisque)]">
                    <button type="button" @click="editCouponModal = false"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-2.5 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold py-2.5 rounded-xl shadow-md transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 6: ADD CATEGORY --}}
    <div x-show="showAddCategoryModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showAddCategoryModal" x-transition.opacity @click="showAddCategoryModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-4">
            <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Add New Category</h3>
            <form action="/estilo-hq-console/categories" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Category Name</label>
                    <input type="text" name="name" placeholder="e.g. Velvet Lehengas" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>
                <div class="flex gap-3 pt-3">
                    <button type="button" @click="showAddCategoryModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-2.5 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] text-white text-xs font-bold py-2.5 rounded-xl shadow-md">Create Category</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 7: ADMIN PROFILE & LOGOUT --}}
    <div x-show="showProfileModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="showProfileModal" x-transition.opacity @click="showProfileModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 space-y-6">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[var(--color-ebony)] text-amber-200 flex items-center justify-center font-bold text-sm shadow-inner">
                        {{ strtoupper(substr($admin->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">{{ $admin->name ?? 'Administrator' }}</h3>
                        <span class="text-[10px] font-sans text-emerald-700 font-bold uppercase">Active Session</span>
                    </div>
                </div>
                <button @click="showProfileModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form action="/estilo-hq-console/profile" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Full Name</label>
                    <input type="text" name="name" value="{{ $admin->name }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2 text-xs font-sans font-bold" />
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Email (Login ID)</label>
                        <input type="email" name="email" value="{{ $admin->email }}" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2 text-xs font-sans" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Phone</label>
                        <input type="text" name="phone" value="{{ $admin->phone ?? '9000000001' }}" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2 text-xs font-sans" />
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">New Password (Optional)</label>
                    <input type="password" name="password" placeholder="Leave empty to keep unchanged" minlength="6" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2 text-xs font-sans" />
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showProfileModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-2.5 rounded-xl">Close</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold py-2.5 rounded-xl shadow-md transition-colors">Update Profile</button>
                </div>
            </form>

            <div class="border-t border-[var(--color-bisque)]/60 pt-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-700 text-xs font-bold uppercase tracking-wider py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2">
                        <span>🚪</span> End Session & Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL 8: EDIT RATING & REVIEW (OVERRIDE BAD/LOW RATINGS) --}}
    <div x-show="editReviewModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="editReviewModal" x-transition.opacity @click="editReviewModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 space-y-5">
            
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center text-base">✏️</span>
                    <div>
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Edit Customer Review & Rating</h3>
                        <span class="text-[10px] font-sans text-gray-500">Modify low stars, refine feedback comments, and set public status</span>
                    </div>
                </div>
                <button type="button" @click="editReviewModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form :action="'/estilo-hq-console/reviews/' + selectedReview.id" method="POST" class="space-y-4">
                @csrf
                
                {{-- Product and ID Info --}}
                <div class="p-3 bg-gray-50 rounded-xl border border-gray-200 flex items-center justify-between text-xs font-sans">
                    <span class="text-gray-600">Product Est ID: <strong class="text-[var(--color-ebony)]" x-text="selectedReview.product_est_id"></strong></span>
                    <span class="text-gray-500 font-mono">Review #<span x-text="selectedReview.id"></span></span>
                </div>

                {{-- Customer Name --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Customer Name</label>
                    <input type="text" name="user_name" x-model="selectedReview.user_name" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                </div>

                {{-- Interactive Star Rating Selector (1 to 5 Stars) --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">
                        Star Rating (1 to 5 Stars)
                    </label>
                    <div class="flex items-center gap-2 p-3 bg-amber-50/60 rounded-xl border border-amber-200">
                        <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                            <button type="button" 
                                    @click="selectedReview.rating = star" 
                                    class="text-2xl transition-transform hover:scale-125 focus:outline-none"
                                    :title="star + ' Star(s)'">
                                <span :class="star <= selectedReview.rating ? 'text-amber-400' : 'text-gray-300'">★</span>
                            </button>
                        </template>
                        <span class="text-xs font-sans font-bold text-amber-900 ml-2" x-text="selectedReview.rating + ' Star(s) Selected'"></span>
                        <input type="hidden" name="rating" :value="selectedReview.rating" />
                    </div>
                    <span class="text-[10px] text-gray-500 mt-1 block">Tip: If a customer left a 1★ or 2★ review, you can elevate it to 4★ or 5★ here.</span>
                </div>

                {{-- Review Comment Textarea --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">
                        Review Comment (Editable)
                    </label>
                    <textarea name="comment" 
                              x-model="selectedReview.comment" 
                              rows="4" 
                              required 
                              placeholder="Write or edit customer feedback..."
                              class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl p-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]"></textarea>
                    <span class="text-[10px] text-gray-500">Edit or rewrite negative words into positive couture feedback.</span>
                </div>

                {{-- Live Visibility Checkbox --}}
                <div class="flex items-center gap-2 p-3 bg-emerald-50/50 rounded-xl border border-emerald-200/60">
                    <input type="checkbox" name="is_approved" value="1" id="edit_is_approved" x-model="selectedReview.is_approved" class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500" />
                    <label for="edit_is_approved" class="text-xs font-sans font-bold text-emerald-900 cursor-pointer">
                        Approve & Display Live on Product Storefront Page
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="editReviewModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-3 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold py-3 rounded-xl shadow-md transition-colors">
                        Save Rating & Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 9: ADD STOREFRONT ANNOUNCEMENT --}}
    <div x-show="showAddAnnouncementModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="showAddAnnouncementModal" x-transition.opacity @click="showAddAnnouncementModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 space-y-5">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center text-lg">📢</span>
                    <div>
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Create Storefront Announcement</h3>
                        <span class="text-[10px] font-sans text-gray-500">Broadcast promotional notices, discount codes, or shipping alerts</span>
                    </div>
                </div>
                <button type="button" @click="showAddAnnouncementModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form action="/estilo-hq-console/announcements" method="POST" class="space-y-4">
                @csrf

                {{-- Title & Emoji Icon --}}
                <div class="grid grid-cols-4 gap-3">
                    <div class="col-span-1">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Icon</label>
                        <select name="icon" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2.5 text-base font-sans focus:outline-none">
                            <option value="📢">📢 Loudspeaker</option>
                            <option value="🎉">🎉 Festive</option>
                            <option value="✨">✨ Sparkle</option>
                            <option value="🎟️">🎟️ Coupon</option>
                            <option value="🚚">🚚 Shipping</option>
                            <option value="🛍️">🛍️ Shopping</option>
                            <option value="💎">💎 Luxury</option>
                            <option value="🔥">🔥 Hot Deal</option>
                            <option value="⏳">⏳ Limited Time</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Short Title / Event Name</label>
                        <input type="text" name="title" placeholder="e.g. Festive Special Sale, Express Delivery" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold focus:outline-none focus:border-[var(--color-rose-antique)]" />
                    </div>
                </div>

                {{-- Full Announcement Message --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">
                        Announcement Message (Broadcast Text)
                    </label>
                    <textarea name="message" rows="3" required placeholder="e.g. Use code DIWALI25 for flat 25% off on all bridal sarees & pure Banarasi silk couture!" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl p-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]"></textarea>
                    <span class="text-[10px] text-gray-500">This message scrolls across the storefront marquee and shows in header banners.</span>
                </div>

                {{-- Campaign Type & Theme Color --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Category / Type</label>
                        <select name="type" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans">
                            <option value="sale">Promotional Sale</option>
                            <option value="coupon">Discount Coupon</option>
                            <option value="festive">Festival Special</option>
                            <option value="shipping">Shipping & Delivery</option>
                            <option value="alert">General Notice</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Theme Style</label>
                        <select name="color" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans">
                            <option value="amber">Royal Gold / Amber</option>
                            <option value="rose">Rose Antique / Crimson</option>
                            <option value="emerald">Emerald Handloom</option>
                            <option value="ebony">Classic Ebony / Navy</option>
                        </select>
                    </div>
                </div>

                {{-- Display Placements --}}
                <div class="space-y-2 p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-600 block">Display Placements:</span>
                    <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)] cursor-pointer">
                        <input type="checkbox" name="show_in_ticker" checked value="1" class="w-4 h-4 rounded text-[var(--color-ebony)]" />
                        <span><strong>Marquee Ticker:</strong> Continuous scrolling bar under the main header</span>
                    </label>
                    <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)] cursor-pointer">
                        <input type="checkbox" name="show_as_banner" value="1" class="w-4 h-4 rounded text-[var(--color-ebony)]" />
                        <span><strong>Top Notification Banner:</strong> Dismissible floating banner at the very top</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showAddAnnouncementModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-3 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold py-3 rounded-xl shadow-md transition-colors">
                        📢 Broadcast Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL 10: EDIT STOREFRONT ANNOUNCEMENT --}}
    <div x-show="editAnnouncementModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="editAnnouncementModal" x-transition.opacity @click="editAnnouncementModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-lg bg-white rounded-3xl p-6 sm:p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 space-y-5">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-900 flex items-center justify-center text-lg">✏️</span>
                    <div>
                        <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Edit Announcement #<span x-text="selectedAnnouncement.id"></span></h3>
                        <span class="text-[10px] font-sans text-gray-500">Update broadcast text, placements, and active status</span>
                    </div>
                </div>
                <button type="button" @click="editAnnouncementModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form :action="'/estilo-hq-console/announcements/' + selectedAnnouncement.id" method="POST" class="space-y-4">
                @csrf

                {{-- Title & Emoji Icon --}}
                <div class="grid grid-cols-4 gap-3">
                    <div class="col-span-1">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Icon</label>
                        <select name="icon" x-model="selectedAnnouncement.icon" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-3 py-2.5 text-base font-sans">
                            <option value="📢">📢</option>
                            <option value="🎉">🎉</option>
                            <option value="✨">✨</option>
                            <option value="🎟️">🎟️</option>
                            <option value="🚚">🚚</option>
                            <option value="🛍️">🛍️</option>
                            <option value="💎">💎</option>
                            <option value="🔥">🔥</option>
                            <option value="⏳">⏳</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">Title / Event Name</label>
                        <input type="text" name="title" x-model="selectedAnnouncement.title" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold focus:outline-none" />
                    </div>
                </div>

                {{-- Full Announcement Message --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1 text-[var(--color-ebony)]">
                        Announcement Message
                    </label>
                    <textarea name="message" x-model="selectedAnnouncement.message" rows="3" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl p-3 text-xs font-sans focus:outline-none"></textarea>
                </div>

                {{-- Display Placements & Status --}}
                <div class="space-y-2.5 p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-gray-600 block">Configuration:</span>
                    <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)] cursor-pointer">
                        <input type="checkbox" name="show_in_ticker" :checked="selectedAnnouncement.show_in_ticker" value="1" class="w-4 h-4 rounded text-[var(--color-ebony)]" />
                        <span>Display in Storefront Marquee Ticker</span>
                    </label>
                    <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)] cursor-pointer">
                        <input type="checkbox" name="show_as_banner" :checked="selectedAnnouncement.show_as_banner" value="1" class="w-4 h-4 rounded text-[var(--color-ebony)]" />
                        <span>Display as Top Dismissible Banner</span>
                    </label>
                    <label class="flex items-center gap-2.5 text-xs font-sans text-emerald-800 font-bold cursor-pointer">
                        <input type="checkbox" name="is_active" :checked="selectedAnnouncement.is_active" value="1" class="w-4 h-4 rounded text-emerald-600" />
                        <span>Active & Currently Broadcasting Live</span>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" @click="editAnnouncementModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-3 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-bold py-3 rounded-xl shadow-md transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
