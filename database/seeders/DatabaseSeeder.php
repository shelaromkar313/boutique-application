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

        // 3. Sales Associate / Executive User
        $associate = User::updateOrCreate(
            ['email' => 'associate@estilo.com'],
            [
                'name' => 'Pooja Verma',
                'phone' => '9876543211',
                'password' => Hash::make('password123'),
                'role' => 'sales_associate',
                'referral_code' => 'ESTILO-SA01',
                'commission_rate' => 12.00,
                'earnings' => 14580.00,
                'balance' => 6420.00,
                'upi_id' => 'pooja.verma@okhdfcbank',
            ]
        );

        // 4. Seed Coupons / Offers
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

        // 5. Seed Referral Sales for Sales Associate
        \App\Models\ReferralSale::updateOrCreate(
            ['order_no' => 'EST-ORD-9021'],
            [
                'associate_id' => $associate->id,
                'product_name' => 'Gulzar Handcrafted Chikankari Anarkali Set',
                'sale_amount' => 1899.00,
                'commission_rate' => 12.00,
                'commission_earned' => 227.88,
                'customer_name' => 'Meera Nair',
                'status' => 'paid',
                'created_at' => now()->subDays(12),
            ]
        );

        \App\Models\ReferralSale::updateOrCreate(
            ['order_no' => 'EST-ORD-9045'],
            [
                'associate_id' => $associate->id,
                'product_name' => 'Varanasi Royal Banarasi Katan Silk Saree',
                'sale_amount' => 3899.00,
                'commission_rate' => 12.00,
                'commission_earned' => 467.88,
                'customer_name' => 'Rhea Kapoor',
                'status' => 'paid',
                'created_at' => now()->subDays(5),
            ]
        );

        \App\Models\ReferralSale::updateOrCreate(
            ['order_no' => 'EST-ORD-9088'],
            [
                'associate_id' => $associate->id,
                'product_name' => 'Zahira Pure Handloom Mulberry Silk Kurti Set',
                'sale_amount' => 2499.00,
                'commission_rate' => 12.00,
                'commission_earned' => 299.88,
                'customer_name' => 'Sneha Patil',
                'status' => 'approved',
                'created_at' => now()->subDays(1),
            ]
        );

        // 6. Seed Sample Product Reviews (Including sample low rating for admin moderation testing)
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
