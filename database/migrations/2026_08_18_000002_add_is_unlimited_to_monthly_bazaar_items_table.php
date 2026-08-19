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
        if (!Schema::hasColumn('monthly_bazaar_items', 'is_unlimited')) {
            Schema::table('monthly_bazaar_items', function (Blueprint $table) {
                $table->boolean('is_unlimited')->default(false)->after('quantity');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('monthly_bazaar_items', 'is_unlimited')) {
            Schema::table('monthly_bazaar_items', function (Blueprint $table) {
                $table->dropColumn('is_unlimited');
            });
        }
    }
};
