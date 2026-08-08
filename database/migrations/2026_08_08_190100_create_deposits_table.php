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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('card_number')->nullable();
            $table->decimal('deposit_amount', 12, 2);
            $table->string('payment_method');
            $table->string('pay_from_number')->nullable();
            $table->string('trx_number')->nullable();
            $table->string('screenshot')->nullable();
            $table->string('status')->default('pending')->comment('pending, approved, rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
