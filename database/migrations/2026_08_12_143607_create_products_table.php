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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('est_id')->unique();
            $table->string('name');
            $table->string('category');
            $table->string('main_category');
            $table->string('sub_category');
            $table->string('fabric');
            $table->string('occasion');
            $table->decimal('price', 10, 2);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('discount')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->boolean('is_new_arrival')->default(false);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->string('sku')->unique();
            $table->json('colors');
            $table->json('sizes');
            $table->text('description');
            $table->json('details');
            $table->text('care');
            $table->json('images');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};