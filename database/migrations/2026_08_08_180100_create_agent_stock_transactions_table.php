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
        Schema::create('agent_stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id');
            $table->bigInteger('stock_id')->nullable();
            $table->string('stock_name');
            $table->string('transaction_type')->comment('allocation,sale');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_card_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('transaction_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_stock_transactions');
    }
};
