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
        Schema::create('monthly_bazaar_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('package_name');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(10);
            $table->integer('sold_quantity')->default(0);
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_bazaar_items');
    }
};
