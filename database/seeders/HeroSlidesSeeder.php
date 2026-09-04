<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlidesSeeder extends Seeder
{
    /**
     * Default hero banner slides (same as original homepage).
     */
    public function run(): void
    {
        $slides = [
            [
                'tag' => 'NEW COLLECTION — 2025',
                'title1' => 'Timeless',
                'title2' => 'Indian',
                'title3' => 'Elegance',
                'description' => 'Handcrafted Indian fashion for the modern woman — curated from artisan weavers across India.',
                'image' => '/hero/hero-main.jpg',
                'object_pos' => 'object-[85%_top] sm:object-[82%_top] md:object-[right_top]',
                'btn_text' => 'Shop Now',
                'btn_link' => '/shop',
                'sub_text' => 'View New Arrivals',
                'sub_link' => '/shop?filter=new',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'tag' => 'LUXURY SILK EDIT',
                'title1' => 'Royal',
                'title2' => 'Banarasi',
                'title3' => 'Sarees',
                'description' => 'Pure silk mark certified sarees featuring gold zari brocade & Kadwa weaving from Varanasi.',
                'image' => '/hero/hero-slide-2.jpg',
                'object_pos' => 'object-[center_top] sm:object-[center_top] md:object-[center_top]',
                'btn_text' => 'Explore Sarees',
                'btn_link' => '/shop?category=Sarees',
                'sub_text' => 'View Banarasi Silk',
                'sub_link' => '/shop?category=Sarees',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'tag' => 'ROYAL HERITAGE CRAFT',
                'title1' => 'Lucknowi',
                'title2' => 'Chikankari',
                'title3' => 'Couture',
                'description' => 'Airy mulmul cotton & silk Anarkalis with hand-embroidered shadow work & silver Mukaish.',
                'image' => '/hero/hero-slide-3.jpg',
                'object_pos' => 'object-[center_top] sm:object-[center_top] md:object-[center_top]',
                'btn_text' => 'Explore Chikankari',
                'btn_link' => '/shop?category=Chikankari+Kurtis',
                'sub_text' => 'View Anarkalis',
                'sub_link' => '/shop?category=Anarkali',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($slides as $s) {
            HeroSlide::updateOrCreate(
                ['tag' => $s['tag'], 'title1' => $s['title1']],
                $s
            );
        }
    }
}
