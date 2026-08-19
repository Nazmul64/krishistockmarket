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
        // 1. Branches
        Schema::create('hrm_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        // 2. Departments
        Schema::create('hrm_departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('head_id')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 3. Designations
        Schema::create('hrm_designations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 4. Shifts
        Schema::create('hrm_shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('break_time_minutes')->default(60);
            $table->integer('grace_time_minutes')->default(15);
            $table->boolean('overtime_enabled')->default(true);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 5. Holidays
        Schema::create('hrm_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('type')->default('public'); // public, company, religious, emergency
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 6. Employee Profiles (Extends User)
        Schema::create('hrm_employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('employee_code')->unique();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('employment_type')->default('full_time'); // full_time, part_time, contract, probation, intern
            $table->string('status')->default('active'); // active, inactive, probation, resigned, terminated, retired, suspended
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('house_rent', 12, 2)->default(0);
            $table->decimal('medical_allowance', 12, 2)->default(0);
            $table->decimal('transport_allowance', 12, 2)->default(0);
            $table->decimal('other_allowance', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->string('payment_method')->default('bank'); // bank, cash, mobile_banking
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('nid')->nullable();
            $table->string('passport')->nullable();
            $table->timestamps();
        });

        // 7. Attendances
        Schema::create('hrm_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->decimal('working_hours', 5, 2)->default(0);
            $table->integer('late_minutes')->default(0);
            $table->integer('overtime_minutes')->default(0);
            $table->string('status')->default('present'); // present, absent, late, half_day, leave, holiday, weekend
            $table->string('ip_address')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 8. Leave Types
        Schema::create('hrm_leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('days_allowed')->default(10);
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 9. Leave Requests
        Schema::create('hrm_leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('leave_type_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(1);
            $table->text('reason')->nullable();
            $table->string('attachment')->nullable();
            $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        // 10. Payrolls (Monthly Run)
        Schema::create('hrm_payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('month_year'); // e.g. 2026-08
            $table->integer('total_employees')->default(0);
            $table->decimal('total_basic', 14, 2)->default(0);
            $table->decimal('total_allowances', 14, 2)->default(0);
            $table->decimal('total_deductions', 14, 2)->default(0);
            $table->decimal('total_net', 14, 2)->default(0);
            $table->string('status')->default('draft'); // draft, generated, approved, paid
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamps();
        });

        // 11. Payroll Items (Per Employee Per Month)
        Schema::create('hrm_payroll_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('overtime_amount', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('late_deduction', 12, 2)->default(0);
            $table->decimal('absent_deduction', 12, 2)->default(0);
            $table->decimal('loan_deduction', 12, 2)->default(0);
            $table->decimal('advance_deduction', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->string('status')->default('unpaid'); // unpaid, paid
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->timestamps();
        });

        // 12. Employee Loans
        Schema::create('hrm_employee_loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('loan_type')->default('personal');
            $table->decimal('loan_amount', 12, 2);
            $table->decimal('monthly_deduction', 12, 2);
            $table->decimal('remaining_amount', 12, 2);
            $table->string('status')->default('pending'); // pending, approved, running, completed, rejected
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        // 13. Salary Advances
        Schema::create('hrm_salary_advances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount', 12, 2);
            $table->text('reason')->nullable();
            $table->string('deduction_month'); // e.g. 2026-09
            $table->string('status')->default('pending'); // pending, approved, deducted, rejected
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamps();
        });

        // 14. Job Posts (Recruitment)
        Schema::create('hrm_job_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->integer('vacancy')->default(1);
            $table->string('employment_type')->default('full_time');
            $table->string('salary_range')->nullable();
            $table->text('description')->nullable();
            $table->date('deadline')->nullable();
            $table->string('status')->default('active'); // active, closed
            $table->timestamps();
        });

        // 15. Applicants
        Schema::create('hrm_applicants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_post_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('resume')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('expected_salary', 12, 2)->nullable();
            $table->string('status')->default('applied'); // applied, shortlisted, interview, selected, rejected, hired
            $table->timestamps();
        });

        // 16. Performances
        Schema::create('hrm_performances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->string('review_period'); // e.g. 2026-Q3, 2026-August
            $table->json('kpi_ratings')->nullable();
            $table->decimal('overall_rating', 3, 1)->default(3.0); // 1.0 to 5.0
            $table->text('comments')->nullable();
            $table->timestamps();
        });

        // 17. Company Assets
        Schema::create('hrm_company_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('asset_code')->unique();
            $table->string('category')->default('electronics'); // laptop, desktop, mobile, sim, id_card, equipment
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('value', 12, 2)->default(0);
            $table->string('condition')->default('good'); // new, good, damaged, repair
            $table->unsignedBigInteger('assigned_user_id')->nullable();
            $table->date('assigned_date')->nullable();
            $table->date('return_date')->nullable();
            $table->string('status')->default('available'); // available, assigned, returned, written_off
            $table->timestamps();
        });

        // 18. Announcements
        Schema::create('hrm_announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->unsignedBigInteger('target_department_id')->nullable(); // null for all
            $table->date('publish_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // 19. Activity Logs
        Schema::create('hrm_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action');
            $table->string('module');
            $table->unsignedBigInteger('record_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hrm_activity_logs');
        Schema::dropIfExists('hrm_announcements');
        Schema::dropIfExists('hrm_company_assets');
        Schema::dropIfExists('hrm_performances');
        Schema::dropIfExists('hrm_applicants');
        Schema::dropIfExists('hrm_job_posts');
        Schema::dropIfExists('hrm_salary_advances');
        Schema::dropIfExists('hrm_employee_loans');
        Schema::dropIfExists('hrm_payroll_items');
        Schema::dropIfExists('hrm_payrolls');
        Schema::dropIfExists('hrm_leave_requests');
        Schema::dropIfExists('hrm_leave_types');
        Schema::dropIfExists('hrm_attendances');
        Schema::dropIfExists('hrm_employee_profiles');
        Schema::dropIfExists('hrm_holidays');
        Schema::dropIfExists('hrm_shifts');
        Schema::dropIfExists('hrm_designations');
        Schema::dropIfExists('hrm_departments');
        Schema::dropIfExists('hrm_branches');
    }
};
