@extends('layouts.app')

@section('title', 'Shop | ESTILO WEAR — Women\'s Boutique Collection')

@section('content')

@php
$categories = ['Designer Kurtis','Cotton Kurtis','Chikankari Kurtis','Straight Kurtis','Anarkali Suits','Banarasi Sarees','Silk Sarees','Organza Sarees','Linen Sarees','Cotton Sarees','Co-Ord Sets','Boutique Dresses','Ethnic Dresses'];
$fabrics    = ['Pure Silk','Chikankari Cotton','Mulmul Cotton','Organza','Banarasi Brocade','Organic Linen'];
$occasions  = ['Wedding Collection','Festive Wear','Office Wear','Casual Wear','Party Wear'];
$sizes      = ['XS','S','M','L','XL','XXL','Free Size'];

$allProducts = [
  ['id'=>'est-001','name'=>'Gulzar Handcrafted Chikankari Anarkali Set',      'category'=>'Chikankari Kurtis','fabric'=>'Chikankari Cotton','occasion'=>'Festive Wear',       'price'=>1899,'oldPrice'=>2599,'discount'=>27,'rating'=>4.9,'reviewCount'=>42,'isNewArrival'=>true, 'isBestSeller'=>true, 'colors'=>[['name'=>'Antique Rose','hex'=>'#C87D87'],['name'=>'Bisque','hex'=>'#E5BCA9']],'sizes'=>['XS','S','M','L','XL','XXL'],'images'=>['/storage/products/est-001-chikankari-anarkali.jpg']],
  ['id'=>'est-002','name'=>'Varanasi Royal Zari Banarasi Silk Saree',         'category'=>'Banarasi Sarees',  'fabric'=>'Banarasi Brocade','occasion'=>'Wedding Collection',  'price'=>2499,'oldPrice'=>3499,'discount'=>29,'rating'=>5.0,'reviewCount'=>38,'isNewArrival'=>true, 'isBestSeller'=>true, 'colors'=>[['name'=>'Blush Pink','hex'=>'#F0C4CB'],['name'=>'Royal Emerald','hex'=>'#6B7556']],'sizes'=>['Free Size'],'images'=>['/storage/products/est-002-banarasi-saree.jpg']],
  ['id'=>'est-003','name'=>'Noor Hand-Painted Floral Organza Saree',          'category'=>'Organza Sarees',   'fabric'=>'Organza',         'occasion'=>'Party Wear',          'price'=>1699,'oldPrice'=>2299,'discount'=>26,'rating'=>4.8,'reviewCount'=>29,'isNewArrival'=>true, 'isBestSeller'=>false,'colors'=>[['name'=>'Champagne Gold','hex'=>'#FBEAD6']],'sizes'=>['Free Size'],'images'=>['/storage/products/est-003-organza-saree.jpg']],
  ['id'=>'est-004','name'=>'Raysha Silk Blend Printed Peplum Co-Ord Set',     'category'=>'Co-Ord Sets',      'fabric'=>'Pure Silk',        'occasion'=>'Casual Wear',         'price'=>1499,'oldPrice'=>1999,'discount'=>25,'rating'=>4.7,'reviewCount'=>31,'isNewArrival'=>false,'isBestSeller'=>true, 'colors'=>[['name'=>'Dried Thyme Green','hex'=>'#6B7556']],'sizes'=>['S','M','L','XL'],'images'=>['/storage/products/est-004-coord-set.jpg']],
  ['id'=>'est-005','name'=>'Aarya Hand Block Printed Cotton Straight Kurti',  'category'=>'Cotton Kurtis',    'fabric'=>'Mulmul Cotton',    'occasion'=>'Office Wear',         'price'=>1099,'oldPrice'=>1499,'discount'=>27,'rating'=>4.9,'reviewCount'=>54,'isNewArrival'=>true, 'isBestSeller'=>true, 'colors'=>[['name'=>'Sage Thyme','hex'=>'#6B7556'],['name'=>'Dusty Rose','hex'=>'#C87D87']],'sizes'=>['S','M','L','XL','XXL'],'images'=>['/storage/products/est-005-cotton-kurti.jpg']],
  ['id'=>'est-006','name'=>'Sultana Royal Zardozi Embroidered Silk Anarkali',  'category'=>'Anarkali Suits',   'fabric'=>'Pure Silk',        'occasion'=>'Wedding Collection',  'price'=>2399,'oldPrice'=>3299,'discount'=>27,'rating'=>5.0,'reviewCount'=>19,'isNewArrival'=>true, 'isBestSeller'=>false,'colors'=>[['name'=>'Antique Crimson','hex'=>'#A25964']],'sizes'=>['S','M','L','XL'],'images'=>['/storage/products/est-006-silk-anarkali.jpg']],
  ['id'=>'est-007','name'=>'Kashvi Chanderi Silk Foil Printed Boutique Dress', 'category'=>'Boutique Dresses', 'fabric'=>'Pure Silk',        'occasion'=>'Party Wear',          'price'=>1599,'oldPrice'=>2199,'discount'=>27,'rating'=>4.8,'reviewCount'=>33,'isNewArrival'=>false,'isBestSeller'=>true, 'colors'=>[['name'=>'Champagne Beige','hex'=>'#FBEAD6']],'sizes'=>['XS','S','M','L','XL'],'images'=>['/storage/products/est-007-boutique-dress.jpg']],
  ['id'=>'est-008','name'=>'Manjari Organic Handloom Linen Saree',             'category'=>'Linen Sarees',     'fabric'=>'Organic Linen',    'occasion'=>'Casual Wear',         'price'=>1299,'oldPrice'=>1799,'discount'=>28,'rating'=>4.7,'reviewCount'=>22,'isNewArrival'=>false,'isBestSeller'=>false,'colors'=>[['name'=>'Dried Thyme','hex'=>'#6B7556']],'sizes'=>['Free Size'],'images'=>['/storage/products/est-008-linen-saree.jpg']],
  ['id'=>'est-009','name'=>'Reeva Sequin Embroidered Georgette Designer Kurti','category'=>'Designer Kurtis',  'fabric'=>'Pure Silk',        'occasion'=>'Party Wear',          'price'=>1199,'oldPrice'=>1599,'discount'=>25,'rating'=>4.8,'reviewCount'=>36,'isNewArrival'=>true, 'isBestSeller'=>false,'colors'=>[['name'=>'Antique Rose','hex'=>'#C87D87']],'sizes'=>['S','M','L','XL'],'images'=>['/storage/products/est-009-designer-kurti.jpg']],
  ['id'=>'est-010','name'=>'Meera Handloom Mulmul Cotton Jamdani Saree',       'category'=>'Cotton Sarees',    'fabric'=>'Mulmul Cotton',    'occasion'=>'Office Wear',         'price'=>1799,'oldPrice'=>2399,'discount'=>25,'rating'=>4.9,'reviewCount'=>27,'isNewArrival'=>false,'isBestSeller'=>true, 'colors'=>[['name'=>'Champagne Ivory','hex'=>'#FBEAD6']],'sizes'=>['Free Size'],'images'=>['/storage/products/est-010-jamdani-saree.jpg']],
  ['id'=>'est-011','name'=>'Tarang Printed Angrakha Style Ethnic Dress',       'category'=>'Ethnic Dresses',   'fabric'=>'Chikankari Cotton','occasion'=>'Festive Wear',        'price'=>1399,'oldPrice'=>1899,'discount'=>26,'rating'=>4.9,'reviewCount'=>45,'isNewArrival'=>true, 'isBestSeller'=>false,'colors'=>[['name'=>'Thyme Green','hex'=>'#6B7556']],'sizes'=>['XS','S','M','L','XL'],'images'=>['/storage/products/est-011-ethnic-dress.jpg']],
  ['id'=>'est-012','name'=>'Bhavya Pure Kanjivaram Golden Zari Silk Saree',    'category'=>'Silk Sarees',      'fabric'=>'Pure Silk',        'occasion'=>'Wedding Collection',  'price'=>2299,'oldPrice'=>3199,'discount'=>28,'rating'=>5.0,'reviewCount'=>51,'isNewArrival'=>true, 'isBestSeller'=>true, 'colors'=>[['name'=>'Antique Crimson','hex'=>'#A25964']],'sizes'=>['Free Size'],'images'=>['/storage/products/est-012-kanjivaram-saree.jpg']],
];
@endphp

