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
        // 1. Fetch images mapped in ImageSeeder
        $circleCategories = Image::where('category', 'circle')->orderBy('sort_order')->get()->map(function($img) {
            return [
                'name' => $img->alt_text,
                'path' => '/shop', // default path, can be customized based on slug later
                'image' => $img->url
            ];
        });

        // 2. Fetch proper Categories from DB
        $categoriesData = Category::all();

        // 3. Occasions from Images
        $occasionsData = Image::where('category', 'occasion')->orderBy('sort_order')->get()->map(function($img) {
            return [
                'name' => $img->alt_text,
                'slug' => str_replace('occasion-', '', $img->slug),
                'image' => $img->url
            ];
        });

        // 4. Products from DB (Trending, New Arrivals, Sarees)
        $trendingProducts = Product::where('is_trending', true)->orderBy('created_at', 'desc')->get();
        if ($trendingProducts->count() < 4) {
            $extra = Product::whereNotIn('id', $trendingProducts->pluck('id'))->orderBy('created_at', 'desc')->take(6)->get();
            $trendingProducts = $trendingProducts->concat($extra);
        }

        $newArrivals = Product::where('is_new_arrival', true)->orderBy('created_at', 'desc')->get();
        if ($newArrivals->count() < 4) {
            $extra = Product::whereNotIn('id', $newArrivals->pluck('id'))->orderBy('created_at', 'desc')->take(6)->get();
            $newArrivals = $newArrivals->concat($extra);
        }

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
            'instagramPosts'
        ));
    }
}
