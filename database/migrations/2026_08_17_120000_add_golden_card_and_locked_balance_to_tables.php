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
        if (Schema::hasTable('card_numbers')) {
            Schema::table('card_numbers', function (Blueprint $table) {
                if (!Schema::hasColumn('card_numbers', 'card_type')) {
                    $table->string('card_type', 50)->default('standard')->after('amount');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'locked_balance')) {
                    $table->decimal('locked_balance', 12, 2)->default(0.00)->after('balance');
                }
                if (!Schema::hasColumn('users', 'membership_card_type')) {
                    $table->string('membership_card_type', 50)->nullable()->after('locked_balance');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('card_numbers')) {
            Schema::table('card_numbers', function (Blueprint $table) {
                if (Schema::hasColumn('card_numbers', 'card_type')) {
                    $table->dropColumn('card_type');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'locked_balance')) {
                    $table->dropColumn('locked_balance');
                }
                if (Schema::hasColumn('users', 'membership_card_type')) {
                    $table->dropColumn('membership_card_type');
                }
            });
        }
    }
};
