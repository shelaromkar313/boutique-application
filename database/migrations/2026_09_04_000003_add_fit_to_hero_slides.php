<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-slide photo fit (cover/contain) + focus so portrait uploads show fully.
     */
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_slides', 'fit_mode')) {
                $table->string('fit_mode', 20)->default('cover')->after('object_pos');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table) {
            if (Schema::hasColumn('hero_slides', 'fit_mode')) {
                $table->dropColumn('fit_mode');
            }
        });
    }
};
