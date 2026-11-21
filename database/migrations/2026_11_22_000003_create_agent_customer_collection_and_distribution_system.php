<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enhance card_holder_collections table
        Schema::table('card_holder_collections', function (Blueprint $table) {
            if (!Schema::hasColumn('card_holder_collections', 'transaction_id')) {
                $table->string('transaction_id', 100)->nullable()->unique()->after('collection_no');
            }
            if (!Schema::hasColumn('card_holder_collections', 'card_number')) {
                $table->string('card_number', 50)->nullable()->index()->after('user_id');
            }
            if (!Schema::hasColumn('card_holder_collections', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('card_number');
            }
            if (!Schema::hasColumn('card_holder_collections', 'customer_phone')) {
                $table->string('customer_phone', 50)->nullable()->after('customer_name');
            }
            if (!Schema::hasColumn('card_holder_collections', 'agent_user_id')) {
                $table->unsignedBigInteger('agent_user_id')->nullable()->after('agent_point_id');
            }
            if (!Schema::hasColumn('card_holder_collections', 'market_type')) {
                $table->string('market_type', 50)->default('monthly_market')->after('agent_user_id'); // monthly_market, weekly_market, special_market, emergency_market, additional_collection
            }
            if (!Schema::hasColumn('card_holder_collections', 'market_month')) {
                $table->string('market_month', 50)->nullable()->after('market_type');
            }
            if (!Schema::hasColumn('card_holder_collections', 'market_year')) {
                $table->integer('market_year')->nullable()->after('market_month');
            }
            if (!Schema::hasColumn('card_holder_collections', 'market_week')) {
                $table->string('market_week', 50)->nullable()->after('market_year');
            }
            if (!Schema::hasColumn('card_holder_collections', 'collection_time')) {
                $table->time('collection_time')->nullable()->after('collection_date');
            }
            if (!Schema::hasColumn('card_holder_collections', 'total_items_count')) {
                $table->integer('total_items_count')->default(0)->after('collection_time');
            }
            if (!Schema::hasColumn('card_holder_collections', 'total_distributed_qty')) {
                $table->decimal('total_distributed_qty', 12, 2)->default(0)->after('total_items_count');
            }
            if (!Schema::hasColumn('card_holder_collections', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('total_distributed_qty');
            }
            if (!Schema::hasColumn('card_holder_collections', 'paid_amount')) {
                $table->decimal('paid_amount', 12, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('card_holder_collections', 'due_amount')) {
                $table->decimal('due_amount', 12, 2)->default(0)->after('paid_amount');
            }
            if (!Schema::hasColumn('card_holder_collections', 'delivery_type')) {
                $table->string('delivery_type', 50)->default('subsidized')->after('due_amount'); // free, subsidized, regular
            }
            if (!Schema::hasColumn('card_holder_collections', 'collected_by')) {
                $table->string('collected_by')->nullable()->after('delivered_by');
            }
            if (!Schema::hasColumn('card_holder_collections', 'collector_phone')) {
                $table->string('collector_phone', 50)->nullable()->after('collected_by');
            }
            if (!Schema::hasColumn('card_holder_collections', 'verification_method')) {
                $table->string('verification_method', 50)->default('physical_card')->after('otp_or_qr_code'); // physical_card, qr_code, otp_sms, cardholder_signature
            }
            if (!Schema::hasColumn('card_holder_collections', 'status')) {
                $table->string('status', 50)->default('completed')->after('verification_method'); // completed, partial, cancelled, returned
            }
            if (!Schema::hasColumn('card_holder_collections', 'delivery_note')) {
                $table->text('delivery_note')->nullable()->after('status');
            }
            if (!Schema::hasColumn('card_holder_collections', 'remarks')) {
                $table->text('remarks')->nullable()->after('delivery_note');
            }
        });

        // 2. Enhance card_holder_collection_items table
        Schema::table('card_holder_collection_items', function (Blueprint $table) {
            if (!Schema::hasColumn('card_holder_collection_items', 'category')) {
                $table->string('category', 100)->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('card_holder_collection_items', 'requested_quantity')) {
                $table->decimal('requested_quantity', 12, 2)->default(0)->after('unit');
            }
            if (!Schema::hasColumn('card_holder_collection_items', 'approved_quantity')) {
                $table->decimal('approved_quantity', 12, 2)->default(0)->after('requested_quantity');
            }
            if (!Schema::hasColumn('card_holder_collection_items', 'unit_rate')) {
                $table->decimal('unit_rate', 12, 2)->default(0)->after('remaining_quantity');
            }
            if (!Schema::hasColumn('card_holder_collection_items', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('unit_rate');
            }
            if (!Schema::hasColumn('card_holder_collection_items', 'notes')) {
                $table->text('notes')->nullable()->after('total_amount');
            }
        });

        // 3. Create agent_point_daily_closings table
        if (!Schema::hasTable('agent_point_daily_closings')) {
            Schema::create('agent_point_daily_closings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_point_id')->index();
                $table->unsignedBigInteger('agent_user_id')->nullable()->index();
                $table->date('closing_date')->index();
                $table->decimal('opening_stock', 14, 2)->default(0);
                $table->decimal('total_received_today', 14, 2)->default(0);
                $table->integer('total_customers_served')->default(0);
                $table->integer('total_monthly_market_count')->default(0);
                $table->integer('total_weekly_market_count')->default(0);
                $table->decimal('total_product_distributed', 14, 2)->default(0);
                $table->decimal('total_distribution_amount', 14, 2)->default(0);
                $table->decimal('total_return_quantity', 14, 2)->default(0);
                $table->decimal('damaged_quantity', 14, 2)->default(0);
                $table->decimal('stock_adjustment_quantity', 14, 2)->default(0);
                $table->decimal('closing_stock', 14, 2)->default(0);
                $table->integer('pending_orders_count')->default(0);
                $table->integer('completed_collections_count')->default(0);
                $table->integer('partial_collections_count')->default(0);
                $table->text('agent_note')->nullable();
                $table->string('status', 50)->default('submitted'); // submitted, verified, adjusted
                $table->unsignedBigInteger('verified_by')->nullable();
                $table->timestamp('verified_at')->nullable();
                $table->text('admin_note')->nullable();
                $table->timestamps();

                $table->unique(['agent_point_id', 'closing_date']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_point_daily_closings');
    }
};
