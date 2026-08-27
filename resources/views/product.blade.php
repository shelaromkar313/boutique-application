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

// Load Customer Reviews from DB
$reviews = \App\Models\Review::where('product_est_id', $product['id'])->where('is_approved', true)->latest()->get();
$totalReviewsCount = $reviews->count() > 0 ? $reviews->count() : ($product['reviewCount'] ?? 1);
$avgRating = $reviews->count() > 0 ? round($reviews->avg('rating'), 1) : ($product['rating'] ?? 5.0);

$ratingBreakdown = [
    5 => $reviews->where('rating', 5)->count(),
    4 => $reviews->where('rating', 4)->count(),
    3 => $reviews->where('rating', 3)->count(),
    2 => $reviews->where('rating', 2)->count(),
    1 => $reviews->where('rating', 1)->count(),
];

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
    showReviewForm: false,
    newRating: 5,
    hoverRating: 0,
    
    get starLabel() {
        const r = this.hoverRating || this.newRating;
        const labels = {
            1: '1 Star — Needs Improvement',
            2: '2 Stars — Fair Quality',
            3: '3 Stars — Good & Comfortable',
            4: '4 Stars — Very Beautiful Fitting',
            5: '5 Stars — Royal, Exceptional & Flawless!'
        };
        return labels[r] || '5 Stars';
    },

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

                    @if(isset($product['sizes']) && count($product['sizes']) > 0)
                    <div class="pt-2">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-sans font-bold text-[var(--color-ebony)] uppercase tracking-wider">Select Size</span>
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

                    <!-- AI Virtual Try-On Banner & Trigger Button -->
                    <div class="pt-2">
                        <button type="button" 
                                @click="$dispatch('open-tryon', { id: product.id, name: product.name, price: product.price, image: product.images[activeImageIndex], category: product.category })" 
                                class="w-full bg-gradient-to-r from-[var(--color-champagne-light)] via-[var(--color-bisque)]/40 to-[var(--color-champagne-light)] hover:from-[var(--color-bisque)]/60 hover:to-[var(--color-bisque)]/60 text-[var(--color-ebony)] border border-[var(--color-rose-antique)]/40 font-sans text-xs font-bold uppercase tracking-widest py-3.5 px-4 rounded-2xl shadow-sm transition-all hover:scale-[1.01] flex items-center justify-center gap-2 group">
                            <span class="w-6 h-6 rounded-full bg-[var(--color-rose-antique)] text-white flex items-center justify-center shadow-sm group-hover:rotate-12 transition-transform">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </span>
                            <span>✨ AI Virtual Try-On — Powered by CatVTON (Free)</span>
                        </button>
                    </div>

                    <div class="flex flex-col gap-4 pt-2">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                            <div class="flex items-center border border-[var(--color-bisque)] rounded-full px-3 py-3 bg-white w-fit">
                                <button @click="quantity = Math.max(1, quantity - 1)" class="text-xs font-bold px-2 hover:text-[var(--color-rose-antique)]">-</button>
                                <span class="text-xs font-sans font-bold px-3" x-text="quantity"></span>
                                <button @click="quantity++" class="text-xs font-bold px-2 hover:text-[var(--color-rose-antique)]">+</button>
                            </div>

                            <button @click="$store.shop.addToCart({ id: product.id, name: product.name, price: product.price, image: product.images[0], color: selectedColor, size: selectedSize, qty: quantity, category: product.category })" class="flex-1 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-full shadow-[var(--shadow-floating)] transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span>Add to Bag</span>
                            </button>

                            <button @click="$store.shop.toggleWishlist({ id: product.id, name: product.name, price: product.price, image: product.images[0], category: product.category })" class="w-14 h-14 rounded-full border border-[var(--color-bisque)] bg-white flex items-center justify-center text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] transition-colors shadow-sm flex-shrink-0">
                                <svg :class="$store.shop.isInWishlist(product.id) ? 'fill-[var(--color-rose-antique)] text-[var(--color-rose-antique)]' : 'fill-none'" class="w-5 h-5" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </div>

                        <button @click="$store.shop.addToCart({ id: product.id, name: product.name, price: product.price, image: product.images[0], color: selectedColor, size: selectedSize, qty: quantity, category: product.category }); setTimeout(() => window.location.href = '/checkout', 300);" class="w-full bg-[var(--color-rose-antique)] hover:bg-[var(--color-ebony)] text-white font-sans text-xs font-bold uppercase tracking-widest py-4 rounded-full shadow-[var(--shadow-floating)] transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
                            Buy Now
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

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- 4. LUXURY RATINGS & REVIEWS SECTION (Max 5 Stars)                  --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        <div id="reviews-section" class="mt-16 sm:mt-24 pt-12 border-t border-[var(--color-bisque)]">
            
            {{-- Notification message if submitted --}}
            @if(session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-300 text-emerald-900 flex items-center justify-between text-xs font-sans font-bold shadow-sm">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 font-bold">✕</button>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-8 p-4 rounded-2xl bg-rose-50 border border-rose-300 text-rose-900 text-xs font-sans space-y-1 shadow-sm">
                @foreach($errors->all() as $err)
                    <p>⚠️ {{ $err }}</p>
                @endforeach
            </div>
            @endif

            {{-- Section Header & Overall Rating Breakdown --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start mb-12">
                
                {{-- Overall Rating Score --}}
                <div class="bg-white p-8 rounded-3xl border border-[var(--color-bisque)] text-center space-y-3 shadow-sm">
                    <span class="text-[10px] font-sans font-bold uppercase tracking-widest text-[var(--color-rose-antique)]">Verified Customer Experience</span>
                    <div class="font-serif text-5xl font-bold text-[var(--color-ebony)]">
                        {{ $avgRating }}<span class="text-2xl text-[var(--color-ebony)]/40 font-sans">/5</span>
                    </div>
                    <div class="flex justify-center text-amber-400 text-xl gap-1">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= round($avgRating))
                                <span class="text-amber-400">★</span>
                            @else
                                <span class="text-gray-300">★</span>
                            @endif
                        @endfor
                    </div>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60">
                        Based on <strong>{{ $totalReviewsCount }}</strong> verified artisan reviews
                    </p>
                    <button type="button" @click="showReviewForm = !showReviewForm" class="w-full mt-2 bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-widest py-3 px-6 rounded-full transition-all shadow-md">
                        <span x-text="showReviewForm ? '✕ Close Review Form' : '★ Write a Review'"></span>
                    </button>
                </div>

                {{-- Rating Distribution Breakdown Bar --}}
                <div class="lg:col-span-2 bg-white p-8 rounded-3xl border border-[var(--color-bisque)] space-y-3 shadow-sm">
                    <h3 class="font-serif text-lg font-bold text-[var(--color-ebony)]">Rating Breakdown</h3>
                    
                    <div class="space-y-2 text-xs font-sans">
                        @foreach([5, 4, 3, 2, 1] as $star)
                        @php
                            $cnt = $ratingBreakdown[$star] ?? 0;
                            $pct = $totalReviewsCount > 0 ? round(($cnt / $totalReviewsCount) * 100) : ($star === 5 ? 90 : 2);
                        @endphp
                        <div class="flex items-center gap-3">
                            <span class="w-12 font-bold text-[var(--color-ebony)] flex items-center gap-1">{{ $star }} ★</span>
                            <div class="flex-1 h-2.5 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-amber-300 to-amber-500 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-10 text-right text-gray-500 font-mono text-[11px]">{{ $cnt }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Interactive "Write a Review" Form Modal / Expandable Card --}}
            <div x-show="showReviewForm" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 -translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="mb-12 bg-white rounded-3xl border-2 border-[var(--color-rose-antique)]/40 p-6 sm:p-10 shadow-lg space-y-6">
                
                <div class="border-b border-[var(--color-bisque)]/60 pb-4">
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">Share Your Experience</h3>
                    <p class="text-xs font-sans text-[var(--color-ebony)]/60 mt-1">Rate this outfit from 1 to 5 stars and let other patrons know about fabric drape, silhouette, and craft.</p>
                </div>

                <form action="/product/{{ $product['id'] }}/review" method="POST" class="space-y-5">
                    @csrf

                    {{-- 1 to 5 Stars Interactive Selector --}}
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)] mb-2">
                            Select Rating (1 to 5 Stars Max) *
                        </label>
                        <div class="flex items-center gap-2">
                            <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                                <button type="button" 
                                        @click="newRating = star"
                                        @mouseenter="hoverRating = star"
                                        @mouseleave="hoverRating = 0"
                                        class="text-3xl sm:text-4xl transition-transform hover:scale-125 focus:outline-none focus:scale-125"
                                        :title="star + ' Star(s)'">
                                    <span :class="(hoverRating || newRating) >= star ? 'text-amber-400' : 'text-gray-300'">★</span>
                                </button>
                            </template>
                            <span class="text-xs font-sans font-bold text-[var(--color-rose-antique)] ml-3" x-text="starLabel"></span>
                            <input type="hidden" name="rating" :value="newRating" />
                        </div>
                    </div>

                    {{-- Customer Name --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)] mb-1.5">
                                Your Name *
                            </label>
                            <input type="text" 
                                   name="user_name" 
                                   value="{{ Auth::check() ? Auth::user()->name : old('user_name') }}" 
                                   placeholder="e.g. Radhika Sharma" 
                                   required 
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>
                        <div>
                            <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)] mb-1.5">
                                Verified Email (Optional)
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ Auth::check() ? Auth::user()->email : '' }}" 
                                   placeholder="your.email@domain.com" 
                                   class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl px-4 py-3 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)]" />
                        </div>
                    </div>

                    {{-- Review Comment --}}
                    <div>
                        <label class="block text-xs font-sans font-bold uppercase tracking-wider text-[var(--color-ebony)] mb-1.5">
                            Detailed Review & Feedback *
                        </label>
                        <textarea name="comment" 
                                  rows="4" 
                                  required 
                                  placeholder="Describe the softness of the fabric, color vibrancy, zari shine, fitting accuracy..."
                                  class="w-full bg-[var(--color-offwhite)] border border-[var(--color-bisque)] rounded-xl p-4 text-xs font-sans focus:outline-none focus:border-[var(--color-rose-antique)] leading-relaxed"></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" @click="showReviewForm = false" class="px-5 py-3 rounded-full bg-gray-100 hover:bg-gray-200 text-xs font-sans font-bold text-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="bg-[var(--color-ebony)] hover:bg-[var(--color-rose-deep)] text-white text-xs font-sans font-bold uppercase tracking-widest px-8 py-3.5 rounded-full shadow-md transition-all hover:scale-105">
                            Submit Review
                        </button>
                    </div>
                </form>

            </div>

            {{-- List of Approved Customer Reviews --}}
            <div class="space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-serif text-xl sm:text-2xl font-bold text-[var(--color-ebony)]">
                        Customer Reviews ({{ $reviews->count() }})
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($reviews as $rev)
                    <div class="bg-white p-6 sm:p-7 rounded-3xl border border-[var(--color-bisque)] shadow-sm space-y-4 flex flex-col justify-between hover:border-[var(--color-rose-antique)]/40 transition-colors">
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-[var(--color-ebony)] text-amber-200 flex items-center justify-center font-bold text-sm shadow-inner">
                                        {{ strtoupper(substr($rev->user_name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-xs text-[var(--color-ebony)]">{{ $rev->user_name }}</span>
                                            <span class="text-[9px] bg-emerald-100 text-emerald-800 font-bold uppercase tracking-wider px-2 py-0.5 rounded-full">✓ Verified</span>
                                        </div>
                                        <span class="text-[10px] text-gray-400 font-sans block">{{ $rev->created_at ? $rev->created_at->format('d M Y') : 'Verified Patron' }}</span>
                                    </div>
                                </div>

                                {{-- Stars --}}
                                <div class="flex text-amber-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $rev->rating)
                                            <span class="text-amber-400">★</span>
                                        @else
                                            <span class="text-gray-300">★</span>
                                        @endif
                                    @endfor
                                </div>
                            </div>

                            <p class="text-xs font-sans text-[var(--color-ebony)]/80 leading-relaxed font-light">
                                "{{ $rev->comment }}"
                            </p>
                        </div>

                        <div class="pt-3 border-t border-[var(--color-bisque)]/40 flex items-center justify-between text-[10px] font-sans text-gray-400">
                            <span>Item: {{ $product['name'] }}</span>
                            <span class="text-emerald-700 font-semibold">Handloom Certified</span>
                        </div>

                    </div>
                    @empty
                    <div class="col-span-2 p-12 text-center bg-white rounded-3xl border border-[var(--color-bisque)] space-y-3">
                        <span class="text-4xl block">✨</span>
                        <h4 class="font-serif text-base font-bold text-[var(--color-ebony)]">Be the First to Review</h4>
                        <p class="text-xs font-sans text-[var(--color-ebony)]/60 max-w-md mx-auto">
                            Share your thoughts with our artisanal community. Rate from 1 to 5 stars and help other patrons discover luxury couture.
                        </p>
                        <button type="button" @click="showReviewForm = true" class="mt-2 inline-flex items-center gap-1.5 bg-[var(--color-ebony)] text-white text-xs font-sans font-bold uppercase tracking-wider px-6 py-2.5 rounded-full">
                            ★ Write First Review
                        </button>
                    </div>
                    @endforelse
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
