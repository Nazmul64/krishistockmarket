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
        // 1. Add assigned_agent_point_id to users
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'assigned_agent_point_id')) {
                    $table->unsignedBigInteger('assigned_agent_point_id')->nullable()->after('agent_area');
                }
                if (!Schema::hasColumn('users', 'alt_phone')) {
                    $table->string('alt_phone', 30)->nullable()->after('phone');
                }
            });
        }

        // 2. Enhance agent_points table
        if (Schema::hasTable('agent_points')) {
            Schema::table('agent_points', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_points', 'company_name')) {
                    $table->string('company_name')->nullable()->after('agent_name');
                }
                if (!Schema::hasColumn('agent_points', 'alt_phone')) {
                    $table->string('alt_phone', 30)->nullable()->after('contact_number');
                }
                if (!Schema::hasColumn('agent_points', 'opening_date')) {
                    $table->date('opening_date')->nullable()->after('capacity');
                }
            });
        }

        // 3. Enhance agent_point_dispatches
        if (Schema::hasTable('agent_point_dispatches')) {
            Schema::table('agent_point_dispatches', function (Blueprint $table) {
                if (!Schema::hasColumn('agent_point_dispatches', 'supplier_id')) {
                    $table->unsignedBigInteger('supplier_id')->nullable()->after('agent_point_id');
                }
                if (!Schema::hasColumn('agent_point_dispatches', 'supplier_supply_id')) {
                    $table->unsignedBigInteger('supplier_supply_id')->nullable()->after('supplier_id');
                }
                if (!Schema::hasColumn('agent_point_dispatches', 'supply_flow')) {
                    $table->string('supply_flow', 40)->default('central_warehouse')->after('supplier_supply_id'); // central_warehouse, direct_agent_supply
                }
                if (!Schema::hasColumn('agent_point_dispatches', 'driver_mobile')) {
                    $table->string('driver_mobile', 30)->nullable()->after('driver_info');
                }
                if (!Schema::hasColumn('agent_point_dispatches', 'dispatch_attachment')) {
                    $table->string('dispatch_attachment')->nullable()->after('dispatch_note');
                }
                if (!Schema::hasColumn('agent_point_dispatches', 'receive_attachment')) {
                    $table->string('receive_attachment')->nullable()->after('receive_note');
                }
            });
        }

        // 4. Create agent_point_inventories
        if (!Schema::hasTable('agent_point_inventories')) {
            Schema::create('agent_point_inventories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_point_id');
                $table->string('product_name');
                $table->string('unit', 30)->default('KG');
                $table->decimal('opening_stock', 12, 2)->default(0);
                $table->decimal('received_stock', 12, 2)->default(0);
                $table->decimal('distributed_stock', 12, 2)->default(0);
                $table->decimal('damaged_stock', 12, 2)->default(0);
                $table->decimal('adjusted_stock', 12, 2)->default(0);
                $table->decimal('available_stock', 12, 2)->default(0);
                $table->decimal('reserved_stock', 12, 2)->default(0);
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['agent_point_id', 'product_name'], 'point_product_unique');
                $table->index('agent_point_id');
                $table->index('product_name');
            });
        }

        // 5. Create agent_point_stock_ledgers
        if (!Schema::hasTable('agent_point_stock_ledgers')) {
            Schema::create('agent_point_stock_ledgers', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('agent_point_id');
                $table->string('product_name');
                $table->string('unit', 30)->default('KG');
                $table->date('transaction_date');
                $table->string('transaction_type', 40); // opening, received, order_delivery, damaged, adjustment, transfer
                $table->decimal('quantity_in', 12, 2)->default(0);
                $table->decimal('quantity_out', 12, 2)->default(0);
                $table->decimal('balance_after', 12, 2)->default(0);
                $table->string('reference_no')->nullable(); // dispatch_no, order_no, etc.
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('recorded_by')->nullable();
                $table->timestamps();

                $table->index(['agent_point_id', 'product_name']);
                $table->index('transaction_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_point_stock_ledgers');
        Schema::dropIfExists('agent_point_inventories');
    }
};
