<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('email');
            }
            if (!Schema::hasColumn('shop_orders', 'platform')) {
                $table->string('platform', 50)->default('Website')->after('ip_address');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (Schema::hasColumn('shop_orders', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
            if (Schema::hasColumn('shop_orders', 'platform')) {
                $table->dropColumn('platform');
            }
        });
    }
};

