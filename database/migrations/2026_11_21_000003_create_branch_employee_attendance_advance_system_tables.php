<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for Branch Employee, Attendance & Salary Advance Management System.
     */
    public function up(): void
    {
        // 1. employees (কর্মচারীর মূল তথ্য ও সম্পূর্ণ বায়োডাটা)
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('user_id')->nullable(); // Optional linked system login
                $table->string('employee_id')->unique(); // Unique Employee ID e.g. EMP-1001
                $table->string('name');
                $table->string('profile_photo')->nullable();
                $table->string('father_name')->nullable();
                $table->string('mother_name')->nullable();
                $table->date('date_of_birth')->nullable();
                $table->string('gender')->nullable(); // Male, Female, Other
                $table->string('marital_status')->nullable(); // Single, Married, etc.
                $table->string('blood_group')->nullable(); // A+, B+, etc.
                $table->string('nationality')->default('Bangladeshi');
                $table->string('nid_number')->nullable();
                $table->string('nid_front_image')->nullable();
                $table->string('nid_back_image')->nullable();
                $table->string('passport_number')->nullable();
                $table->string('emergency_contact')->nullable();
                $table->string('emergency_contact_relation')->nullable();

                // Contact Information
                $table->string('phone');
                $table->string('alt_phone')->nullable();
                $table->string('email')->nullable();
                $table->text('present_address')->nullable();
                $table->text('permanent_address')->nullable();
                $table->string('district')->nullable();
                $table->string('upazila')->nullable();
                $table->string('post_office')->nullable();
                $table->string('postal_code')->nullable();

                // Employment Information
                $table->string('department')->nullable();
                $table->string('designation');
                $table->string('job_type')->default('Full Time'); // Full Time, Part Time, Contract, Intern
                $table->date('joining_date')->nullable();
                $table->string('employment_status')->default('active'); // active, inactive, probation, resigned, terminated
                $table->string('reporting_person')->nullable();
                $table->unsignedBigInteger('shift_id')->nullable();
                $table->string('employee_type')->default('Staff'); // Manager, Supervisor, Cashier, Staff, Delivery

                // Basic Salary Setup
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('house_rent', 12, 2)->default(0);
                $table->decimal('medical_allowance', 12, 2)->default(0);
                $table->decimal('transport_allowance', 12, 2)->default(0);
                $table->decimal('food_allowance', 12, 2)->default(0);
                $table->decimal('other_allowance', 12, 2)->default(0);
                $table->decimal('commission', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('overtime_rate', 12, 2)->default(0);
                $table->text('salary_deduction_rules')->nullable();
                $table->decimal('monthly_salary', 12, 2)->default(0);
                $table->string('salary_payment_method')->default('cash'); // cash, bank, mobile_banking
                $table->string('bank_name')->nullable();
                $table->string('bank_account_no')->nullable();
                $table->string('mobile_wallet_number')->nullable(); // bKash/Nagad

                $table->boolean('is_active')->default(true);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'employment_status']);
            });
        }

        // 2. employee_documents (NID, ছবি, সিভি ও অন্যান্য ডকুমেন্ট)
        if (!Schema::hasTable('employee_documents')) {
            Schema::create('employee_documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->string('document_type'); // photo, nid_front, nid_back, cv, appointment_letter, certificate, experience, other
                $table->string('document_title');
                $table->string('file_path');
                $table->string('file_name')->nullable();
                $table->string('file_size')->nullable();
                $table->string('mime_type')->nullable();
                $table->boolean('is_private')->default(true); // Always protected
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 3. employee_salary_structures (কর্মচারীর বেতন কাঠামো)
        if (!Schema::hasTable('employee_salary_structures')) {
            Schema::create('employee_salary_structures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('house_rent', 12, 2)->default(0);
                $table->decimal('medical_allowance', 12, 2)->default(0);
                $table->decimal('transport_allowance', 12, 2)->default(0);
                $table->decimal('food_allowance', 12, 2)->default(0);
                $table->decimal('other_allowance', 12, 2)->default(0);
                $table->decimal('commission', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('overtime_rate', 12, 2)->default(0);
                $table->text('deduction_rules')->nullable();
                $table->decimal('gross_salary', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->string('payment_method')->default('cash');
                $table->string('bank_name')->nullable();
                $table->string('bank_account_no')->nullable();
                $table->string('mobile_wallet_number')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 4. employee_shifts (কর্মচারীর Shift)
        if (!Schema::hasTable('employee_shifts')) {
            Schema::create('employee_shifts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id')->nullable(); // null means universal/default
                $table->string('shift_name'); // General Shift, Morning, Evening, Night
                $table->time('start_time'); // 09:00:00
                $table->time('end_time');   // 18:00:00
                $table->integer('late_grace_minutes')->default(15);
                $table->time('half_day_time')->nullable();
                $table->boolean('is_night_shift')->default(false);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // 5. attendance_devices (Fingerprint / Biometric Machine Configuration)
        if (!Schema::hasTable('attendance_devices')) {
            Schema::create('attendance_devices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_id');
                $table->string('device_name'); // e.g. ZKTeco Main Gate BR-01
                $table->string('device_id')->unique(); // DEV-BR01-01
                $table->string('serial_number')->nullable();
                $table->string('model')->nullable(); // ZKTeco MB10, UA760, etc.
                $table->string('device_type')->default('zkteco'); // zkteco, tcp_ip, api_push, sdk
                $table->string('ip_address')->nullable();
                $table->integer('port')->default(4370);
                $table->string('connection_type')->default('LAN'); // LAN, TCP/IP, API, SDK, Cloud Push
                $table->string('api_protocol')->default('TCP/IP');
                $table->string('username')->nullable();
                $table->text('password_encrypted')->nullable();
                $table->string('time_zone')->default('Asia/Dhaka');
                $table->integer('sync_interval_minutes')->default(15);
                $table->string('status')->default('disconnected'); // connected, disconnected, syncing, synced, error, disabled
                $table->timestamp('last_sync_time')->nullable();
                $table->timestamp('last_successful_sync')->nullable();
                $table->text('last_error_message')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 6. attendance_device_logs (Machine Sync Logs)
        if (!Schema::hasTable('attendance_device_logs')) {
            Schema::create('attendance_device_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id');
                $table->unsignedBigInteger('branch_id');
                $table->string('sync_type')->default('auto'); // auto, manual, webhook
                $table->integer('records_received')->default(0);
                $table->integer('records_synced')->default(0);
                $table->integer('records_duplicate')->default(0);
                $table->string('status')->default('success'); // success, error
                $table->text('error_details')->nullable();
                $table->timestamp('synced_at')->useCurrent();

                $table->foreign('device_id')->references('id')->on('attendance_devices')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 7. employee_attendance_logs (Attendance-এর মূল Punch Log)
        if (!Schema::hasTable('employee_attendance_logs')) {
            Schema::create('employee_attendance_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('device_id')->nullable();
                $table->dateTime('punch_time');
                $table->string('punch_state')->default('check_in'); // check_in, check_out, break_out, break_in
                $table->string('verify_type')->default('fingerprint'); // fingerprint, card, password, face, manual
                $table->string('log_hash')->unique(); // Hash of employee_id + device_id + punch_time for duplicate prevention
                $table->text('raw_payload')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'punch_time']);
            });
        }

        // 8. employee_attendances (দৈনিক Attendance)
        if (!Schema::hasTable('employee_attendances')) {
            Schema::create('employee_attendances', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->date('date');
                $table->time('check_in')->nullable();
                $table->time('check_out')->nullable();
                $table->string('status')->default('present'); // present, absent, late, half_day, leave, weekly_holiday, holiday, overtime
                $table->integer('late_minutes')->default(0);
                $table->integer('overtime_minutes')->default(0);
                $table->decimal('working_hours', 5, 2)->default(0);
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('verification_mode')->default('manual'); // manual, machine
                $table->unsignedBigInteger('attendance_taken_by')->nullable();
                $table->text('remarks')->nullable();
                $table->boolean('is_corrected')->default(false);
                $table->unsignedBigInteger('corrected_by')->nullable();
                $table->text('correction_reason')->nullable();
                $table->timestamps();

                $table->unique(['employee_id', 'date']);
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'date', 'status']);
            });
        }

        // 9. employee_leave_requests (Leave Management)
        if (!Schema::hasTable('employee_leave_requests')) {
            Schema::create('employee_leave_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->string('leave_type')->default('casual'); // casual, sick, annual, emergency, unpaid, other
                $table->date('start_date');
                $table->date('end_date');
                $table->integer('total_days')->default(1);
                $table->text('reason')->nullable();
                $table->string('attachment')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected, cancelled
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'status']);
            });
        }

        // 10. employee_salary_advances (Salary Advance Records)
        if (!Schema::hasTable('employee_salary_advances')) {
            Schema::create('employee_salary_advances', function (Blueprint $table) {
                $table->id();
                $table->string('advance_no')->unique(); // e.g. ADV-BR01-202611-001
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->date('advance_date');
                $table->decimal('advance_amount', 12, 2);
                $table->text('reason')->nullable();
                $table->string('payment_method')->default('cash'); // cash, bank, mobile_banking
                $table->unsignedBigInteger('paid_by')->nullable();
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->string('adjustment_month')->nullable(); // e.g. 2026-12
                $table->decimal('installment_amount', 12, 2)->default(0);
                $table->decimal('remaining_amount', 12, 2);
                $table->decimal('adjusted_amount', 12, 2)->default(0);
                $table->string('status')->default('approved'); // pending, approved, disbursed, partially_adjusted, fully_adjusted, rejected
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'status']);
            });
        }

        // 11. employee_advance_adjustments (Advance Adjustment Records)
        if (!Schema::hasTable('employee_advance_adjustments')) {
            Schema::create('employee_advance_adjustments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('salary_advance_id');
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('payroll_id')->nullable();
                $table->date('adjustment_date');
                $table->string('adjustment_month'); // e.g. 2026-11
                $table->decimal('adjusted_amount', 12, 2);
                $table->decimal('remaining_balance_after', 12, 2);
                $table->unsignedBigInteger('adjusted_by')->nullable();
                $table->text('remarks')->nullable();
                $table->timestamps();

                $table->foreign('salary_advance_id')->references('id')->on('employee_salary_advances')->onDelete('cascade');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 12. employee_payrolls (Monthly Payroll)
        if (!Schema::hasTable('employee_payrolls')) {
            Schema::create('employee_payrolls', function (Blueprint $table) {
                $table->id();
                $table->string('payroll_code')->unique(); // PAY-BR01-2026-11
                $table->unsignedBigInteger('branch_id');
                $table->string('salary_month'); // e.g. 2026-11
                $table->integer('total_employees')->default(0);
                $table->decimal('total_gross', 14, 2)->default(0);
                $table->decimal('total_allowances', 14, 2)->default(0);
                $table->decimal('total_overtime', 14, 2)->default(0);
                $table->decimal('total_advance_deductions', 14, 2)->default(0);
                $table->decimal('total_other_deductions', 14, 2)->default(0);
                $table->decimal('total_net_payable', 14, 2)->default(0);
                $table->decimal('total_paid', 14, 2)->default(0);
                $table->decimal('total_pending', 14, 2)->default(0);
                $table->string('status')->default('draft'); // draft, generated, approved, paid, closed
                $table->unsignedBigInteger('processed_by')->nullable();
                $table->timestamp('processed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->unique(['branch_id', 'salary_month']);
            });
        }

        // 13. employee_payroll_items (Payroll Breakdown)
        if (!Schema::hasTable('employee_payroll_items')) {
            Schema::create('employee_payroll_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('payroll_id');
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('house_rent', 12, 2)->default(0);
                $table->decimal('medical_allowance', 12, 2)->default(0);
                $table->decimal('transport_allowance', 12, 2)->default(0);
                $table->decimal('food_allowance', 12, 2)->default(0);
                $table->decimal('other_allowance', 12, 2)->default(0);
                $table->integer('overtime_hours')->default(0);
                $table->decimal('overtime_rate', 12, 2)->default(0);
                $table->decimal('overtime_amount', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('commission', 12, 2)->default(0);
                $table->integer('total_present_days')->default(0);
                $table->integer('total_absent_days')->default(0);
                $table->integer('total_late_days')->default(0);
                $table->integer('total_leave_days')->default(0);
                $table->decimal('absent_deduction', 12, 2)->default(0);
                $table->decimal('late_deduction', 12, 2)->default(0);
                $table->decimal('advance_adjustment_amount', 12, 2)->default(0);
                $table->decimal('other_deductions', 12, 2)->default(0);
                $table->decimal('gross_salary', 12, 2)->default(0);
                $table->decimal('net_payable', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->string('payment_status')->default('pending'); // pending, partially_paid, paid
                $table->string('payment_method')->nullable();
                $table->timestamp('paid_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('payroll_id')->references('id')->on('employee_payrolls')->onDelete('cascade');
                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            });
        }

        // 14. employee_salary_payments (Salary Payment History)
        if (!Schema::hasTable('employee_salary_payments')) {
            Schema::create('employee_salary_payments', function (Blueprint $table) {
                $table->id();
                $table->string('payment_no')->unique(); // e.g. SLP-BR01-202611-001
                $table->unsignedBigInteger('employee_id');
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('payroll_id')->nullable();
                $table->string('salary_month'); // 2026-11
                $table->decimal('basic_salary', 12, 2)->default(0);
                $table->decimal('allowance_total', 12, 2)->default(0);
                $table->decimal('bonus', 12, 2)->default(0);
                $table->decimal('overtime_amount', 12, 2)->default(0);
                $table->decimal('advance_adjusted', 12, 2)->default(0);
                $table->decimal('deductions', 12, 2)->default(0);
                $table->decimal('net_salary', 12, 2)->default(0);
                $table->decimal('paid_amount', 12, 2)->default(0);
                $table->date('payment_date');
                $table->string('payment_method')->default('cash'); // cash, bank, mobile_banking
                $table->string('transaction_ref')->nullable();
                $table->unsignedBigInteger('paid_by')->nullable();
                $table->string('status')->default('paid'); // paid, partially_paid, pending
                $table->string('receipt_no')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'salary_month']);
            });
        }

        // 15. employee_activity_logs (Employee-related Activity Logs)
        if (!Schema::hasTable('employee_activity_logs')) {
            Schema::create('employee_activity_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->unsignedBigInteger('branch_id');
                $table->unsignedBigInteger('employee_id')->nullable();
                $table->string('action'); // Employee Created, NID Uploaded, Salary Changed, Advance Created, etc.
                $table->string('action_type')->nullable(); // employee, attendance, salary, advance, payroll, leave, device
                $table->text('description')->nullable();
                $table->text('previous_data')->nullable(); // JSON representation of previous state
                $table->text('updated_data')->nullable();  // JSON representation of updated state
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
                $table->index(['branch_id', 'action_type']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_activity_logs');
        Schema::dropIfExists('employee_salary_payments');
        Schema::dropIfExists('employee_payroll_items');
        Schema::dropIfExists('employee_payrolls');
        Schema::dropIfExists('employee_advance_adjustments');
        Schema::dropIfExists('employee_salary_advances');
        Schema::dropIfExists('employee_leave_requests');
        Schema::dropIfExists('employee_attendances');
        Schema::dropIfExists('employee_attendance_logs');
        Schema::dropIfExists('attendance_device_logs');
        Schema::dropIfExists('attendance_devices');
        Schema::dropIfExists('employee_shifts');
        Schema::dropIfExists('employee_salary_structures');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employees');
    }
};
