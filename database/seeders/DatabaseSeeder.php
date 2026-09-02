<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database
     */
    public function run(): void
    {    
        $this->call([
            BoutiqueSeeder::class,
            AuthSeeder::class,
            ImageSeeder::class,
            DemoOrdersAndUsersSeeder::class,
            ReviewsSeeder::class,
            StorefrontAnnouncementsSeeder::class,
        ]);

        // 1. Customer User
        $customer = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Ananya Sharma',
                'phone' => '9876543212',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        // 2. Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@estilo.com'],
            [
                'name' => 'Boutique Admin (Atelier Director)',
                'phone' => '9876543210',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        // 3. Seed Coupons / Offers
        \App\Models\Coupon::updateOrCreate(
            ['code' => 'BOUTIQUE10'],
            [
                'title' => '10% Welcome Discount',
                'discount_type' => 'percentage',
                'discount_value' => 10.00,
                'min_order_value' => 1499.00,
                'campaign_type' => 'monthly',
                'valid_until' => now()->addMonths(6),
                'usage_count' => 84,
                'is_active' => true,
            ]
        );

        \App\Models\Coupon::updateOrCreate(
            ['code' => 'FESTIVE25'],
            [
                'title' => 'Diwali & Festive Royal Gala 25% Off',
                'discount_type' => 'percentage',
                'discount_value' => 25.00,
                'min_order_value' => 2999.00,
                'campaign_type' => 'festival',
                'valid_until' => now()->addMonths(3),
                'usage_count' => 156,
                'is_active' => true,
            ]
        );

        \App\Models\Coupon::updateOrCreate(
            ['code' => 'ROYAL500'],
            [
                'title' => 'Flat ₹500 Off on Banarasi & Kanjivaram Sarees',
                'discount_type' => 'fixed',
                'discount_value' => 500.00,
                'min_order_value' => 3500.00,
                'campaign_type' => 'festival',
                'valid_until' => now()->addMonths(2),
                'usage_count' => 42,
                'is_active' => true,
            ]
        );

        // 5. Seed Sample Product Reviews (Including sample low rating for admin moderation testing)
        \App\Models\Review::updateOrCreate(
            ['product_est_id' => 'est-001', 'user_name' => 'Ananya Sharma'],
            [
                'rating' => 5,
                'comment' => 'The Chikankari handwork is breathtaking! The fitting preview was surprisingly accurate for my height.',
                'is_approved' => true,
                'created_at' => now()->subDays(3),
            ]
        );

        \App\Models\Review::updateOrCreate(
            ['product_est_id' => 'est-002', 'user_name' => 'Kavita Roy'],
            [
                'rating' => 5,
                'comment' => 'Pure silk fabric with genuine gold zari sheen. Received endless compliments at my cousin’s wedding!',
                'is_approved' => true,
                'created_at' => now()->subDays(7),
            ]
        );

        \App\Models\Review::updateOrCreate(
            ['product_est_id' => 'est-003', 'user_name' => 'Pooja Deshmukh'],
            [
                'rating' => 4,
                'comment' => 'Very lightweight organza drape and delicate floral painting. Lovely for summer evenings.',
                'is_approved' => true,
                'created_at' => now()->subDays(5),
            ]
        );

        \App\Models\Review::updateOrCreate(
            ['product_est_id' => 'est-001', 'user_name' => 'Meera Patel'],
            [
                'rating' => 2,
                'comment' => 'Delivery took 4 days and dupatta length was a bit long for my liking.',
                'is_approved' => true,
                'created_at' => now()->subDays(2),
            ]
        );
    }
}
