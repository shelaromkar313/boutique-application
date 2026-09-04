<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Homepage hero slideshow — fully editable by admin.
     */
    public function up(): void
    {
        if (!Schema::hasTable('hero_slides')) {
            Schema::create('hero_slides', function (Blueprint $table) {
                $table->id();
                $table->string('tag')->default('');
                $table->string('title1')->default('');
                $table->string('title2')->default('');
                $table->string('title3')->default('');
                $table->text('description')->nullable();
                $table->string('image', 500)->default('/hero/hero-main.jpg');
                $table->string('object_pos')->default('object-[center_top] sm:object-[center_top] md:object-[center_top]');
                $table->string('btn_text')->default('Shop Now');
                $table->string('btn_link')->default('/shop');
                $table->string('sub_text')->default('View New Arrivals');
                $table->string('sub_link')->default('/shop?filter=new');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
