<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
    */

    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string("stock_name");
            $table->longText("description")->nullable();
            $table->bigInteger("stock_quantity");
            $table->bigInteger("sold_quantity")->nullable();
            $table->string("status")->comment("active,inactive")->default('active');
            $table->timestamp("published_date");
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
        Schema::dropIfExists('stocks');
    }
}
