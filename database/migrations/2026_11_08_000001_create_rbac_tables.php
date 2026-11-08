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
        // 1. User Types (Internal, External, Service)
        if (!Schema::hasTable('user_types')) {
            Schema::create('user_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->boolean('is_internal')->default(true);
                $table->string('status')->default('active');
                $table->timestamps();
            });
        }

        // 2. Roles
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('guard_name')->default('web');
                $table->text('description')->nullable();
                $table->string('category')->default('Internal'); // Internal, External, Service
                $table->string('data_scope')->default('all'); // all, team, branch, department, assigned, own
                $table->boolean('is_system')->default(false);
                $table->string('status')->default('active');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 3. Granular RBAC Permissions
        if (!Schema::hasTable('rbac_permissions')) {
            Schema::create('rbac_permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique(); // module.action.scope e.g. users.view.all
                $table->string('module');
                $table->string('action');
                $table->string('guard_name')->default('web');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 4. Role-Permission Pivot Table
        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->primary(['role_id', 'permission_id']);
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('rbac_permissions')->onDelete('cascade');
            });
        }

        // 5. Model Has Roles
        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->string('model_type')->default('App\\Models\\User');
                $table->unsignedBigInteger('model_id');
                $table->primary(['role_id', 'model_id', 'model_type']);
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }

        // 6. Model Has Permissions (Direct Permissions override)
        if (!Schema::hasTable('model_has_permissions')) {
            Schema::create('model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->string('model_type')->default('App\\Models\\User');
                $table->unsignedBigInteger('model_id');
                $table->primary(['permission_id', 'model_id', 'model_type']);
                $table->foreign('permission_id')->references('id')->on('rbac_permissions')->onDelete('cascade');
            });
        }

        // 7. Audit Logs Table (Tamper-evident system activity)
        if (!Schema::hasTable('audit_logs')) {
            Schema::create('audit_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('actor_id')->nullable();
                $table->string('actor_name')->nullable();
                $table->string('actor_role')->nullable();
                $table->string('action');
                $table->string('subject_type')->nullable();
                $table->unsignedBigInteger('subject_id')->nullable();
                $table->longText('before_state')->nullable();
                $table->longText('after_state')->nullable();
                $table->text('reason')->nullable();
                $table->string('ip_address')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('request_id')->nullable();
                $table->timestamps();
            });
        }

        // 8. Cyber Security Alerts
        if (!Schema::hasTable('security_alerts')) {
            Schema::create('security_alerts', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('medium');
                $table->string('source_ip')->nullable();
                $table->string('target')->nullable();
                $table->enum('status', ['active', 'acknowledged', 'resolved'])->default('active');
                $table->text('details')->nullable();
                $table->unsignedBigInteger('resolved_by')->nullable();
                $table->timestamp('resolved_at')->nullable();
                $table->timestamps();
            });
        }

        // 9. Fraud Detection Cases
        if (!Schema::hasTable('fraud_cases')) {
            Schema::create('fraud_cases', function (Blueprint $table) {
                $table->id();
                $table->string('case_number')->unique();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('title');
                $table->integer('risk_score')->default(50);
                $table->enum('status', ['open', 'investigating', 'resolved', 'escalated'])->default('open');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->timestamps();
            });
        }

        // 10. IP & Account Blocklist
        if (!Schema::hasTable('blocklists')) {
            Schema::create('blocklists', function (Blueprint $table) {
                $table->id();
                $table->enum('type', ['ip', 'phone', 'email', 'account'])->default('ip');
                $table->string('value');
                $table->text('reason');
                $table->unsignedBigInteger('blocked_by')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->enum('status', ['active', 'removed'])->default('active');
                $table->timestamps();
            });
        }

        // 11. Support Tickets
        if (!Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();
                $table->string('ticket_number')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('customer_name');
                $table->string('customer_email')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('subject');
                $table->text('description');
                $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
                $table->enum('status', ['open', 'in_progress', 'resolved', 'closed', 'escalated'])->default('open');
                $table->string('category')->default('General');
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->timestamp('last_reply_at')->nullable();
                $table->timestamps();
            });
        }

        // 12. Support Ticket Replies
        if (!Schema::hasTable('ticket_replies')) {
            Schema::create('ticket_replies', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('ticket_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->text('message');
                $table->boolean('is_staff')->default(false);
                $table->boolean('is_internal_note')->default(false);
                $table->timestamps();
                $table->foreign('ticket_id')->references('id')->on('support_tickets')->onDelete('cascade');
            });
        }

        // 13. Access Requests (Maker-Checker / Approvals)
        if (!Schema::hasTable('access_requests')) {
            Schema::create('access_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('requester_id');
                $table->unsignedBigInteger('target_user_id');
                $table->string('requested_role');
                $table->text('reason')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->timestamp('approved_at')->nullable();
                $table->timestamps();
            });
        }

        // 14. Expand Users Table for RBAC & Scope
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'user_type_id')) {
                $table->unsignedBigInteger('user_type_id')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('user_type_id');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->default('active')->after('role_id'); // active, inactive, suspended
            }
            if (!Schema::hasColumn('users', 'data_scope')) {
                $table->string('data_scope')->default('own')->after('status'); // all, team, branch, department, assigned, own
            }
            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('data_scope');
            }
            if (!Schema::hasColumn('users', 'branch_id')) {
                $table->unsignedBigInteger('branch_id')->nullable()->after('department_id');
            }
            if (!Schema::hasColumn('users', 'manager_id')) {
                $table->unsignedBigInteger('manager_id')->nullable()->after('branch_id');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('manager_id');
            }
            if (!Schema::hasColumn('users', 'last_login_ip')) {
                $table->string('last_login_ip')->nullable()->after('last_login_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('support_tickets');
        Schema::dropIfExists('blocklists');
        Schema::dropIfExists('fraud_cases');
        Schema::dropIfExists('security_alerts');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('access_requests');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('rbac_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('user_types');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_type_id',
                'role_id',
                'status',
                'data_scope',
                'department_id',
                'branch_id',
                'manager_id',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
