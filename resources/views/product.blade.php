@extends('layouts.app')

@php
$productsData = [
  [
    'id' => 'est-001', 'name' => 'Gulzar Handcrafted Chikankari Anarkali Set', 'category' => 'Chikankari Kurtis', 'mainCategory' => 'Kurtis', 'fabric' => 'Chikankari Cotton', 'occasion' => 'Festive Wear', 'price' => 1899, 'oldPrice' => 2599, 'discount' => 27, 'rating' => 4.9, 'reviewCount' => 42, 'isNewArrival' => true, 'sku' => 'EST-GUL-001', 'colors' => [ ['name' => 'Antique Rose', 'hex' => '#C87D87'], ['name' => 'Bisque', 'hex' => '#E5BCA9'], ['name' => 'Pure Ivory', 'hex' => '#FFF9F5'] ], 'sizes' => ['XS', 'S', 'M', 'L', 'XL', 'XXL'], 'description' => 'Imbued with royal Nawabi elegance, our Gulzar Anarkali Set is meticulously hand-embroidered by artisan women in Lucknow.', 'details' => ['Fabric: 100% Breathable Cotton Mulmul', 'Work: Handcrafted Lucknowi Chikankari & Mukaish Sequins', 'Includes: Anarkali Kurta, Churidar & Chiffon Dupatta'], 'care' => 'Dry Clean Only. Cool Iron on reverse side.', 'images' => ['/storage/products/est-001-chikankari-anarkali.jpg', '/storage/products/est-006-silk-anarkali.jpg']
  ],
  [
    'id' => 'est-002', 'name' => 'Varanasi Royal Zari Banarasi Silk Saree', 'category' => 'Banarasi Sarees', 'mainCategory' => 'Sarees', 'fabric' => 'Banarasi Brocade', 'occasion' => 'Wedding Collection', 'price' => 2499, 'oldPrice' => 3499, 'discount' => 29, 'rating' => 5.0, 'reviewCount' => 38, 'isNewArrival' => true, 'sku' => 'EST-BAN-002', 'colors' => [ ['name' => 'Blush Pink', 'hex' => '#F0C4CB'], ['name' => 'Royal Emerald', 'hex' => '#6B7556'], ['name' => 'Crimson Red', 'hex' => '#A25964'] ], 'sizes' => ['Free Size'], 'description' => 'A masterpiece from the heritage looms of Varanasi. Handwoven in pure Katan silk with gold electro-plated Kadwa weaves.', 'details' => ['Saree Fabric: Pure Katan Silk', 'Blouse Piece: Included (Unstitched 80cm Brocade)', 'Weave: Kadwa Handloom Zari Weave'], 'care' => 'Dry Clean Only. Preserve wrapped in pure cotton muslin cloth.', 'images' => ['/storage/products/est-002-banarasi-saree.jpg', '/storage/products/est-012-kanjivaram-saree.jpg']
  ],
  [
    'id' => 'est-003', 'name' => 'Noor Hand-Painted Floral Organza Saree', 'category' => 'Organza Sarees', 'mainCategory' => 'Sarees', 'fabric' => 'Organza', 'occasion' => 'Party Wear', 'price' => 1699, 'oldPrice' => 2299, 'discount' => 26, 'rating' => 4.8, 'reviewCount' => 29, 'isNewArrival' => true, 'sku' => 'EST-ORG-003', 'colors' => [ ['name' => 'Champagne Gold', 'hex' => '#FBEAD6'], ['name' => 'Rose Blush', 'hex' => '#F0C4CB'] ], 'sizes' => ['Free Size'], 'description' => 'Ethereal and whisper-light. Hand-painted botanical motifs gracefully flow across pure sheer organza fabric.', 'details' => ['Fabric: 100% Sheer Mulberry Organza Silk', 'Work: Artisan Hand-painting & Scalloped Gota Edge'], 'care' => 'Dry Clean Only.', 'images' => ['/storage/products/est-003-organza-saree.jpg', '/storage/products/est-002-banarasi-saree.jpg']
  ],
  [
    'id' => 'est-004', 'name' => 'Raysha Silk Blend Printed Peplum Co-Ord Set', 'category' => 'Co-Ord Sets', 'mainCategory' => 'Co-Ord Sets', 'fabric' => 'Pure Silk', 'occasion' => 'Casual Wear', 'price' => 1499, 'oldPrice' => 1999, 'discount' => 25, 'rating' => 4.7, 'reviewCount' => 31, 'isNewArrival' => false, 'sku' => 'EST-COO-004', 'colors' => [ ['name' => 'Dried Thyme Green', 'hex' => '#6B7556'], ['name' => 'Bisque Sand', 'hex' => '#E5BCA9'] ], 'sizes' => ['S', 'M', 'L', 'XL'], 'description' => 'Designed for the modern woman who craves statement elegance.', 'details' => ['Fabric: Premium Soft Art Silk Blend', 'Set Includes: Peplum Tunic Top & Straight Fit Pants'], 'care' => 'Gentle Hand Wash or Dry Clean.', 'images' => ['/storage/products/est-004-coord-set.jpg']
  ],
  [
    'id' => 'est-005', 'name' => 'Aarya Hand Block Printed Cotton Straight Kurti', 'category' => 'Cotton Kurtis', 'mainCategory' => 'Kurtis', 'fabric' => 'Mulmul Cotton', 'occasion' => 'Office Wear', 'price' => 1099, 'oldPrice' => 1499, 'discount' => 27, 'rating' => 4.9, 'reviewCount' => 54, 'isNewArrival' => true, 'sku' => 'EST-AAR-005', 'colors' => [ ['name' => 'Sage Thyme', 'hex' => '#6B7556'], ['name' => 'Dusty Rose', 'hex' => '#C87D87'] ], 'sizes' => ['S', 'M', 'L', 'XL', 'XXL'], 'description' => 'Classic hand block printed cotton kurti perfect for work and daily comfort.', 'details' => ['Fabric: 100% Breathable Mulmul Cotton', 'Work: Bagru Hand Block Print'], 'care' => 'Machine Wash Cold.', 'images' => ['/storage/products/est-005-cotton-kurti.jpg']
  ],
  [
    'id' => 'est-006', 'name' => 'Sultana Royal Zardozi Embroidered Silk Anarkali', 'category' => 'Anarkali Suits', 'mainCategory' => 'Kurtis', 'fabric' => 'Pure Silk', 'occasion' => 'Wedding Collection', 'price' => 2399, 'oldPrice' => 3299, 'discount' => 27, 'rating' => 5.0, 'reviewCount' => 19, 'isNewArrival' => true, 'sku' => 'EST-SUL-006', 'colors' => [ ['name' => 'Antique Crimson', 'hex' => '#A25964'] ], 'sizes' => ['S', 'M', 'L', 'XL'], 'description' => 'Opulent royal zardozi embroidery on pure silk for grand celebrations.', 'details' => ['Fabric: Raw Silk', 'Work: Zardozi & Mukaish Work'], 'care' => 'Dry Clean Only.', 'images' => ['/storage/products/est-006-silk-anarkali.jpg']
  ]
];

