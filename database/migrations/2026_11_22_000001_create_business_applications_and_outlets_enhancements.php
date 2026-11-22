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
        // 1. Business Applications Table
        if (!Schema::hasTable('business_applications')) {
            Schema::create('business_applications', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // investor, wholesale, franchise, umrah_haj
                $table->string('name');
                $table->string('phone');
                $table->string('whatsapp_number')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('division')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->string('profession')->nullable();
                $table->string('amount')->nullable(); // For investor
                $table->string('quantity')->nullable(); // For wholesale
                $table->string('showroom_size')->nullable(); // For franchise
                $table->string('showroom_location')->nullable(); // For franchise
                $table->string('age')->nullable(); // For umrah
                $table->string('marital_status')->nullable(); // For umrah
                $table->string('gender')->nullable(); // For umrah
                $table->string('previous_pilgrimage')->nullable(); // For umrah
                $table->string('has_passport')->nullable(); // For umrah
                $table->text('selection_reason')->nullable(); // For umrah
                $table->string('is_financially_capable')->nullable(); // For umrah
                $table->string('source_info')->nullable(); // For umrah
                $table->text('remarks')->nullable();
                $table->string('status')->default('pending'); // pending, contacted, approved, rejected
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }

        // 2. Outlets Enhancements to branches table
        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                if (!Schema::hasColumn('branches', 'google_map_url')) {
                    $table->text('google_map_url')->nullable()->after('longitude');
                }
                if (!Schema::hasColumn('branches', 'weekly_off_day')) {
                    $table->string('weekly_off_day')->nullable()->default('Daily Open')->after('closing_time');
                }
                if (!Schema::hasColumn('branches', 'open_days_text')) {
                    $table->string('open_days_text')->nullable()->default('Open 7 Days')->after('weekly_off_day');
                }
                if (!Schema::hasColumn('branches', 'operating_days')) {
                    $table->text('operating_days')->nullable()->after('open_days_text');
                }
                if (!Schema::hasColumn('branches', 'sort_order')) {
                    $table->integer('sort_order')->default(0)->after('operating_days');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_applications');

        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                $columns = ['google_map_url', 'weekly_off_day', 'open_days_text', 'operating_days', 'sort_order'];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('branches', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
