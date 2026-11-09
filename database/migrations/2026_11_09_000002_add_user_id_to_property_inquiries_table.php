<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('property_inquiries')) {
            Schema::table('property_inquiries', function (Blueprint $table) {
                if (!Schema::hasColumn('property_inquiries', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('id')->index();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('property_inquiries')) {
            Schema::table('property_inquiries', function (Blueprint $table) {
                if (Schema::hasColumn('property_inquiries', 'user_id')) {
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};