$dbProduct = \App\Models\Product::where('est_id', $id)->orWhere('id', $id)->first();
$referralCode = session('referral_code') ?? request('ref');
$associate = null;
if ($referralCode) {
    $associate = \App\Models\User::where('referral_code', strtoupper($referralCode))->first();
}

if ($dbProduct) {
    $effectivePrice = ($referralCode && $dbProduct->sales_price && $dbProduct->sales_price > 0)
        ? (float) $dbProduct->sales_price
        : ($referralCode ? (float) ($dbProduct->price + 50) : (float) $dbProduct->price);

    $product = [
        'id'           => $dbProduct->est_id,
        'name'         => $dbProduct->name,
        'category'     => $dbProduct->category,
        'mainCategory' => $dbProduct->main_category,
        'fabric'       => $dbProduct->fabric,
        'occasion'     => $dbProduct->occasion,
        'price'        => $effectivePrice,
        'basePrice'    => (float) $dbProduct->price,
        'salesPrice'   => (float) ($dbProduct->sales_price ?: ($dbProduct->price + 50)),
        'oldPrice'     => (float) $dbProduct->old_price,
        'discount'     => (int) $dbProduct->discount,
        'rating'       => (float) $dbProduct->rating,
        'reviewCount'  => (int) $dbProduct->review_count,
        'isNewArrival' => (bool) $dbProduct->is_new_arrival,
        'sku'          => $dbProduct->sku,
        'colors'       => is_array($dbProduct->colors) ? $dbProduct->colors : [['name' => 'Royal Hue', 'hex' => '#C87D87']],
        'sizes'        => is_array($dbProduct->sizes) ? $dbProduct->sizes : ['XS', 'S', 'M', 'L', 'XL', 'XXL'],
        'description'  => $dbProduct->description,
        'details'      => is_array($dbProduct->details) ? $dbProduct->details : ['Craft: Handloom Artisanal', 'Origin: Lucknow / Varanasi'],
        'care'         => $dbProduct->care ?? 'Dry Clean Only.',
        'images'       => is_array($dbProduct->images) ? $dbProduct->images : ['/storage/hero/hero-main.jpg'],
        'isReferral'   => !empty($referralCode),
        'referralCode' => $referralCode,
        'associateName'=> $associate ? $associate->name : null,
    ];
} else {
    $rawProduct = collect($productsData)->firstWhere('id', $id) ?? $productsData[0];
    $effectivePrice = $referralCode ? ($rawProduct['price'] + 50) : $rawProduct['price'];
    $product = array_merge($rawProduct, [
        'price'        => $effectivePrice,
        'basePrice'    => $rawProduct['price'],
        'salesPrice'   => $rawProduct['price'] + 50,
        'isReferral'   => !empty($referralCode),
        'referralCode' => $referralCode,
        'associateName'=> $associate ? $associate->name : null,
    ]);
}

