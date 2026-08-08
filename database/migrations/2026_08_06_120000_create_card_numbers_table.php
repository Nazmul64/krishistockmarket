<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_numbers', function (Blueprint $table) {
            $table->id();
            $table->string('number', 12)->unique();
            $table->double('amount', 8, 2)->default(300.00);
            $table->boolean('is_used')->default(false);
            $table->unsignedBigInteger('used_by')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->foreign('used_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_numbers');
    }
};
