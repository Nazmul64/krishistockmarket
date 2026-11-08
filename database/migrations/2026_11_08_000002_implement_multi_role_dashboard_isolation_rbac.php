<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add slug and portal_prefix to roles table
        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                if (!Schema::hasColumn('roles', 'slug')) {
                    $table->string('slug', 50)->nullable()->after('name');
                }
                if (!Schema::hasColumn('roles', 'portal_prefix')) {
                    $table->string('portal_prefix', 50)->default('/portal')->after('guard_name');
                }
            });

            // Backfill slug from code and set portal_prefix
            $rolePrefixes = [
                'super_admin'      => '/admin',
                'admin'            => '/admin',
                'manager'          => '/manager',
                'employee'         => '/employee',
                'staff'            => '/staff',
                'support_lead'     => '/support',
                'support_agent'    => '/support',
                'security_analyst' => '/security',
                'auditor'          => '/audit',
                'supplier'         => '/supplier',
                'user'             => '/portal',
            ];

            $roles = DB::table('roles')->get();
            foreach ($roles as $r) {
                $slug = $r->slug ?: $r->code;
                $prefix = $rolePrefixes[$r->code] ?? '/portal';
                DB::table('roles')->where('id', $r->id)->update([
                    'slug'          => $slug,
                    'portal_prefix' => $prefix,
                ]);
            }
        }

        // 2. Add slug to rbac_permissions table
        if (Schema::hasTable('rbac_permissions')) {
            Schema::table('rbac_permissions', function (Blueprint $table) {
                if (!Schema::hasColumn('rbac_permissions', 'slug')) {
                    $table->string('slug', 100)->nullable()->after('name');
                }
            });

            // Backfill slug from code
            DB::statement("UPDATE rbac_permissions SET slug = code WHERE slug IS NULL OR slug = ''");
        }

        // 3. Add user_type and is_active to users table
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'user_type')) {
                    $table->enum('user_type', ['internal', 'external'])->default('internal')->after('role');
                }
                if (!Schema::hasColumn('users', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('user_type');
                }
            });

            // Backfill user_type and is_active
            DB::statement("UPDATE users SET is_active = (status = 'active')");
            DB::statement("UPDATE users SET user_type = 'external' WHERE role IN ('user', 'customer', 'supplier')");
            DB::statement("UPDATE users SET user_type = 'internal' WHERE role NOT IN ('user', 'customer', 'supplier')");
        }

        // 4. Create user_roles table
        if (!Schema::hasTable('user_roles')) {
            Schema::create('user_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('role_id');
                $table->primary(['user_id', 'role_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });

            // Populate from model_has_roles
            if (Schema::hasTable('model_has_roles')) {
                $existing = DB::table('model_has_roles')->get();
                foreach ($existing as $row) {
                    DB::table('user_roles')->updateOrInsert([
                        'user_id' => $row->model_id,
                        'role_id' => $row->role_id,
                    ]);
                }
            }
        }

        // 5. Create role_permissions table
        if (!Schema::hasTable('role_permissions')) {
            Schema::create('role_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->unsignedBigInteger('permission_id');
                $table->primary(['role_id', 'permission_id']);
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('rbac_permissions')->onDelete('cascade');
            });

            // Populate from role_has_permissions
            if (Schema::hasTable('role_has_permissions')) {
                $existing = DB::table('role_has_permissions')->get();
                foreach ($existing as $row) {
                    DB::table('role_permissions')->updateOrInsert([
                        'role_id'       => $row->role_id,
                        'permission_id' => $row->permission_id,
                    ]);
                }
            }
        }

        // 6. Create user_direct_permissions table
        if (!Schema::hasTable('user_direct_permissions')) {
            Schema::create('user_direct_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('permission_id');
                $table->primary(['user_id', 'permission_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('permission_id')->references('id')->on('rbac_permissions')->onDelete('cascade');
            });

            // Populate from model_has_permissions
            if (Schema::hasTable('model_has_permissions')) {
                $existing = DB::table('model_has_permissions')->get();
                foreach ($existing as $row) {
                    DB::table('user_direct_permissions')->updateOrInsert([
                        'user_id'       => $row->model_id,
                        'permission_id' => $row->permission_id,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_direct_permissions');
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('user_roles');

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'user_type')) {
                    $table->dropColumn('user_type');
                }
                if (Schema::hasColumn('users', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }

        if (Schema::hasTable('rbac_permissions')) {
            Schema::table('rbac_permissions', function (Blueprint $table) {
                if (Schema::hasColumn('rbac_permissions', 'slug')) {
                    $table->dropColumn('slug');
                }
            });
        }

        if (Schema::hasTable('roles')) {
            Schema::table('roles', function (Blueprint $table) {
                if (Schema::hasColumn('roles', 'slug')) {
                    $table->dropColumn('slug');
                }
                if (Schema::hasColumn('roles', 'portal_prefix')) {
                    $table->dropColumn('portal_prefix');
                }
            });
        }
    }
};

