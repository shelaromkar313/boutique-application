{{-- ProductCard Partial - works with both DB Eloquent models and plain arrays --}}
{{-- Usage: @include('partials.product-card', ['product' => $product]) --}}
@php
    // Normalize: support both Eloquent model (snake_case) and plain array (camelCase)
    if (is_object($product)) {
        $p = [
            'id'           => $product->est_id,
            'name'         => $product->name,
            'category'     => $product->category,
            'price'        => (float) $product->price,
            'oldPrice'     => (float) $product->old_price,
            'discount'     => (int) $product->discount,
            'rating'       => (float) $product->rating,
            'reviewCount'  => (int) $product->review_count,
            'isNewArrival' => (bool) $product->is_new_arrival,
            'isBestSeller' => (bool) $product->is_best_seller,
            'colors'       => $product->colors ?? [],
            'sizes'        => $product->sizes ?? [],
            'images'       => $product->images ?? [],
        ];
    } else {
        $p = $product;
        // map camelCase oldPrice if coming from plain array
        if (!isset($p['oldPrice']) && isset($p['old_price'])) $p['oldPrice'] = $p['old_price'];
        if (!isset($p['reviewCount']) && isset($p['review_count'])) $p['reviewCount'] = $p['review_count'];
        if (!isset($p['isNewArrival']) && isset($p['is_new_arrival'])) $p['isNewArrival'] = $p['is_new_arrival'];
        if (!isset($p['isBestSeller']) && isset($p['is_best_seller'])) $p['isBestSeller'] = $p['is_best_seller'];
    }

    $mainImage = $p['images'][0] ?? '/storage/hero/hero-main.jpg';

    // Normalize colors: DB may store as plain strings ["Red","Blue"] or objects [{"hex":"#..","name":".."}]
    $colorNameToHex = [
        'red' => '#E53E3E', 'rose' => '#FB7185', 'pink' => '#F472B6', 'maroon' => '#7B2D42',
        'blue' => '#3B82F6', 'navy' => '#1E3A5F', 'sky' => '#38BDF8', 'teal' => '#14B8A6',
        'green' => '#22C55E', 'olive' => '#6B7A2A', 'mint' => '#86EFAC',
        'yellow' => '#FACC15', 'gold' => '#D4A843', 'orange' => '#F97316', 'peach' => '#FBCBA8',
        'purple' => '#A855F7', 'violet' => '#7C3AED', 'lavender' => '#C4B5FD',
        'brown' => '#92400E', 'beige' => '#F5E6C8', 'cream' => '#FFF8E7', 'ivory' => '#FFFFF0',
        'black' => '#1A1A1A', 'white' => '#FFFFFF', 'grey' => '#9CA3AF', 'gray' => '#9CA3AF',
        'silver' => '#C0C0C0', 'copper' => '#B87333', 'mustard' => '#D4A843',
        'magenta' => '#D946EF', 'coral' => '#FF6B6B', 'champagne' => '#FBEAD6',
        'turquoise' => '#40E0D0', 'indigo' => '#6366F1', 'fuchsia' => '#E879F9',
        'multicolor' => 'linear-gradient(135deg, #f43f5e, #f59e0b, #10b981)',
    ];

    $rawColors = $p['colors'] ?? [];
    $normalizedColors = [];
    foreach ($rawColors as $c) {
        if (is_array($c) && isset($c['hex'])) {
            $normalizedColors[] = $c; // already correct format
        } elseif (is_string($c)) {
            $key = strtolower(trim($c));
            $normalizedColors[] = [
                'name' => $c,
                'hex'  => $colorNameToHex[$key] ?? '#CCCCCC',
            ];
        }
    }

    $colorsPreview = array_slice($normalizedColors, 0, 3);
    $extraColors   = max(0, count($normalizedColors) - 3);
    $sizesPreview = array_slice($p['sizes'] ?? [], 0, 4);
    $savings      = isset($p['oldPrice']) && isset($p['price']) ? ($p['oldPrice'] - $p['price']) : 0;
@endphp

