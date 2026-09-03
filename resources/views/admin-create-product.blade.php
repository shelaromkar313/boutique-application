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
                sizeMode: 'apparel',
                freeSizeQty: 10,
                product: {
                    name: 'Handloom Pure Tussar Silk Anarkali Suit',
                    category: 'Anarkali Suits',
                    price: 2899,
                    size_stock: { 'XS': 1, 'S': 2, 'M': 4, 'L': 2, 'XL': 3, 'XXL': 2 }
                },
                checkCategory() {
                    const cat = (this.product.category || '').toLowerCase();
                    if (cat.includes('saree') || cat.includes('sari') || cat.includes('dupatta') || cat.includes('shawl') || cat.includes('stole') || cat.includes('unstitched')) {
                        this.sizeMode = 'freesize';
                    }
                },
                init() {
                    this.checkCategory();
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
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]">Category *</label>
                            <span x-show="sizeMode === 'freesize'" class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">🥻 Free Size Mode Active</span>
                        </div>
                        <input type="text" name="category" list="cat_list" x-model="product.category" @input="checkCategory()" @change="checkCategory()" placeholder="e.g. Chikankari Kurtis, Silk Sarees" required
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
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Fabric & Weave Type (new names auto-appear in shop filter)</label>
                        <input type="text" name="fabric" list="fabric_list" placeholder="e.g. Pure Mulberry Silk, Mulmul Cotton" value="Handloom Pure Silk"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 focus:bg-white transition-all shadow-inner" />
                        <datalist id="fabric_list">
                            @foreach(\App\Models\Product::select('fabric')->distinct()->pluck('fabric') as $fab)
                                <option value="{{ $fab }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                </div>

                {{-- Sale Pricing --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 bg-rose-50/60 rounded-2xl border border-rose-200">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Discount % (0 = no sale)</label>
                        <input type="number" name="discount" min="0" max="90" value="20"
                               class="w-full bg-white border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-bold font-sans focus:outline-none focus:border-rose-400" />
                    </div>
                    <label class="flex items-center gap-2.5 p-3 bg-white rounded-xl border border-rose-300 cursor-pointer self-end">
                        <input type="checkbox" name="is_sale" checked value="1" class="w-4 h-4 rounded accent-rose-600 cursor-pointer" />
                        <span class="text-xs font-bold text-gray-900">🏷️ Show in % SALE menu <span class="block text-[10px] font-normal text-gray-500">Uncheck = hidden from Sale section</span></span>
                    </label>
                </div>

                {{-- Size Selection Mode & Stock Quantity (Free Size for Sarees vs Standard Apparel Sizes) --}}
                <div class="p-5 bg-[var(--color-champagne-light)]/40 rounded-2xl border border-[var(--color-bisque)] space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)]">
                                📦 Garment Sizing & Inventory Stock
                            </label>
                            <p class="text-[11px] text-gray-500 font-sans mt-0.5">Select sizing format based on outfit type (e.g. Free Size for Sarees & Dupattas vs standard sizes for stitched suits):</p>
                        </div>
                        <span class="text-xs font-bold text-emerald-900 bg-emerald-100 border border-emerald-300 px-3 py-1 rounded-full self-start sm:self-auto shrink-0"
                              x-text="'Total In Stock: ' + (sizeMode === 'freesize' ? (Number(freeSizeQty) || 0) : (Object.values(product.size_stock).reduce((acc, val) => Number(acc) + Number(val || 0), 0))) + ' Units'">
                        </span>
                    </div>

                    {{-- Mode Toggle Tabs --}}
                    <div class="grid grid-cols-2 gap-2 p-1 bg-white rounded-xl border border-[var(--color-bisque)] shadow-xs">
                        <button type="button" @click="sizeMode = 'freesize'"
                                :class="sizeMode === 'freesize' ? 'bg-[var(--color-ebony)] text-white font-bold shadow-sm' : 'text-gray-600 hover:text-[var(--color-ebony)] font-medium'"
                                class="py-2 px-3 rounded-lg text-xs font-sans transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>🥻 Free Size / One Size</span>
                            <span class="text-[10px] opacity-80 hidden sm:inline">(Sarees, Dupattas, Shawls)</span>
                        </button>
                        <button type="button" @click="sizeMode = 'apparel'"
                                :class="sizeMode === 'apparel' ? 'bg-[var(--color-ebony)] text-white font-bold shadow-sm' : 'text-gray-600 hover:text-[var(--color-ebony)] font-medium'"
                                class="py-2 px-3 rounded-lg text-xs font-sans transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>👗 Standard Stitched Sizes</span>
                            <span class="text-[10px] opacity-80 hidden sm:inline">(XS to XXL Kurtis & Suits)</span>
                        </button>
                    </div>

                    {{-- 1. Free Size Input Card --}}
                    <div x-show="sizeMode === 'freesize'" x-transition class="bg-white p-4 rounded-xl border border-amber-300/80 space-y-2.5">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-[var(--color-ebony)] block">✨ Free Size / Universal Dimensions</span>
                                <span class="text-[11px] text-gray-500">Universal standard fit for handloom sarees (5.5m + 0.8m blouse), shawls, stoles, and unstitched dress materials.</span>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800 bg-amber-100 px-2.5 py-0.5 rounded-full">One Size</span>
                        </div>
                        <div class="flex items-center gap-3 pt-1">
                            <label class="text-xs font-sans font-bold text-gray-700 whitespace-nowrap">Available Warehouse Stock Units:</label>
                            <input type="number" min="1" name="size_stock[Free Size]" x-model="freeSizeQty"
                                   :disabled="sizeMode !== 'freesize'"
                                   class="w-32 bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-lg py-2 px-3 text-center text-sm font-bold font-mono focus:outline-none focus:border-amber-400" />
                            <span class="text-xs text-gray-500 font-sans">pieces ready for dispatch</span>
                        </div>
                    </div>

                    {{-- 2. Standard Apparel Sizes (XS, S, M, L, XL, XXL) --}}
                    <div x-show="sizeMode === 'apparel'" x-transition class="space-y-2">
                        <p class="text-[11px] text-gray-500 font-sans">Set exact physical warehouse units for each individual garment size:</p>
                        <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 pt-1">
                            <template x-for="sz in ['XS', 'S', 'M', 'L', 'XL', 'XXL']" :key="sz">
                                <div class="bg-white p-2.5 rounded-xl border border-[var(--color-bisque)] text-center space-y-1 shadow-xs">
                                    <span class="block text-xs font-bold font-mono text-[var(--color-ebony)]" x-text="sz"></span>
                                    <input type="number" min="0" :name="'size_stock[' + sz + ']'" x-model="product.size_stock[sz]"
                                           :disabled="sizeMode !== 'apparel'"
                                           class="w-full bg-[var(--color-offwhite)] border border-gray-200 rounded-lg py-1.5 text-center text-xs font-bold font-mono focus:outline-none focus:border-[var(--color-rose-antique)]" />
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Color Palette & Photo --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Occasion / Festive Section *</label>
                        <select name="occasion" required
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-sm font-sans focus:outline-none focus:border-amber-400 bg-white">
                            <option value="Festive Wear" selected>🎉 Festive Wear (shows in FESTIVE menu)</option>
                            <option value="Wedding Collection">💒 Wedding Collection</option>
                            <option value="Party Wear">🥂 Party Wear</option>
                            <option value="Office Wear">💼 Office Wear</option>
                            <option value="Casual Wear">🌿 Casual Wear</option>
                            <option value="Festive / Wedding">🎉 Festive / Wedding</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Upload Garment Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full text-xs font-sans file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[var(--color-ebony)] file:text-white hover:file:bg-[var(--color-rose-deep)] transition-colors" />
                    </div>
                </div>

                {{-- Color Palette --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider mb-1.5 text-[var(--color-ebony)]">Color Palette (Comma Separated)</label>
                        <input type="text" name="colors" value="Royal Emerald, Rose Gold, Ivory White"
                               class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-amber-400" />
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
