<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buy_stocks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->bigInteger('payment_id');
            $table->string('sceenshort');
            $table->string('pay_from_number');
            $table->string('trx_number');
            $table->bigInteger('stock_id');
            $table->bigInteger('buy_quantiy');
            $table->float('buyed_price');
            $table->timestamp('buyed_date');
            $table->string('status')->comment('aproved,pending,sellpending,sellaproved');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buy_stocks');
    }
}
