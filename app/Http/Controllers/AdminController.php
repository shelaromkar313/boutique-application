<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReferralSale;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    private function adminBaseUrl(?string $tab = null): string
    {
        $base = request()->is('estilo-hq-console*') || request()->path() === 'estilo-hq-console' ? '/estilo-hq-console' : '/admin';

        return $tab ? $base . '?tab=' . $tab : $base;
    }

    /**
     * Comprehensive Admin Management Portal (Unified & Tabbed)
     */
    public function index(Request $request)
    {
        $admin = Auth::user();
        if (!$admin || !$admin->isAdmin()) {
            return redirect('/estilo-hq-console/login')->withErrors([
                'email' => 'Restricted Area: Please authenticate with your Administrator credentials.'
            ]);
        }

        $tab = $request->query('tab', 'overview');

        $products = Product::latest()->get();
        $categories = Category::all();
        $orders = Order::latest()->get();
        $customers = User::where('role', 'customer')->orWhereNull('role')->latest()->get();
        try {
            $associates = DB::table('sales_associates')->latest()->get();
        } catch (\Throwable $e) {
            $associates = User::whereIn('role', ['sales_associate', 'associate', 'sales_executive'])->latest()->get();
        }
        $reviews = Review::latest()->get();
        $coupons = Coupon::latest()->get();
        $announcedCoupons = Coupon::where('is_announced', true)->where('is_active', true)
            ->where(function($q) { $q->whereNull('valid_until')->orWhere('valid_until', '>=', now()); })
            ->get();
        $announcements = Announcement::orderBy('sort_order', 'asc')->latest()->get();
        $referralSales = ReferralSale::with('associate')->latest()->get();

        // Key KPI Metrics
        $totalRevenue = $orders->where('status', '!=', 'cancelled')->sum('total');

        $totalOrdersCount = $orders->count();
        $totalProductsCount = $products->count();
        $inStockCount = $products->where('in_stock', true)->count();
        $totalAssociatesCount = $associates->count();
        $totalCommissionPaid = ReferralSale::where('status', 'paid')->sum('commission_earned');

        // Monthly Reports Data
        $monthlyReports = ReferralSale::selectRaw('DATE_FORMAT(created_at, "%M %Y") as month, COUNT(*) as orders_count, SUM(sale_amount) as total_sales, SUM(commission_earned) as total_commission')
            ->groupBy('month')
            ->orderByRaw('MIN(created_at) DESC')
            ->get();

        return view('admin-dashboard', compact(
            'tab',
            'admin',
            'products',
            'categories',
            'orders',
            'customers',
            'associates',
            'reviews',
            'coupons',
            'announcedCoupons',
            'announcements',
            'referralSales',
            'totalRevenue',
            'totalOrdersCount',
            'totalProductsCount',
            'inStockCount',
            'totalAssociatesCount',
            'totalCommissionPaid',
            'monthlyReports'
        ));
    }

    /**
     * Dedicated Administrator Profile & Security Page
     */
    public function profile()
    {
        $admin = Auth::user();
        if (!$admin || !$admin->isAdmin()) {
            return redirect('/estilo-hq-console/login')->withErrors([
                'email' => 'Restricted Area: Please authenticate with your Administrator credentials.'
            ]);
        }

        return view('admin-profile', compact('admin'));
    }

    /**
     * Update Administrator Profile
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();
        if (!$admin || !$admin->isAdmin()) {
            return redirect('/estilo-hq-console/login')->withErrors([
                'email' => 'Restricted Area: Please authenticate with your Administrator credentials.'
            ]);
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $admin->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return redirect($this->adminBaseUrl('overview'))->with('success', '✨ Administrator profile details updated successfully!');
    }

    /**
     * Add New Product
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'price'       => 'required|numeric|min:1',
            'description' => 'required|string',
        ]);

        $nextId = ((int) Product::max('id')) + 1;
        $estId = 'est-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        while (Product::where('est_id', $estId)->exists()) {
            $nextId++;
            $estId = 'est-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }
        $sku = 'EST-' . strtoupper(Str::random(4)) . '-' . rand(100, 999);

        // Handle image upload or image URL
        $images = ['/storage/hero/hero-main.jpg'];
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $images = ['/storage/' . $path];
        } elseif ($request->filled('image_url')) {
            $images = [$request->image_url];
        }

        // Colors
        $colors = array_filter(array_map('trim', explode(',', $request->input('colors', 'Royal Navy, Rose Blush, Golden Zari'))));

        // Process Size-Wise Stock Inventory e.g. Free Size: 10, or XS: 1, S: 2, M: 4, L: 2, XL: 3, XXL: 2
        $sizeStock = [];
        if ($request->has('size_stock') && is_array($request->input('size_stock'))) {
            foreach ($request->input('size_stock') as $sz => $qty) {
                if (is_numeric($qty) && (int) $qty > 0) {
                    $cleanSz = trim($sz);
                    if (in_array(strtolower($cleanSz), ['free size', 'freesize', 'one size', 'onesize', 'unstitched'])) {
                        $key = 'Free Size';
                    } else {
                        $key = strtoupper($cleanSz);
                    }
                    $sizeStock[$key] = (int) $qty;
                }
            }
        } elseif ($request->filled('size_stock_text')) {
            $entries = explode(',', $request->input('size_stock_text'));
            foreach ($entries as $entry) {
                if (str_contains($entry, '-') || str_contains($entry, ':')) {
                    $delim = str_contains($entry, '-') ? '-' : ':';
                    [$sz, $qty] = explode($delim, $entry, 2);
                    $cleanSz = trim($sz);
                    if (in_array(strtolower($cleanSz), ['free size', 'freesize', 'one size', 'onesize', 'unstitched'])) {
                        $key = 'Free Size';
                    } else {
                        $key = strtoupper($cleanSz);
                    }
                    $sizeStock[$key] = max(0, (int) trim($qty));
                }
            }
        }

        if (empty($sizeStock)) {
            $catLower = strtolower($request->input('category', ''));
            if (str_contains($catLower, 'saree') || str_contains($catLower, 'sari') || str_contains($catLower, 'dupatta') || str_contains($catLower, 'shawl') || str_contains($catLower, 'unstitched')) {
                $sizeStock['Free Size'] = 5;
            } else {
                $sizes = array_filter(array_map('trim', explode(',', $request->input('sizes', 'XS, S, M, L, XL, XXL'))));
                foreach ($sizes as $s) {
                    $sizeStock[strtoupper($s)] = 2;
                }
            }
        }

        $sizes = array_keys($sizeStock);
        $totalUnits = array_sum($sizeStock);
        $inStock = $totalUnits > 0;
        $salesPrice = round($request->price * 1.05 + 50, -1);
        $fabric = trim($request->input('fabric', 'Handloom Artisanal')) ?: 'Handloom Artisanal';
        // New Category/Fabric typed by admin is saved as-is -> auto-appears in shop filters
        if ($request->filled('category') && !Category::where('name', $request->category)->exists()) {
            Category::create(['name' => $request->category, 'slug' => Str::slug($request->category), 'subcategories' => []]);
        }
        // % SALE toggle: checked + discount>0 = visible in SALE menu, else discount 0
        $onSale = $request->has('is_sale') || $request->has('is_featured');
        $discount = $onSale ? max(0, min(90, (int) $request->input('discount', 20))) : 0;
        $oldPrice = $discount > 0 ? round($request->price / (1 - $discount / 100)) : $request->price;

        Product::create([
            'est_id'         => $estId,
            'sku'            => $sku,
            'name'           => $request->name,
            'category'       => $request->category,
            'main_category'  => $request->input('main_category', 'Women Couture'),
            'sub_category'   => $request->input('sub_category', $request->category),
            'fabric'         => $fabric,
            'occasion'       => $request->input('occasion', 'Festive / Wedding'),
            'price'          => $request->price,
            'sales_price'    => $salesPrice,
            'old_price'      => $oldPrice,
            'discount'       => $discount,
            'rating'         => 5.0,
            'review_count'   => 1,
            'in_stock'       => $inStock,
            'is_trending'    => $request->has('is_trending'),
            'is_new_arrival' => $request->has('is_new_arrival'),
            'is_best_seller' => $request->has('is_best_seller') || $request->has('is_featured'),
            'is_featured'    => $request->has('is_featured'),
            'colors'         => $colors,
            'sizes'          => $sizes,
            'size_stock'     => $sizeStock,
            'description'    => $request->description,
            'details'        => ['Craft' => 'Handloom Artisanal', 'Origin' => 'Lucknow / Varanasi'],
            'care'           => 'Dry Clean Only. Steam iron on reverse.',
            'images'         => $images,
        ]);

        return redirect($this->adminBaseUrl('inventory'))->with('success', "✨ New Couture Outfit added successfully with {$totalUnits} units in stock!");
    }

    /**
     * Update Product & Stock
     */
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Process Size-Wise Stock Inventory
        $sizeStock = is_array($product->size_stock) ? $product->size_stock : [];
        if ($request->has('size_stock') && is_array($request->input('size_stock'))) {
            $sizeStock = [];
            foreach ($request->input('size_stock') as $sz => $qty) {
                if (is_numeric($qty) && (int) $qty > 0) {
                    $cleanSz = trim($sz);
                    if (in_array(strtolower($cleanSz), ['free size', 'freesize', 'one size', 'onesize', 'unstitched'])) {
                        $key = 'Free Size';
                    } else {
                        $key = strtoupper($cleanSz);
                    }
                    $sizeStock[$key] = (int) $qty;
                }
            }
        } elseif ($request->filled('size_stock_text')) {
            $sizeStock = [];
            $entries = explode(',', $request->input('size_stock_text'));
            foreach ($entries as $entry) {
                if (str_contains($entry, '-') || str_contains($entry, ':')) {
                    $delim = str_contains($entry, '-') ? '-' : ':';
                    [$sz, $qty] = explode($delim, $entry, 2);
                    $cleanSz = trim($sz);
                    if (in_array(strtolower($cleanSz), ['free size', 'freesize', 'one size', 'onesize', 'unstitched'])) {
                        $key = 'Free Size';
                    } else {
                        $key = strtoupper($cleanSz);
                    }
                    $sizeStock[$key] = max(0, (int) trim($qty));
                }
            }
        }

        $totalUnits = count($sizeStock) > 0 ? array_sum($sizeStock) : 0;
        $inStock = $totalUnits > 0;
        $sizes = count($sizeStock) > 0 ? array_keys($sizeStock) : $product->sizes;

        $newPrice = (float) $request->input('price', $product->price);
        $onSale = $request->has('is_sale') || $request->has('is_featured');
        // If discount field present use it, else keep existing only when still on sale
        $discount = $request->has('discount') ? ($onSale ? max(0, min(90, (int) $request->input('discount', 0))) : 0) : ($onSale ? (int) $product->discount : 0);
        $oldPrice = $discount > 0 ? round($newPrice / (1 - $discount / 100)) : $newPrice;
        if ($request->filled('category') && !Category::where('name', $request->category)->exists()) {
            Category::create(['name' => $request->category, 'slug' => Str::slug($request->category), 'subcategories' => []]);
        }

        $data = [
            'name'        => $request->input('name', $product->name),
            'category'    => $request->input('category', $product->category),
            'fabric'      => trim($request->input('fabric', $product->fabric)) ?: $product->fabric,
            'occasion'    => $request->input('occasion', $product->occasion),
            'price'       => $newPrice,
            'sales_price' => round($newPrice * 1.05 + 50, -1),
            'old_price'   => $oldPrice,
            'discount'    => $discount,
            'in_stock'       => $inStock,
            'is_featured'    => $request->has('is_featured'),
            'is_trending'    => $request->has('is_trending'),
            'is_new_arrival' => $request->has('is_new_arrival'),
            'description'    => $request->input('description', $product->description),
            'sizes'          => $sizes,
            'size_stock'     => $sizeStock,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['images'] = ['/storage/' . $path];
        }

        if ($request->filled('colors')) {
            $data['colors'] = array_filter(array_map('trim', explode(',', $request->input('colors'))));
        }

        $product->update($data);

        return redirect($this->adminBaseUrl('inventory'))->with('success', "✨ Product '{$product->name}' updated successfully ({$totalUnits} units in stock)!");
    }

    /**
     * Delete Product
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect($this->adminBaseUrl('inventory'))->with('success', 'Product removed from catalog.');
    }

    /**
     * Manage Product Category
     */
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:categories,name']);
        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'subcategories' => [],
        ]);
        return redirect($this->adminBaseUrl('inventory'))->with('success', 'New Category created successfully!');
    }

    public function deleteCategory($id)
    {
        $cat = Category::findOrFail($id);
        $cat->delete();
        return redirect($this->adminBaseUrl('inventory'))->with('success', 'Category removed.');
    }

    /**
     * Delete Fabric type: resets all products using it to default weave
     */
    public function deleteFabric(Request $request)
    {
        $request->validate(['fabric' => 'required|string']);
        $count = Product::where('fabric', $request->fabric)->update(['fabric' => 'Handloom Artisanal']);
        return redirect($this->adminBaseUrl('inventory'))->with('success', "Fabric '{$request->fabric}' removed from {$count} product(s).");
    }

    /**
     * Reviews Moderation: Edit Ratings (1-5 Stars) & Reviews (Modify Bad/Low Reviews)
     */
    public function updateReview(Request $request, $id)
    {
        $request->validate([
            'rating'    => 'required|integer|min:1|max:5',
            'comment'   => 'required|string|max:2000',
            'user_name' => 'nullable|string|max:255',
        ]);

        $review = Review::findOrFail($id);
        $review->update([
            'user_name'   => $request->input('user_name', $review->user_name),
            'rating'      => (int) $request->input('rating', $review->rating),
            'comment'     => trim($request->input('comment', $review->comment)),
            'is_approved' => $request->boolean('is_approved'),
        ]);

        // Recalculate product overall star rating
        $product = Product::where('est_id', $review->product_est_id)->first();
        if ($product && method_exists($product, 'updateRatingStats')) {
            $product->updateRatingStats();
        }

        return redirect($this->adminBaseUrl('reviews'))->with('success', "✨ Review #{$review->id} updated successfully! Rating set to {$review->rating} Stars.");
    }

    /**
     * Quick Action: Boost a low/bad rating to 5 Stars and approve it
     */
    public function boostReview($id)
    {
        $review = Review::findOrFail($id);
        $review->rating = 5;
        $review->is_approved = true;
        $review->save();

        $product = Product::where('est_id', $review->product_est_id)->first();
        if ($product && method_exists($product, 'updateRatingStats')) {
            $product->updateRatingStats();
        }

        return redirect($this->adminBaseUrl('reviews'))->with('success', "⭐ Review #{$review->id} boosted to 5 Stars & Approved!");
    }

    /**
     * Quick Action: Toggle Review Public Approval Visibility
     */
    public function toggleReview($id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = !$review->is_approved;
        $review->save();

        $product = Product::where('est_id', $review->product_est_id)->first();
        if ($product && method_exists($product, 'updateRatingStats')) {
            $product->updateRatingStats();
        }

        $status = $review->is_approved ? 'Approved & Live' : 'Hidden from Store';
        return redirect($this->adminBaseUrl('reviews'))->with('success', "Review #{$review->id} is now {$status}.");
    }

    /**
     * Delete Review
     */
    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $estId = $review->product_est_id;
        $review->delete();

        $product = Product::where('est_id', $estId)->first();
        if ($product && method_exists($product, 'updateRatingStats')) {
            $product->updateRatingStats();
        }

        return redirect($this->adminBaseUrl('reviews'))->with('success', 'Review removed from database.');
    }

    /**
     * Process Customer Orders (Update Status)
     */
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $status = $request->input('status', 'confirmed');
        $order->update(['status' => $status]);
        return redirect($this->adminBaseUrl('orders'))->with('success', "Order #{$order->order_no} status updated to " . strtoupper($status));
    }

    /**
     * Update Marketing Associate Commission & Balance Details
     */
    public function updateAssociate(Request $request, $id)
    {
        $associate = User::findOrFail($id);
        $associate->update([
            'commission_rate' => $request->input('commission_rate', $associate->commission_rate),
            'balance'         => $request->input('balance', $associate->balance),
            'upi_id'          => $request->input('upi_id', $associate->upi_id),
        ]);
        return redirect($this->adminBaseUrl('customers'))->with('success', "Settings for {$associate->name} updated successfully.");
    }

    /**
     * Approve Payout for Associate
     */
    public function approvePayout(Request $request, $id)
    {
        $associate = User::findOrFail($id);
        $amount = (float) $request->input('amount', $associate->balance);

        if ($amount > 0 && $amount <= $associate->balance) {
            $associate->decrement('balance', $amount);
            $associate->increment('earnings', $amount);
            ReferralSale::where('associate_id', $associate->id)
                ->where('status', 'pending')
                ->update(['status' => 'paid']);

            return redirect($this->adminBaseUrl('customers'))->with('success', "✨ Payout of ₹" . number_format($amount, 2) . " processed successfully for {$associate->name} ({$associate->upi_id}).");
        }

        return redirect($this->adminBaseUrl('customers'))->withErrors(['payout' => 'Invalid payout amount.']);
    }

    /**
     * Dedicated Create Coupon Page
     */
    public function createCoupon()
    {
        $admin = Auth::user();
        return view('admin-create-coupon', compact('admin'));
    }

    /**
     * Dedicated Create Announcement Page
     */
    public function createAnnouncement()
    {
        $admin = Auth::user();
        return view('admin-create-announcement', compact('admin'));
    }

    /**
     * Dedicated Create Product Page
     */
    public function createProduct()
    {
        $admin = Auth::user();
        $categories = Category::all();
        return view('admin-create-product', compact('admin', 'categories'));
    }

    /**
     * Create Offers & Discount Coupons
     */
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code'           => 'required|string|unique:coupons,code',
            'title'          => 'required|string',
            'discount_value' => 'required|numeric|min:1',
        ]);

        Coupon::create([
            'code'            => strtoupper($request->code),
            'title'           => $request->title,
            'discount_type'   => $request->input('discount_type', 'percentage'),
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->input('min_order_value', 0),
            'campaign_type'   => $request->input('campaign_type', 'festival'),
            'valid_until'     => $request->input('valid_until', now()->addMonths(3)),
            'is_active'       => true,
        ]);

        return redirect($this->adminBaseUrl('offers'))->with('success', "Coupon '{$request->code}' generated and published successfully!");
    }

    public function updateCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code'           => 'required|string|unique:coupons,code,' . $coupon->id,
            'title'          => 'required|string',
            'discount_value' => 'required|numeric|min:1',
        ]);

        $coupon->update([
            'code'            => strtoupper($request->code),
            'title'           => $request->title,
            'discount_type'   => $request->input('discount_type', 'percentage'),
            'discount_value'  => $request->discount_value,
            'min_order_value' => $request->input('min_order_value', 0),
            'campaign_type'   => $request->input('campaign_type', 'festival'),
            'valid_until'     => $request->input('valid_until', $coupon->valid_until),
            'is_active'       => $request->boolean('is_active', true),
        ]);

        return redirect($this->adminBaseUrl('offers'))->with('success', "Coupon '{$coupon->code}' updated successfully!");
    }

    public function toggleCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => !$coupon->is_active]);
        return redirect($this->adminBaseUrl('offers'))->with('success', "Coupon '{$coupon->code}' status updated.");
    }

    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $code = $coupon->code;
        $coupon->delete();
        return redirect($this->adminBaseUrl('offers'))->with('success', "Coupon '{$code}' deleted successfully.");
    }

    /**
     * Toggle Coupon Announcement in Storefront Ticker
     * Allows admin to broadcast a coupon as a marquee announcement to all visitors.
     */
    public function announceCoupon(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        // If custom announcement text provided, save it; otherwise auto-generate
        $announcementText = $request->filled('announcement_text')
            ? trim($request->input('announcement_text'))
            : null;

        if (!$announcementText) {
            $discountLabel = $coupon->discount_type === 'percentage'
                ? "{$coupon->discount_value}% OFF"
                : "₹{$coupon->discount_value} OFF";
            $announcementText = "🎉 Use code {$coupon->code} and get {$discountLabel}! {$coupon->title}";
            if ($coupon->valid_until) {
                $announcementText .= ' — Valid till ' . $coupon->valid_until->format('d M Y');
            }
        }

        // Toggle: if already announced with same text, turn it off
        $isNowAnnounced = !($coupon->is_announced && $coupon->announcement_text === $announcementText);

        $coupon->update([
            'is_announced'      => $isNowAnnounced,
            'announcement_text' => $isNowAnnounced ? $announcementText : null,
        ]);

        $msg = $isNowAnnounced
            ? "📢 Coupon '{$coupon->code}' is now announced on the storefront!"
            : "🔕 Announcement for '{$coupon->code}' has been removed from the storefront.";

        return redirect($this->adminBaseUrl('offers'))->with('success', $msg);
    }

    /**
     * 4.9 Storefront Announcements Suite
     */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type'    => 'nullable|string',
            'color'   => 'nullable|string',
            'icon'    => 'nullable|string|max:10',
        ]);

        Announcement::create([
            'title'          => $request->title,
            'message'        => $request->message,
            'type'           => $request->input('type', 'sale'),
            'color'          => $request->input('color', 'amber'),
            'icon'           => $request->input('icon', '📢'),
            'show_in_ticker' => $request->has('show_in_ticker'),
            'show_as_banner' => $request->has('show_as_banner'),
            'is_active'      => true,
            'starts_at'      => $request->filled('starts_at') ? $request->starts_at : null,
            'ends_at'        => $request->filled('ends_at') ? $request->ends_at : null,
            'sort_order'     => (int) $request->input('sort_order', 0),
        ]);

        return redirect($this->adminBaseUrl('announcements'))->with('success', '📢 Announcement published live to storefront!');
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $request->validate([
            'title'   => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        $announcement->update([
            'title'          => $request->title,
            'message'        => $request->message,
            'type'           => $request->input('type', $announcement->type),
            'color'          => $request->input('color', $announcement->color),
            'icon'           => $request->input('icon', $announcement->icon),
            'show_in_ticker' => $request->has('show_in_ticker'),
            'show_as_banner' => $request->has('show_as_banner'),
            'is_active'      => $request->has('is_active'),
            'starts_at'      => $request->filled('starts_at') ? $request->starts_at : null,
            'ends_at'        => $request->filled('ends_at') ? $request->ends_at : null,
        ]);

        return redirect($this->adminBaseUrl('announcements'))->with('success', 'Announcement updated successfully!');
    }

    public function toggleAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->update(['is_active' => !$announcement->is_active]);
        $status = $announcement->is_active ? 'Active (Live)' : 'Paused (Hidden)';
        return redirect($this->adminBaseUrl('announcements'))->with('success', "Announcement '{$announcement->title}' status set to {$status}.");
    }

    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $title = $announcement->title;
        $announcement->delete();
        return redirect($this->adminBaseUrl('announcements'))->with('success', "Announcement '{$title}' removed.");
    }
}
