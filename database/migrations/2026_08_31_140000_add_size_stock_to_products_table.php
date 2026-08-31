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
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'size_stock')) {
                $table->json('size_stock')->nullable()->after('sizes');
            }
            if (Schema::hasColumn('products', 'fabric')) {
                $table->string('fabric')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'size_stock')) {
                $table->dropColumn('size_stock');
            }
        });
    }
};