$relatedProducts = collect($productsData)->filter(function ($p) use ($product) {
    return $p['id'] !== $product['id'] && ($p['mainCategory'] ?? '') === ($product['mainCategory'] ?? '');
})->take(4);

@endphp

@section('title', $product['name'] . ' | ESTILO WEAR')

@section('content')

<div class="pb-16 sm:pb-24 pt-4 sm:pt-6" x-data="{
    product: {{ json_encode($product) }},
    activeImageIndex: 0,
    selectedColor: '{{ $product['colors'][0]['name'] ?? '' }}',
    selectedSize: '{{ $product['sizes'][0] ?? 'M' }}',
    quantity: 1,
    pincode: '',
    pincodeMsg: null,
    isWishlisted: false,
    
    checkPincode() {
        if(this.pincode.length === 6) {
            this.pincodeMsg = 'Express delivery available to ' + this.pincode + ' by ' + new Date(Date.now() + 86400000 * 3).toDateString();
        } else {
            this.pincodeMsg = 'Please enter a valid 6-digit PIN code.';
        }
    },
    copyLink() {
        navigator.clipboard.writeText(window.location.href);
        alert('Product link copied to clipboard!');
    }
}">

    <!-- Breadcrumb Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <nav class="text-xs font-sans text-[var(--color-ebony)]/60 flex items-center gap-2">
            <a href="/" class="hover:text-[var(--color-rose-antique)]">Home</a>
            <span>/</span>
            <a href="/shop?category={{ urlencode($product['category']) }}" class="hover:text-[var(--color-rose-antique)]">{{ $product['category'] }}</a>
            <span>/</span>
            <span class="text-[var(--color-ebony)] font-bold truncate max-w-xs">{{ $product['name'] }}</span>
        </nav>
    </div>

    <!-- Main Product Layout -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14">
            
            <!-- Left Column: Image Gallery -->
            <div class="space-y-4">
                <div class="relative aspect-[3/4] w-full rounded-3xl overflow-hidden bg-[var(--color-champagne-light)]/30 shadow-sm border border-[var(--color-bisque)]/50">
                    <img :src="product.images[activeImageIndex]" :alt="product.name" class="w-full h-full object-cover object-top transition-all duration-500" />
                    
                    <div class="absolute top-4 left-4 flex flex-col gap-2 z-10">
                        @if($product['isNewArrival'])
                            <span class="bg-[var(--color-ebony)] text-white text-[10px] font-sans font-bold uppercase tracking-widest px-3 py-1 rounded-full">New Arrival</span>
                        @endif
                        @if(isset($product['discount']) && $product['discount'] > 0)
                            <span class="bg-[var(--color-rose-antique)] text-white text-[10px] font-sans font-bold uppercase tracking-widest px-3 py-1 rounded-full">{{ $product['discount'] }}% OFF</span>
                        @endif
                    </div>

                    <button @click="copyLink" class="absolute top-4 right-4 z-10 w-10 h-10 rounded-full bg-white/90 shadow-md flex items-center justify-center text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors" title="Share Outfit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                    </button>
                </div>

                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    <template x-for="(img, idx) in product.images" :key="idx">
                        <button @click="activeImageIndex = idx" :class="activeImageIndex === idx ? 'border-[var(--color-rose-antique)] scale-105 shadow-md' : 'border-transparent opacity-70'" class="w-20 h-24 rounded-xl overflow-hidden border-2 transition-all flex-shrink-0">
                            <img :src="img" alt="" class="w-full h-full object-cover object-top" />
                        </button>
                    </template>
                </div>
            </div>

            <!-- Right Column: Product Information -->
            <div class="space-y-6 flex flex-col justify-between">
                <div class="space-y-4">
                    
                    <div class="flex items-center justify-between text-xs font-sans">
                        <span class="text-[var(--color-rose-antique)] font-bold uppercase tracking-widest">{{ $product['category'] }} • SKU: {{ $product['sku'] ?? 'EST-892' }}</span>
                        <div class="flex items-center gap-1.5 text-[var(--color-ebony)]">
                            <div class="flex text-amber-500">
                                @for($i = 0; $i < 5; $i++)
                                <svg class="w-3 h-3 fill-amber-500" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                @endfor
                            </div>
                            <span class="font-bold text-xs">{{ $product['rating'] }}</span>
                            <span class="text-[var(--color-ebony)]/50">({{ $product['reviewCount'] }} Reviews)</span>
                        </div>
                    </div>

                    <h1 class="font-serif text-2xl sm:text-4xl font-bold text-[var(--color-ebony)] leading-tight">{{ $product['name'] }}</h1>

                    @if(!empty($product['isReferral']))
                    <div class="bg-amber-50 border border-amber-200/80 rounded-2xl p-3.5 flex items-center justify-between gap-3 shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-900 flex items-center justify-center font-bold text-sm shrink-0">✦</span>
                            <div>
                                <span class="block text-[11px] font-sans font-bold text-amber-900">Stylist Curated Link Active</span>
                                <p class="text-[10px] font-sans text-amber-800/80">
                                    Special partner collection verified by <strong>{{ $product['associateName'] ?? 'Estilo Associate' }}</strong> ({{ $product['referralCode'] }}).
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] font-sans font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full shrink-0">Verified</span>
                    </div>
                    @endif

                    <div class="flex items-baseline gap-4 pt-1">
                        <span class="font-serif text-3xl font-bold text-[var(--color-ebony)]">₹{{ number_format($product['price']) }}</span>
                        @if(isset($product['oldPrice']))
                            <span class="font-sans text-sm text-[var(--color-ebony)]/40 line-through">₹{{ number_format($product['oldPrice']) }}</span>
                        @endif
                        @if(isset($product['discount']) && $product['discount'] > 0)
                            <span class="bg-[var(--color-rose-antique)]/10 text-[var(--color-rose-deep)] font-sans text-xs font-bold px-3 py-1 rounded-full uppercase">You Save ₹{{ number_format($product['oldPrice'] - $product['price']) }} ({{ $product['discount'] }}% OFF)</span>
                        @endif
                    </div>

                    <p class="text-xs sm:text-sm font-sans text-[var(--color-ebony)]/80 leading-relaxed font-light">{{ $product['description'] }}</p>

                    @if(isset($product['colors']) && count($product['colors']) > 0)
                    <div class="pt-2">
                        <span class="block text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider mb-2">Color: <strong class="text-[var(--color-rose-antique)]" x-text="selectedColor"></strong></span>
                        <div class="flex items-center gap-3">
                            <template x-for="c in product.colors" :key="c.name">
                                <button @click="selectedColor = c.name" :class="selectedColor === c.name ? 'border-[var(--color-ebony)] scale-110 shadow-sm' : 'border-transparent'" :style="'background-color: ' + c.hex" class="w-9 h-9 rounded-full border-2 flex items-center justify-center transition-all" :title="c.name">
                                    <svg x-show="selectedColor === c.name" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </template>
                        </div>
                    </div>
                    @endif

                    @php
                        $isFreeSize = (isset($product['sizes']) && count($product['sizes']) === 1 && in_array(strtolower($product['sizes'][0]), ['free size', 'one size', 'unstitched']))
                                     || (isset($product['category']) && str_contains(strtolower($product['category']), 'saree'));
                    @endphp

                    @if(isset($product['sizes']) && count($product['sizes']) > 0)
                    <div class="pt-2">
                        @if($isFreeSize)
                        {{-- 🥻 Free Size / Saree Display Card --}}
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider flex items-center gap-1.5">
                                    <span>🥻 Garment Size:</span>
                                    <span class="text-[var(--color-rose-antique)] font-bold">Free Size / Universal Fit</span>
                                </span>
                                <span class="text-[10px] font-sans font-bold uppercase tracking-wider text-amber-800 bg-amber-100 border border-amber-300 px-2.5 py-0.5 rounded-full">One Size</span>
                            </div>
                            <div class="p-3.5 bg-gradient-to-r from-[var(--color-champagne-light)]/40 to-pink-50/30 rounded-2xl border border-[var(--color-bisque)] flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-white border border-[var(--color-bisque)] shadow-xs flex items-center justify-center text-base shrink-0">✨</span>
                                <div class="text-xs font-sans text-[var(--color-ebony)]/90 space-y-0.5">
                                    <p class="font-bold">Universal Standard Dimensions</p>
                                    <p class="text-[11px] text-gray-600">Standard 5.5 Meter Handloom Saree Length + 0.8 Meter Unstitched Matching Blouse Piece included.</p>
                                </div>
                            </div>
                        </div>
                        @else
                        {{-- 👗 Standard Apparel Sizes Selection (XS, S, M, L, XL, XXL) --}}
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">
                                Select Size: <span class="text-[var(--color-rose-antique)] font-bold" x-text="selectedSize"></span>
                            </span>
                            <button type="button" @click="$store.shop.isSizeGuideOpen = true" class="text-xs font-sans text-[var(--color-rose-antique)] hover:underline font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                Size Guide
                            </button>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            <template x-for="s in product.sizes" :key="s">
                                <button @click="selectedSize = s" :class="selectedSize === s ? 'bg-[var(--color-ebony)] text-white border-[var(--color-ebony)] shadow-md' : 'bg-white text-[var(--color-ebony)] border-[var(--color-bisque)] hover:border-[var(--color-rose-antique)]'" class="px-5 py-2.5 rounded-xl text-xs font-sans font-bold border transition-all" x-text="s"></button>
                            </template>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="bg-[var(--color-champagne-light)]/50 p-4 rounded-2xl border border-[var(--color-bisque)]/60 space-y-2">
                        <span class="text-xs font-sans font-bold text-[var(--color-ebony)] flex items-center gap-1.5 uppercase tracking-wider">
                            <svg class="w-4 h-4 text-[var(--color-rose-antique)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Check Express Delivery
                        </span>
                        <form @submit.prevent="checkPincode" class="flex gap-2">
                            <input type="text" maxlength="6" x-model="pincode" placeholder="Enter 6-digit PIN code" class="flex-1 bg-white border border-[var(--color-bisque)] rounded-xl px-3 py-2 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                            <button type="submit" class="bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-xl hover:bg-[var(--color-rose-deep)] transition-colors">Check</button>
                        </form>
                        <p x-show="pincodeMsg" x-text="pincodeMsg" class="text-[11px] font-sans text-green-700 font-bold pt-1"></p>
                    </div>

                    <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 sm:gap-4 pt-2">
                        <div class="flex items-center border border-[var(--color-bisque)] rounded-full px-3 py-3 bg-white w-fit">
                            <button @click="quantity = Math.max(1, quantity - 1)" class="text-xs font-bold px-2 hover:text-[var(--color-rose-antique)]">-</button>
                            <span class="text-xs font-sans font-bold px-3" x-text="quantity"></span>
                            <button @click="quantity++" class="text-xs font-bold px-2 hover:text-[var(--color-rose-antique)]">+</button>
                        </div>

                        <button @click="$store.shop.addToCart({ id: product.id, name: product.name, price: product.price, image: product.images[0], color: selectedColor, size: selectedSize, qty: quantity, category: product.category })" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-full shadow-[var(--shadow-floating)] transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg> Add to Shopping Bag
                        </button>

                        <button @click="$store.shop.toggleWishlist({ id: product.id, name: product.name, price: product.price, image: product.images[0], category: product.category })" class="w-14 h-14 rounded-full border border-[var(--color-bisque)] bg-white flex items-center justify-center text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors shadow-sm flex-shrink-0">
                            <svg :class="$store.shop.isInWishlist(product.id) ? 'fill-[var(--color-rose-antique)] text-[var(--color-rose-antique)]' : 'fill-none'" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </div>


                </div>

                <div class="bg-white p-6 rounded-3xl border border-[var(--color-bisque)]/60 space-y-3 font-sans text-xs">
                    <h3 class="font-serif text-base font-bold text-[var(--color-ebony)] uppercase tracking-wider border-b border-[var(--color-bisque)]/40 pb-2">Craftsmanship & Product Details</h3>
                    <ul class="space-y-2 text-[var(--color-ebony)]/80 list-disc pl-4">
                        @if(isset($product['details']))
                            @foreach($product['details'] as $detail)
                                <li>{{ $detail }}</li>
                            @endforeach
                        @endif
                    </ul>
                    <p class="pt-2 text-[var(--color-ebony)]/60 border-t border-[var(--color-bisque)]/30"><strong>Care Instructions:</strong> {{ $product['care'] ?? 'Dry Clean Only.' }}</p>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
