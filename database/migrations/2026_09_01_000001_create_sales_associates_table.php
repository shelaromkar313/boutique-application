<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('sales_associates')) {
            Schema::create('sales_associates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->string('referral_code')->nullable()->unique();
                $table->decimal('commission_rate', 5, 2)->default(10.00);
                $table->decimal('earnings', 10, 2)->default(0.00);
                $table->decimal('balance', 10, 2)->default(0.00);
                $table->string('upi_id')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        $salesUsers = DB::table('users')
            ->whereIn('role', ['sales_associate', 'sales_executive', 'associate'])
            ->get();

        foreach ($salesUsers as $user) {
            DB::table('sales_associates')->updateOrInsert(
                ['user_id' => $user->id],
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'referral_code' => $user->referral_code,
                    'commission_rate' => $user->commission_rate ?? 10.00,
                    'earnings' => $user->earnings ?? 0.00,
                    'balance' => $user->balance ?? 0.00,
                    'upi_id' => $user->upi_id,
                    'status' => 'active',
                    'created_at' => $user->created_at ?? now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_associates');
    }
};
