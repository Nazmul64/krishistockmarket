<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'customer_id',
        'role',
        'user_type',
        'is_active',
        'user_type_id',
        'role_id',
        'status',
        'data_scope',
        'department_id',
        'branch_id',
        'manager_id',
        'balance',
        'locked_balance',
        'membership_card_type',
        'referral_id',
        'avatar',
        'phone',
        'nid_number',
        'nid_document',
        'present_address',
        'permanent_address',
        'nominee_name',
        'nominee_phone',
        'nominee_nid',
        'nominee_relation',
        'agent_shop_name',
        'agent_type',
        'agent_area',
        'dealer_id',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
    ];

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'user_type_id');
    }

    public function roleRelation()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function directPermissions()
    {
        return $this->belongsToMany(RbacPermission::class, 'user_direct_permissions', 'user_id', 'permission_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function teamMembers()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\HRM\HrmDepartment::class, 'department_id');
    }

    public function branch()
    {
        return $this->belongsTo(\App\Models\HRM\HrmBranch::class, 'branch_id');
    }

    public function supplierProfile()
    {
        return $this->hasOne(SupplierProfile::class, 'user_id');
    }

    public function supplies()
    {
        return $this->hasMany(SupplierSupply::class, 'supplier_id');
    }

    public function supplierPayments()
    {
        return $this->hasMany(SupplierPayment::class, 'supplier_id');
    }

    public function permission()
    {
        return $this->hasOne(Permission::class, 'user_id');
    }

    public function employeeInfo()
    {
        return $this->hasOne(EmployeeInfo::class, 'user_id');
    }

    public function hrmProfile()
    {
        return $this->hasOne(\App\Models\HRM\HrmEmployeeProfile::class, 'user_id');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }

    public function shareholderProfile()
    {
        return $this->hasOne(ShareholderProfile::class, 'user_id');
    }

    public function investments()
    {
        return $this->hasMany(UserInvestment::class, 'user_id');
    }

    public function investmentWithdrawals()
    {
        return $this->hasMany(InvestmentWithdrawal::class, 'user_id');
    }

    public function partnerships()
    {
        return $this->hasMany(Partnership::class, 'user_id');
    }

    public function agentInventories()
    {
        return $this->hasMany(AgentInventoryBalance::class, 'agent_id');
    }

    public function providentFunds()
    {
        return $this->hasMany(\App\Models\HRM\HrmProvidentFund::class, 'user_id');
    }

    public function grievances()
    {
        return $this->hasMany(Grievance::class, 'user_id');
    }

    public function shopOrders()
    {
        return $this->hasMany(\App\Models\Shop\ShopOrder::class, 'user_id');
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        if (isset($this->is_active) && !$this->is_active) {
            return false;
        }
        return ($this->status ?? 'active') === 'active';
    }

    /**
     * Check if user has a specific role code or name.
     */
    public function hasRole($roleCode): bool
    {
        $roleCode = strtolower(trim($roleCode));

        // Direct column check
        if (strtolower($this->role ?? '') === $roleCode) {
            return true;
        }

        // Relation role_id check
        if ($this->roleRelation) {
            if (strtolower($this->roleRelation->code ?? '') === $roleCode || 
                strtolower($this->roleRelation->slug ?? '') === $roleCode ||
                strtolower($this->roleRelation->name ?? '') === $roleCode) {
                return true;
            }
        }

        // Many-to-many roles pivot check
        if ($this->relationLoaded('roles') || $this->roles()->exists()) {
            return $this->roles->contains(function ($r) use ($roleCode) {
                return strtolower($r->code ?? '') === $roleCode || 
                       strtolower($r->slug ?? '') === $roleCode ||
                       strtolower($r->name ?? '') === $roleCode;
            });
        }

        return false;
    }

    /**
     * Check if user has ANY of the specified roles.
     */
    public function hasAnyRole(array $roleCodes): bool
    {
        foreach ($roleCodes as $code) {
            if ($this->hasRole($code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the designated portal prefix for this user based on their role.
     * e.g. /admin, /manager, /employee, /staff, /support, /security, /audit, /portal
     */
    public function getPortalPrefix(): string
    {
        if ($this->roleRelation && !empty($this->roleRelation->portal_prefix)) {
            return $this->roleRelation->portal_prefix;
        }

        $roleSlug = strtolower(trim($this->role ?? 'user'));
        return match($roleSlug) {
            'super_admin', 'admin', 'chairman', 'managing_director', 'ceo', 'finance_admin', 'investment_manager', 'hr_manager', 'marketing_manager' => '/admin',
            'manager'                        => '/manager',
            'employee', 'agent'              => '/employee',
            'staff'                          => '/staff',
            'support_lead', 'support_agent'  => '/support',
            'security_analyst'               => '/security',
            'auditor'                        => '/audit',
            'supplier', 'seller'             => '/supplier',
            default                          => '/portal',
        };
    }

    /**
     * Get the dedicated dashboard route name for this user.
     */
    public function getDashboardRoute(): string
    {
        if ($this->hasAnyRole(['super_admin', 'admin', 'chairman', 'managing_director', 'ceo', 'finance_admin', 'investment_manager', 'hr_manager', 'marketing_manager'])) {
            return 'admin.dashboard';
        }
        if ($this->hasRole('manager')) {
            return 'manager.dashboard';
        }
        if ($this->hasAnyRole(['employee', 'agent'])) {
            return 'employee.dashboard';
        }
        if ($this->hasRole('staff')) {
            return 'staff.dashboard';
        }
        if ($this->hasRole('support_lead')) {
            return 'support.lead.dashboard';
        }
        if ($this->hasRole('support_agent')) {
            return 'support.agent.dashboard';
        }
        if ($this->hasRole('security_analyst')) {
            return 'security.dashboard';
        }
        if ($this->hasRole('auditor')) {
            return 'audit.dashboard';
        }
        if ($this->hasRole('supplier') || $this->hasRole('seller')) {
            return 'supplier.dashboard';
        }

        return 'portal.dashboard';
    }

    /**
     * Check if user has permission for a specific module or action key.
     */
    public function hasPermission($permissionKey)
    {
        // Super Admin & Admin have full access
        if ($this->hasRole('super_admin') || $this->hasRole('admin')) {
            return true;
        }

        // Inactive users have no permissions
        if (!$this->isActive()) {
            return false;
        }

        $targetKey = strtolower(trim($permissionKey));

        // 1. Check Role-based granular permissions
        if ($this->roleRelation) {
            $hasInRole = $this->roleRelation->permissions->contains(function ($perm) use ($targetKey) {
                return strtolower($perm->code) === $targetKey || strtolower($perm->slug) === $targetKey;
            });
            if ($hasInRole) {
                return true;
            }
        }

        foreach ($this->roles as $role) {
            $hasInRole = $role->permissions->contains(function ($perm) use ($targetKey) {
                return strtolower($perm->code) === $targetKey || strtolower($perm->slug) === $targetKey;
            });
            if ($hasInRole) {
                return true;
            }
        }

        // 2. Check direct model permissions (ad-hoc overrides)
        $hasDirect = $this->directPermissions->contains(function ($perm) use ($targetKey) {
            return strtolower($perm->code) === $targetKey || strtolower($perm->slug) === $targetKey;
        });
        if ($hasDirect) {
            return true;
        }

        // Also check model_has_permissions for backward compatibility
        $directLegacy = DB::table('model_has_permissions')
            ->join('rbac_permissions', 'model_has_permissions.permission_id', '=', 'rbac_permissions.id')
            ->where('model_id', $this->id)
            ->where(function ($q) use ($targetKey) {
                $q->whereRaw('LOWER(rbac_permissions.code) = ?', [$targetKey])
                  ->orWhereRaw('LOWER(rbac_permissions.slug) = ?', [$targetKey]);
            })
            ->exists();

        if ($directLegacy) {
            return true;
        }

        // 3. Backward Compatibility: check legacy 'permissions' table
        $perm = $this->permission;
        if ($perm && !empty($perm->permission_list)) {
            $list = json_decode($perm->permission_list, true);
            if (!is_array($list)) {
                $list = array_map('trim', explode(',', $perm->permission_list));
            }
            if (in_array($targetKey, array_map('strtolower', $list))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Grant an ad-hoc direct permission to the user.
     */
    public function assignDirectPermission($permissionIdOrCode)
    {
        $perm = is_numeric($permissionIdOrCode)
            ? RbacPermission::find($permissionIdOrCode)
            : RbacPermission::where('code', $permissionIdOrCode)->orWhere('slug', $permissionIdOrCode)->first();

        if ($perm) {
            DB::table('user_direct_permissions')->updateOrInsert([
                'user_id'       => $this->id,
                'permission_id' => $perm->id,
            ]);
            DB::table('model_has_permissions')->updateOrInsert([
                'model_type'    => 'App\\Models\\User',
                'model_id'      => $this->id,
                'permission_id' => $perm->id,
            ]);
            $this->load('directPermissions');
        }
    }

    /**
     * Revoke an ad-hoc direct permission from the user.
     */
    public function revokeDirectPermission($permissionIdOrCode)
    {
        $perm = is_numeric($permissionIdOrCode)
            ? RbacPermission::find($permissionIdOrCode)
            : RbacPermission::where('code', $permissionIdOrCode)->orWhere('slug', $permissionIdOrCode)->first();

        if ($perm) {
            DB::table('user_direct_permissions')
                ->where('user_id', $this->id)
                ->where('permission_id', $perm->id)
                ->delete();

            DB::table('model_has_permissions')
                ->where('model_id', $this->id)
                ->where('permission_id', $perm->id)
                ->delete();

            $this->load('directPermissions');
        }
    }

    /**
     * Sync ad-hoc direct permissions.
     */
    public function syncDirectPermissions(array $permissionIds)
    {
        DB::table('user_direct_permissions')->where('user_id', $this->id)->delete();
        DB::table('model_has_permissions')->where('model_id', $this->id)->where('model_type', 'App\\Models\\User')->delete();

        foreach ($permissionIds as $pId) {
            DB::table('user_direct_permissions')->insert([
                'user_id'       => $this->id,
                'permission_id' => $pId,
            ]);
            DB::table('model_has_permissions')->insert([
                'model_type'    => 'App\\Models\\User',
                'model_id'      => $this->id,
                'permission_id' => $pId,
            ]);
        }
        $this->load('directPermissions');
    }
}
