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
        Schema::create('monthly_bazaar_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('item_id');
            $table->string('package_title');
            $table->decimal('price', 12, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 12, 2);
            $table->string('payment_method');
            $table->string('pay_from_number')->nullable();
            $table->string('trx_number')->nullable();
            $table->string('screenshot')->nullable();
            $table->string('status')->default('pending')->comment('pending,approved,rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_bazaar_orders');
    }
};
