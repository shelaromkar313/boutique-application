<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\ReferralSale;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoOrdersAndUsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create or ensure Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@estilo.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('Admin@123'),
                'phone' => '+91 90000 00001',
            ]
        );

        // 2. Create Sales Associates (Executives)
        $associateNeha = User::firstOrCreate(
            ['email' => 'neha.verma@estilo.com'],
            [
                'name'            => 'Neha Verma',
                'role'            => 'sales_associate',
                'password'        => Hash::make('Associate@123'),
                'phone'           => '+91 98200 11223',
                'referral_code'   => 'ESTILO-NEHA01',
                'commission_rate' => 10.00,
                'earnings'        => 12800.00,
                'balance'         => 4250.00,
                'upi_id'          => 'neha.verma@okaxis',
            ]
        );

        $associateRajesh = User::firstOrCreate(
            ['email' => 'rajesh.patel@estilo.com'],
            [
                'name'            => 'Rajesh Patel',
                'role'            => 'sales_associate',
                'password'        => Hash::make('Associate@123'),
                'phone'           => '+91 98110 33445',
                'referral_code'   => 'ESTILO-RAJESH02',
                'commission_rate' => 12.00,
                'earnings'        => 8900.00,
                'balance'         => 3180.00,
                'upi_id'          => 'rajesh.patel@okicici',
            ]
        );

        // 3. Create Customers
        $customerMeera = User::firstOrCreate(
            ['email' => 'meera.patel@gmail.com'],
            [
                'name'     => 'Meera Patel',
                'role'     => 'customer',
                'password' => Hash::make('Customer@123'),
                'phone'    => '+91 98201 44520',
            ]
        );

        $customerAnanya = User::firstOrCreate(
            ['email' => 'ananya.roy@outlook.com'],
            [
                'name'     => 'Ananya Roy',
                'role'     => 'customer',
                'password' => Hash::make('Customer@123'),
                'phone'    => '+91 97110 88231',
            ]
        );

        $customerRhea = User::firstOrCreate(
            ['email' => 'rhea.sundaram@gmail.com'],
            [
                'name'     => 'Rhea Sundaram',
                'role'     => 'customer',
                'password' => Hash::make('Customer@123'),
                'phone'    => '+91 98450 12890',
            ]
        );

        $customerPooja = User::firstOrCreate(
            ['email' => 'pooja.deshmukh@gmail.com'],
            [
                'name'     => 'Pooja Deshmukh',
                'role'     => 'customer',
                'password' => Hash::make('Customer@123'),
                'phone'    => '+91 98230 77412',
            ]
        );

        // Fetch products for items
        $p1 = Product::first();
        $p2 = Product::skip(1)->first() ?? $p1;
        $p3 = Product::skip(2)->first() ?? $p1;

        // 4. Create Order 1 - Confirmed with Referral
        $order1Items = [
            [
                'est_id'         => $p1 ? $p1->est_id : 'est-001',
                'name'           => $p1 ? $p1->name : 'Royal Banarasi Katan Silk Saree',
                'price'          => $p1 ? (float)$p1->price : 18500.00,
                'quantity'       => 1,
                'selectedColor'  => 'Crimson Red & Gold Zari',
                'selectedSize'   => 'Free Size (Includes Blouse Piece)',
                'image'          => $p1 && !empty($p1->images) ? $p1->images[0] : '/storage/hero/hero-main.jpg',
            ],
            [
                'est_id'         => $p2 ? $p2->est_id : 'est-002',
                'name'           => $p2 ? $p2->name : 'Lucknowi Chikankari Georgette Set',
                'price'          => $p2 ? (float)$p2->price : 4200.00,
                'quantity'       => 1,
                'selectedColor'  => 'Rose Blush',
                'selectedSize'   => 'M (Medium)',
                'image'          => $p2 && !empty($p2->images) ? $p2->images[0] : '/storage/hero/hero-main.jpg',
            ]
        ];

        $subtotal1 = array_sum(array_map(fn($it) => $it['price'] * $it['quantity'], $order1Items));
        $discount1 = 2270.00; // 10% coupon
        $total1 = $subtotal1 - $discount1;

        $order1 = Order::updateOrCreate(
            ['order_no' => 'EST-849201'],
            [
                'user_id'           => $customerMeera->id,
                'full_name'         => 'Meera Patel',
                'email'             => 'meera.patel@gmail.com',
                'phone'             => '+91 98201 44520',
                'address'           => 'Flat 1402, Sea Green Towers, Worli Sea Face',
                'city'              => 'Mumbai',
                'state'             => 'Maharashtra',
                'pincode'           => '400030',
                'subtotal'          => $subtotal1,
                'shipping'          => 0.00,
                'discount'          => $discount1,
                'total'             => $total1,
                'currency'          => 'INR',
                'payment_id'        => 'RAZORPAY-PAY_Nq817xZl09a',
                'razorpay_order_id' => 'order_Rz99812901',
                'signature'         => 'verified_sig_991823',
                'status'            => 'confirmed',
                'items'             => json_encode($order1Items),
                'note'              => 'Payment Mode: UPI Instant (Verified) • Coupon Applied: BOUTIQUE10 • Referred by: ESTILO-NEHA01',
                'created_at'        => now()->subHours(4),
            ]
        );

        ReferralSale::updateOrCreate(
            ['order_no' => 'EST-849201'],
            [
                'associate_id'      => $associateNeha->id,
                'product_name'      => $order1Items[0]['name'] . ' (+1 more)',
                'sale_amount'       => $total1,
                'commission_rate'   => 10.00,
                'commission_earned' => round($total1 * 0.10, 2),
                'customer_name'     => 'Meera Patel',
                'status'            => 'paid',
                'created_at'        => now()->subHours(4),
            ]
        );

        // 5. Create Order 2 - Dispatched
        $order2Items = [
            [
                'est_id'         => $p3 ? $p3->est_id : 'est-003',
                'name'           => $p3 ? $p3->name : 'Organza Hand-Embroidered Zari Anarkali',
                'price'          => $p3 ? (float)$p3->price : 12800.00,
                'quantity'       => 1,
                'selectedColor'  => 'Mint Green',
                'selectedSize'   => 'L (Large)',
                'image'          => $p3 && !empty($p3->images) ? $p3->images[0] : '/storage/hero/hero-main.jpg',
            ]
        ];

        $order2 = Order::updateOrCreate(
            ['order_no' => 'EST-783912'],
            [
                'user_id'           => $customerAnanya->id,
                'full_name'         => 'Ananya Roy',
                'email'             => 'ananya.roy@outlook.com',
                'phone'             => '+91 97110 88231',
                'address'           => 'B-42, Vasant Vihar',
                'city'              => 'New Delhi',
                'state'             => 'Delhi',
                'pincode'           => '110057',
                'subtotal'          => 12800.00,
                'shipping'          => 0.00,
                'discount'          => 0.00,
                'total'             => 12800.00,
                'currency'          => 'INR',
                'payment_id'        => 'RAZORPAY-PAY_Kp229aMw781',
                'razorpay_order_id' => 'order_Rz44109283',
                'signature'         => 'verified_sig_772183',
                'status'            => 'dispatched',
                'items'             => json_encode($order2Items),
                'note'              => 'Payment: Net Banking (HDFC) • Tracking AWB: BLUEDART-8891024',
                'created_at'        => now()->subDays(1),
            ]
        );

        // 6. Create Order 3 - Delivered
        $order3Items = [
            [
                'est_id'         => $p1 ? $p1->est_id : 'est-001',
                'name'           => 'Pure Kanjivaram Bridal Silk Saree',
                'price'          => 24500.00,
                'quantity'       => 1,
                'selectedColor'  => 'Temple Red & Gold',
                'selectedSize'   => 'Free Size',
                'image'          => $p1 && !empty($p1->images) ? $p1->images[0] : '/storage/hero/hero-main.jpg',
            ]
        ];

        $order3 = Order::updateOrCreate(
            ['order_no' => 'EST-651204'],
            [
                'user_id'           => $customerRhea->id,
                'full_name'         => 'Rhea Sundaram',
                'email'             => 'rhea.sundaram@gmail.com',
                'phone'             => '+91 98450 12890',
                'address'           => 'Villa 18, Palm Meadows, Whitefield',
                'city'              => 'Bengaluru',
                'state'             => 'Karnataka',
                'pincode'           => '560066',
                'subtotal'          => 24500.00,
                'shipping'          => 0.00,
                'discount'          => 2450.00,
                'total'             => 22050.00,
                'currency'          => 'INR',
                'payment_id'        => 'RAZORPAY-PAY_Jk991zRt443',
                'razorpay_order_id' => 'order_Rz11902844',
                'signature'         => 'verified_sig_338192',
                'status'            => 'delivered',
                'items'             => json_encode($order3Items),
                'note'              => 'Payment: Razorpay Card • Referred by: ESTILO-RAJESH02 • Delivered on 26 Aug',
                'created_at'        => now()->subDays(3),
            ]
        );

        ReferralSale::updateOrCreate(
            ['order_no' => 'EST-651204'],
            [
                'associate_id'      => $associateRajesh->id,
                'product_name'      => 'Pure Kanjivaram Bridal Silk Saree',
                'sale_amount'       => 22050.00,
                'commission_rate'   => 12.00,
                'commission_earned' => 2646.00,
                'customer_name'     => 'Rhea Sundaram',
                'status'            => 'paid',
                'created_at'        => now()->subDays(3),
            ]
        );

        // 7. Create Order 4 - Pending
        $order4Items = [
            [
                'est_id'         => $p2 ? $p2->est_id : 'est-002',
                'name'           => 'Chanderi Handloom Floral Dupatta Set',
                'price'          => 3850.00,
                'quantity'       => 2,
                'selectedColor'  => 'Pastel Lavender',
                'selectedSize'   => 'M',
                'image'          => $p2 && !empty($p2->images) ? $p2->images[0] : '/storage/hero/hero-main.jpg',
            ]
        ];

        $order4 = Order::updateOrCreate(
            ['order_no' => 'EST-912048'],
            [
                'user_id'           => $customerPooja->id,
                'full_name'         => 'Pooja Deshmukh',
                'email'             => 'pooja.deshmukh@gmail.com',
                'phone'             => '+91 98230 77412',
                'address'           => 'A-301, Marvel Imperial, Koregaon Park',
                'city'              => 'Pune',
                'state'             => 'Maharashtra',
                'pincode'           => '411001',
                'subtotal'          => 7700.00,
                'shipping'          => 199.00,
                'discount'          => 0.00,
                'total'             => 7899.00,
                'currency'          => 'INR',
                'payment_id'        => 'COD-PENDING',
                'status'            => 'pending',
                'items'             => json_encode($order4Items),
                'note'              => 'Payment: Cash on Delivery (COD) • Customer requested morning delivery',
                'created_at'        => now()->subMinutes(45),
            ]
        );
    }
}
