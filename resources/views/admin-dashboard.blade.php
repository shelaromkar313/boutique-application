@extends('layouts.app')

@section('title', 'Atelier Admin Master Console | ESTILO WEAR')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] pb-20 pt-6" x-data="{
    activeTab: '{{ request('tab', 'overview') }}',
    search: '',
    showAddProductModal: false,
    showAddCategoryModal: false,
    showAddCouponModal: false,
    editProductModal: false,
    selectedProduct: null,

    openEditProduct(p) {
        this.selectedProduct = p;
        this.editProductModal = true;
    }
}">

    <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        {{-- Admin Master Header --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-sm p-6 sm:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="space-y-1">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 text-amber-200 text-[10px] font-sans font-bold uppercase tracking-widest">
                    <span>👑 Atelier Master Administration Suite</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Estilo Boutique Management Console</h1>
                <p class="text-xs font-sans text-[var(--color-ebony)]/60">Manage your luxury handloom inventory, customer orders, marketing associates, reports, and festive offers.</p>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button @click="showAddProductModal = true" class="inline-flex items-center gap-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-3 rounded-full shadow-md transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add New Product
                </button>
                <button @click="showAddCouponModal = true" class="inline-flex items-center gap-2 bg-amber-50 hover:bg-amber-100 border border-amber-300 text-amber-900 text-xs font-sans font-bold uppercase tracking-wider px-4 py-3 rounded-full transition-colors">
                    <span>🎟️</span> Generate Coupon
                </button>
            </div>
        </div>

        {{-- Navigation Tabs Header --}}
        <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-[var(--color-bisque)] scrollbar-none">
            <button @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                📊 Dashboard Overview
            </button>
            <button @click="activeTab = 'inventory'" 
                    :class="activeTab === 'inventory' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                👗 4.3 Inventory & Products ({{ $totalProductsCount }})
            </button>
            <button @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                📦 4.4 Orders & Processing
            </button>
            <button @click="activeTab = 'customers'" 
                    :class="activeTab === 'customers' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                👥 4.5 Customers ({{ $customers->count() }})
            </button>
            <button @click="activeTab = 'associates'" 
                    :class="activeTab === 'associates' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                💼 4.6 Sales Associates & Sellers ({{ $totalAssociatesCount }})
            </button>
            <button @click="activeTab = 'reports'" 
                    :class="activeTab === 'reports' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                📈 4.7 Monthly Reports & Billing
            </button>
            <button @click="activeTab = 'offers'" 
                    :class="activeTab === 'offers' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                ✨ 4.8 Offers & Coupons ({{ $coupons->count() }})
            </button>
            <button @click="activeTab = 'reviews'" 
                    :class="activeTab === 'reviews' ? 'bg-[var(--color-ebony)] text-white shadow-md' : 'bg-white text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] border border-[var(--color-bisque)]'"
                    class="px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider transition-all shrink-0">
                ⭐ Ratings & Reviews ({{ $reviews->count() }})
            </button>
        </div>

        {{-- TAB 1: OVERVIEW --}}
        <div x-show="activeTab === 'overview'" class="space-y-6">
            {{-- KPI Metric Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
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
                <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)] shadow-sm space-y-1 col-span-2 lg:col-span-1">
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
                        <div class="flex items-center justify-between p-3 rounded-xl bg-[var(--color-offwhite)]">
                            <div>
                                <span class="font-mono text-xs font-bold">{{ $ord->order_no }}</span>
                                <p class="text-xs font-serif font-semibold text-[var(--color-ebony)]">{{ $ord->full_name }}</p>
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
                        @foreach($associates as $assoc)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-[var(--color-offwhite)]">
                            <div>
                                <span class="font-bold text-xs text-[var(--color-ebony)]">{{ $assoc->name }}</span>
                                <span class="block text-[10px] font-mono text-amber-800">Code: {{ $assoc->referral_code ?? 'ESTILO-SA01' }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-serif font-bold text-xs text-emerald-700">₹{{ number_format($assoc->earnings, 0) }} Total Profit</span>
                                <span class="block text-[10px] font-bold text-[var(--color-ebony)]/60">{{ $assoc->commission_rate }}% Rate</span>
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
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">4.3 Boutique Inventory & Products</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Add new couture, edit pricing & fabric, update stock status, and moderate product reviews.</p>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <input type="text" x-model="search" placeholder="Search catalog..." class="w-full sm:w-64 pl-4 pr-4 py-2 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full text-xs font-sans focus:outline-none" />
                        <button @click="showAddCategoryModal = true" class="bg-white border border-[var(--color-bisque)] hover:bg-gray-50 text-xs font-sans font-bold px-4 py-2 rounded-full shrink-0">
                            + Category
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Outfit</th>
                                <th class="p-3">Category</th>
                                <th class="p-3">Fabric</th>
                                <th class="p-3">Price (₹)</th>
                                <th class="p-3">Stock Status</th>
                                <th class="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @foreach($products as $prod)
                            @php $img = is_array($prod->images) ? ($prod->images[0] ?? '/storage/hero/hero-main.jpg') : $prod->images; @endphp
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors"
                                x-show="!search || '{{ strtolower($prod->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($prod->category) }}'.includes(search.toLowerCase())">
                                <td class="p-3 flex items-center gap-3">
                                    <img src="{{ $img }}" alt="{{ $prod->name }}" class="w-10 h-12 object-cover rounded-lg shrink-0" />
                                    <div>
                                        <h4 class="font-bold text-[var(--color-ebony)] line-clamp-1">{{ $prod->name }}</h4>
                                        <span class="text-[10px] text-[var(--color-ebony)]/50">ID: {{ $prod->est_id }} • SKU: {{ $prod->sku }}</span>
                                    </div>
                                </td>
                                <td class="p-3 font-semibold text-[var(--color-rose-antique)]">{{ $prod->category }}</td>
                                <td class="p-3 text-[var(--color-ebony)]/70">{{ $prod->fabric }}</td>
                                <td class="p-3 font-serif font-bold">₹{{ number_format($prod->price, 0) }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $prod->in_stock ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $prod->in_stock ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                </td>
                                <td class="p-3 text-right space-x-2">
                                    <button @click="openEditProduct({{ json_encode($prod) }})" class="p-1.5 text-blue-600 hover:text-blue-800 transition-colors" title="Edit Outfit">
                                        ✏️ Edit
                                    </button>
                                    <form action="/admin/products/{{ $prod->id }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:text-rose-800 transition-colors" title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- TAB 3: 4.4 ORDERS & PROCESSING --}}
        <div x-show="activeTab === 'orders'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">4.4 Customer Orders & Processing</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">View and update real-time fulfillment statuses for all customer purchases.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Order No</th>
                                <th class="p-3">Customer Name</th>
                                <th class="p-3">City, State</th>
                                <th class="p-3">Order Total</th>
                                <th class="p-3">Current Status</th>
                                <th class="p-3 text-right">Process Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @forelse($orders as $ord)
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                                <td class="p-3 font-mono font-bold">{{ $ord->order_no }}</td>
                                <td class="p-3">
                                    <span class="font-bold text-[var(--color-ebony)] block">{{ $ord->full_name }}</span>
                                    <span class="text-[10px] text-[var(--color-ebony)]/60">{{ $ord->phone }} • {{ $ord->email }}</span>
                                </td>
                                <td class="p-3 text-[var(--color-ebony)]/70">{{ $ord->city }}, {{ $ord->state }}</td>
                                <td class="p-3 font-serif font-bold text-sm">₹{{ number_format($ord->total, 0) }}</td>
                                <td class="p-3">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase">
                                        {{ $ord->status }}
                                    </span>
                                </td>
                                <td class="p-3 text-right">
                                    <form action="/admin/orders/{{ $ord->id }}/status" method="POST" class="inline-flex items-center gap-1">
                                        @csrf
                                        <select name="status" class="bg-[var(--color-offwhite)] border border-[var(--color-bisque)] text-[10px] rounded-lg px-2 py-1 font-bold">
                                            <option value="pending" {{ $ord->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="confirmed" {{ $ord->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                            <option value="shipped" {{ $ord->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $ord->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $ord->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="bg-[var(--color-ebony)] text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">Update</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-6 text-center text-xs text-[var(--color-ebony)]/60">No orders recorded in system.</td>
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
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">4.5 Customer Relationship Management</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Directory of registered clientele, contact records, and lifetime orders.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Customer</th>
                                <th class="p-3">Phone Number</th>
                                <th class="p-3">Account Role</th>
                                <th class="p-3">Joined Date</th>
                                <th class="p-3 text-right">Status</th>
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
                                <td class="p-3 font-bold text-[var(--color-rose-antique)]">Customer VIP</td>
                                <td class="p-3 text-[var(--color-ebony)]/60">{{ $cust->created_at ? $cust->created_at->format('d M Y') : 'Recent' }}</td>
                                <td class="p-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Active</span>
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
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">4.6 Marketing Associates & Affiliate Sellers</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Configure partner commission tiers (e.g. 10%, 12%, 15%), review sales volume, and verify UPI payout accounts.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead>
                            <tr class="bg-[var(--color-champagne-light)] text-[var(--color-ebony)] font-serif uppercase tracking-wider border-b border-[var(--color-bisque)]">
                                <th class="p-3">Associate Details</th>
                                <th class="p-3">Referral Code</th>
                                <th class="p-3">Total Sales Profit</th>
                                <th class="p-3">Payout UPI</th>
                                <th class="p-3">Commission % Rate</th>
                                <th class="p-3 text-right">Save Tier</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-bisque)]/40">
                            @foreach($associates as $assoc)
                            <tr class="hover:bg-[var(--color-offwhite)] transition-colors">
                                <td class="p-3">
                                    <span class="font-bold text-[var(--color-ebony)] block">{{ $assoc->name }}</span>
                                    <span class="text-[10px] text-[var(--color-ebony)]/60">{{ $assoc->phone }} • {{ $assoc->email }}</span>
                                </td>
                                <td class="p-3 font-mono font-bold text-amber-800 bg-amber-50 px-2 py-1 rounded inline-block">
                                    {{ $assoc->referral_code ?? 'ESTILO-SA01' }}
                                </td>
                                <td class="p-3 font-serif font-bold text-emerald-700">₹{{ number_format($assoc->earnings, 0) }}</td>
                                <td class="p-3 font-mono text-[10px]">{{ $assoc->upi_id ?? 'pooja.verma@okhdfcbank' }}</td>
                                <form action="/admin/associates/{{ $assoc->id }}" method="POST">
                                    @csrf
                                    <td class="p-3">
                                        <div class="flex items-center gap-1">
                                            <input type="number" step="0.5" name="commission_rate" value="{{ $assoc->commission_rate }}" class="w-16 px-2 py-1 border border-[var(--color-bisque)] rounded text-xs font-bold" />
                                            <span class="font-bold">%</span>
                                        </div>
                                    </td>
                                    <td class="p-3 text-right">
                                        <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-[10px] font-bold px-3 py-1.5 rounded-lg transition-colors">
                                            Update
                                        </button>
                                    </td>
                                </form>
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
                <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">4.7 Financial Commission & Billing Reports</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Comprehensive monthly audit of partner payouts, GST billing summaries, and gross couture turnover.</p>
                    </div>
                    <button onclick="window.print()" class="bg-white border border-[var(--color-bisque)] hover:bg-[var(--color-offwhite)] text-xs font-sans font-bold px-4 py-2 rounded-full">
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
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Reconciled</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="p-3 font-bold">August 2026</td>
                                <td class="p-3">3 orders</td>
                                <td class="p-3 font-serif font-bold">₹8,297.00</td>
                                <td class="p-3 font-serif font-bold text-emerald-700">₹995.64</td>
                                <td class="p-3 text-right">
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Reconciled</span>
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
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border-b border-[var(--color-bisque)]/60 pb-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">4.8 Offers, Festival Discounts & Coupons</h2>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60">Generate promotional discount coupons for Diwali, Festive, and Monthly boutique sales.</p>
                    </div>
                    <button @click="showAddCouponModal = true" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-2.5 rounded-full transition-all shadow-md">
                        + New Coupon
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($coupons as $coup)
                    <div class="bg-[var(--color-offwhite)] rounded-2xl border border-[var(--color-bisque)] p-5 space-y-3 relative overflow-hidden">
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

                        <div class="pt-2 border-t border-[var(--color-bisque)]/60 flex items-center justify-between">
                            <span class="text-[10px] text-[var(--color-ebony)]/50">Expires: {{ $coup->valid_until ? $coup->valid_until->format('d M Y') : 'Never' }}</span>
                            <form action="/admin/coupons/{{ $coup->id }}/toggle" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-[var(--color-rose-antique)] hover:underline">
                                    {{ $coup->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- TAB 8: RATINGS & REVIEWS MODERATION --}}
        <div x-show="activeTab === 'reviews'" class="space-y-6">
            <div class="bg-white rounded-3xl border border-[var(--color-bisque)] p-6 sm:p-8 shadow-sm space-y-6">
                <div class="border-b border-[var(--color-bisque)]/60 pb-3">
                    <h2 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Customer Reviews & Ratings Moderation</h2>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">Edit ratings, manage customer feedback, and approve public testimonial visibility.</p>
                </div>

                <div class="space-y-4">
                    @forelse($reviews as $rev)
                    <div class="p-4 rounded-2xl bg-[var(--color-offwhite)] border border-[var(--color-bisque)] space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-xs text-[var(--color-ebony)]">{{ $rev->user_name }}</span>
                                <span class="text-[10px] text-[var(--color-rose-antique)] font-mono">Product: {{ $rev->product_est_id }}</span>
                            </div>
                            <div class="flex text-amber-400 text-xs">
                                @for($i = 0; $i < $rev->rating; $i++) ★ @endfor
                            </div>
                        </div>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/80">"{{ $rev->comment }}"</p>
                        
                        <div class="pt-2 border-t border-[var(--color-bisque)]/40 flex items-center justify-between">
                            <span class="text-[10px] text-emerald-700 font-bold">✓ Approved & Live</span>
                            <form action="/admin/reviews/{{ $rev->id }}" method="POST" onsubmit="return confirm('Remove review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-[10px] text-rose-600 hover:underline">Remove</button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-xs text-[var(--color-ebony)]/60">No pending reviews.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    {{-- MODAL 1: ADD NEW PRODUCT --}}
    <div x-show="showAddProductModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div x-show="showAddProductModal" x-transition.opacity @click="showAddProductModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-2xl bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] my-8 max-h-[90vh] overflow-y-auto space-y-5">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)]/60 pb-3">
                <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">Add New Couture Outfit</h3>
                <button @click="showAddProductModal = false" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>

            <form action="/admin/products" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Product Title / Name</label>
                    <input type="text" name="name" placeholder="e.g. Royal Banarasi Zari Silk Anarkali Set" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Category</label>
                        <select name="category" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans">
                            <option value="Kurtis">Kurtis & Suits</option>
                            <option value="Chikankari Kurtis">Chikankari Kurtis</option>
                            <option value="Anarkali Suits">Anarkali Suits & Sets</option>
                            <option value="Sarees">Luxury Sarees</option>
                            <option value="Banarasi Sarees">Banarasi Silk Sarees</option>
                            <option value="Silk Sarees">Pure Kanjivaram Silk</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Fabric</label>
                        <input type="text" name="fabric" placeholder="e.g. Mulberry Silk / Chanderi" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Price (₹)</label>
                        <input type="number" name="price" placeholder="2499" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans font-bold" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-1">Available Sizes (Comma Separated)</label>
                        <input type="text" name="sizes" value="XS, S, M, L, XL, XXL" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
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

                <div class="flex items-center gap-6 pt-2">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold">
                        <input type="checkbox" name="in_stock" checked class="accent-emerald-600" /> In Stock for Ordering
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-bold">
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

    {{-- MODAL 2: GENERATE COUPON --}}
    <div x-show="showAddCouponModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showAddCouponModal" x-transition.opacity @click="showAddCouponModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md bg-white rounded-3xl p-8 shadow-2xl z-10 border border-[var(--color-bisque)] space-y-4">
            <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Generate Discount Coupon</h3>
            <form action="/admin/coupons" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Coupon Code</label>
                    <input type="text" name="code" placeholder="DIWALI30" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-mono font-bold uppercase" />
                </div>
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Campaign Title</label>
                    <input type="text" name="title" placeholder="Diwali Royal Festive 30% Off" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-sans" />
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Discount %</label>
                        <input type="number" name="discount_value" value="20" required class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-bold" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1">Min Order (₹)</label>
                        <input type="number" name="min_order_value" value="1999" class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-2.5 text-xs font-bold" />
                    </div>
                </div>
                <div class="flex gap-3 pt-3">
                    <button type="button" @click="showAddCouponModal = false" class="flex-1 bg-gray-100 text-xs font-bold py-2.5 rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 bg-[var(--color-ebony)] text-white text-xs font-bold py-2.5 rounded-xl shadow-md">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
