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
        Schema::table('monthly_bazaar_orders', function (Blueprint $table) {
            $table->string('request_area')->nullable()->after('screenshot');
            $table->string('agent_point')->nullable()->after('request_area');
            $table->integer('allocated_quantity')->default(0)->after('agent_point');
            $table->string('distribution_status')->default('pending')->after('allocated_quantity')->comment('pending,allocated,ready_for_collection,distributed,rejected');
            $table->text('collection_note')->nullable()->after('distribution_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_bazaar_orders', function (Blueprint $table) {
            $table->dropColumn(['request_area', 'agent_point', 'allocated_quantity', 'distribution_status', 'collection_note']);
        });
    }
};
