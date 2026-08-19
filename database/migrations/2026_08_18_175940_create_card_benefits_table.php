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
        Schema::create('card_benefits', function (Blueprint $table) {
            $table->id();
            $table->string('card_name'); // e.g. 'কৃষি এসএমই গোল্ড কার্ড (Krishi SME Gold Card)'
            $table->string('card_type')->default('gold'); // 'gold', 'red', 'silver', 'custom'
            $table->string('badge_text')->nullable(); // e.g. 'প্রিমিয়াম গোল্ডেন সুবিধা'
            $table->string('card_number_sample')->nullable(); // e.g. 'Card No: 100001'
            $table->string('validity')->nullable(); // e.g. '12/2030'
            $table->string('image')->nullable(); // Image path
            $table->text('short_description')->nullable();
            $table->json('facilities')->nullable(); // List of benefits / bullet points
            $table->string('card_color_theme')->default('gold'); // 'gold', 'red', 'blue', 'green'
            $table->string('action_button_text')->default('কার্ডের জন্য আবেদন করুন');
            $table->string('action_button_url')->nullable()->default('/register');
            $table->tinyInteger('status')->default(1); // 1 = Active, 0 = Inactive
            $table->integer('order_num')->default(0);
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
        Schema::dropIfExists('card_benefits');
    }
};
