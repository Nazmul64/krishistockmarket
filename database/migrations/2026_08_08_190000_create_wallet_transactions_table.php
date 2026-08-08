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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->bigInteger('user_id');
            $table->string('card_number')->nullable();
            $table->string('transaction_type')->comment('Registration Balance, Deposit, Stock Purchase, Monthly Market Purchase, Refund, Adjustment');
            $table->decimal('credit_amount', 12, 2)->default(0);
            $table->decimal('debit_amount', 12, 2)->default(0);
            $table->decimal('previous_balance', 12, 2)->default(0);
            $table->decimal('new_balance', 12, 2)->default(0);
            $table->string('payment_method')->default('Wallet Balance');
            $table->string('trx_number')->nullable();
            $table->string('status')->default('Approved')->comment('Approved, Pending, Rejected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
