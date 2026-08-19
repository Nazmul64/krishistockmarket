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
        Schema::table('features', function (Blueprint $table) {
            if (!Schema::hasColumn('features', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
            if (Schema::hasColumn('features', 'icon')) {
                $table->string('icon')->nullable()->change();
            }
            if (Schema::hasColumn('features', 'color')) {
                $table->string('color')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('features', function (Blueprint $table) {
            if (Schema::hasColumn('features', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
