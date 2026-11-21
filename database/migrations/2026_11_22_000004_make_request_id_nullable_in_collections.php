<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw ALTER to modify request_id to nullable to handle foreign key / index safely
        try {
            DB::statement('ALTER TABLE `card_holder_collections` MODIFY `request_id` BIGINT UNSIGNED NULL');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE `card_holder_collection_items` MODIFY `request_item_id` BIGINT UNSIGNED NULL');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE `card_holder_collection_items` MODIFY `allocated_quantity` DECIMAL(10,2) NULL DEFAULT 0.00');
        } catch (\Exception $e) {}

        try {
            DB::statement('ALTER TABLE `card_holder_collection_items` MODIFY `delivered_quantity` DECIMAL(10,2) NOT NULL DEFAULT 0.00');
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
        // Safe down
    }
};
