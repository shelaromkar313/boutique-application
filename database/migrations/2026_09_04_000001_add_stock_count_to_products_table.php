<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add visible per-product total stock count + backfill from size_stock.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'stock_count')) {
                $table->unsignedInteger('stock_count')->default(0)->after('size_stock');
            }
        });

        // Backfill: sum of size_stock, fallback to sizes*2 (same as Product::totalUnits)
        foreach (DB::table('products')->select('id', 'sizes', 'size_stock')->get() as $row) {
            $stock = $row->size_stock ? json_decode($row->size_stock, true) : null;
            if (is_array($stock) && count($stock) > 0) {
                $total = (int) array_sum($stock);
            } else {
                $sizes = $row->sizes ? json_decode($row->sizes, true) : [];
                $total = (is_array($sizes) && count($sizes) > 0) ? count($sizes) * 2 : 0;
            }
            DB::table('products')->where('id', $row->id)->update(['stock_count' => $total]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'stock_count')) {
                $table->dropColumn('stock_count');
            }
        });
    }
};
