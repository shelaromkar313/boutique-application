<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure sample customer users exist
        $meera = User::firstOrCreate(
            ['email' => 'meera.patel@gmail.com'],
            ['name' => 'Meera Patel', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98201 44520']
        );
        $ananya = User::firstOrCreate(
            ['email' => 'ananya.roy@outlook.com'],
            ['name' => 'Ananya Roy', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 97110 88231']
        );
        $rhea = User::firstOrCreate(
            ['email' => 'rhea.sundaram@gmail.com'],
            ['name' => 'Rhea Sundaram', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98450 12890']
        );
        $pooja = User::firstOrCreate(
            ['email' => 'pooja.deshmukh@gmail.com'],
            ['name' => 'Pooja Deshmukh', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98230 77412']
        );
        $divya = User::firstOrCreate(
            ['email' => 'divya.sen@yahoo.com'],
            ['name' => 'Divya Sen', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98300 55112']
        );
        $tanvi = User::firstOrCreate(
            ['email' => 'tanvi.m@gmail.com'],
            ['name' => 'Tanvi Malhotra', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98100 66223']
        );
        $shreya = User::firstOrCreate(
            ['email' => 'shreya.iyer@gmail.com'],
            ['name' => 'Shreya Iyer', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98400 77334']
        );
        $aditi = User::firstOrCreate(
            ['email' => 'aditi.rao@gmail.com'],
            ['name' => 'Aditi Rao', 'role' => 'customer', 'password' => Hash::make('Customer@123'), 'phone' => '+91 98800 88445']
        );

        // Fetch product IDs
        $p1 = Product::first();
        $p2 = Product::skip(1)->first() ?? $p1;
        $p3 = Product::skip(2)->first() ?? $p1;

        $p1Id = $p1 ? $p1->est_id : 'est-001';
        $p2Id = $p2 ? $p2->est_id : 'est-002';
        $p3Id = $p3 ? $p3->est_id : 'est-003';

        // Clear existing duplicate demo reviews
        Review::truncate();

        $reviews = [
            // 1. Five Star Review (Approved & Live)
            [
                'product_est_id' => $p1Id,
                'user_id'        => $meera->id,
                'user_name'      => 'Meera Patel',
                'rating'         => 5,
                'comment'        => 'The handwoven gold zari work on this Banarasi silk is breathtaking! Wore it for my cousin\'s wedding in Udaipur and received endless compliments. The drape and richness are truly royal.',
                'is_approved'    => true,
                'created_at'     => now()->subDays(2),
            ],

            // 2. Five Star Review (Approved & Live)
            [
                'product_est_id' => $p2Id,
                'user_id'        => $ananya->id,
                'user_name'      => 'Ananya Roy',
                'rating'         => 5,
                'comment'        => 'Exquisite craftsmanship and delicate needlework. The fabric is feather-light and the pastel shade looks even more regal in person. Highly recommended!',
                'is_approved'    => true,
                'created_at'     => now()->subDays(3),
            ],

            // 3. Two Star Low Rating (Approved - Ready for Admin to Edit/Boost to 5 Stars!)
            [
                'product_est_id' => $p1Id,
                'user_id'        => $divya->id,
                'user_name'      => 'Divya Sen',
                'rating'         => 2,
                'comment'        => 'The saree color was slightly darker than my phone screen showed, and the courier took 4 days to arrive in Kolkata. The blouse piece fabric was stiff before dry cleaning.',
                'is_approved'    => true,
                'created_at'     => now()->subDays(4),
            ],

            // 4. Three Star Review (Approved - Moderation candidate)
            [
                'product_est_id' => $p3Id,
                'user_id'        => $tanvi->id,
                'user_name'      => 'Tanvi Malhotra',
                'rating'         => 3,
                'comment'        => 'Good embroidery work on the yoke, but the waist fitting was slightly loose for size M. Had to get it altered locally. Quality of fabric is decent.',
                'is_approved'    => true,
                'created_at'     => now()->subDays(5),
            ],

            // 5. Five Star Review (Approved & Live)
            [
                'product_est_id' => $p3Id,
                'user_id'        => $rhea->id,
                'user_name'      => 'Rhea Sundaram',
                'rating'         => 5,
                'comment'        => 'Flawless fitting and luxury finish! The subtle gold zari shimmer under evening lights is pure elegance. Fast dispatch to Bengaluru as well.',
                'is_approved'    => true,
                'created_at'     => now()->subDays(6),
            ],

            // 6. Four Star Review (Approved & Live)
            [
                'product_est_id' => $p2Id,
                'user_id'        => $pooja->id,
                'user_name'      => 'Pooja Deshmukh',
                'rating'         => 4,
                'comment'        => 'Very comfortable and premium breathable georgette. Beautiful Chikankari floral motifs. Would love to see more pastel color options in the next collection.',
                'is_approved'    => true,
                'created_at'     => now()->subDays(7),
            ],

            // 7. One Star Review (Unapproved / Hidden - Low Rating for Admin moderation)
            [
                'product_est_id' => $p2Id,
                'user_id'        => $shreya->id,
                'user_name'      => 'Shreya Iyer',
                'rating'         => 1,
                'comment'        => 'Courier delivery boy called multiple times while I was at office. Box packaging was dented at the corner. Please use stronger exterior protective boxes.',
                'is_approved'    => false,
                'created_at'     => now()->subDays(1),
            ],

            // 8. Five Star Review (Unapproved / Pending Admin Approval)
            [
                'product_est_id' => $p1Id,
                'user_id'        => $aditi->id,
                'user_name'      => 'Aditi Rao',
                'rating'         => 5,
                'comment'        => 'Absolute heirloom quality! Felt like royalty wearing this on Diwali. Estilo\'s packaging with the silk dust bag and authenticity card is top notch.',
                'is_approved'    => false,
                'created_at'     => now()->subHours(8),
            ],
        ];

        foreach ($reviews as $revData) {
            Review::create($revData);
        }

        // Recalculate product rating statistics for all products
        Product::all()->each(function ($product) {
            if (method_exists($product, 'updateRatingStats')) {
                $product->updateRatingStats();
            }
        });
    }
}
