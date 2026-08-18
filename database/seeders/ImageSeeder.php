<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            // ═══════════════════════════════════════════
            // HERO SECTION
            // ═══════════════════════════════════════════
            ['name' => 'Hero Main Model',              'slug' => 'hero-main',                'url' => '/images/hero/hero-main.jpg',                      'category' => 'hero',        'section' => 'home-hero',            'alt_text' => 'Estilo Wear — Model wearing Luxury Royal Kurti', 'sort_order' => 1],

            // ═══════════════════════════════════════════
            // CIRCULAR CATEGORIES (Home page)
            // ═══════════════════════════════════════════
            ['name' => 'Circle — New Arrivals',         'slug' => 'circle-new-arrivals',      'url' => '/images/circles/new-arrivals.jpg',                'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'New Arrivals',              'sort_order' => 1],
            ['name' => 'Circle — Kurtis',               'slug' => 'circle-kurtis',            'url' => '/images/circles/kurtis.jpg',                      'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Kurtis',                    'sort_order' => 2],
            ['name' => 'Circle — Cotton Kurtis',        'slug' => 'circle-cotton-kurtis',     'url' => '/images/circles/cotton-kurtis.jpg',               'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Cotton Kurtis',             'sort_order' => 3],
            ['name' => 'Circle — Designer Sarees',      'slug' => 'circle-designer-sarees',   'url' => '/images/circles/designer-sarees.jpg',             'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Designer Sarees',           'sort_order' => 4],
            ['name' => 'Circle — Anarkali',             'slug' => 'circle-anarkali',          'url' => '/images/circles/anarkali.jpg',                    'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Anarkali',                  'sort_order' => 5],
            ['name' => 'Circle — Co-Ord Sets',          'slug' => 'circle-coord-sets',        'url' => '/images/circles/coord-sets.jpg',                  'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Co-Ord Sets',               'sort_order' => 6],
            ['name' => 'Circle — Wedding Collection',   'slug' => 'circle-wedding',           'url' => '/images/circles/wedding.jpg',                     'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Wedding Collection',        'sort_order' => 7],
            ['name' => 'Circle — Festive Wear',         'slug' => 'circle-festive',           'url' => '/images/circles/festive.jpg',                     'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Festive Wear',              'sort_order' => 8],
            ['name' => 'Circle — Office Wear',          'slug' => 'circle-office',            'url' => '/images/circles/office.jpg',                      'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Office Wear',               'sort_order' => 9],
            ['name' => 'Circle — Party Wear',           'slug' => 'circle-party',             'url' => '/images/circles/party.jpg',                       'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Party Wear',                'sort_order' => 10],
            ['name' => 'Circle — Ethnic Dresses',       'slug' => 'circle-ethnic',            'url' => '/images/circles/ethnic-dresses.jpg',              'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Ethnic Dresses',            'sort_order' => 11],
            ['name' => 'Circle — Designer Collection',  'slug' => 'circle-designer',          'url' => '/images/circles/designer-collection.jpg',         'category' => 'circle',      'section' => 'home-circles',         'alt_text' => 'Designer Collection',       'sort_order' => 12],

            // ═══════════════════════════════════════════
            // FEATURED CATEGORIES GRID
            // ═══════════════════════════════════════════
            ['name' => 'Category — Kurtis & Suits',         'slug' => 'cat-kurtis',           'url' => '/images/categories/kurtis-suits.jpg',             'category' => 'category',    'section' => 'home-categories',      'alt_text' => 'Kurtis & Suits',            'sort_order' => 1],
            ['name' => 'Category — Luxury Sarees',          'slug' => 'cat-sarees',           'url' => '/images/categories/luxury-sarees.jpg',            'category' => 'category',    'section' => 'home-categories',      'alt_text' => 'Luxury Sarees',             'sort_order' => 2],
            ['name' => 'Category — Co-Ord Sets',            'slug' => 'cat-coord',            'url' => '/images/categories/coord-sets.jpg',               'category' => 'category',    'section' => 'home-categories',      'alt_text' => 'Co-Ord Sets',               'sort_order' => 3],
            ['name' => 'Category — Ethnic Dresses',         'slug' => 'cat-ethnic',           'url' => '/images/categories/ethnic-dresses.jpg',           'category' => 'category',    'section' => 'home-categories',      'alt_text' => 'Ethnic & Boutique Dresses', 'sort_order' => 4],
            ['name' => 'Category — Festive & Wedding',      'slug' => 'cat-festive-wedding',  'url' => '/images/categories/festive-wedding.jpg',          'category' => 'category',    'section' => 'home-categories',      'alt_text' => 'Festive & Wedding Couture', 'sort_order' => 5],

            // ═══════════════════════════════════════════
            // PRODUCT IMAGES
            // ═══════════════════════════════════════════
            ['name' => 'Product — Chikankari Anarkali',     'slug' => 'product-est-001',      'url' => '/images/products/est-001-chikankari-anarkali.jpg', 'category' => 'product',     'section' => 'products',             'alt_text' => 'Gulzar Handcrafted Chikankari Anarkali Set',       'sort_order' => 1],
            ['name' => 'Product — Banarasi Silk Saree',     'slug' => 'product-est-002',      'url' => '/images/products/est-002-banarasi-saree.jpg',      'category' => 'product',     'section' => 'products',             'alt_text' => 'Varanasi Royal Zari Banarasi Silk Saree',          'sort_order' => 2],
            ['name' => 'Product — Organza Saree',           'slug' => 'product-est-003',      'url' => '/images/products/est-003-organza-saree.jpg',       'category' => 'product',     'section' => 'products',             'alt_text' => 'Noor Hand-Painted Floral Organza Saree',           'sort_order' => 3],
            ['name' => 'Product — Peplum Co-Ord Set',       'slug' => 'product-est-004',      'url' => '/images/products/est-004-coord-set.jpg',           'category' => 'product',     'section' => 'products',             'alt_text' => 'Raysha Silk Blend Printed Peplum Co-Ord Set',      'sort_order' => 4],
            ['name' => 'Product — Cotton Straight Kurti',   'slug' => 'product-est-005',      'url' => '/images/products/est-005-cotton-kurti.jpg',        'category' => 'product',     'section' => 'products',             'alt_text' => 'Aarya Hand Block Printed Cotton Straight Kurti',   'sort_order' => 5],
            ['name' => 'Product — Zardozi Silk Anarkali',   'slug' => 'product-est-006',      'url' => '/images/products/est-006-silk-anarkali.jpg',       'category' => 'product',     'section' => 'products',             'alt_text' => 'Sultana Royal Zardozi Embroidered Silk Anarkali',  'sort_order' => 6],
            ['name' => 'Product — Chanderi Boutique Dress', 'slug' => 'product-est-007',      'url' => '/images/products/est-007-boutique-dress.jpg',      'category' => 'product',     'section' => 'products',             'alt_text' => 'Kashvi Chanderi Silk Foil Printed Boutique Dress', 'sort_order' => 7],
            ['name' => 'Product — Handloom Linen Saree',    'slug' => 'product-est-008',      'url' => '/images/products/est-008-linen-saree.jpg',         'category' => 'product',     'section' => 'products',             'alt_text' => 'Manjari Organic Handloom Linen Saree',             'sort_order' => 8],
            ['name' => 'Product — Georgette Designer Kurti','slug' => 'product-est-009',      'url' => '/images/products/est-009-designer-kurti.jpg',      'category' => 'product',     'section' => 'products',             'alt_text' => 'Reeva Sequin Embroidered Georgette Designer Kurti','sort_order' => 9],
            ['name' => 'Product — Mulmul Jamdani Saree',    'slug' => 'product-est-010',      'url' => '/images/products/est-010-jamdani-saree.jpg',       'category' => 'product',     'section' => 'products',             'alt_text' => 'Meera Handloom Mulmul Cotton Jamdani Saree',       'sort_order' => 10],
            ['name' => 'Product — Angrakha Ethnic Dress',   'slug' => 'product-est-011',      'url' => '/images/products/est-011-ethnic-dress.jpg',        'category' => 'product',     'section' => 'products',             'alt_text' => 'Tarang Printed Angrakha Style Ethnic Dress',       'sort_order' => 11],
            ['name' => 'Product — Kanjivaram Silk Saree',   'slug' => 'product-est-012',      'url' => '/images/products/est-012-kanjivaram-saree.jpg',    'category' => 'product',     'section' => 'products',             'alt_text' => 'Bhavya Pure Kanjivaram Golden Zari Silk Saree',    'sort_order' => 12],

            // ═══════════════════════════════════════════
            // CHIKANKARI EDITORIAL BANNER
            // ═══════════════════════════════════════════
            ['name' => 'Editorial — Chikankari Banner',     'slug' => 'editorial-chikankari',  'url' => '/images/editorial/chikankari-banner.jpg',         'category' => 'editorial',   'section' => 'home-editorial',       'alt_text' => 'Lucknowi Chikankari Artistry',                     'sort_order' => 1],

            // ═══════════════════════════════════════════
            // SHOP BY OCCASION
            // ═══════════════════════════════════════════
            ['name' => 'Occasion — Wedding',                'slug' => 'occasion-wedding',      'url' => '/images/occasions/wedding.jpg',                   'category' => 'occasion',    'section' => 'home-occasions',       'alt_text' => 'Wedding Collection',        'sort_order' => 1],
            ['name' => 'Occasion — Festive',                'slug' => 'occasion-festive',      'url' => '/images/occasions/festive.jpg',                   'category' => 'occasion',    'section' => 'home-occasions',       'alt_text' => 'Festive Wear',              'sort_order' => 2],
            ['name' => 'Occasion — Office',                 'slug' => 'occasion-office',       'url' => '/images/occasions/office.jpg',                    'category' => 'occasion',    'section' => 'home-occasions',       'alt_text' => 'Office Wear',               'sort_order' => 3],
            ['name' => 'Occasion — Casual',                 'slug' => 'occasion-casual',       'url' => '/images/occasions/casual.jpg',                    'category' => 'occasion',    'section' => 'home-occasions',       'alt_text' => 'Casual Wear',               'sort_order' => 4],
            ['name' => 'Occasion — Party',                  'slug' => 'occasion-party',        'url' => '/images/occasions/party.jpg',                     'category' => 'occasion',    'section' => 'home-occasions',       'alt_text' => 'Party Wear',                'sort_order' => 5],

            // ═══════════════════════════════════════════
            // TESTIMONIAL AVATARS
            // ═══════════════════════════════════════════
            ['name' => 'Avatar — Ananya Sharma',            'slug' => 'avatar-ananya',         'url' => '/images/testimonials/ananya.jpg',                 'category' => 'testimonial','section' => 'home-testimonials',    'alt_text' => 'Ananya Sharma',             'sort_order' => 1],
            ['name' => 'Avatar — Priyanka Sen',             'slug' => 'avatar-priyanka',       'url' => '/images/testimonials/priyanka.jpg',               'category' => 'testimonial','section' => 'home-testimonials',    'alt_text' => 'Priyanka Sen',              'sort_order' => 2],
            ['name' => 'Avatar — Dr. Radhika Menon',        'slug' => 'avatar-radhika',        'url' => '/images/testimonials/radhika.jpg',                'category' => 'testimonial','section' => 'home-testimonials',    'alt_text' => 'Dr. Radhika Menon',         'sort_order' => 3],

            // ═══════════════════════════════════════════
            // INSTAGRAM LOOKBOOK
            // ═══════════════════════════════════════════
            ['name' => 'Instagram — Look 1',                'slug' => 'insta-1',               'url' => '/images/instagram/look-1.jpg',                    'category' => 'instagram',   'section' => 'home-instagram',       'alt_text' => 'Instagram Look 1 #EstiloWomen',   'sort_order' => 1],
            ['name' => 'Instagram — Look 2',                'slug' => 'insta-2',               'url' => '/images/instagram/look-2.jpg',                    'category' => 'instagram',   'section' => 'home-instagram',       'alt_text' => 'Instagram Look 2 #SlayEveryLook', 'sort_order' => 2],
            ['name' => 'Instagram — Look 3',                'slug' => 'insta-3',               'url' => '/images/instagram/look-3.jpg',                    'category' => 'instagram',   'section' => 'home-instagram',       'alt_text' => 'Instagram Look 3 #ChikankariLove','sort_order' => 3],
            ['name' => 'Instagram — Look 4',                'slug' => 'insta-4',               'url' => '/images/instagram/look-4.jpg',                    'category' => 'instagram',   'section' => 'home-instagram',       'alt_text' => 'Instagram Look 4 #BoutiqueCouture','sort_order'=> 4],

            // ═══════════════════════════════════════════
            // NAVBAR / FOOTER / MISC
            // ═══════════════════════════════════════════
            ['name' => 'Logo',                              'slug' => 'site-logo',             'url' => '/images/hero/hero-main.jpg',                      'category' => 'branding',    'section' => 'global',               'alt_text' => 'Estilo Wear Logo',          'sort_order' => 1],
        ];

        foreach ($images as $img) {
            Image::updateOrCreate(
                ['slug' => $img['slug']],
                $img
            );
        }

        $this->command->info('✅ Seeded ' . count($images) . ' downloaded local images into the `images` table.');
    }
}
