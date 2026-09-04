<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 2. Fetch proper Categories from DB (admin-created only)
        $categoriesData = Category::orderBy('name')->get();

        // 1. Homepage circle bar shows ONLY admin-created Categories (no hardcoded images).
        // Photo = category photo if set, else first matching product photo.
        $circleCategories = $categoriesData->map(function($cat) {
            $words = array_filter(preg_split('/[\s&\/-]+/', strtolower($cat->name)), fn($w) => strlen($w) >= 3);
            $prod = null;
            foreach ($words as $w) {
                $prod = Product::where('category', 'LIKE', '%' . $w . '%')
                    ->orWhere('occasion', 'LIKE', '%' . $w . '%')
                    ->orderBy('created_at', 'desc')->first();
                if ($prod) break;
            }
            $prod = $prod ?? Product::orderBy('created_at', 'desc')->first();
            $images = $prod ? (is_array($prod->images) ? $prod->images : []) : [];
            $image = $cat->image ?: ($images[0] ?? '/images/circles/default.jpg');
            return [
                'name' => $cat->name,
                'path' => '/shop?category=' . urlencode($cat->name),
                'image' => $image,
            ];
        });

        // 3. Occasions from Images — card links to best-matching product occasion
        // so admin renames never produce zero-result pages.
        $allOccasions = Product::select('occasion')->distinct()->pluck('occasion')->filter()->values();
        $occasionsData = Image::where('category', 'occasion')->orderBy('sort_order')->get()->map(function($img) use ($allOccasions) {
            $name = $img->alt_text ?: $img->name;
            $words = array_filter(preg_split('/[\s&\/–—-]+/u', strtolower($name)), fn($w) => strlen($w) >= 3);
            $best = null;
            $bestScore = 0;
            foreach ($allOccasions as $occ) {
                $ow = array_filter(preg_split('/[\s&\/–—-]+/u', strtolower($occ)), fn($w) => strlen($w) >= 3);
                $score = count(array_intersect($words, $ow));
                if ($score > $bestScore) {
                    $bestScore = $score;
                    $best = $occ;
                }
            }
            return [
                'name' => $name,
                'link' => $bestScore > 0 ? $best : $name,
                'slug' => str_replace('occasion-', '', $img->slug),
                'image' => $img->url
            ];
        });

        // 4. Products from DB (Trending, New Arrivals — strictly respect admin toggle, no fallback)
        $trendingProducts = Product::where('is_trending', true)->orderBy('created_at', 'desc')->take(12)->get();

        $newArrivals = Product::where('is_new_arrival', true)->orderBy('created_at', 'desc')->take(12)->get();

        $sareeSpotlight = Product::where('main_category', 'Sarees')->orWhere('category', 'LIKE', '%Saree%')->orderBy('created_at', 'desc')->get();
        if ($sareeSpotlight->count() < 4) {
            $sareeSpotlight = Product::where('category', 'LIKE', '%Saree%')->orderBy('created_at', 'desc')->get();
        }

        // 5. Hardcoded static aesthetic sections (testimonials, instagram)
        $fabricsData = [
            ['name' => 'Pure Silk',           'count' => '48 Designs'],
            ['name' => 'Chikankari Cotton',   'count' => '32 Designs'],
            ['name' => 'Organza',             'count' => '24 Designs'],
            ['name' => 'Mulmul Cotton',       'count' => '40 Designs'],
            ['name' => 'Banarasi Brocade',    'count' => '18 Designs'],
            ['name' => 'Organic Linen',       'count' => '29 Designs'],
            ['name' => 'Chanderi Silk',       'count' => '21 Designs'],
            ['name' => 'Georgette Foil',      'count' => '35 Designs'],
        ];

        $testimonialsData = [
            [
                'id' => 1,
                'name' => 'Ananya Sharma',
                'city' => 'Mumbai',
                'role' => 'Fashion Curator & Stylist',
                'rating' => 5,
                'product' => 'Gulzar Chikankari Anarkali Set',
                'comment' => 'Estilo Wear is pure luxury! The Lucknowi Chikankari Anarkali set I ordered for my sister\'s sangeet was divine. The fabric quality, shadow embroidery, and fit exceeded all expectations.',
                'avatar' => '/images/testimonials/ananya.jpg',
                'verified' => true,
                'date' => '2 days ago'
            ],
            [
                'id' => 2,
                'name' => 'Priyanka Sen',
                'city' => 'Kolkata',
                'role' => 'Interior Architect',
                'rating' => 5,
                'product' => 'Varanasi Royal Zari Banarasi Saree',
                'comment' => 'The Banarasi silk saree from Estilo Wear feels like holding heritage art in your hands. The Kadwa zari work is flawless. Arrived beautifully packaged in a signature boutique box!',
                'avatar' => '/images/testimonials/priyanka.jpg',
                'verified' => true,
                'date' => '1 week ago'
            ],
            [
                'id' => 3,
                'name' => 'Dr. Radhika Menon',
                'city' => 'Bengaluru',
                'role' => 'Senior Physician',
                'rating' => 5,
                'product' => 'Aarya Hand Block Cotton Kurti',
                'comment' => 'Finding office-wear ethnic outfits that balance comfort and executive style was always hard until I found Estilo Wear. Their hand block printed cotton kurtis are my daily go-to!',
                'avatar' => '/images/testimonials/radhika.jpg',
                'verified' => true,
                'date' => '2 weeks ago'
            ],
            [
                'id' => 4,
                'name' => 'Meera Rajput',
                'city' => 'Jaipur',
                'role' => 'Creative Director',
                'rating' => 5,
                'product' => 'Noor Hand-Painted Organza Saree',
                'comment' => 'The sheer elegance of their floral organza sarees is unmatched. The drape is feather-light and the colors look even more vibrant in person. Customer service was super responsive too!',
                'avatar' => '/images/testimonials/ananya.jpg',
                'verified' => true,
                'date' => '3 weeks ago'
            ],
            [
                'id' => 5,
                'name' => 'Tanvi Deshmukh',
                'city' => 'Pune',
                'role' => 'Brand Consultant',
                'rating' => 5,
                'product' => 'Raysha Silk Blend Co-Ord Set',
                'comment' => 'The modern ethnic Co-Ord set is my favorite purchase this festive season. Chic silhouettes with traditional prints — got countless compliments at my Diwali dinner party!',
                'avatar' => '/images/testimonials/priyanka.jpg',
                'verified' => true,
                'date' => '1 month ago'
            ],
            [
                'id' => 6,
                'name' => 'Shruti Patel',
                'city' => 'Ahmedabad',
                'role' => 'Luxury Wedding Planner',
                'rating' => 5,
                'product' => 'Bhavya Kanjivaram Golden Zari Saree',
                'comment' => 'Authentic silk weave with genuine Silk Mark certification. I recommend Estilo Wear to all my brides for their trousseau collection. Quality you can trust blindly.',
                'avatar' => '/images/testimonials/radhika.jpg',
                'verified' => true,
                'date' => '1 month ago'
            ]
        ];

        // 6. Hero slideshow from DB (admin-editable); fallback to defaults if empty
        $heroSlides = \App\Models\HeroSlide::where('is_active', true)
            ->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn($s) => [
                'tag' => $s->tag,
                'titleline1' => $s->title1,
                'titleline2' => $s->title2,
                'titleline3' => $s->title3,
                'desc' => $s->description,
                'image' => $s->image,
                'objectPos' => $s->object_pos ?: 'object-[center_top] sm:object-[center_top] md:object-[center_top]',
                'fit' => $s->fit_mode ?: 'cover',
                'btnText' => $s->btn_text,
                'btnLink' => $s->btn_link,
                'subLinkText' => $s->sub_text,
                'subLink' => $s->sub_link,
            ])->toArray();
        if (empty($heroSlides)) {
            $heroSlides = [
                ['tag' => 'NEW COLLECTION — 2025', 'titleline1' => 'Timeless', 'titleline2' => 'Indian', 'titleline3' => 'Elegance', 'desc' => 'Handcrafted Indian fashion for the modern woman — curated from artisan weavers across India.', 'image' => '/hero/hero-main.jpg', 'objectPos' => 'object-[85%_top] sm:object-[82%_top] md:object-[right_top]', 'fit' => 'cover', 'btnText' => 'Shop Now', 'btnLink' => '/shop', 'subLinkText' => 'View New Arrivals', 'subLink' => '/shop?filter=new'],
                ['tag' => 'LUXURY SILK EDIT', 'titleline1' => 'Royal', 'titleline2' => 'Banarasi', 'titleline3' => 'Sarees', 'desc' => 'Pure silk mark certified sarees featuring gold zari brocade & Kadwa weaving from Varanasi.', 'image' => '/hero/hero-slide-2.jpg', 'objectPos' => 'object-[center_top] sm:object-[center_top] md:object-[center_top]', 'fit' => 'cover', 'btnText' => 'Explore Sarees', 'btnLink' => '/shop?category=Sarees', 'subLinkText' => 'View Banarasi Silk', 'subLink' => '/shop?category=Sarees'],
                ['tag' => 'ROYAL HERITAGE CRAFT', 'titleline1' => 'Lucknowi', 'titleline2' => 'Chikankari', 'titleline3' => 'Couture', 'desc' => 'Airy mulmul cotton & silk Anarkalis with hand-embroidered shadow work & silver Mukaish.', 'image' => '/hero/hero-slide-3.jpg', 'objectPos' => 'object-[center_top] sm:object-[center_top] md:object-[center_top]', 'fit' => 'cover', 'btnText' => 'Explore Chikankari', 'btnLink' => '/shop?category=Chikankari+Kurtis', 'subLinkText' => 'View Anarkalis', 'subLink' => '/shop?category=Anarkali'],
            ];
        }

        $instagramPosts = Image::where('category', 'instagram')->orderBy('sort_order')->get()->map(function($img, $index) {
            $likes = ['2.4k', '3.8k', '1.9k', '4.1k', '5.2k', '3.1k'];
            $tags = ['#EstiloWomen', '#SlayEveryLook', '#ChikankariLove', '#BoutiqueCouture', '#RoyalSilk', '#FestiveDrape'];
            return [
                'id' => $index + 1,
                'image' => $img->url,
                'likes' => $likes[$index] ?? '1k',
                'tag' => $tags[$index] ?? '#EstiloWear'
            ];
        });

        return view('home', compact(
            'circleCategories',
            'categoriesData',
            'occasionsData',
            'trendingProducts',
            'newArrivals',
            'sareeSpotlight',
            'fabricsData',
            'testimonialsData',
            'instagramPosts',
            'heroSlides'
        ));
    }
}
