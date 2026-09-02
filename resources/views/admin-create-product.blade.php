@extends('layouts.app')

@section('title', 'Estilo HQ — Add New Couture Outfit')

@section('content')
<div class="min-h-screen bg-[var(--color-offwhite)] py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Navigation Breadcrumb --}}
        <div class="flex items-center justify-between">
            <a href="/estilo-hq-console?tab=inventory" class="inline-flex items-center gap-2 text-xs font-sans font-bold text-[var(--color-ebony)]/70 hover:text-[var(--color-ebony)] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Inventory & Products
            </a>
            <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-rose-antique)] bg-rose-100 px-3 py-1 rounded-full">
                👗 Catalog Management
            </span>
        </div>

        {{-- Main Creation Card --}}
        <div class="bg-white rounded-3xl border border-[var(--color-bisque)] shadow-xl p-6 sm:p-10 space-y-8"
             x-data="{
                product: {
                    name: 'Handloom Pure Tussar Silk Anarkali Suit',
                    category: 'Anarkali Suits',
                    price: 2899,
                    size_stock: { 'XS': 1, 'S': 2, 'M': 4, 'L': 2, 'XL': 3, 'XXL': 2 }
                }
             }">

            {{-- Header --}}
            <div class="border-b border-[var(--color-bisque)]/60 pb-5">
                <h1 class="font-serif text-2xl sm:text-3xl font-bold text-[var(--color-ebony)]">Add New Couture Outfit</h1>
                <p class="text-xs font-sans text-gray-500 mt-1">Configure luxury handloom apparel, size-wise inventory stock, fabric specifications, and publish directly to the boutique storefront.</p>
            </div>

            {{-- Form Body --}}
            <form action="/estilo-hq-console/products" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Name & Category --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Outfit Name / Title *</label>
                        <input type="text" name="name" x-model="product.name" placeholder="e.g. Royal Lucknowi Chikankari Kurti" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Category *</label>
                        <input type="text" name="category" list="cat_list" x-model="product.category" placeholder="e.g. Chikankari Kurtis, Silk Sarees" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                        <datalist id="cat_list">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->name }}"></option>
                            @endforeach
                            <option value="Chikankari Kurtis"></option>
                            <option value="Silk Sarees"></option>
                            <option value="Anarkali Suits"></option>
                            <option value="Organza Sarees"></option>
                            <option value="Bridal Couture"></option>
                        </datalist>
                    </div>
                </div>

                {{-- Price & Fabric --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Price in INR (₹) *</label>
                        <input type="number" name="price" x-model="product.price" required min="1"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-bold font-serif focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Fabric & Weave Type</label>
                        <input type="text" name="fabric" placeholder="e.g. Pure Mulberry Silk, Mulmul Cotton" value="Handloom Pure Silk"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                    </div>
                </div>

                {{-- Size-Wise Stock Quantity (XS, S, M, L, XL, XXL) --}}
                <div class="p-5 bg-[var(--color-champagne-light)]/40 rounded-2xl border border-[var(--color-bisque)] space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]">
                            📦 Stock Inventory Quantity Per Size
                        </label>
                        <span class="text-xs font-bold text-emerald-900 bg-emerald-100 border border-emerald-300 px-3 py-0.5 rounded-full"
                              x-text="'Total: ' + (Object.values(product.size_stock).reduce((acc, val) => Number(acc) + Number(val || 0), 0)) + ' Units In Stock'">
                        </span>
                    </div>
                    <p class="text-[11px] text-gray-500 font-sans">Set exact physical warehouse units for each garment size:</p>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 pt-1">
                        <template x-for="sz in ['XS', 'S', 'M', 'L', 'XL', 'XXL']" :key="sz">
                            <div class="bg-white p-2.5 rounded-xl border border-[var(--color-bisque)] text-center space-y-1 shadow-xs">
                                <span class="block text-xs font-bold font-mono text-[var(--color-ebony)]" x-text="sz"></span>
                                <input type="number" min="0" :name="'size_stock[' + sz + ']'" x-model="product.size_stock[sz]" class="w-full bg-[var(--color-offwhite)] border border-gray-200 rounded-lg py-1.5 text-center text-xs font-bold font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Color Palette & Photo --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Color Palette (Comma Separated)</label>
                        <input type="text" name="colors" value="Royal Emerald, Rose Gold, Ivory White"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-amber-400" />
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Upload Garment Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-xs font-sans file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[var(--color-ebony)] file:text-white hover:file:bg-[var(--color-rose-deep)] transition-colors" />
                    </div>
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Garment Description *</label>
                    <textarea name="description" rows="3" required placeholder="Describe craftsmanship, zari weaving, and styling details..."
                              class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl p-4 text-xs font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner">Exquisitely tailored artisanal handloom outfit with royal gold zari borders and breathable inner lining.</textarea>
                </div>

                {{-- Homepage Visibility & Placement Options --}}
                <div class="p-5 bg-gradient-to-r from-[var(--color-champagne-light)]/40 to-pink-50/40 rounded-2xl border border-[var(--color-bisque)] space-y-3.5">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]">
                            🌟 Storefront Showcase & Homepage Placement
                        </label>
                        <p class="text-[11px] text-gray-500 font-sans mt-0.5">Select which prominent featured collections on the customer storefront should include this outfit:</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        {{-- Trending This Season --}}
                        <label class="flex items-start gap-2.5 p-3 bg-white rounded-xl border border-[var(--color-bisque)] hover:border-amber-400 cursor-pointer shadow-xs transition-all hover:scale-[1.01]">
                            <input type="checkbox" name="is_trending" checked value="1" class="mt-0.5 w-4 h-4 rounded accent-amber-600 cursor-pointer shrink-0" />
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-gray-900 flex items-center gap-1">
                                    🔥 Trending This Season
                                </span>
                                <span class="block text-[10px] text-gray-500 leading-tight">Display in top horizontal slider on homepage</span>
                            </div>
                        </label>

                        {{-- New Arrivals Collection --}}
                        <label class="flex items-start gap-2.5 p-3 bg-white rounded-xl border border-[var(--color-bisque)] hover:border-rose-400 cursor-pointer shadow-xs transition-all hover:scale-[1.01]">
                            <input type="checkbox" name="is_new_arrival" checked value="1" class="mt-0.5 w-4 h-4 rounded accent-[var(--color-rose-antique)] cursor-pointer shrink-0" />
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-gray-900 flex items-center gap-1">
                                    ✨ New Arrivals Collection
                                </span>
                                <span class="block text-[10px] text-gray-500 leading-tight">Display in 'Fresh Off The Looms' carousel</span>
                            </div>
                        </label>

                        {{-- Best Seller / Spotlight --}}
                        <label class="flex items-start gap-2.5 p-3 bg-white rounded-xl border border-[var(--color-bisque)] hover:border-emerald-400 cursor-pointer shadow-xs transition-all hover:scale-[1.01]">
                            <input type="checkbox" name="is_featured" checked value="1" class="mt-0.5 w-4 h-4 rounded accent-emerald-600 cursor-pointer shrink-0" />
                            <div class="space-y-0.5">
                                <span class="block text-xs font-bold text-gray-900 flex items-center gap-1">
                                    👑 Featured Best Seller
                                </span>
                                <span class="block text-[10px] text-gray-500 leading-tight">Highlight with Best Seller badge & spotlight</span>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-4 pt-4 border-t border-[var(--color-bisque)]/60">
                    <a href="/estilo-hq-console?tab=inventory"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold py-3.5 rounded-xl transition-colors">
                        Cancel & Return
                    </a>
                    <button type="submit"
                            class="flex-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-widest py-3.5 px-8 rounded-xl shadow-lg transition-all hover:scale-[1.02] active:scale-[0.98]">
                        ✨ Publish Outfit to Catalog
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
