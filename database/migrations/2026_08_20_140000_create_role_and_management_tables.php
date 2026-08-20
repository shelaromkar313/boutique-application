<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add fields to users table if they do not exist
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code')->nullable()->unique()->after('role');
            }
            if (!Schema::hasColumn('users', 'commission_rate')) {
                $table->decimal('commission_rate', 5, 2)->default(10.00)->after('referral_code'); // e.g. 10%
            }
            if (!Schema::hasColumn('users', 'earnings')) {
                $table->decimal('earnings', 10, 2)->default(0.00)->after('commission_rate');
            }
            if (!Schema::hasColumn('users', 'balance')) {
                $table->decimal('balance', 10, 2)->default(0.00)->after('earnings');
            }
            if (!Schema::hasColumn('users', 'upi_id')) {
                $table->string('upi_id')->nullable()->after('balance');
            }
        });

        // 2. Create coupons & offers table
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('title');
                $table->string('discount_type')->default('percentage'); // percentage or fixed
                $table->decimal('discount_value', 8, 2)->default(10.00);
                $table->decimal('min_order_value', 10, 2)->default(0.00);
                $table->string('campaign_type')->default('festival'); // festival, monthly, flash_sale, referral
                $table->date('valid_until')->nullable();
                $table->unsignedInteger('usage_count')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Create reviews and ratings table
        if (!Schema::hasTable('reviews')) {
            Schema::create('reviews', function (Blueprint $table) {
                $table->id();
                $table->string('product_est_id')->index();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('user_name');
                $table->unsignedTinyInteger('rating')->default(5);
                $table->text('comment')->nullable();
                $table->boolean('is_approved')->default(true);
                $table->timestamps();
            });
        }

        // 4. Create referral sales & commissions table
        if (!Schema::hasTable('referral_sales')) {
            Schema::create('referral_sales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('associate_id')->constrained('users')->cascadeOnDelete();
                $table->string('order_no')->index();
                $table->string('product_name');
                $table->decimal('sale_amount', 10, 2);
                $table->decimal('commission_rate', 5, 2);
                $table->decimal('commission_earned', 10, 2);
                $table->string('customer_name')->nullable();
                $table->string('status')->default('approved'); // pending, approved, paid
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_sales');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('coupons');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'referral_code', 'commission_rate', 'earnings', 'balance', 'upi_id']);
        });
    }
};
