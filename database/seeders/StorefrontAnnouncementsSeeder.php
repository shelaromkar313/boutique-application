<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Coupon;
use Illuminate\Database\Seeder;

class StorefrontAnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear out old incomplete announcements to synchronize with live ticker
        Announcement::truncate();

        $announcements = [
            [
                'title'          => 'New Festive Season',
                'message'        => 'Flat 50% Off across all Pure Silk Sarees & Embroidered Anarkalis! Use Code FESTIVE50.',
                'type'           => 'festive',
                'color'          => 'amber',
                'icon'           => '✨',
                'show_in_ticker' => true,
                'show_as_banner' => false,
                'is_active'      => true,
                'starts_at'      => now()->subDays(1),
                'ends_at'        => now()->addDays(30),
            ],
            [
                'title'          => 'Complimentary Express Delivery',
                'message'        => 'Free Express Doorstep Shipping on all orders above ₹1,499 with Luxury Dust Bag Packaging.',
                'type'           => 'shipping',
                'color'          => 'emerald',
                'icon'           => '🚚',
                'show_in_ticker' => true,
                'show_as_banner' => false,
                'is_active'      => true,
                'starts_at'      => now()->subDays(5),
                'ends_at'        => null,
            ],
            [
                'title'          => 'Authentic Handloom Couture',
                'message'        => 'Certified GI Handwoven Silk directly crafted by master weavers of Varanasi & Lucknow.',
                'type'           => 'luxury',
                'color'          => 'rose',
                'icon'           => '💎',
                'show_in_ticker' => true,
                'show_as_banner' => false,
                'is_active'      => true,
                'starts_at'      => now()->subDays(10),
                'ends_at'        => null,
            ],
            [
                'title'          => 'Store Assurance',
                'message'        => 'Hassle-Free 7-Day Returns • 100% Secure Razorpay Checkout • Cash on Delivery (COD) Available.',
                'type'           => 'info',
                'color'          => 'blue',
                'icon'           => '🛡️',
                'show_in_ticker' => true,
                'show_as_banner' => false,
                'is_active'      => true,
                'starts_at'      => now()->subDays(10),
                'ends_at'        => null,
            ],
            [
                'title'          => 'Festive Silk Collection',
                'message'        => 'New Pure Katan Silk Sarees & Handcrafted Chikankari Georgette Suits now live!',
                'type'           => 'sale',
                'color'          => 'amber',
                'icon'           => '🎉',
                'show_in_ticker' => true,
                'show_as_banner' => false,
                'is_active'      => true,
                'starts_at'      => now()->subDays(2),
                'ends_at'        => now()->addDays(15),
            ],
            [
                'title'          => 'VIP Welcome Gift',
                'message'        => 'Get extra 15% instant discount on your first order. Use Coupon Code WELCOME15 at checkout.',
                'type'           => 'promo',
                'color'          => 'purple',
                'icon'           => '🎁',
                'show_in_ticker' => true,
                'show_as_banner' => false,
                'is_active'      => true,
                'starts_at'      => now()->subDays(7),
                'ends_at'        => now()->addDays(60),
            ],
        ];

        foreach ($announcements as $ann) {
            Announcement::create($ann);
        }

        // 2. Ensure Coupons with announcement enabled are active and synchronized
        $c1 = Coupon::where('code', 'BOUTIQUE10')->first();
        if ($c1) {
            $c1->is_announced = true;
            $c1->announcement_text = '🎟️ SPECIAL 10% OFF: Use code BOUTIQUE10 on orders above ₹1,999';
            $c1->save();
        }

        $c2 = Coupon::where('code', 'ROYAL20')->first();
        if ($c2) {
            $c2->is_announced = true;
            $c2->announcement_text = '👑 ROYAL FESTIVE 20% OFF: Use coupon code ROYAL20 on minimum ₹4,999';
            $c2->save();
        }
    }
}
