<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for the complete Multi-Branch E-Commerce Ecosystem.
     */
    public function up(): void
    {
        // 1. Link shop_orders to branches
        if (Schema::hasTable('shop_orders') && !Schema::hasColumn('shop_orders', 'branch_id')) {
            Schema::table('shop_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('user_id');
                $table->unsignedBigInteger('assigned_by')->nullable()->after('branch_id');
                $table->timestamp('assigned_at')->nullable()->after('assigned_by');
                $table->string('branch_fulfillment_status')->default('unassigned')->after('assigned_at'); // unassigned, assigned, preparing, shipped, delivered, cancelled
                
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            });
        }

        // 2. Add extra configuration settings to branches table
        if (Schema::hasTable('branches')) {
            Schema::table('branches', function (Blueprint $table) {
                if (!Schema::hasColumn('branches', 'opening_cash')) {
                    $table->decimal('opening_cash', 12, 2)->default(0)->after('closing_time');
                }
                if (!Schema::hasColumn('branches', 'low_stock_threshold')) {
                    $table->integer('low_stock_threshold')->default(10)->after('opening_cash');
                }
                if (!Schema::hasColumn('branches', 'expense_approval_limit')) {
                    $table->decimal('expense_approval_limit', 12, 2)->default(10000)->after('low_stock_threshold');
                }
                if (!Schema::hasColumn('branches', 'operating_hours')) {
                    $table->string('operating_hours')->nullable()->after('expense_approval_limit');
                }
                if (!Schema::hasColumn('branches', 'delivery_settings')) {
                    $table->text('delivery_settings')->nullable()->after('operating_hours');
                }
            });
        }

        // 3. Branch Financial Transactions Ledger
        if (!Schema::hasTable('branch_financial_transactions')) {
            Schema::create('branch_financial_transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id');
                $table->date('transaction_date');
                $table->string('transaction_type'); // opening_cash, cash_sale, digital_sale, expense, salary_payment, salary_advance, cash_deposit, cash_withdrawal, refund, adjustment
                $table->decimal('in_amount', 12, 2)->default(0);
                $table->decimal('out_amount', 12, 2)->default(0);
                $table->decimal('balance', 12, 2)->default(0);
                $table->string('payment_method')->default('cash');
                $table->string('reference_no')->nullable();
                $table->string('reference_type')->nullable(); // PosSale, ShopOrder, BranchExpense, SalaryPayment, etc.
                $table->unsignedBigInteger('reference_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 4. Employee Salary Structure
        if (!Schema::hasTable('salary_structures')) {
            Schema::create('salary_structures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('house_allowance', 12, 2)->default(0);
                $table->decimal('transport_allowance', 12, 2)->default(0);
                $table->decimal('food_allowance', 12, 2)->default(0);
                $table->decimal('medical_allowance', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('overtime_rate', 12, 2)->default(0);
                $table->decimal('commission_rate', 12, 2)->default(0);
                $table->decimal('deduction', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employee_profiles')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 5. Employee Salary Payments History
        if (!Schema::hasTable('salary_payments')) {
            Schema::create('salary_payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->string('salary_month'); // e.g. 2026-11
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('allowance', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('deduction', 12, 2)->default(0);
                $table->decimal('advance_adjustment', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->date('payment_date');
                $table->string('payment_method')->default('cash');
                $table->string('transaction_id')->nullable();
                $table->unsignedBigInteger('paid_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->string('status')->default('paid'); // pending, partially_paid, paid, overdue
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employee_profiles')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 6. Employee Salary Advances
        if (!Schema::hasTable('salary_advances')) {
            Schema::create('salary_advances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->decimal('advance_amount', 12, 2);
                $table->date('date');
                $table->text('reason')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->string('deduction_month')->nullable(); // e.g. 2026-12
                $table->decimal('advance_balance', 12, 2)->default(0);
                $table->string('status')->default('approved'); // pending, approved, deducted, rejected
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employee_profiles')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 7. Employee Leaves Table
        if (!Schema::hasTable('employee_leaves')) {
            Schema::create('employee_leaves', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->string('leave_type')->default('casual'); // casual, sick, annual, unpaid
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('days')->default(1);
                $table->text('reason')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->string('status')->default('approved'); // pending, approved, rejected
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employee_profiles')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 8. Branch Activity & Audit Logs
        if (!Schema::hasTable('branch_activity_logs')) {
            Schema::create('branch_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action'); // stock_added, stock_removed, product_sold, return, expense_created, salary_paid, etc.
                $table->string('entity_type')->nullable(); // Product, Order, Expense, Salary, Staff, Transfer
                $table->unsignedBigInteger('entity_id')->nullable();
                $table->text('previous_value')->nullable();
                $table->text('new_value')->nullable();
                $table->text('description')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_activity_logs');
        Schema::dropIfExists('employee_leaves');
        Schema::dropIfExists('salary_advances');
        Schema::dropIfExists('salary_payments');
        Schema::dropIfExists('salary_structures');
        Schema::dropIfExists('branch_financial_transactions');
        
        if (Schema::hasTable('shop_orders') && Schema::hasColumn('shop_orders', 'branch_id')) {
            Schema::table('shop_orders', function (Blueprint $table) {
                $table->dropForeign(['branch_id']);
                $table->dropColumn(['branch_id', 'assigned_by', 'assigned_at', 'branch_fulfillment_status']);
            });
        }
    }
};
