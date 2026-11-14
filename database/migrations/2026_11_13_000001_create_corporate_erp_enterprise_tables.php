<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Complete Corporate Store, Inventory, Asset,
     * Maintenance & Financial Management ERP System.
     */
    public function up(): void
    {
        // 1. Company Profile & Enterprise Master Data
        if (!Schema::hasTable('erp_company_profiles')) {
            Schema::create('erp_company_profiles', function (Blueprint $table) {
                $table->id();
                $table->string('company_name');
                $table->string('company_name_bn')->nullable();
                $table->string('logo')->nullable();
                $table->text('address')->nullable();
                $table->text('corporate_address')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('website')->nullable();
                $table->string('trade_license')->nullable();
                $table->string('tin_no')->nullable();
                $table->string('bin_no')->nullable();
                $table->string('registration_no')->nullable();
                $table->string('fiscal_year')->default('2026-2027');
                $table->string('currency')->default('BDT (৳)');
                $table->text('description')->nullable();
                $table->string('authorized_person')->nullable();
                $table->string('signature_img')->nullable();
                $table->string('seal_img')->nullable();
                $table->timestamps();
            });
        }

        // 2. Warehouses & Stores
        if (!Schema::hasTable('erp_warehouses')) {
            Schema::create('erp_warehouses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->unique();
                $table->string('warehouse_type')->default('central'); // central, office, it, furniture, stationery, vehicle_parts, branch
                $table->string('location')->nullable();
                $table->string('manager_name')->nullable();
                $table->string('store_keeper_name')->nullable();
                $table->string('contact_phone')->nullable();
                $table->string('capacity')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Item Categories & Subcategories
        if (!Schema::hasTable('erp_item_categories')) {
            Schema::create('erp_item_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->unique();
                $table->string('type')->default('general'); // stationery, furniture, it_equipment, electrical, kitchen_utilities, vehicle_items, maintenance_materials, general
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_item_subcategories')) {
            Schema::create('erp_item_subcategories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('erp_item_categories')->cascadeOnDelete();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Units of Measurement
        if (!Schema::hasTable('erp_item_units')) {
            Schema::create('erp_item_units', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('symbol'); // PCS, Box, Packet, Dozen, Ream, Meter, KG, Liter, Set, Pair, Carton
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Items / Inventory Catalog
        if (!Schema::hasTable('erp_items')) {
            Schema::create('erp_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('erp_item_categories')->nullOnDelete();
                $table->foreignId('subcategory_id')->nullable()->constrained('erp_item_subcategories')->nullOnDelete();
                $table->foreignId('unit_id')->nullable()->constrained('erp_item_units')->nullOnDelete();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->unique(); // e.g. ITM-0001
                $table->string('sku')->nullable();
                $table->string('barcode')->nullable();
                $table->string('qr_code')->nullable();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('tracking_type')->default('quantity'); // quantity, serial, batch, asset
                $table->decimal('purchase_price', 16, 2)->default(0);
                $table->decimal('selling_price', 16, 2)->default(0);
                $table->decimal('current_stock', 14, 2)->default(0);
                $table->decimal('min_stock', 14, 2)->default(5);
                $table->decimal('max_stock', 14, 2)->default(500);
                $table->decimal('reorder_level', 14, 2)->default(10);
                $table->foreignId('default_warehouse_id')->nullable()->constrained('erp_warehouses')->nullOnDelete();
                $table->string('supplier_name')->nullable();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->string('warranty_period')->nullable();
                $table->date('expiry_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 6. Stock Transactions (In, Out, Transfer, Return, Adjustment)
        if (!Schema::hasTable('erp_stock_transactions')) {
            Schema::create('erp_stock_transactions', function (Blueprint $table) {
                $table->id();
                $table->string('transaction_number')->unique();
                $table->string('transaction_type'); // stock_in, stock_out, transfer, return, adjustment, purchase_receive
                $table->foreignId('item_id')->constrained('erp_items')->cascadeOnDelete();
                $table->foreignId('warehouse_id')->nullable()->constrained('erp_warehouses')->nullOnDelete();
                $table->foreignId('from_warehouse_id')->nullable()->constrained('erp_warehouses')->nullOnDelete();
                $table->foreignId('to_warehouse_id')->nullable()->constrained('erp_warehouses')->nullOnDelete();
                $table->decimal('quantity', 14, 2)->default(0);
                $table->decimal('unit_price', 16, 2)->default(0);
                $table->decimal('total_amount', 16, 2)->default(0);
                $table->string('reference_no')->nullable(); // PO No, Challan No, Requisition No
                $table->string('reference_type')->nullable();
                $table->unsignedBigInteger('department_id')->nullable();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('purpose')->nullable();
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 7. Stock Requisitions & Items
        if (!Schema::hasTable('erp_stock_requisitions')) {
            Schema::create('erp_stock_requisitions', function (Blueprint $table) {
                $table->id();
                $table->string('requisition_number')->unique();
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('department_name')->nullable();
                $table->unsignedBigInteger('requesting_user_id')->nullable();
                $table->string('requesting_employee_name')->nullable();
                $table->foreignId('warehouse_id')->nullable()->constrained('erp_warehouses')->nullOnDelete();
                $table->date('request_date')->nullable();
                $table->date('required_date')->nullable();
                $table->text('purpose')->nullable();
                $table->string('priority')->default('medium'); // low, medium, high, urgent
                $table->string('status')->default('pending'); // pending, approved, rejected, issued, closed
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->dateTime('approved_at')->nullable();
                $table->text('admin_remarks')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_stock_requisition_items')) {
            Schema::create('erp_stock_requisition_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('requisition_id')->constrained('erp_stock_requisitions')->cascadeOnDelete();
                $table->foreignId('item_id')->constrained('erp_items')->cascadeOnDelete();
                $table->decimal('requested_qty', 14, 2)->default(0);
                $table->decimal('approved_qty', 14, 2)->default(0);
                $table->decimal('issued_qty', 14, 2)->default(0);
                $table->string('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 8. Stock Transfers & Items
        if (!Schema::hasTable('erp_stock_transfers')) {
            Schema::create('erp_stock_transfers', function (Blueprint $table) {
                $table->id();
                $table->string('transfer_number')->unique();
                $table->foreignId('from_warehouse_id')->constrained('erp_warehouses')->cascadeOnDelete();
                $table->foreignId('to_warehouse_id')->constrained('erp_warehouses')->cascadeOnDelete();
                $table->date('transfer_date');
                $table->string('status')->default('pending'); // pending, in_transit, completed, cancelled
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('dispatched_by')->nullable();
                $table->unsignedBigInteger('received_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_stock_transfer_items')) {
            Schema::create('erp_stock_transfer_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transfer_id')->constrained('erp_stock_transfers')->cascadeOnDelete();
                $table->foreignId('item_id')->constrained('erp_items')->cascadeOnDelete();
                $table->decimal('quantity', 14, 2)->default(0);
                $table->decimal('received_qty', 14, 2)->default(0);
                $table->string('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 9. Stock Adjustments
        if (!Schema::hasTable('erp_stock_adjustments')) {
            Schema::create('erp_stock_adjustments', function (Blueprint $table) {
                $table->id();
                $table->string('adjustment_number')->unique();
                $table->foreignId('warehouse_id')->constrained('erp_warehouses')->cascadeOnDelete();
                $table->foreignId('item_id')->constrained('erp_items')->cascadeOnDelete();
                $table->string('type'); // increase, decrease
                $table->decimal('previous_qty', 14, 2)->default(0);
                $table->decimal('adjusted_qty', 14, 2)->default(0);
                $table->decimal('diff_qty', 14, 2)->default(0);
                $table->string('reason'); // physical_count_difference, damaged, lost, expired, data_correction
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 10. Suppliers & Procurement
        if (!Schema::hasTable('erp_suppliers')) {
            Schema::create('erp_suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('supplier_name');
                $table->string('supplier_code')->unique();
                $table->string('contact_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->text('address')->nullable();
                $table->string('tax_tin')->nullable();
                $table->decimal('opening_balance', 16, 2)->default(0);
                $table->decimal('current_balance', 16, 2)->default(0); // payable
                $table->string('payment_terms')->nullable(); // Net 30, COD, Advance
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 11. Purchase Orders & GRN
        if (!Schema::hasTable('erp_purchase_orders')) {
            Schema::create('erp_purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('po_number')->unique();
                $table->foreignId('supplier_id')->constrained('erp_suppliers')->cascadeOnDelete();
                $table->date('po_date');
                $table->date('delivery_due_date')->nullable();
                $table->decimal('subtotal', 16, 2)->default(0);
                $table->decimal('discount', 16, 2)->default(0);
                $table->decimal('vat_tax', 16, 2)->default(0);
                $table->decimal('shipping_cost', 16, 2)->default(0);
                $table->decimal('total_amount', 16, 2)->default(0);
                $table->string('payment_terms')->nullable();
                $table->string('status')->default('draft'); // draft, submitted, approved, partially_received, received, cancelled
                $table->text('terms_conditions')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_purchase_order_items')) {
            Schema::create('erp_purchase_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('po_id')->constrained('erp_purchase_orders')->cascadeOnDelete();
                $table->foreignId('item_id')->constrained('erp_items')->cascadeOnDelete();
                $table->decimal('quantity', 14, 2)->default(0);
                $table->decimal('unit_price', 16, 2)->default(0);
                $table->decimal('total_price', 16, 2)->default(0);
                $table->decimal('received_qty', 14, 2)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_goods_received_notes')) {
            Schema::create('erp_goods_received_notes', function (Blueprint $table) {
                $table->id();
                $table->string('grn_number')->unique();
                $table->foreignId('po_id')->nullable()->constrained('erp_purchase_orders')->nullOnDelete();
                $table->foreignId('supplier_id')->constrained('erp_suppliers')->cascadeOnDelete();
                $table->foreignId('warehouse_id')->constrained('erp_warehouses')->cascadeOnDelete();
                $table->string('challan_number')->nullable();
                $table->date('received_date');
                $table->string('quality_status')->default('inspected_passed'); // passed, partial_reject, rejected
                $table->text('inspection_notes')->nullable();
                $table->unsignedBigInteger('received_by')->nullable();
                $table->timestamps();
            });
        }

        // 12. Fixed Asset Management
        if (!Schema::hasTable('erp_asset_categories')) {
            Schema::create('erp_asset_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->unique();
                $table->integer('useful_life_years')->default(5);
                $table->decimal('default_depreciation_rate', 5, 2)->default(10.00); // percentage
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_assets')) {
            Schema::create('erp_assets', function (Blueprint $table) {
                $table->id();
                $table->string('asset_code')->unique(); // AST-2026-0001
                $table->string('asset_name');
                $table->string('asset_name_bn')->nullable();
                $table->foreignId('category_id')->constrained('erp_asset_categories')->cascadeOnDelete();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('serial_number')->nullable();
                $table->string('qr_code')->nullable();
                $table->date('purchase_date');
                $table->decimal('purchase_price', 16, 2)->default(0);
                $table->decimal('salvage_value', 16, 2)->default(0);
                $table->integer('useful_life_years')->default(5);
                $table->string('depreciation_method')->default('straight_line'); // straight_line, written_down
                $table->decimal('current_book_value', 16, 2)->default(0);
                $table->decimal('accumulated_depreciation', 16, 2)->default(0);
                $table->foreignId('warehouse_id')->nullable()->constrained('erp_warehouses')->nullOnDelete();
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('department_name')->nullable();
                $table->unsignedBigInteger('assigned_employee_id')->nullable();
                $table->string('assigned_employee_name')->nullable();
                $table->date('assigned_date')->nullable();
                $table->string('condition')->default('good'); // excellent, good, fair, needs_repair, damaged
                $table->string('status')->default('available'); // available, assigned, in_use, under_maintenance, damaged, lost, disposed, sold, retired
                $table->string('location_detail')->nullable(); // e.g. Floor 3, Room 304, Desk 12
                $table->string('image')->nullable();
                $table->date('warranty_expiry')->nullable();
                $table->string('supplier_name')->nullable();
                $table->string('invoice_no')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_asset_assignments')) {
            Schema::create('erp_asset_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('erp_assets')->cascadeOnDelete();
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('employee_name');
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('department_name')->nullable();
                $table->date('assignment_date');
                $table->date('return_date')->nullable();
                $table->string('condition_at_handover')->default('good');
                $table->string('condition_at_return')->nullable();
                $table->string('handover_by')->nullable();
                $table->string('received_by')->nullable();
                $table->string('status')->default('active'); // active, returned
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_asset_transfers')) {
            Schema::create('erp_asset_transfers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('erp_assets')->cascadeOnDelete();
                $table->string('from_department')->nullable();
                $table->string('to_department')->nullable();
                $table->string('from_employee')->nullable();
                $table->string('to_employee')->nullable();
                $table->date('transfer_date');
                $table->text('reason')->nullable();
                $table->string('approved_by')->nullable();
                $table->string('status')->default('approved');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_asset_depreciations')) {
            Schema::create('erp_asset_depreciations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('erp_assets')->cascadeOnDelete();
                $table->string('fiscal_year');
                $table->date('depreciation_date');
                $table->string('method');
                $table->decimal('opening_value', 16, 2)->default(0);
                $table->decimal('depreciation_amount', 16, 2)->default(0);
                $table->decimal('closing_value', 16, 2)->default(0);
                $table->unsignedBigInteger('voucher_id')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_asset_disposals')) {
            Schema::create('erp_asset_disposals', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('erp_assets')->cascadeOnDelete();
                $table->date('disposal_date');
                $table->string('reason'); // damaged, obsolete, sold, donated, scrapped
                $table->decimal('original_cost', 16, 2)->default(0);
                $table->decimal('accumulated_depreciation', 16, 2)->default(0);
                $table->decimal('book_value_at_disposal', 16, 2)->default(0);
                $table->decimal('sale_value', 16, 2)->default(0);
                $table->decimal('gain_loss', 16, 2)->default(0);
                $table->string('approved_by')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 13. Vehicles & Transport Management
        if (!Schema::hasTable('erp_vehicles')) {
            Schema::create('erp_vehicles', function (Blueprint $table) {
                $table->id();
                $table->string('vehicle_number')->unique(); // e.g. Dhaka Metro-Ga 12-3456
                $table->string('vehicle_type')->default('car'); // car, motorcycle, microbus, pickup, truck, van, bicycle
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->integer('manufacturing_year')->nullable();
                $table->date('purchase_date')->nullable();
                $table->decimal('purchase_price', 16, 2)->default(0);
                $table->string('chassis_no')->nullable();
                $table->string('engine_no')->nullable();
                $table->string('fuel_type')->default('octane'); // petrol, octane, diesel, cng, electric, hybrid
                $table->decimal('current_mileage', 12, 2)->default(0);
                $table->string('driver_name')->nullable();
                $table->string('driver_phone')->nullable();
                $table->unsignedBigInteger('department_id')->nullable();
                $table->string('department_name')->nullable();
                $table->string('status')->default('active'); // active, in_service, under_maintenance, disposed
                $table->string('photo')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_vehicle_documents')) {
            Schema::create('erp_vehicle_documents', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained('erp_vehicles')->cascadeOnDelete();
                $table->string('document_type'); // registration, tax_token, fitness, insurance, route_permit, driving_license
                $table->string('document_number')->nullable();
                $table->date('issue_date')->nullable();
                $table->date('expiry_date');
                $table->integer('alert_days')->default(15);
                $table->string('file_path')->nullable();
                $table->string('status')->default('valid'); // valid, expiring_soon, expired
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_vehicle_trips')) {
            Schema::create('erp_vehicle_trips', function (Blueprint $table) {
                $table->id();
                $table->string('trip_number')->unique();
                $table->foreignId('vehicle_id')->constrained('erp_vehicles')->cascadeOnDelete();
                $table->string('driver_name')->nullable();
                $table->string('start_location');
                $table->string('destination');
                $table->date('trip_date');
                $table->decimal('start_mileage', 12, 2)->default(0);
                $table->decimal('end_mileage', 12, 2)->default(0);
                $table->decimal('distance_km', 12, 2)->default(0);
                $table->string('purpose')->nullable();
                $table->decimal('fuel_cost', 14, 2)->default(0);
                $table->decimal('toll_cost', 14, 2)->default(0);
                $table->decimal('other_cost', 14, 2)->default(0);
                $table->string('status')->default('completed'); // pending, approved, completed
                $table->string('approved_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_vehicle_fuels')) {
            Schema::create('erp_vehicle_fuels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained('erp_vehicles')->cascadeOnDelete();
                $table->date('fuel_date');
                $table->string('fuel_type');
                $table->decimal('quantity_liters', 10, 2)->default(0);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('total_amount', 14, 2)->default(0);
                $table->decimal('odometer_reading', 12, 2)->default(0);
                $table->string('fuel_station')->nullable();
                $table->string('voucher_ref')->nullable();
                $table->string('slip_file')->nullable();
                $table->timestamps();
            });
        }

        // 14. Maintenance & Repair Management
        if (!Schema::hasTable('erp_maintenance_requests')) {
            Schema::create('erp_maintenance_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_number')->unique();
                $table->foreignId('asset_id')->nullable()->constrained('erp_assets')->nullOnDelete();
                $table->foreignId('vehicle_id')->nullable()->constrained('erp_vehicles')->nullOnDelete();
                $table->string('department_name')->nullable();
                $table->string('maintenance_type')->default('corrective'); // preventive, corrective, emergency, scheduled, breakdown
                $table->string('priority')->default('medium'); // low, medium, high, urgent
                $table->text('problem_description');
                $table->string('requested_by')->nullable();
                $table->date('request_date');
                $table->decimal('estimated_cost', 16, 2)->default(0);
                $table->string('technician_name')->nullable();
                $table->string('service_provider')->nullable();
                $table->string('status')->default('pending'); // pending, approved, in_progress, completed, rejected
                $table->date('completion_date')->nullable();
                $table->decimal('spare_parts_cost', 16, 2)->default(0);
                $table->decimal('labor_cost', 16, 2)->default(0);
                $table->decimal('total_cost', 16, 2)->default(0);
                $table->unsignedBigInteger('voucher_id')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_maintenance_schedules')) {
            Schema::create('erp_maintenance_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->nullable()->constrained('erp_assets')->nullOnDelete();
                $table->foreignId('vehicle_id')->nullable()->constrained('erp_vehicles')->nullOnDelete();
                $table->string('service_name');
                $table->string('interval_type')->default('days'); // days, mileage, hours
                $table->integer('interval_value')->default(180); // e.g. 180 days (6 months)
                $table->date('last_service_date')->nullable();
                $table->date('next_due_date');
                $table->integer('alert_before_days')->default(7);
                $table->string('status')->default('active'); // active, paused
                $table->timestamps();
            });
        }

        // 15. Bills & Invoices Management
        if (!Schema::hasTable('erp_bills')) {
            Schema::create('erp_bills', function (Blueprint $table) {
                $table->id();
                $table->string('bill_number')->unique();
                $table->string('bill_type')->default('general'); // purchase, supplier, utility_electricity, utility_gas, utility_water, utility_internet, office_rent, vehicle, maintenance, stationery, furniture, service, miscellaneous
                $table->string('vendor_name');
                $table->string('department_name')->nullable();
                $table->string('reference_no')->nullable();
                $table->date('bill_date');
                $table->date('due_date')->nullable();
                $table->decimal('subtotal', 16, 2)->default(0);
                $table->decimal('discount', 16, 2)->default(0);
                $table->decimal('vat_tax', 16, 2)->default(0);
                $table->decimal('total_amount', 16, 2)->default(0);
                $table->decimal('paid_amount', 16, 2)->default(0);
                $table->decimal('due_amount', 16, 2)->default(0);
                $table->string('payment_status')->default('unpaid'); // unpaid, partially_paid, paid, overdue
                $table->string('approval_status')->default('draft'); // draft, pending_approval, approved, rejected
                $table->string('attachment')->nullable();
                $table->text('description')->nullable();
                $table->string('approved_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_bill_items')) {
            Schema::create('erp_bill_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bill_id')->constrained('erp_bills')->cascadeOnDelete();
                $table->string('description');
                $table->decimal('quantity', 14, 2)->default(1);
                $table->decimal('unit_price', 16, 2)->default(0);
                $table->decimal('total_price', 16, 2)->default(0);
                $table->timestamps();
            });
        }

        // 16. Chart of Accounts & General Ledger (Central Accounting)
        if (!Schema::hasTable('erp_chart_of_accounts')) {
            Schema::create('erp_chart_of_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('account_code')->unique(); // e.g. 1001, 2001
                $table->string('account_name');
                $table->string('account_name_bn')->nullable();
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('root_type'); // asset, liability, equity, income, expense
                $table->string('normal_balance')->default('debit'); // debit, credit
                $table->decimal('opening_balance', 16, 2)->default(0);
                $table->decimal('current_balance', 16, 2)->default(0);
                $table->boolean('is_system')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_cash_bank_accounts')) {
            Schema::create('erp_cash_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('account_type')->default('bank'); // cash, bank, mfs
                $table->string('account_name');
                $table->string('account_number')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('branch_name')->nullable();
                $table->string('routing_number')->nullable();
                $table->decimal('opening_balance', 16, 2)->default(0);
                $table->decimal('current_balance', 16, 2)->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 17. Vouchers & Entries (Payment, Receipt, Journal, Contra, Adjustment)
        if (!Schema::hasTable('erp_vouchers')) {
            Schema::create('erp_vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('voucher_number')->unique(); // PV-2026-0001, RV-2026-0001, JV-2026-0001, CV-2026-0001, AV-2026-0001
                $table->string('voucher_type'); // payment, receipt, journal, contra, adjustment
                $table->date('voucher_date');
                $table->string('payee_or_payer');
                $table->string('payment_method')->default('cash'); // cash, bank, cheque, mfs
                $table->foreignId('bank_account_id')->nullable()->constrained('erp_cash_bank_accounts')->nullOnDelete();
                $table->decimal('amount', 16, 2)->default(0);
                $table->string('amount_in_words')->nullable();
                $table->foreignId('reference_bill_id')->nullable()->constrained('erp_bills')->nullOnDelete();
                $table->string('reference_no')->nullable();
                $table->text('description')->nullable();
                $table->string('attachment')->nullable();
                $table->string('status')->default('draft'); // draft, posted, cancelled
                $table->string('prepared_by')->nullable();
                $table->string('checked_by')->nullable();
                $table->string('approved_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_voucher_entries')) {
            Schema::create('erp_voucher_entries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('voucher_id')->constrained('erp_vouchers')->cascadeOnDelete();
                $table->foreignId('account_id')->constrained('erp_chart_of_accounts')->cascadeOnDelete();
                $table->decimal('debit', 16, 2)->default(0);
                $table->decimal('credit', 16, 2)->default(0);
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_general_ledger_entries')) {
            Schema::create('erp_general_ledger_entries', function (Blueprint $table) {
                $table->id();
                $table->date('transaction_date');
                $table->foreignId('voucher_id')->nullable()->constrained('erp_vouchers')->cascadeOnDelete();
                $table->foreignId('account_id')->constrained('erp_chart_of_accounts')->cascadeOnDelete();
                $table->string('reference_type')->nullable();
                $table->string('reference_number')->nullable();
                $table->decimal('debit', 16, 2)->default(0);
                $table->decimal('credit', 16, 2)->default(0);
                $table->decimal('balance_after', 16, 2)->default(0);
                $table->text('description')->nullable();
                $table->string('fiscal_year')->default('2026-2027');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 18. Money Receipts (Income Collection)
        if (!Schema::hasTable('erp_money_receipts')) {
            Schema::create('erp_money_receipts', function (Blueprint $table) {
                $table->id();
                $table->string('receipt_number')->unique(); // MR-2026-0001
                $table->date('receipt_date');
                $table->string('customer_name');
                $table->string('customer_phone')->nullable();
                $table->string('customer_address')->nullable();
                $table->string('payment_purpose');
                $table->string('payment_method')->default('cash'); // cash, bank, cheque, mfs
                $table->foreignId('bank_account_id')->nullable()->constrained('erp_cash_bank_accounts')->nullOnDelete();
                $table->decimal('received_amount', 16, 2)->default(0);
                $table->decimal('previous_due', 16, 2)->default(0);
                $table->decimal('remaining_due', 16, 2)->default(0);
                $table->string('amount_in_words')->nullable();
                $table->foreignId('voucher_id')->nullable()->constrained('erp_vouchers')->nullOnDelete();
                $table->string('received_by')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        // 19. Cash Memos (POS & Direct Sales with Stock & Sales Accounting Integration)
        if (!Schema::hasTable('erp_cash_memos')) {
            Schema::create('erp_cash_memos', function (Blueprint $table) {
                $table->id();
                $table->string('memo_number')->unique(); // CM-2026-0001
                $table->date('memo_date');
                $table->string('customer_name');
                $table->string('customer_phone')->nullable();
                $table->string('customer_address')->nullable();
                $table->decimal('subtotal', 16, 2)->default(0);
                $table->decimal('discount', 16, 2)->default(0);
                $table->decimal('vat', 16, 2)->default(0);
                $table->decimal('grand_total', 16, 2)->default(0);
                $table->decimal('paid_amount', 16, 2)->default(0);
                $table->decimal('due_amount', 16, 2)->default(0);
                $table->string('payment_method')->default('cash');
                $table->foreignId('bank_account_id')->nullable()->constrained('erp_cash_bank_accounts')->nullOnDelete();
                $table->string('sales_person_name')->nullable();
                $table->string('status')->default('paid'); // paid, partial, due, returned
                $table->text('remarks')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_cash_memo_items')) {
            Schema::create('erp_cash_memo_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('cash_memo_id')->constrained('erp_cash_memos')->cascadeOnDelete();
                $table->foreignId('item_id')->nullable()->constrained('erp_items')->nullOnDelete();
                $table->string('item_name');
                $table->decimal('quantity', 14, 2)->default(1);
                $table->decimal('unit_price', 16, 2)->default(0);
                $table->decimal('subtotal', 16, 2)->default(0);
                $table->decimal('discount', 16, 2)->default(0);
                $table->decimal('total', 16, 2)->default(0);
                $table->timestamps();
            });
        }

        // 20. Expenses, Incomes & Budgets
        if (!Schema::hasTable('erp_expense_categories')) {
            Schema::create('erp_expense_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_expense_entries')) {
            Schema::create('erp_expense_entries', function (Blueprint $table) {
                $table->id();
                $table->string('expense_number')->unique();
                $table->date('expense_date');
                $table->foreignId('category_id')->constrained('erp_expense_categories')->cascadeOnDelete();
                $table->string('department_name')->nullable();
                $table->string('payee');
                $table->decimal('amount', 16, 2)->default(0);
                $table->string('payment_method')->default('cash');
                $table->foreignId('cash_bank_account_id')->nullable()->constrained('erp_cash_bank_accounts')->nullOnDelete();
                $table->foreignId('voucher_id')->nullable()->constrained('erp_vouchers')->nullOnDelete();
                $table->text('description')->nullable();
                $table->string('attachment')->nullable();
                $table->string('approval_status')->default('approved'); // pending, approved, rejected
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_income_categories')) {
            Schema::create('erp_income_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('code')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_income_entries')) {
            Schema::create('erp_income_entries', function (Blueprint $table) {
                $table->id();
                $table->string('income_number')->unique();
                $table->date('income_date');
                $table->foreignId('category_id')->constrained('erp_income_categories')->cascadeOnDelete();
                $table->string('department_name')->nullable();
                $table->string('payer');
                $table->decimal('amount', 16, 2)->default(0);
                $table->string('payment_method')->default('cash');
                $table->foreignId('cash_bank_account_id')->nullable()->constrained('erp_cash_bank_accounts')->nullOnDelete();
                $table->foreignId('voucher_id')->nullable()->constrained('erp_vouchers')->nullOnDelete();
                $table->foreignId('receipt_id')->nullable()->constrained('erp_money_receipts')->nullOnDelete();
                $table->text('description')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('erp_budgets')) {
            Schema::create('erp_budgets', function (Blueprint $table) {
                $table->id();
                $table->string('fiscal_year')->default('2026-2027');
                $table->string('department_name');
                $table->string('budget_head'); // Expense category or purpose
                $table->decimal('budget_amount', 16, 2)->default(0);
                $table->decimal('spent_amount', 16, 2)->default(0);
                $table->decimal('remaining_amount', 16, 2)->default(0);
                $table->decimal('alert_threshold_percentage', 5, 2)->default(90.00);
                $table->string('status')->default('active'); // active, exceeded, closed
                $table->timestamps();
            });
        }

        // 21. Corporate Documents & Legal File Vault
        if (!Schema::hasTable('erp_documents')) {
            Schema::create('erp_documents', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('document_number')->nullable();
                $table->string('category')->default('general'); // license, agreement, asset, vehicle, tax, legal, general
                $table->string('file_path');
                $table->date('issue_date')->nullable();
                $table->date('expiry_date')->nullable();
                $table->integer('alert_days')->default(30);
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('erp_documents');
        Schema::dropIfExists('erp_budgets');
        Schema::dropIfExists('erp_income_entries');
        Schema::dropIfExists('erp_income_categories');
        Schema::dropIfExists('erp_expense_entries');
        Schema::dropIfExists('erp_expense_categories');
        Schema::dropIfExists('erp_cash_memo_items');
        Schema::dropIfExists('erp_cash_memos');
        Schema::dropIfExists('erp_money_receipts');
        Schema::dropIfExists('erp_general_ledger_entries');
        Schema::dropIfExists('erp_voucher_entries');
        Schema::dropIfExists('erp_vouchers');
        Schema::dropIfExists('erp_cash_bank_accounts');
        Schema::dropIfExists('erp_chart_of_accounts');
        Schema::dropIfExists('erp_bill_items');
        Schema::dropIfExists('erp_bills');
        Schema::dropIfExists('erp_maintenance_schedules');
        Schema::dropIfExists('erp_maintenance_requests');
        Schema::dropIfExists('erp_vehicle_fuels');
        Schema::dropIfExists('erp_vehicle_trips');
        Schema::dropIfExists('erp_vehicle_documents');
        Schema::dropIfExists('erp_vehicles');
        Schema::dropIfExists('erp_asset_disposals');
        Schema::dropIfExists('erp_asset_depreciations');
        Schema::dropIfExists('erp_asset_transfers');
        Schema::dropIfExists('erp_asset_assignments');
        Schema::dropIfExists('erp_assets');
        Schema::dropIfExists('erp_asset_categories');
        Schema::dropIfExists('erp_goods_received_notes');
        Schema::dropIfExists('erp_purchase_order_items');
        Schema::dropIfExists('erp_purchase_orders');
        Schema::dropIfExists('erp_suppliers');
        Schema::dropIfExists('erp_stock_adjustments');
        Schema::dropIfExists('erp_stock_transfer_items');
        Schema::dropIfExists('erp_stock_transfers');
        Schema::dropIfExists('erp_stock_requisition_items');
        Schema::dropIfExists('erp_stock_requisitions');
        Schema::dropIfExists('erp_stock_transactions');
        Schema::dropIfExists('erp_items');
        Schema::dropIfExists('erp_item_units');
        Schema::dropIfExists('erp_item_subcategories');
        Schema::dropIfExists('erp_item_categories');
        Schema::dropIfExists('erp_warehouses');
        Schema::dropIfExists('erp_company_profiles');
    }
};

