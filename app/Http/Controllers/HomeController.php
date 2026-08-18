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
        $trendingProducts = Product::where('is_trending', true)->take(4)->get();
        $newArrivals = Product::where('is_new_arrival', true)->take(4)->get();
        $sareeSpotlight = Product::where('main_category', 'Sarees')->take(4)->get();

        // 5. Hardcoded static aesthetic sections (testimonials, instagram)
        $fabricsData = [
            ['name' => 'Pure Silk',           'count' => '48 Designs'],
            ['name' => 'Chikankari Cotton',   'count' => '32 Designs'],
            ['name' => 'Organza',             'count' => '24 Designs'],
            ['name' => 'Mulmul Cotton',       'count' => '40 Designs'],
            ['name' => 'Banarasi Brocade',    'count' => '18 Designs'],
            ['name' => 'Organic Linen',       'count' => '29 Designs'],
        ];

        $testimonialsData = [
            ['id' => 1, 'name' => 'Ananya Sharma',     'city' => 'Mumbai',    'role' => 'Fashion Curator & Stylist', 'rating' => 5,
             'comment' => 'Estilo Wear is pure luxury! The Lucknowi Chikankari Anarkali set I ordered for my sister\'s sangeet was so divine. The fabric quality, shadow embroidery, and fit exceeded all expectations.',
             'avatar' => '/images/testimonials/ananya.jpg'],
            ['id' => 2, 'name' => 'Priyanka Sen',      'city' => 'Kolkata',   'role' => 'Interior Architect',        'rating' => 5,
             'comment' => 'The Banarasi silk saree from Estilo Wear feels like holding heritage art in your hands. The Kadwa zari work is flawless. Arrived beautifully packaged in a signature wooden boutique box!',
             'avatar' => '/images/testimonials/priyanka.jpg'],
            ['id' => 3, 'name' => 'Dr. Radhika Menon', 'city' => 'Bengaluru', 'role' => 'Senior Physician',          'rating' => 5,
             'comment' => 'Finding office-wear ethnic outfits that balance comfort and executive style was always hard until I found Estilo Wear. Their hand block printed cotton kurtis are my daily go-to!',
             'avatar' => '/images/testimonials/radhika.jpg'],
        ];

        $instagramPosts = Image::where('category', 'instagram')->orderBy('sort_order')->get()->map(function($img, $index) {
            $likes = ['2.4k', '3.8k', '1.9k', '4.1k'];
            $tags = ['#EstiloWomen', '#SlayEveryLook', '#ChikankariLove', '#BoutiqueCouture'];
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