{{-- Pass products JSON to Alpine --}}
<div class="pb-16 sm:pb-20 pt-4 sm:pt-6"
    x-data="shopPage({{ json_encode($allProducts) }}, {{ json_encode(['categories'=>$categories,'fabrics'=>$fabrics,'occasions'=>$occasions,'sizes'=>$sizes]) }})"
    x-init="init()">

    {{-- Banner --}}
    <div class="bg-[var(--color-champagne-light)]/50 py-8 sm:py-12 mb-6 sm:mb-10 border-y border-[var(--color-bisque)]/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] uppercase tracking-[0.3em]">The Complete Atelier</span>
            <h1 class="font-serif text-2xl sm:text-5xl font-bold text-[var(--color-ebony)] mt-2">Women's Boutique Collection</h1>
            <p class="text-[11px] sm:text-sm font-sans text-[var(--color-ebony)]/70 mt-2 max-w-xl mx-auto">Discover handcrafted kurtis, silk sarees, anarkali suits, and modern ethnic co-ord sets tailored for the modern connoisseur.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Top Control Bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3 sm:gap-4 pb-4 sm:pb-6 mb-6 sm:mb-8 border-b border-[var(--color-bisque)]/60">
            
            <button @click="mobileFilterOpen = true" class="lg:hidden inline-flex items-center gap-2 bg-white border border-[var(--color-bisque)] px-4 py-2.5 rounded-full text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)] shadow-sm">
                <svg class="w-4 h-4 text-[var(--color-rose-antique)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                Filters
            </button>

            <div class="flex items-center gap-3 text-xs font-sans text-[var(--color-ebony)]/70">
                <span>Showing <strong class="text-[var(--color-ebony)]" x-text="filteredProducts.length"></strong> Products</span>
                <button x-show="selectedCategory || selectedFabric || selectedOccasion || selectedSizes.length > 0" style="display:none;" @click="clearFilters()" class="text-[var(--color-rose-antique)] hover:underline font-bold flex items-center gap-1 ml-2">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset Filters
                </button>
            </div>

            <div class="flex items-center gap-4 ml-auto">
                <div class="hidden md:flex items-center gap-1 border border-[var(--color-bisque)] rounded-full p-1 bg-white">
                    <button @click="gridColumns = 3" :class="gridColumns===3?'bg-[var(--color-ebony)] text-white':'text-[var(--color-ebony)]/60 hover:text-[var(--color-ebony)]'" class="p-1.5 rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button @click="gridColumns = 4" :class="gridColumns===4?'bg-[var(--color-ebony)] text-white':'text-[var(--color-ebony)]/60 hover:text-[var(--color-ebony)]'" class="p-1.5 rounded-full transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </button>
                </div>
                <div class="relative inline-block">
                    <select x-model="sortBy" class="bg-white border border-[var(--color-bisque)] rounded-full px-4 py-2.5 text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider focus:outline-none focus:border-[var(--color-rose-antique)] shadow-sm cursor-pointer pr-8 appearance-none">
                        <option value="featured">Sort by: Featured</option>
                        <option value="newest">Sort by: New Arrivals</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="rating">Customer Rating</option>
                    </select>
                    <svg class="absolute right-3 top-3 w-3 h-3 text-[var(--color-ebony)] pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>
        </div>

        {{-- Main Content Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            {{-- Desktop Sidebar Filters --}}
            <aside class="hidden lg:block space-y-8 pr-4">

                {{-- Category --}}
                <div class="space-y-3">
                    <h3 class="font-serif text-base font-bold text-[var(--color-ebony)] border-b border-[var(--color-bisque)] pb-2 uppercase tracking-wider">Categories</h3>
                    <div class="space-y-2 max-h-56 overflow-y-auto pr-2">
                        <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)] cursor-pointer hover:text-[var(--color-rose-antique)]">
                            <input type="radio" name="cat" value="" x-model="selectedCategory" class="accent-[#C87D87]" /><span>All Categories</span>
                        </label>
                        @foreach($categories as $cat)
                        <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)]/80 cursor-pointer hover:text-[var(--color-rose-antique)]">
                            <input type="radio" name="cat" value="{{ $cat }}" x-model="selectedCategory" class="accent-[#C87D87]" /><span>{{ $cat }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Fabric --}}
                <div class="space-y-3">
                    <h3 class="font-serif text-base font-bold text-[var(--color-ebony)] border-b border-[var(--color-bisque)] pb-2 uppercase tracking-wider">Fabric</h3>
                    <div class="space-y-2">
                        @foreach($fabrics as $fab)
                        <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)]/80 cursor-pointer hover:text-[var(--color-rose-antique)]">
                            <input type="checkbox" value="{{ $fab }}" @change="selectedFabric = $event.target.checked ? '{{ $fab }}' : (selectedFabric === '{{ $fab }}' ? '' : selectedFabric)" :checked="selectedFabric === '{{ $fab }}'" class="accent-[#C87D87] rounded" /><span>{{ $fab }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Occasion --}}
                <div class="space-y-3">
                    <h3 class="font-serif text-base font-bold text-[var(--color-ebony)] border-b border-[var(--color-bisque)] pb-2 uppercase tracking-wider">Occasion</h3>
                    <div class="space-y-2">
                        @foreach($occasions as $occ)
                        <label class="flex items-center gap-2.5 text-xs font-sans text-[var(--color-ebony)]/80 cursor-pointer hover:text-[var(--color-rose-antique)]">
                            <input type="checkbox" value="{{ $occ }}" @change="selectedOccasion = $event.target.checked ? '{{ $occ }}' : (selectedOccasion === '{{ $occ }}' ? '' : selectedOccasion)" :checked="selectedOccasion === '{{ $occ }}'" class="accent-[#C87D87] rounded" /><span>{{ $occ }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Size --}}
                <div class="space-y-3">
                    <h3 class="font-serif text-base font-bold text-[var(--color-ebony)] border-b border-[var(--color-bisque)] pb-2 uppercase tracking-wider">Size</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $sz)
                        <button type="button" @click="toggleSize('{{ $sz }}')" :class="selectedSizes.includes('{{ $sz }}') ? 'bg-[var(--color-ebony)] text-white border-[var(--color-ebony)]' : 'bg-white text-[var(--color-ebony)] border-[var(--color-bisque)] hover:border-[var(--color-rose-antique)]'" class="px-3 py-1.5 rounded-lg text-xs font-sans font-semibold border transition-all">{{ $sz }}</button>
                        @endforeach
                    </div>
                </div>

                {{-- Price Slider --}}
                <div class="space-y-3">
                    <div class="flex justify-between text-xs font-sans font-bold text-[var(--color-ebony)]">
                        <span>Max Price:</span>
                        <span class="text-[var(--color-rose-antique)]" x-text="'₹' + Number(priceRange).toLocaleString('en-IN')"></span>
                    </div>
                    <input type="range" min="1000" max="25000" step="500" x-model="priceRange" class="w-full accent-[#C87D87] cursor-pointer" />
                </div>

            </aside>

            {{-- Product Grid --}}
            <main class="lg:col-span-3">
                <div x-show="filteredProducts.length > 0" :class="gridColumns === 4 ? 'grid-cols-2 lg:grid-cols-3 xl:grid-cols-3' : 'grid-cols-2 lg:grid-cols-2'" class="grid gap-3 sm:gap-6">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-[0_20px_60px_rgba(200,125,135,0.2)] transition-all duration-500 border border-[rgba(229,188,169,0.3)] flex flex-col" x-data="{ wishlisted: false }">
                            <div class="relative overflow-hidden bg-[rgba(251,234,214,0.3)]" style="aspect-ratio:3/4;">
                                <a :href="'/product/' + product.id" class="block w-full h-full">
                                    <img :src="product.images[0]" :alt="product.name" class="w-full h-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy" />
                                </a>
                                {{-- Badges --}}
                                <div class="absolute top-2 sm:top-3 left-2 sm:left-3 flex flex-col gap-1 sm:gap-1.5 z-10 pointer-events-none">
                                    <span x-show="product.isNewArrival" class="bg-[#1A1818] text-white font-sans text-[8px] sm:text-[10px] uppercase font-bold tracking-widest px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">New</span>
                                    <span x-show="product.discount > 0" class="bg-[#C87D87] text-white font-sans text-[8px] sm:text-[10px] uppercase font-bold tracking-widest px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md" x-text="product.discount + '% OFF'"></span>
                                    <span x-show="product.isBestSeller && !product.isNewArrival" class="bg-[#6B7556] text-white font-sans text-[8px] sm:text-[10px] uppercase font-bold tracking-widest px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">Best Seller</span>
                                </div>
                                {{-- Wishlist --}}
                                <button @click.prevent="wishlisted = !wishlisted" class="absolute top-2 sm:top-3 right-2 sm:right-3 z-10 w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-white/90 backdrop-blur-md shadow-md flex items-center justify-center text-[#1A1818] hover:text-[#C87D87] hover:scale-110 transition-all duration-300">
                                    <svg :class="wishlisted ? 'fill-[#C87D87] text-[#C87D87]' : 'fill-none'" class="w-3 h-3 sm:w-4 sm:h-4" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                </button>
                                {{-- Quick View hover --}}
                                <div class="absolute bottom-3 left-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0 flex items-center gap-2">
                                    <a :href="'/product/' + product.id" class="flex-1 bg-white/90 hover:bg-white text-[#1A1818] font-sans text-xs font-semibold py-2.5 px-3 rounded-full backdrop-blur-md shadow-lg flex items-center justify-center gap-1.5 transition-all hover:text-[#C87D87]">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Quick View
                                    </a>
                                    <button class="w-10 h-10 rounded-full bg-[#1A1818] hover:bg-[#C87D87] text-white flex items-center justify-center shadow-lg transition-colors flex-shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    </button>
                                </div>
                            </div>
                            {{-- Info --}}
                            <div class="p-2.5 sm:p-4 flex-1 flex flex-col justify-between space-y-2 sm:space-y-3">
                                <div>
                                    <div class="flex items-center justify-between text-[9px] sm:text-[11px] font-sans mb-0.5 sm:mb-1">
                                        <span class="uppercase tracking-wider font-medium text-[#C87D87] truncate mr-1" x-text="product.category"></span>
                                        <div class="flex items-center gap-0.5 sm:gap-1 flex-shrink-0">
                                            <svg class="fill-amber-500 w-3 h-3" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                            <span class="font-semibold text-[#1A1818]" x-text="product.rating"></span>
                                            <span class="text-[#1A1818]/40 hidden sm:inline" x-text="'(' + product.reviewCount + ')'"></span>
                                        </div>
                                    </div>
                                    <a :href="'/product/' + product.id" class="block">
                                        <h3 class="font-serif text-xs sm:text-base font-bold text-[#1A1818] group-hover:text-[#C87D87] transition-colors line-clamp-1" x-text="product.name"></h3>
                                    </a>
                                </div>
                                {{-- Color dots --}}
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center gap-1">
                                        <template x-for="(c, ci) in product.colors.slice(0,3)" :key="ci">
                                            <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full border border-black/20 inline-block" :style="'background-color:' + c.hex" :title="c.name"></span>
                                        </template>
                                        <span x-show="product.colors.length > 3" class="text-[9px] sm:text-[10px] text-[#1A1818]/50 font-sans" x-text="'+' + (product.colors.length - 3)"></span>
                                    </div>
                                    <div class="hidden sm:flex items-center gap-1 text-[10px] font-sans font-semibold text-[#1A1818]/60">
                                        <template x-for="(s, si) in product.sizes.slice(0,4)" :key="si">
                                            <span class="bg-[#FFF9F5] px-1.5 py-0.5 rounded border border-[rgba(229,188,169,0.5)]" x-text="s"></span>
                                        </template>
                                    </div>
                                </div>
                                {{-- Price --}}
                                <div class="flex items-baseline gap-1 sm:gap-2 pt-1.5 sm:pt-2 border-t border-[rgba(229,188,169,0.3)]">
                                    <span class="font-serif text-sm sm:text-lg font-bold text-[#1A1818]" x-text="'₹' + product.price.toLocaleString('en-IN')"></span>
                                    <span x-show="product.oldPrice" class="font-sans text-[10px] sm:text-xs text-[#1A1818]/40 line-through" x-text="'₹' + product.oldPrice.toLocaleString('en-IN')"></span>
                                    <span x-show="product.discount > 0" class="font-sans text-[9px] sm:text-[11px] font-bold text-[#6B7556] ml-auto" x-text="'Save ₹' + (product.oldPrice - product.price).toLocaleString('en-IN')"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="filteredProducts.length === 0" style="display:none;" class="text-center py-20 bg-white rounded-3xl border border-[var(--color-bisque)]/50 p-8 space-y-4">
                    <h3 class="font-serif text-2xl font-bold text-[var(--color-ebony)]">No Matching Outfits Found</h3>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60 max-w-md mx-auto">Try adjusting your filter options or clear all filters to explore our full boutique collection.</p>
                    <button @click="clearFilters()" class="bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-full hover:bg-[var(--color-rose-deep)] transition-colors">Clear All Filters</button>
                </div>
            </main>
        </div>
    </div>

    {{-- Mobile Filter Drawer --}}
    <div x-show="mobileFilterOpen" style="display:none;">
        <div @click="mobileFilterOpen = false" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 backdrop-blur-sm"></div>
        <aside x-show="mobileFilterOpen"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
            class="fixed top-0 right-0 bottom-0 w-[85%] max-w-sm bg-white z-50 shadow-2xl overflow-y-auto p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-[var(--color-bisque)] pb-4">
                <h3 class="font-serif text-xl font-bold text-[var(--color-ebony)]">Filter & Sort</h3>
                <button @click="mobileFilterOpen = false" class="p-2 hover:text-[var(--color-rose-antique)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            {{-- Same filters repeated for mobile --}}
            <div class="space-y-3">
                <h3 class="font-serif text-sm font-bold text-[var(--color-ebony)] uppercase tracking-wider">Categories</h3>
                <div class="space-y-2 max-h-40 overflow-y-auto">
                    <label class="flex items-center gap-2 text-xs font-sans cursor-pointer"><input type="radio" name="mcat" value="" x-model="selectedCategory" class="accent-[#C87D87]" /><span>All</span></label>
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-2 text-xs font-sans cursor-pointer"><input type="radio" name="mcat" value="{{ $cat }}" x-model="selectedCategory" class="accent-[#C87D87]" /><span>{{ $cat }}</span></label>
                    @endforeach
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach($sizes as $sz)
                <button type="button" @click="toggleSize('{{ $sz }}')" :class="selectedSizes.includes('{{ $sz }}') ? 'bg-[var(--color-ebony)] text-white border-[var(--color-ebony)]' : 'bg-white text-[var(--color-ebony)] border-[var(--color-bisque)]'" class="px-3 py-1.5 rounded-lg text-xs font-sans font-semibold border transition-all">{{ $sz }}</button>
                @endforeach
            </div>
            <button @click="mobileFilterOpen = false; clearFilters()" class="w-full bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest py-3 rounded-full">Clear & Close</button>
        </aside>
    </div>
</div>

<script>
function shopPage(products, meta) {
    return {
        products,
        meta,
        selectedCategory: new URLSearchParams(window.location.search).get('category') || '',
        selectedFabric:   new URLSearchParams(window.location.search).get('fabric') || '',
        selectedOccasion: new URLSearchParams(window.location.search).get('occasion') || '',
        selectedSizes:    [],
        priceRange:       25000,
        sortBy:           new URLSearchParams(window.location.search).get('filter') === 'new' ? 'newest' : 'featured',
        mobileFilterOpen: false,
        gridColumns:      3,

        init() { /* ready */ },

        toggleSize(s) {
            this.selectedSizes.includes(s)
                ? this.selectedSizes = this.selectedSizes.filter(x => x !== s)
                : this.selectedSizes.push(s);
        },

        clearFilters() {
            this.selectedCategory = '';
            this.selectedFabric   = '';
            this.selectedOccasion = '';
            this.selectedSizes    = [];
            this.priceRange       = 25000;
        },

        get filteredProducts() {
            return this.products.filter(p => {
                if (this.selectedCategory && !p.category.toLowerCase().includes(this.selectedCategory.toLowerCase())) return false;
                if (this.selectedFabric   && p.fabric   !== this.selectedFabric)   return false;
                if (this.selectedOccasion && p.occasion !== this.selectedOccasion) return false;
                if (this.selectedSizes.length && !p.sizes.some(s => this.selectedSizes.includes(s))) return false;
                if (p.price > this.priceRange) return false;
                return true;
            }).sort((a, b) => {
                if (this.sortBy === 'price-low')  return a.price - b.price;
                if (this.sortBy === 'price-high') return b.price - a.price;
                if (this.sortBy === 'rating')     return b.rating - a.rating;
                if (this.sortBy === 'newest')     return (b.isNewArrival ? 1 : 0) - (a.isNewArrival ? 1 : 0);
                return 0;
            });
        }
    }
}
</script>

@endsection
