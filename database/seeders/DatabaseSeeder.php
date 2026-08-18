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
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(BoutiqueSeeder::class);
        $this->call(ImageSeeder::class);

        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'role' => 'customer',
            ]
        );

        User::firstOrCreate(
            ['email' => 'admin@estilo.com'],
            [
                'name' => 'Boutique Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
    }
}
