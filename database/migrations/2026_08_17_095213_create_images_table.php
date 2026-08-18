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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('name');                        // human-readable label
            $table->string('slug')->unique();              // unique key for lookup
            $table->text('url');                            // full image URL (Unsplash / local)
            $table->string('category')->nullable();        // e.g. hero, product, category, instagram, testimonial
            $table->string('section')->nullable();         // which page section this belongs to
            $table->string('alt_text')->nullable();        // alt attribute for accessibility
            $table->unsignedInteger('width')->nullable();  // original image width
            $table->unsignedInteger('height')->nullable(); // original image height
            $table->boolean('is_active')->default(true);   // soft toggle
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['category', 'section']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
