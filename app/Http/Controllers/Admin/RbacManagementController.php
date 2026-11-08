<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Blocklist;
use App\Models\FraudCase;
use App\Models\RbacPermission;
use App\Models\Role;
use App\Models\SecurityAlert;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RbacManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin,admin');
    }

    /**
     * Display comprehensive User Management list with filters.
     */
    public function users(Request $request)
    {
        $query = User::with(['userType', 'roleRelation', 'department', 'branch'])->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_type_id')) {
            $query->where('user_type_id', $request->user_type_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(15);
        $userTypes = UserType::all();
        $roles = Role::where('status', 'active')->get();
        $departments = class_exists(\App\Models\HRM\HrmDepartment::class) ? \App\Models\HRM\HrmDepartment::all() : collect();
        $branches = class_exists(\App\Models\HRM\HrmBranch::class) ? \App\Models\HRM\HrmBranch::all() : collect();

        // Stats summary
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', '!=', 'active')->count();
        $internalUsers = User::whereHas('userType', function ($q) {
            $q->where('is_internal', true);
        })->count();

        return view('admin.rbac.users', compact(
            'users',
            'userTypes',
            'roles',
            'departments',
            'branches',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'internalUsers'
        ));
    }

    /**
     * Store new user with RBAC role assignment.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'username'     => 'required|string|unique:users,username|max:50',
            'email'        => 'nullable|email|unique:users,email|max:255',
            'phone'        => 'required|string|unique:users,phone|max:20',
            'password'     => 'required|string|min:6|confirmed',
            'user_type_id' => 'required|exists:user_types,id',
            'role_id'      => 'required|exists:roles,id',
            'status'       => 'required|in:active,inactive,suspended',
            'data_scope'   => 'nullable|string',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $role = Role::findOrFail($request->role_id);

        // Security check: only super_admin can create or assign super_admin
        if ($role->code === 'super_admin' && !Auth::user()->hasRole('super_admin')) {
            return back()->with('error', 'শুধুমাত্র সুপার এডমিন অন্য কাউকে সুপার এডমিন রোল দিতে পারেন।');
        }

        $avatarName = 'default-user.png';
        if ($request->hasFile('avatar')) {
            $avatarName = 'user_' . time() . '_' . Str::random(6) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('upload/images/user/'), $avatarName);
        }

        $user = User::create([
            'name'         => $request->name,
            'username'     => $request->username,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'password'     => Hash::make($request->password),
            'user_type_id' => $request->user_type_id,
            'role_id'      => $role->id,
            'role'         => $role->code,
            'data_scope'   => $request->data_scope ?? $role->data_scope ?? 'own',
            'department_id'=> $request->department_id,
            'branch_id'    => $request->branch_id,
            'status'       => $request->status,
            'avatar'       => $avatarName,
            'email_verified_at' => now(),
        ]);

        // Link in pivot table
        DB::table('model_has_roles')->updateOrInsert(
            ['model_id' => $user->id, 'model_type' => 'App\\Models\\User'],
            ['role_id' => $role->id]
        );

        AuditLog::log(
            'USER_CREATED',
            $user,
            null,
            $user->only(['id', 'username', 'name', 'role', 'status']),
            "User {$user->username} created with role {$role->name}"
        );

        return redirect()->route('admin.rbac.users')->with('success', "ব্যবহারকারী '{$user->name}' সফলভাবে তৈরি করা হয়েছে।");
    }

    /**
     * Update user details and role.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|unique:users,email,' . $user->id,
            'phone'        => 'required|string|unique:users,phone,' . $user->id,
            'user_type_id' => 'required|exists:user_types,id',
            'role_id'      => 'required|exists:roles,id',
            'status'       => 'required|in:active,inactive,suspended',
        ]);

        $role = Role::findOrFail($request->role_id);

        // Protection: do not allow non-superadmin to modify a superadmin or elevate to superadmin
        if (($user->hasRole('super_admin') || $role->code === 'super_admin') && !Auth::user()->hasRole('super_admin')) {
            return back()->with('error', 'সুপার এডমিন অ্যাকাউন্ট বা রোল পরিবর্তনের অনুমতি কেবল সুপার এডমিনের রয়েছে।');
        }

        $beforeState = $user->only(['name', 'email', 'phone', 'role', 'status', 'role_id']);

        $updateData = [
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'user_type_id' => $request->user_type_id,
            'role_id'      => $role->id,
            'role'         => $role->code,
            'status'       => $request->status,
            'department_id'=> $request->department_id,
            'branch_id'    => $request->branch_id,
            'data_scope'   => $request->data_scope ?? $user->data_scope,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {
            $avatarName = 'user_' . time() . '_' . Str::random(6) . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('upload/images/user/'), $avatarName);
            $updateData['avatar'] = $avatarName;
        }

        $user->update($updateData);

        // Update pivot
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        DB::table('model_has_roles')->insert([
            'model_id'   => $user->id,
            'model_type' => 'App\\Models\\User',
            'role_id'    => $role->id,
        ]);

        AuditLog::log(
            'USER_UPDATED',
            $user,
            $beforeState,
            $user->only(['name', 'email', 'phone', 'role', 'status', 'role_id']),
            "User {$user->username} profile updated"
        );

        return redirect()->route('admin.rbac.users')->with('success', "ব্যবহারকারী '{$user->name}' সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Toggle user status (Activate / Deactivate / Suspend).
     */
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newStatus = $request->input('status', 'inactive');

        // Prevent deactivating the last active super admin
        if ($user->hasRole('super_admin') && $newStatus !== 'active') {
            $activeSuperAdmins = User::where('role', 'super_admin')->where('status', 'active')->count();
            if ($activeSuperAdmins <= 1) {
                return back()->with('error', 'সিস্টেমের একমাত্র সক্রিয় সুপার এডমিনকে নিষ্ক্রিয় করা যাবে না!');
            }
        }

        $prevStatus = $user->status;
        $user->update(['status' => $newStatus]);

        AuditLog::log(
            'USER_STATUS_CHANGED',
            $user,
            ['status' => $prevStatus],
            ['status' => $newStatus],
            "Changed account status from {$prevStatus} to {$newStatus}"
        );

        return back()->with('success', "ব্যবহারকারী '{$user->name}' স্ট্যাটাস পরিবর্তিত হয়ে '{$newStatus}' হয়েছে।");
    }

    /**
     * Force logout a user by invalidating their remember token.
     */
    public function forceLogout($id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'remember_token' => Str::random(60),
        ]);

        AuditLog::log(
            'USER_FORCE_LOGOUT',
            $user,
            null,
            null,
            "Forced logout invoked for user {$user->username}"
        );

        return back()->with('success', "ব্যবহারকারী '{$user->name}'-এর সেশন সফলভাবে প্রত্যাহার ও ফোর্স লগআউট করা হয়েছে।");
    }

    /**
     * Manage user ad-hoc direct permissions (Fine-grained capabilities).
     */
    public function manageDirectPermissions($id)
    {
        $user = User::with(['roleRelation.permissions', 'directPermissions'])->findOrFail($id);

        $rolePermissions = $user->roleRelation ? $user->roleRelation->permissions->pluck('id')->toArray() : [];
        $directPermissionIds = $user->directPermissions->pluck('id')->toArray();

        $permissionsByModule = RbacPermission::all()->groupBy('module');

        return view('admin.rbac.user_permissions', compact('user', 'rolePermissions', 'directPermissionIds', 'permissionsByModule'));
    }

    /**
     * Update user ad-hoc direct permissions.
     */
    public function updateDirectPermissions(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Security check: non-superadmin cannot alter superadmin permissions
        if ($user->hasRole('super_admin') && !Auth::user()->hasRole('super_admin')) {
            return back()->with('error', 'সুপার এডমিনের পারমিশন শুধুমাত্র সুপার এডমিন পরিবর্তন করতে পারেন।');
        }

        $prevDirectIds = $user->directPermissions->pluck('id')->toArray();
        $newPermissionIds = $request->input('permissions', []);

        $user->syncDirectPermissions($newPermissionIds);

        $assignedCodes = RbacPermission::whereIn('id', $newPermissionIds)->pluck('code')->toArray();

        AuditLog::log(
            'USER_DIRECT_PERMISSIONS_UPDATED',
            $user,
            ['direct_permission_ids' => $prevDirectIds],
            ['direct_permission_ids' => $newPermissionIds, 'codes' => $assignedCodes],
            "Updated ad-hoc direct permissions for user {$user->username}"
        );

        return redirect()->route('admin.rbac.users')->with('success', "ব্যবহারকারী '{$user->name}'-এর ডাইনামিক পারমিশন সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Display Roles & Permissions list.
     */
    public function roles()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        $permissionsByModule = RbacPermission::all()->groupBy('module');

        return view('admin.rbac.roles', compact('roles', 'permissionsByModule'));
    }

    /**
     * Store new custom Role with permissions.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'code'        => 'required|string|unique:roles,code|max:50',
            'category'    => 'required|string',
            'data_scope'  => 'required|string',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name'        => $request->name,
            'code'        => Str::slug($request->code, '_'),
            'category'    => $request->category,
            'data_scope'  => $request->data_scope,
            'description' => $request->description,
            'is_system'   => false,
            'status'      => 'active',
            'created_by'  => Auth::id(),
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        AuditLog::log(
            'ROLE_CREATED',
            $role,
            null,
            $role->toArray(),
            "New Role '{$role->name}' created"
        );

        return redirect()->route('admin.rbac.roles')->with('success', "নতুন রোল '{$role->name}' সফলভাবে তৈরি হয়েছে।");
    }

    /**
     * Update Role and its permissions.
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:100',
            'data_scope'  => 'required|string',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $beforeState = [
            'name'        => $role->name,
            'data_scope'  => $role->data_scope,
            'permissions' => $role->permissions->pluck('id')->toArray(),
        ];

        $role->update([
            'name'        => $request->name,
            'data_scope'  => $request->data_scope,
            'description' => $request->description,
        ]);

        // Sync permissions
        $permIds = $request->input('permissions', []);
        $role->permissions()->sync($permIds);

        AuditLog::log(
            'ROLE_UPDATED',
            $role,
            $beforeState,
            ['name' => $role->name, 'data_scope' => $role->data_scope, 'permissions' => $permIds],
            "Role '{$role->name}' permissions and scope updated"
        );

        return redirect()->route('admin.rbac.roles')->with('success', "রোল '{$role->name}' এবং পারমিশন সফলভাবে আপডেট করা হয়েছে।");
    }

    /**
     * Clone an existing role.
     */
    public function cloneRole(Request $request, $id)
    {
        $sourceRole = Role::with('permissions')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|unique:roles,code|max:50',
        ]);

        $newRole = Role::create([
            'name'        => $request->name,
            'code'        => Str::slug($request->code, '_'),
            'category'    => $sourceRole->category,
            'data_scope'  => $sourceRole->data_scope,
            'description' => 'Cloned from ' . $sourceRole->name . '. ' . $sourceRole->description,
            'is_system'   => false,
            'status'      => 'active',
            'created_by'  => Auth::id(),
        ]);

        $newRole->permissions()->sync($sourceRole->permissions->pluck('id'));

        AuditLog::log(
            'ROLE_CLONED',
            $newRole,
            null,
            ['cloned_from' => $sourceRole->id],
            "Role {$newRole->name} cloned from {$sourceRole->name}"
        );

        return back()->with('success', "রোল '{$sourceRole->name}' ক্লোন করে নতুন রোল '{$newRole->name}' তৈরি করা হয়েছে।");
    }

    /**
     * Archive custom role (Cannot delete system roles).
     */
    public function archiveRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->is_system) {
            return back()->with('error', 'সিস্টেমের ডিফল্ট বা প্রি-বিল্ট রোল আর্কাইভ বা মুছে ফেলা সম্ভব নয়!');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'এই রোলে সক্রিয় ব্যবহারকারী থাকায় রোলটি আর্কাইভ করা যাবে না। আগে ব্যবহারকারীদের রোল পরিবর্তন করুন।');
        }

        $role->update(['status' => 'archived']);

        AuditLog::log('ROLE_ARCHIVED', $role, null, null, "Role {$role->name} archived");

        return back()->with('success', "রোল '{$role->name}' সফলভাবে আর্কাইভ করা হয়েছে।");
    }

    /**
     * View Permission Catalog Matrix.
     */
    public function permissions()
    {
        $permissions = RbacPermission::with('roles')->get();
        $permissionsByModule = $permissions->groupBy('module');
        $roles = Role::where('status', 'active')->get();

        return view('admin.rbac.permissions', compact('permissionsByModule', 'roles'));
    }

    /**
     * Display Audit Logs & Trail with Diff Inspector.
     */
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('actor')->orderBy('id', 'desc');

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('actor')) {
            $actorSearch = trim($request->actor);
            $query->where(function ($q) use ($actorSearch) {
                $q->where('actor_name', 'like', "%{$actorSearch}%")
                  ->orWhere('actor_role', 'like', "%{$actorSearch}%")
                  ->orWhere('ip_address', 'like', "%{$actorSearch}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Export to CSV if requested
        if ($request->get('export') === 'csv') {
            $logs = $query->get();
            $filename = 'audit_logs_' . date('Y-m-d_H-i-s') . '.csv';

            $headers = [
                'Content-Type'        => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function () use ($logs) {
                $file = fopen('php://output', 'w');
                // UTF-8 BOM for Excel Bangla compatibility
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, ['ID', 'Actor Name', 'Role', 'Action', 'Target Type', 'Target ID', 'Reason', 'IP Address', 'Timestamp UTC']);

                foreach ($logs as $log) {
                    fputcsv($file, [
                        $log->id,
                        $log->actor_name,
                        $log->actor_role,
                        $log->action,
                        $log->subject_type,
                        $log->subject_id,
                        $log->reason,
                        $log->ip_address,
                        $log->created_at->toIso8601String(),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $auditLogs = $query->paginate(25);

        return view('admin.rbac.audit_logs', compact('auditLogs'));
    }

    /**
     * Security Hub: Live Cyber Alerts, Fraud Cases, IP Blocklist.
     */
    public function securityHub()
    {
        $alerts = SecurityAlert::orderBy('id', 'desc')->take(20)->get();
        $fraudCases = FraudCase::orderBy('id', 'desc')->take(20)->get();
        $blocklists = Blocklist::with('blocker')->orderBy('id', 'desc')->get();

        $activeAlertsCount = SecurityAlert::where('status', 'active')->count();
        $openFraudCasesCount = FraudCase::where('status', 'open')->count();
        $activeBlocksCount = Blocklist::where('status', 'active')->count();

        return view('admin.rbac.security_hub', compact(
            'alerts',
            'fraudCases',
            'blocklists',
            'activeAlertsCount',
            'openFraudCasesCount',
            'activeBlocksCount'
        ));
    }

    /**
     * Add entry to Blocklist (IP / Phone / Email / Account).
     */
    public function storeBlocklist(Request $request)
    {
        $request->validate([
            'type'   => 'required|in:ip,phone,email,account',
            'value'  => 'required|string|max:191',
            'reason' => 'required|string|max:500',
        ]);

        $block = Blocklist::create([
            'type'       => $request->type,
            'value'      => trim($request->value),
            'reason'     => $request->reason,
            'blocked_by' => Auth::id(),
            'status'     => 'active',
        ]);

        AuditLog::log(
            'BLOCKLIST_ADDED',
            $block,
            null,
            $block->toArray(),
            "Added {$block->type} '{$block->value}' to blocklist. Reason: {$block->reason}"
        );

        return back()->with('success', "{$request->type} '{$request->value}' সফলভাবে ব্লকলিস্টে যুক্ত করা হয়েছে।");
    }

    /**
     * Remove entry from Blocklist.
     */
    public function removeBlocklist($id)
    {
        $block = Blocklist::findOrFail($id);
        $block->delete();

        AuditLog::log(
            'BLOCKLIST_REMOVED',
            null,
            ['value' => $block->value, 'type' => $block->type],
            null,
            "Removed {$block->type} '{$block->value}' from blocklist"
        );

        return back()->with('success', "ব্লকলিস্ট থেকে '{$block->value}' অবমুক্ত (Unblock) করা হয়েছে।");
    }

    /**
     * Update Cyber Alert status.
     */
    public function acknowledgeAlert($id)
    {
        $alert = SecurityAlert::findOrFail($id);
        $alert->update([
            'status'      => 'acknowledged',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        AuditLog::log('CYBER_ALERT_ACKNOWLEDGED', $alert, null, null, "Alert {$alert->title} acknowledged");

        return back()->with('success', 'সাইবার অ্যালার্ট সফলভাবে গৃহীত (Acknowledged) হয়েছে।');
    }

    /**
     * Update Fraud Case status and resolution.
     */
    public function updateFraudCase(Request $request, $id)
    {
        $case = FraudCase::findOrFail($id);
        $request->validate([
            'status' => 'required|in:open,investigating,resolved,escalated',
            'notes'  => 'nullable|string',
        ]);

        $case->update([
            'status' => $request->status,
            'notes'  => $request->notes ? ($case->notes . "\n[" . now()->format('Y-m-d H:i') . ' by ' . Auth::user()->name . ']: ' . $request->notes) : $case->notes,
        ]);

        AuditLog::log('FRAUD_CASE_UPDATED', $case, null, ['status' => $case->status], "Fraud Case {$case->case_number} status updated to {$case->status}");

        return back()->with('success', "ফ্রড কেস '{$case->case_number}' সফলভাবে আপডেট করা হয়েছে।");
    }
}
