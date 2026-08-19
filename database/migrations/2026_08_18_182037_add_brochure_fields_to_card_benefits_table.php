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
        Schema::table('card_benefits', function (Blueprint $table) {
            $table->string('card_fee')->nullable()->after('validity');
            $table->string('investment_limit')->nullable()->after('card_fee');
            $table->string('monthly_profit')->nullable()->after('investment_limit');
            $table->string('withdrawal_notice')->nullable()->after('monthly_profit');
            $table->string('brochure_image')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_benefits', function (Blueprint $table) {
            $table->dropColumn(['card_fee', 'investment_limit', 'monthly_profit', 'withdrawal_notice', 'brochure_image']);
        });
    }
};
