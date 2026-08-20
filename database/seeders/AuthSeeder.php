<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * AuthSeeder — Creates all authentication users:
 *
 *  Role             | Email                        | Phone       | Password
 * ──────────────────|─────────────────────────────|─────────────|──────────────
 *  admin            | admin@estilo.com             | 9000000001  | Admin@123
 *  admin            | superadmin@estilo.com        | 9000000002  | SuperAdmin@123
 *  sales_associate  | associate@estilo.com         | 9876543211  | Partner@123
 *  sales_associate  | priya.sharma@estilo.com      | 9876543213  | Partner@123
 *  sales_associate  | aarav.mehta@estilo.com       | 9876543214  | Partner@123
 *  customer         | test@example.com             | 9876543212  | Customer@123
 *  customer         | meera.nair@gmail.com         | 9123456789  | Customer@123
 *  customer         | divya.raj@gmail.com          | 9198765432  | Customer@123
 */
class AuthSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Admins ────────────────────────────────────────────────────────
        $this->upsertUser([
            'name'            => 'Boutique Admin',
            'email'           => 'admin@estilo.com',
            'phone'           => '9000000001',
            'password'        => Hash::make('Admin@123'),
            'role'            => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->upsertUser([
            'name'            => 'Super Admin',
            'email'           => 'superadmin@estilo.com',
            'phone'           => '9000000002',
            'password'        => Hash::make('SuperAdmin@123'),
            'role'            => 'admin',
            'email_verified_at' => now(),
        ]);

        // ── 2. Sales Associates / Partners ───────────────────────────────────
        $associates = [
            [
                'name'            => 'Pooja Verma',
                'email'           => 'associate@estilo.com',
                'phone'           => '9876543211',
                'password'        => Hash::make('Partner@123'),
                'role'            => 'sales_associate',
                'referral_code'   => 'ESTILO-SA01',
                'commission_rate' => 12.00,
                'earnings'        => 14580.00,
                'balance'         => 6420.00,
                'upi_id'          => 'pooja.verma@okhdfcbank',
                'email_verified_at' => now(),
            ],
            [
                'name'            => 'Priya Sharma',
                'email'           => 'priya.sharma@estilo.com',
                'phone'           => '9876543213',
                'password'        => Hash::make('Partner@123'),
                'role'            => 'sales_associate',
                'referral_code'   => 'ESTILO-SA02',
                'commission_rate' => 10.00,
                'earnings'        => 8240.00,
                'balance'         => 3120.00,
                'upi_id'          => 'priya.sharma@paytm',
                'email_verified_at' => now(),
            ],
            [
                'name'            => 'Aarav Mehta',
                'email'           => 'aarav.mehta@estilo.com',
                'phone'           => '9876543214',
                'password'        => Hash::make('Partner@123'),
                'role'            => 'sales_associate',
                'referral_code'   => 'ESTILO-SA03',
                'commission_rate' => 10.00,
                'earnings'        => 5300.00,
                'balance'         => 2100.00,
                'upi_id'          => 'aarav.mehta@upi',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($associates as $a) {
            $this->upsertUser($a);
        }

        // ── 3. Customers ─────────────────────────────────────────────────────
        $customers = [
            [
                'name'     => 'Test Customer',
                'email'    => 'test@example.com',
                'phone'    => '9876543212',
                'password' => Hash::make('Customer@123'),
                'role'     => 'customer',
                'email_verified_at' => now(),
            ],
            [
                'name'     => 'Meera Nair',
                'email'    => 'meera.nair@gmail.com',
                'phone'    => '9123456789',
                'password' => Hash::make('Customer@123'),
                'role'     => 'customer',
                'email_verified_at' => now(),
            ],
            [
                'name'     => 'Divya Raj',
                'email'    => 'divya.raj@gmail.com',
                'phone'    => '9198765432',
                'password' => Hash::make('Customer@123'),
                'role'     => 'customer',
                'email_verified_at' => now(),
            ],
            [
                'name'     => 'Ananya Singh',
                'email'    => 'ananya.singh@gmail.com',
                'phone'    => '9012345678',
                'password' => Hash::make('Customer@123'),
                'role'     => 'customer',
                'email_verified_at' => now(),
            ],
            [
                'name'     => 'Kavya Reddy',
                'email'    => 'kavya.reddy@gmail.com',
                'phone'    => '9345678901',
                'password' => Hash::make('Customer@123'),
                'role'     => 'customer',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($customers as $c) {
            $this->upsertUser($c);
        }

        // ── 4. Demo OTP codes (for testing phone login without SMS) ───────────
        $this->seedDemoOtpCodes();

        $this->command->info('✅ AuthSeeder complete:');
        $this->command->table(
            ['Role', 'Email', 'Phone', 'Password'],
            [
                ['Admin',          'admin@estilo.com',        '9000000001', 'Admin@123'],
                ['Admin',          'superadmin@estilo.com',   '9000000002', 'SuperAdmin@123'],
                ['Sales Partner',  'associate@estilo.com',    '9876543211', 'Partner@123'],
                ['Sales Partner',  'priya.sharma@estilo.com', '9876543213', 'Partner@123'],
                ['Sales Partner',  'aarav.mehta@estilo.com',  '9876543214', 'Partner@123'],
                ['Customer',       'test@example.com',        '9876543212', 'Customer@123'],
                ['Customer',       'meera.nair@gmail.com',    '9123456789', 'Customer@123'],
                ['Customer',       'divya.raj@gmail.com',     '9198765432', 'Customer@123'],
            ]
        );
    }

    /**
     * Insert or update a user by email (idempotent — safe to re-run).
     */
    private function upsertUser(array $data): void
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();

        DB::table('users')->upsert(
            [$data],
            ['email'],     // unique key to match on
            array_keys($data)  // columns to update on conflict
        );
    }

    /**
     * Seed static demo OTP codes so phone login works without a real SMS gateway.
     * Code 1234 is always valid for any registered phone during demo.
     */
    private function seedDemoOtpCodes(): void
    {
        $phones = [
            '9000000001', // admin
            '9000000002', // super admin
            '9876543211', // associate 1
            '9876543212', // customer
            '9876543213', // associate 2
            '9876543214', // associate 3
            '9123456789', // customer
            '9198765432', // customer
        ];

        DB::table('otp_codes')->truncate();

        foreach ($phones as $phone) {
            DB::table('otp_codes')->insert([
                'phone'      => $phone,
                'code'       => '1234',             // universal demo OTP
                'role'       => 'customer',
                'expires_at' => now()->addYears(1), // long-lived for demo
                'is_used'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