<div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-[var(--shadow-floating)] transition-all duration-500 border border-[var(--color-bisque)]/30 flex flex-col">
    <!-- Image Display Container -->
    <div class="relative aspect-[3/4] w-full overflow-hidden bg-[var(--color-champagne-light)]/30">
        
        <!-- Main Product Link -->
        <a href="/product/{{ $p['id'] }}" class="block w-full h-full">
            <img
                src="{{ $mainImage }}"
                alt="{{ $p['name'] }}"
                class="w-full h-full object-cover object-top transition-transform duration-700 ease-out group-hover:scale-105"
                loading="lazy"
            />
        </a>

        <!-- Badges Overlay -->
        <div class="absolute top-2 sm:top-3 left-2 sm:left-3 flex flex-col gap-1 sm:gap-1.5 z-10 pointer-events-none">
            @if(!empty($p['isNewArrival']))
                <span class="bg-[var(--color-ebony)] text-white font-sans text-[8px] sm:text-[10px] uppercase font-bold tracking-widest px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">New</span>
            @endif
            @if(!empty($p['discount']) && $p['discount'] > 0)
                <span class="bg-[var(--color-rose-antique)] text-white font-sans text-[8px] sm:text-[10px] uppercase font-bold tracking-widest px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">{{ $p['discount'] }}% OFF</span>
            @endif
            @if(!empty($p['isBestSeller']) && empty($p['isNewArrival']))
                <span class="bg-[var(--color-thyme)] text-white font-sans text-[8px] sm:text-[10px] uppercase font-bold tracking-widest px-1.5 sm:px-2.5 py-0.5 sm:py-1 rounded-full shadow-md">Best Seller</span>
            @endif
        </div>

        <!-- Wishlist Button -->
        <button
            @click.prevent="$store.shop.toggleWishlist({ id: '{{ $p['id'] }}', name: '{{ addslashes($p['name']) }}', price: {{ $p['price'] }}, image: '{{ $mainImage }}', category: '{{ addslashes($p['category']) }}' })"
            class="absolute top-2 sm:top-3 right-2 sm:right-3 z-10 w-7 h-7 sm:w-9 sm:h-9 rounded-full bg-white/90 backdrop-blur-md shadow-md flex items-center justify-center text-[var(--color-ebony)] hover:text-[var(--color-rose-antique)] hover:scale-110 transition-all duration-300"
            aria-label="Add to Wishlist"
        >
            <svg :class="$store.shop.isInWishlist('{{ $p['id'] }}') ? 'fill-[var(--color-rose-antique)] text-[var(--color-rose-antique)]' : 'fill-none'" class="w-3 h-3 sm:w-4 sm:h-4" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </button>

        <!-- Quick View & Quick Add hover drawer -->
        <div class="absolute bottom-3 left-3 right-3 z-10 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-3 group-hover:translate-y-0 flex items-center gap-2">
            <a href="/product/{{ $p['id'] }}" class="flex-1 bg-white/90 hover:bg-white text-[var(--color-ebony)] font-sans text-xs font-semibold py-2.5 px-3 rounded-full backdrop-blur-md shadow-lg flex items-center justify-center gap-1.5 transition-all hover:text-[var(--color-rose-antique)]">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Quick View
            </a>
            <button @click.prevent="$store.shop.addToCart({ id: '{{ $p['id'] }}', name: '{{ addslashes($p['name']) }}', price: {{ $p['price'] }}, image: '{{ $mainImage }}', category: '{{ addslashes($p['category']) }}' })" class="w-10 h-10 rounded-full bg-[var(--color-ebony)] hover:bg-[var(--color-rose-antique)] text-white flex items-center justify-center shadow-lg transition-colors flex-shrink-0" title="Quick Add to Bag">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </button>
        </div>
    </div>

    <!-- Product Information Body -->
    <div class="p-2.5 sm:p-4 flex-1 flex flex-col justify-between space-y-2 sm:space-y-3">
        <div>
            <!-- Subcategory & Rating -->
            <div class="flex items-center justify-between text-[9px] sm:text-[11px] font-sans text-[var(--color-ebony)]/60 mb-0.5 sm:mb-1">
                <span class="uppercase tracking-wider font-medium text-[var(--color-rose-antique)] truncate mr-1">{{ $p['category'] }}</span>
                <div class="flex items-center gap-0.5 sm:gap-1 flex-shrink-0">
                    <svg class="text-amber-500 fill-amber-500 w-3 h-3" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                    <span class="font-semibold text-[var(--color-ebony)]">{{ $p['rating'] }}</span>
                    <span class="text-[var(--color-ebony)]/40 hidden sm:inline">({{ $p['reviewCount'] }})</span>
                </div>
            </div>

            <!-- Product Title -->
            <a href="/product/{{ $p['id'] }}" class="block">
                <h3 class="font-serif text-xs sm:text-base font-bold text-[var(--color-ebony)] group-hover:text-[var(--color-rose-antique)] transition-colors line-clamp-1">{{ $p['name'] }}</h3>
            </a>
        </div>

        <!-- Color Dots & Size Pills -->
        <div class="flex items-center justify-between pt-1">
            <div class="flex items-center gap-1">
                @foreach($colorsPreview as $c)
                    <span class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full border border-black/20 inline-block" style="background-color: {{ $c['hex'] }}" title="{{ $c['name'] }}"></span>
                @endforeach
                @if($extraColors > 0)
                    <span class="text-[9px] sm:text-[10px] text-[var(--color-ebony)]/50 font-sans">+{{ $extraColors }}</span>
                @endif
            </div>
            <div class="hidden sm:flex items-center gap-1 text-[10px] font-sans font-semibold text-[var(--color-ebony)]/60">
                @foreach($sizesPreview as $s)
                    <span class="bg-[var(--color-offwhite)] px-1.5 py-0.5 rounded border border-[var(--color-bisque)]/50">{{ $s }}</span>
                @endforeach
            </div>
        </div>

        <!-- Price & Savings -->
        <div class="flex items-baseline gap-1 sm:gap-2 pt-1.5 sm:pt-2 border-t border-[var(--color-bisque)]/30">
            <span class="font-serif text-sm sm:text-lg font-bold text-[var(--color-ebony)]">₹{{ number_format($p['price']) }}</span>
            @if(!empty($p['oldPrice']))
                <span class="font-sans text-[10px] sm:text-xs text-[var(--color-ebony)]/40 line-through">₹{{ number_format($p['oldPrice']) }}</span>
            @endif
            @if($savings > 0)
                <span class="font-sans text-[9px] sm:text-[11px] font-bold text-[var(--color-thyme)] ml-auto">Save ₹{{ number_format($savings) }}</span>
            @endif
        </div>
    </div>
</div>
