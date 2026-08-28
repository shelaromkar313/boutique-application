<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');                               // Short label e.g. "Diwali Sale"
            $table->text('message');                              // Full ticker/banner text
            $table->string('type')->default('info');              // info | sale | event | coupon | alert
            $table->string('color')->default('bisque');           // bisque | amber | rose | emerald | blue | purple
            $table->string('icon')->nullable();                   // emoji e.g. 🎉
            $table->boolean('show_in_ticker')->default(true);     // Appear in marquee ticker
            $table->boolean('show_as_banner')->default(false);    // Appear as dismissible top banner
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
