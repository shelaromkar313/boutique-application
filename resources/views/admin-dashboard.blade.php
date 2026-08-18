@extends('layouts.app')

@section('title', 'Atelier Admin Portal | ESTILO WEAR')

@section('content')

@php
$products = \App\Models\Product::all();
$categories = \App\Models\Category::all();

$totalProducts = $products->count();
$inStockCount = $products->where('in_stock', true)->count();
$outOfStockCount = $products->where('in_stock', false)->count();
$featuredCount = $products->where('is_featured', true)->count();
$avgRating = $totalProducts > 0 ? round($products->avg('rating'), 1) : 5.0;
@endphp

<div class="min-h-screen bg-[var(--color-offwhite)] pb-20 pt-6" x-data="{
    search: '',
    selectedCategory: '',
    showAddModal: false,
    deleteModal: false,
    selectedProduct: null,
    
    confirmDelete(p) {
        this.selectedProduct = p;
        this.deleteModal = true;
    }
}">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Admin Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-[var(--color-bisque)]/60 mb-8">
            <div>
                <span class="text-[10px] font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">Management Console</span>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)] mt-1">Estilo Boutique Atelier Admin</h1>
            </div>
            <div class="flex items-center gap-3">
                <button @click="showAddModal = true" class="inline-flex items-center gap-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-wider px-5 py-3 rounded-full shadow-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add New Outfit
                </button>
            </div>
        </div>

        {{-- Metric KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-6 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)]/60 shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Total Catalog</span>
                <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">{{ $totalProducts }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)]/60 shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-thyme)]">Active In Stock</span>
                <h3 class="font-serif text-2xl font-bold text-[var(--color-thyme)]">{{ $inStockCount }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)]/60 shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-rose-antique)]">Out of Stock</span>
                <h3 class="font-serif text-2xl font-bold text-[var(--color-rose-antique)]">{{ $outOfStockCount }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)]/60 shadow-sm space-y-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]/60">Featured Pieces</span>
                <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">{{ $featuredCount }}</h3>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-[var(--color-bisque)]/60 shadow-sm space-y-1 col-span-2 lg:col-span-1">
                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-amber-500">Average Rating</span>
                <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)] flex items-center gap-1">
                    {{ $avgRating }} <svg class="w-4 h-4 fill-amber-400" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                </h3>
            </div>
        </div>

        {{-- Inventory Table Card --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)]/60 shadow-sm overflow-hidden space-y-4 p-6">
            
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4 border-b border-[var(--color-bisque)]/40">
                <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Boutique Inventory Catalog</h3>
                <div class="w-full sm:w-72 relative">
                    <svg class="w-4 h-4 absolute left-3 top-3 text-[var(--color-ebony)]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" x-model="search" placeholder="Search by name, fabric..." class="w-full pl-9 pr-4 py-2 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-full text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs font-sans border-collapse">
                    <thead>
                        <tr class="bg-[var(--color-champagne-light)] font-serif uppercase tracking-wider text-[var(--color-ebony)] border-b border-[var(--color-bisque)]">
                            <th class="p-3">Outfit</th>
                            <th class="p-3">Category</th>
                            <th class="p-3">Fabric</th>
                            <th class="p-3">Price</th>
                            <th class="p-3">Stock Status</th>
                            <th class="p-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-bisque)]/40">
                        @foreach($products as $prod)
                        <tr class="hover:bg-[var(--color-offwhite)]/80 transition-colors" x-show="!search || '{{ strtolower($prod->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($prod->fabric) }}'.includes(search.toLowerCase())">
                            <td class="p-3 flex items-center gap-3">
                                @php $img = is_array($prod->images) ? ($prod->images[0] ?? '/storage/hero/hero-main.jpg') : $prod->images; @endphp
                                <img src="{{ $img }}" alt="{{ $prod->name }}" class="w-10 h-12 object-cover rounded-lg flex-shrink-0" />
                                <div>
                                    <h4 class="font-bold text-[var(--color-ebony)] line-clamp-1">{{ $prod->name }}</h4>
                                    <span class="text-[10px] text-[var(--color-ebony)]/50">SKU: {{ $prod->sku ?? $prod->est_id }}</span>
                                </div>
                            </td>
                            <td class="p-3 font-semibold text-[var(--color-rose-antique)]">{{ $prod->category }}</td>
                            <td class="p-3 text-[var(--color-ebony)]/70">{{ $prod->fabric }}</td>
                            <td class="p-3 font-serif font-bold text-[var(--color-ebony)]">₹{{ number_format($prod->price, 0) }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $prod->in_stock ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $prod->in_stock ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </td>
                            <td class="p-3 text-right space-x-2">
                                <a href="/product/{{ $prod->est_id ?? $prod->id }}" target="_blank" class="p-1.5 text-[var(--color-ebony)]/60 hover:text-[var(--color-rose-antique)] transition-colors inline-block" title="View">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>

@endsection
