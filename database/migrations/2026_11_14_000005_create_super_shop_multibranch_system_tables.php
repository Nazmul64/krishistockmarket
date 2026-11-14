<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Super Shop & Multi-Branch Management System.
     */
    public function up(): void
    {
        // 1. Branches Table
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g. BR-01, BR-02
            $table->string('type')->default('super_shop'); // super_shop, retail_store, warehouse
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('area')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closing_time')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Warehouses Table
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // e.g. WH-01
            $table->string('location')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_central')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 3. Branch Users Assignment Table
        Schema::create('branch_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role')->default('branch_manager'); // branch_manager, assistant_manager, supervisor, cashier, inventory_officer, sales_executive
            $table->boolean('is_primary')->default(true);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 4. Employee Profiles Table
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('branch_id');
            $table->string('employee_code')->unique(); // e.g. EMP-101
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('designation'); // Branch Manager, Cashier, Supervisor, etc.
            $table->decimal('salary', 12, 2)->default(0);
            $table->date('joining_date')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // 5. Employee Attendance Table
        Schema::create('employee_attendance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('branch_id');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'on_leave'])->default('present');
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employee_profiles')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // 6. Branch Inventories Table (Connects existing shop_products to branches)
        Schema::create('branch_inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('stock_quantity')->default(0);
            $table->integer('reserved_stock')->default(0);
            $table->integer('reorder_level')->default(10);
            $table->integer('safety_stock')->default(5);
            $table->decimal('purchase_price', 12, 2)->nullable();
            $table->decimal('selling_price', 12, 2)->nullable(); // null means inherit from shop_products.price
            $table->string('shelf_location')->nullable(); // e.g. 'Aisle 2, Shelf B'
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['branch_id', 'product_id']);
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 7. Stock Movements Audit Table
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');
            $table->string('type'); // purchase_in, transfer_in, transfer_out, pos_sale, ecommerce_sale, return_in, supplier_return_out, damage_out, expired_out, adjustment
            $table->integer('quantity'); // Positive for IN, Negative for OUT
            $table->integer('balance_after')->default(0);
            $table->string('reference_no')->nullable(); // PO#, TR#, POS#, etc.
            $table->string('batch_no')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 8. Inventory Batches Table (For Expiry Management & FEFO)
        Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('product_id');
            $table->string('batch_number');
            $table->date('mfg_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('initial_qty')->default(0);
            $table->integer('current_qty')->default(0);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->enum('status', ['active', 'expired', 'exhausted'])->default('active');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 9. Suppliers Table
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('payment_terms')->nullable(); // Cash, 30 Days Credit, 15 Days
            $table->decimal('opening_balance', 12, 2)->default(0);
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 10. Purchase Orders Table
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // PO-2611-1001
            $table->unsignedBigInteger('supplier_id');
            $table->string('destination_type')->default('branch'); // branch or warehouse
            $table->unsignedBigInteger('destination_id'); // branch_id or warehouse_id
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'received', 'partially_received', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });

        // 11. Purchase Order Items Table
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('sku')->nullable();
            $table->integer('quantity');
            $table->string('unit')->default('pcs');
            $table->decimal('purchase_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->integer('received_qty')->default(0);
            $table->timestamps();

            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 12. Goods Receipt Notes (GRN) Table
        Schema::create('goods_receipt_notes', function (Blueprint $table) {
            $table->id();
            $table->string('grn_number')->unique(); // GRN-2611-1001
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->string('destination_type')->default('branch');
            $table->unsignedBigInteger('destination_id');
            $table->date('received_date');
            $table->unsignedBigInteger('received_by')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
        });

        // 13. Goods Receipt Note Items Table
        Schema::create('goods_receipt_note_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grn_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('ordered_qty')->default(0);
            $table->integer('received_qty')->default(0);
            $table->integer('accepted_qty')->default(0);
            $table->integer('damaged_qty')->default(0);
            $table->integer('rejected_qty')->default(0);
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            $table->foreign('grn_id')->references('id')->on('goods_receipt_notes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 14. Stock Transfers Table (Branch to Branch)
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_number')->unique(); // TR-2611-1001
            $table->unsignedBigInteger('source_branch_id');
            $table->unsignedBigInteger('destination_branch_id');
            $table->date('request_date');
            $table->date('dispatch_date')->nullable();
            $table->date('received_date')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'approved', 'in_transit', 'partially_received', 'received', 'rejected', 'cancelled'])->default('draft');
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->unsignedBigInteger('dispatched_by')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('source_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('destination_branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // 15. Stock Transfer Items Table
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transfer_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('requested_qty')->default(0);
            $table->integer('dispatched_qty')->default(0);
            $table->integer('received_qty')->default(0);
            $table->integer('damaged_qty')->default(0);
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('transfer_id')->references('id')->on('stock_transfers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 16. POS Counters Table
        Schema::create('pos_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->string('counter_name'); // Counter 01, Counter 02
            $table->string('counter_code')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // 17. Cashier Shifts Table
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('pos_counter_id')->nullable();
            $table->unsignedBigInteger('cashier_id');
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->decimal('opening_cash', 12, 2)->default(0);
            $table->decimal('expected_cash', 12, 2)->default(0);
            $table->decimal('actual_cash', 12, 2)->default(0);
            $table->decimal('cash_difference', 12, 2)->default(0);
            $table->decimal('total_cash_sales', 12, 2)->default(0);
            $table->decimal('total_digital_sales', 12, 2)->default(0);
            $table->decimal('total_sales_amount', 12, 2)->default(0);
            $table->integer('total_transactions')->default(0);
            $table->text('closing_notes')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 18. Cash Drawer Transactions Table
        Schema::create('cash_drawer_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->enum('type', ['cash_in', 'cash_out', 'expense', 'refund']);
            $table->decimal('amount', 12, 2);
            $table->string('reason');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('shift_id')->references('id')->on('cashier_shifts')->onDelete('cascade');
        });

        // 19. POS Sales Table
        Schema::create('pos_sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique(); // POS-BR01-2611-1001
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('pos_counter_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('cashier_id');
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->string('payment_method')->default('cash'); // cash, bkash, nagad, card, mixed
            $table->enum('payment_status', ['paid', 'partial', 'due'])->default('paid');
            $table->enum('sale_status', ['completed', 'hold', 'refunded', 'cancelled'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamp('sale_time');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('cashier_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 20. POS Sale Items Table
        Schema::create('pos_sale_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_sale_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('cost_price', 12, 2)->default(0); // For accurate profit calculation
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();

            $table->foreign('pos_sale_id')->references('id')->on('pos_sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 21. POS Payments Table (Supports split payments)
        Schema::create('pos_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pos_sale_id');
            $table->string('payment_method'); // cash, bkash, nagad, card, rocket
            $table->decimal('amount', 12, 2);
            $table->string('transaction_ref')->nullable();
            $table->timestamps();

            $table->foreign('pos_sale_id')->references('id')->on('pos_sales')->onDelete('cascade');
        });

        // 22. POS Sales Returns Table
        Schema::create('pos_sales_returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_no')->unique(); // RET-2611-1001
            $table->unsignedBigInteger('pos_sale_id');
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('cashier_id');
            $table->decimal('total_refund_amount', 12, 2)->default(0);
            $table->string('refund_method')->default('cash');
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();

            $table->foreign('pos_sale_id')->references('id')->on('pos_sales')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });

        // 23. POS Sales Return Items Table
        Schema::create('pos_sales_return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_return_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('refund_amount', 12, 2);
            $table->enum('condition', ['sellable', 'damaged'])->default('sellable');
            $table->boolean('restored_to_stock')->default(true);
            $table->timestamps();

            $table->foreign('sales_return_id')->references('id')->on('pos_sales_returns')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
        });

        // 24. Expense Categories Table
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 25. Branch Expenses Table
        Schema::create('branch_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->string('payment_method')->default('cash');
            $table->text('description')->nullable();
            $table->string('receipt_attachment')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('approved');
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('expense_categories')->onDelete('cascade');
        });

        // 26. Customer Loyalty Memberships Table
        Schema::create('loyalty_memberships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('customer_phone')->unique();
            $table->string('customer_name');
            $table->string('membership_number')->unique(); // e.g. SHW-2611-1001
            $table->enum('tier', ['regular', 'silver', 'gold', 'platinum'])->default('regular');
            $table->integer('total_points')->default(0);
            $table->integer('redeemed_points')->default(0);
            $table->integer('available_points')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 27. Loyalty Points Ledger Table
        Schema::create('loyalty_points_ledger', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('membership_id');
            $table->enum('type', ['earned', 'redeemed', 'expired', 'adjusted'])->default('earned');
            $table->integer('points');
            $table->unsignedBigInteger('pos_sale_id')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('loyalty_memberships')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points_ledger');
        Schema::dropIfExists('loyalty_memberships');
        Schema::dropIfExists('branch_expenses');
        Schema::dropIfExists('expense_categories');
        Schema::dropIfExists('pos_sales_return_items');
        Schema::dropIfExists('pos_sales_returns');
        Schema::dropIfExists('pos_payments');
        Schema::dropIfExists('pos_sale_items');
        Schema::dropIfExists('pos_sales');
        Schema::dropIfExists('cash_drawer_transactions');
        Schema::dropIfExists('cashier_shifts');
        Schema::dropIfExists('pos_counters');
        Schema::dropIfExists('stock_transfer_items');
        Schema::dropIfExists('stock_transfers');
        Schema::dropIfExists('goods_receipt_note_items');
        Schema::dropIfExists('goods_receipt_notes');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('inventory_batches');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('branch_inventories');
        Schema::dropIfExists('employee_attendance');
        Schema::dropIfExists('employee_profiles');
        Schema::dropIfExists('branch_users');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('branches');
    }
};

