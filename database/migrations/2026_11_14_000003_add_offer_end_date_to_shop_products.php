<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('shop_products') && !Schema::hasColumn('shop_products', 'offer_end_date')) {
            Schema::table('shop_products', function (Blueprint $table) {
                $table->dateTime('offer_end_date')->nullable()->after('is_monthly_bazar');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('shop_products') && Schema::hasColumn('shop_products', 'offer_end_date')) {
            Schema::table('shop_products', function (Blueprint $table) {
                $table->dropColumn('offer_end_date');
            });
        }
    }
};

