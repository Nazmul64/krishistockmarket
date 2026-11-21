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
        // 1. Enhance supplier_profiles
        if (Schema::hasTable('supplier_profiles')) {
            Schema::table('supplier_profiles', function (Blueprint $table) {
                if (!Schema::hasColumn('supplier_profiles', 'representative_name')) {
                    $table->string('representative_name')->nullable()->after('company_name');
                }
                if (!Schema::hasColumn('supplier_profiles', 'alt_phone')) {
                    $table->string('alt_phone', 30)->nullable()->after('representative_name');
                }
                if (!Schema::hasColumn('supplier_profiles', 'district')) {
                    $table->string('district')->nullable()->after('district_thana');
                }
                if (!Schema::hasColumn('supplier_profiles', 'thana')) {
                    $table->string('thana')->nullable()->after('district');
                }
                if (!Schema::hasColumn('supplier_profiles', 'business_type')) {
                    $table->string('business_type')->nullable()->after('opening_date');
                }
                if (!Schema::hasColumn('supplier_profiles', 'nid_trade_license')) {
                    $table->string('nid_trade_license')->nullable()->after('business_type');
                }
                if (!Schema::hasColumn('supplier_profiles', 'status')) {
                    $table->string('status', 20)->default('active')->after('nid_trade_license');
                }
                if (!Schema::hasColumn('supplier_profiles', 'logo')) {
                    $table->string('logo')->nullable()->after('status');
                }
            });
        }

        // 2. Enhance supplier_supplies
        if (Schema::hasTable('supplier_supplies')) {
            Schema::table('supplier_supplies', function (Blueprint $table) {
                if (!Schema::hasColumn('supplier_supplies', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable()->after('status');
                }
                if (!Schema::hasColumn('supplier_supplies', 'delivery_note')) {
                    $table->text('delivery_note')->nullable()->after('rejection_reason');
                }
                if (!Schema::hasColumn('supplier_supplies', 'stock_updated')) {
                    $table->boolean('stock_updated')->default(false)->after('approved_at');
                }
            });
        }

        // 3. Enhance supplier_payments
        if (Schema::hasTable('supplier_payments')) {
            Schema::table('supplier_payments', function (Blueprint $table) {
                if (!Schema::hasColumn('supplier_payments', 'receipt_no')) {
                    $table->string('receipt_no', 50)->nullable()->after('id');
                }
                if (!Schema::hasColumn('supplier_payments', 'cash_account')) {
                    $table->string('cash_account')->nullable()->after('payment_method');
                }
                if (!Schema::hasColumn('supplier_payments', 'attachment')) {
                    $table->string('attachment')->nullable()->after('transaction_id');
                }
            });
        }

        // 4. Enhance agent_points
        if (Schema::hasTable('agent_points')) {
            Schema::table('agent_points', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_points', 'code')) {
                    $table->string('code', 30)->nullable()->after('id');
                }
                if (!Schema::hasColumn('agent_points', 'agent_name')) {
                    $table->string('agent_name')->nullable()->after('name');
                }
                if (!Schema::hasColumn('agent_points', 'agent_user_id')) {
                    $table->unsignedBigInteger('agent_user_id')->nullable()->after('agent_name');
                }
                if (!Schema::hasColumn('agent_points', 'district')) {
                    $table->string('district')->nullable()->after('area');
                }
                if (!Schema::hasColumn('agent_points', 'thana_upazila')) {
                    $table->string('thana_upazila')->nullable()->after('district');
                }
                if (!Schema::hasColumn('agent_points', 'capacity')) {
                    $table->integer('capacity')->default(500)->after('status');
                }
            });
        }

        // 5. Monthly Market Requests
        if (!Schema::hasTable('monthly_market_requests')) {
            Schema::create('monthly_market_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_no', 40)->unique();
                $table->unsignedBigInteger('user_id');
                $table->string('card_number', 50)->nullable();
                $table->string('month', 20); // e.g. November
                $table->string('year', 10);  // e.g. 2026
                $table->date('request_date');
                $table->string('request_area');
                $table->string('district')->nullable();
                $table->string('upazila_thana')->nullable();
                $table->unsignedBigInteger('agent_point_id')->nullable();
                $table->string('agent_point_name')->nullable();
                $table->integer('total_items')->default(0);
                $table->decimal('total_estimated_amount', 12, 2)->default(0);
                $table->string('status', 30)->default('pending'); // pending, under_review, approved, rejected, processing, ready_for_collection, distributed, completed
                $table->string('collection_status', 30)->default('pending_collection'); // pending_collection, partially_collected, fully_collected, cancelled
                $table->text('collection_instruction')->nullable();
                $table->text('admin_note')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'month', 'year']);
                $table->index('request_area');
                $table->index('agent_point_id');
                $table->index('status');
            });
        }

        // 6. Monthly Market Request Items
        if (!Schema::hasTable('monthly_market_request_items')) {
            Schema::create('monthly_market_request_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('request_id');
                $table->string('product_name');
                $table->decimal('requested_quantity', 10, 2);
                $table->string('unit', 30)->default('KG');
                $table->decimal('approved_quantity', 10, 2)->default(0);
                $table->decimal('allocated_quantity', 10, 2)->default(0);
                $table->decimal('collected_quantity', 10, 2)->default(0);
                $table->decimal('remaining_quantity', 10, 2)->default(0);
                $table->decimal('unit_price', 10, 2)->default(0);
                $table->decimal('total_price', 12, 2)->default(0);
                $table->string('note')->nullable();
                $table->timestamps();

                $table->foreign('request_id')->references('id')->on('monthly_market_requests')->onDelete('cascade');
                $table->index('product_name');
            });
        }

        // 7. Agent Point Allocations
        if (!Schema::hasTable('agent_point_allocations')) {
            Schema::create('agent_point_allocations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_point_id');
                $table->string('month', 20);
                $table->string('year', 10);
                $table->string('product_name');
                $table->string('unit', 30)->default('KG');
                $table->decimal('requested_quantity', 12, 2)->default(0);
                $table->decimal('allocated_quantity', 12, 2)->default(0);
                $table->decimal('dispatched_quantity', 12, 2)->default(0);
                $table->decimal('received_quantity', 12, 2)->default(0);
                $table->decimal('distributed_quantity', 12, 2)->default(0);
                $table->decimal('remaining_quantity', 12, 2)->default(0);
                $table->string('status', 30)->default('allocated');
                $table->text('notes')->nullable();
                $table->text('allocation_reason')->nullable();
                $table->timestamps();

                $table->index(['agent_point_id', 'month', 'year']);
            });
        }

        // 8. Agent Point Dispatches
        if (!Schema::hasTable('agent_point_dispatches')) {
            Schema::create('agent_point_dispatches', function (Blueprint $table) {
                $table->id();
                $table->string('dispatch_no', 40)->unique();
                $table->string('distribution_month', 20);
                $table->string('distribution_year', 10);
                $table->unsignedBigInteger('agent_point_id');
                $table->string('area');
                $table->string('vehicle_info')->nullable();
                $table->string('driver_info')->nullable();
                $table->text('delivery_note')->nullable();
                $table->text('dispatch_note')->nullable();
                $table->date('dispatch_date');
                $table->unsignedBigInteger('dispatched_by')->nullable();
                $table->string('status', 30)->default('in_transit'); // pending, in_transit, received, completed
                $table->date('received_date')->nullable();
                $table->string('received_by')->nullable();
                $table->text('receive_note')->nullable();
                $table->timestamps();

                $table->index(['agent_point_id', 'status']);
            });
        }

        // 9. Agent Point Dispatch Items
        if (!Schema::hasTable('agent_point_dispatch_items')) {
            Schema::create('agent_point_dispatch_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dispatch_id');
                $table->string('product_name');
                $table->string('unit', 30)->default('KG');
                $table->decimal('sent_quantity', 10, 2);
                $table->decimal('received_quantity', 10, 2)->default(0);
                $table->decimal('short_quantity', 10, 2)->default(0);
                $table->decimal('damaged_quantity', 10, 2)->default(0);
                $table->string('notes')->nullable();
                $table->timestamps();

                $table->foreign('dispatch_id')->references('id')->on('agent_point_dispatches')->onDelete('cascade');
            });
        }

        // 10. Card Holder Collections
        if (!Schema::hasTable('card_holder_collections')) {
            Schema::create('card_holder_collections', function (Blueprint $table) {
                $table->id();
                $table->string('collection_no', 40)->unique();
                $table->unsignedBigInteger('request_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('agent_point_id')->nullable();
                $table->date('collection_date');
                $table->string('collection_status', 30)->default('fully_collected'); // partially_collected, fully_collected
                $table->string('delivered_by')->nullable();
                $table->string('received_by')->nullable();
                $table->string('otp_or_qr_code', 50)->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->index('request_id');
                $table->index('user_id');
            });
        }

        // 11. Card Holder Collection Items
        if (!Schema::hasTable('card_holder_collection_items')) {
            Schema::create('card_holder_collection_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('collection_id');
                $table->unsignedBigInteger('request_item_id');
                $table->string('product_name');
                $table->string('unit', 30)->default('KG');
                $table->decimal('allocated_quantity', 10, 2);
                $table->decimal('delivered_quantity', 10, 2);
                $table->decimal('remaining_quantity', 10, 2)->default(0);
                $table->timestamps();

                $table->foreign('collection_id')->references('id')->on('card_holder_collections')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_holder_collection_items');
        Schema::dropIfExists('card_holder_collections');
        Schema::dropIfExists('agent_point_dispatch_items');
        Schema::dropIfExists('agent_point_dispatches');
        Schema::dropIfExists('agent_point_allocations');
        Schema::dropIfExists('monthly_market_request_items');
        Schema::dropIfExists('monthly_market_requests');
    }
};
