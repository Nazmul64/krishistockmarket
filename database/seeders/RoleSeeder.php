<?php

namespace Database\Seeders;

use App\Models\RbacPermission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesData = [
            [
                'name'          => 'Super Admin',
                'slug'          => 'super_admin',
                'code'          => 'super_admin',
                'portal_prefix' => '/admin',
                'guard_name'    => 'admin_guard',
                'category'      => 'Internal',
                'data_scope'    => 'all',
                'is_system'     => true,
                'description'   => 'সর্বোচ্চ নিয়ন্ত্রক: সম্পূর্ণ সিস্টেম কনফিগারেশন, রোল অ্যাসাইনমেন্ট, ইউজার লাইফসাইকেল।',
                'permissions'   => ['*'], // Universal permissions
            ],
            [
                'name'          => 'Admin',
                'slug'          => 'admin',
                'code'          => 'admin',
                'portal_prefix' => '/admin',
                'guard_name'    => 'admin_guard',
                'category'      => 'Internal',
                'data_scope'    => 'all',
                'is_system'     => true,
                'description'   => 'প্রশাসক: সম্পূর্ণ সিস্টেম কনফিগারেশন, রোল অ্যাসাইনমেন্ট, ইউজার লাইফসাইকেল।',
                'permissions'   => [
                    'dashboard.view',
                    'users.manage', 'users.view', 'users.create', 'users.update', 'users.deactivate', 'users.restore', 'users.assign_role', 'users.reset_password', 'users.force_logout',
                    'packages.view', 'packages.create', 'packages.upload_image', 'packages.publish', 'packages.manage',
                    'chat.view',
                    'tickets.assign', 'tickets.view', 'tickets.close', 'tickets.escalate',
                    'cyber_alerts.view', 'cyber_alerts.acknowledge', 'cyber_alerts.escalate',
                    'blocklist.view', 'blocklist.add', 'blocklist.remove', 'blocklist.manage',
                    'audit_logs.view', 'audit_logs.export',
                    'roles.view', 'roles.create', 'roles.update', 'roles.clone',
                    'permissions.view', 'permissions.assign',
                    'employees.view', 'employees.create', 'employees.update', 'employees.export',
                    'customers.view', 'customers.verify', 'customers.restrict', 'customers.export',
                    'fraud_cases.view', 'fraud_cases.assign', 'fraud_cases.investigate', 'fraud_cases.resolve',
                    'reports.view', 'reports.export',
                    'settings.view', 'settings.update',
                    'stocks.manage', 'finance.withdraw', 'finance.deposit',
                ],
            ],
            [
                'name'          => 'Manager',
                'slug'          => 'manager',
                'code'          => 'manager',
                'portal_prefix' => '/manager',
                'guard_name'    => 'staff_guard',
                'category'      => 'Internal',
                'data_scope'    => 'team',
                'is_system'     => true,
                'description'   => 'ম্যানেজার: টিম তদারকি, অ্যাপ্রুভাল প্রসেস, বিভাগীয় রিপোর্ট।',
                'permissions'   => [
                    'dashboard.view',
                    'packages.view', 'packages.create', 'packages.upload_image', 'packages.publish', 'packages.manage',
                    'chat.view',
                    'employees.view', 'employees.update',
                    'reports.view',
                    'tickets.view',
                ],
            ],
            [
                'name'          => 'Employee',
                'slug'          => 'employee',
                'code'          => 'employee',
                'portal_prefix' => '/employee',
                'guard_name'    => 'staff_guard',
                'category'      => 'Internal',
                'data_scope'    => 'own',
                'is_system'     => true,
                'description'   => 'এমপ্লয়ী: অ্যাসাইন করা নির্দিষ্ট টাস্ক (যেমন: প্যাকেজ কনটেন্ট/ছবি আপডেট)।',
                'permissions'   => [
                    'dashboard.view',
                    'packages.view',
                ],
            ],
            [
                'name'          => 'Staff',
                'slug'          => 'staff',
                'code'          => 'staff',
                'portal_prefix' => '/staff',
                'guard_name'    => 'staff_guard',
                'category'      => 'Internal',
                'data_scope'    => 'assigned',
                'is_system'     => true,
                'description'   => 'অপারেশনাল স্টাফ: দৈনিক অপারেশনাল ইনপুট ও সীমিত ডেটা এন্ট্রি।',
                'permissions'   => [
                    'dashboard.view',
                    'packages.view',
                    'tickets.view', 'tickets.reply', 'tickets.escalate',
                ],
            ],
            [
                'name'          => 'Support Lead',
                'slug'          => 'support_lead',
                'code'          => 'support_lead',
                'portal_prefix' => '/support',
                'guard_name'    => 'support_guard',
                'category'      => 'Internal',
                'data_scope'    => 'team',
                'is_system'     => true,
                'description'   => 'সাপোর্ট প্রধান: টিকেট এসাইনমেন্ট, সাপোর্ট টিম মনিটরিং, এসকেলেশন।',
                'permissions'   => [
                    'dashboard.view',
                    'chat.view', 'chat.reply',
                    'tickets.assign', 'tickets.view', 'tickets.reply', 'tickets.close', 'tickets.escalate',
                    'customers.view', 'customers.verify',
                    'reports.view',
                ],
            ],
            [
                'name'          => 'Support Agent',
                'slug'          => 'support_agent',
                'code'          => 'support_agent',
                'portal_prefix' => '/support',
                'guard_name'    => 'support_guard',
                'category'      => 'Internal',
                'data_scope'    => 'assigned',
                'is_system'     => true,
                'description'   => 'সাপোর্ট এজেন্ট: লাইভ চ্যাট হ্যান্ডলিং, সরাসরি গ্রাহকের টিকেট রিপ্লাই।',
                'permissions'   => [
                    'dashboard.view',
                    'chat.view', 'chat.reply',
                    'tickets.view', 'tickets.reply', 'tickets.close', 'tickets.escalate',
                    'customers.view',
                ],
            ],
            [
                'name'          => 'Security Analyst',
                'slug'          => 'security_analyst',
                'code'          => 'security_analyst',
                'portal_prefix' => '/security',
                'guard_name'    => 'security_guard',
                'category'      => 'Internal',
                'data_scope'    => 'security',
                'is_system'     => true,
                'description'   => 'নিরাপত্তা বিশ্লেষক: লাইভ সাইবার অ্যালার্ট, ব্রুট-ফোর্স মনিটরিং, আইপি ব্লকলিস্ট।',
                'permissions'   => [
                    'dashboard.view',
                    'cyber_alerts.view', 'cyber_alerts.acknowledge', 'cyber_alerts.escalate',
                    'blocklist.manage', 'blocklist.view', 'blocklist.add', 'blocklist.remove',
                    'audit_logs.view',
                    'fraud_cases.view', 'fraud_cases.assign', 'fraud_cases.investigate', 'fraud_cases.resolve',
                    'customers.view', 'customers.restrict',
                ],
            ],
            [
                'name'          => 'Auditor (Read-only)',
                'slug'          => 'auditor',
                'code'          => 'auditor',
                'portal_prefix' => '/audit',
                'guard_name'    => 'audit_guard',
                'category'      => 'Internal',
                'data_scope'    => 'all',
                'is_system'     => true,
                'description'   => 'অডিটর: সিস্টেম অডিট ট্রেইল, অপরিবর্তনীয় লগ ও কমপ্লায়েন্স রিপোর্ট।',
                'permissions'   => [
                    'dashboard.view',
                    'audit_logs.view', 'audit_logs.export',
                    'cyber_alerts.view',
                    'chat.view',
                    'packages.view',
                    'users.view',
                    'reports.view', 'reports.export',
                ],
            ],
            [
                'name'          => 'Customer',
                'slug'          => 'user',
                'code'          => 'user',
                'portal_prefix' => '/portal',
                'guard_name'    => 'customer_guard',
                'category'      => 'External',
                'data_scope'    => 'own',
                'is_system'     => true,
                'description'   => 'গ্রাহক: নিজস্ব সার্ভিস, প্রোফাইল ম্যানেজমেন্ট ও সাপোর্ট টিকেট তৈরি।',
                'permissions'   => [
                    'dashboard.view',
                    'packages.view',
                ],
            ],
        ];

        $allPermissions = RbacPermission::all();

        foreach ($rolesData as $data) {
            $perms = $data['permissions'];
            unset($data['permissions']);

            $role = Role::updateOrCreate(['code' => $data['code']], $data);

            if ($perms === ['*']) {
                $role->permissions()->sync($allPermissions->pluck('id'));
                // Also sync role_has_permissions
                DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
                foreach ($allPermissions as $perm) {
                    DB::table('role_has_permissions')->insert([
                        'role_id'       => $role->id,
                        'permission_id' => $perm->id,
                    ]);
                }
            } else {
                $permIds = RbacPermission::whereIn('code', $perms)->orWhereIn('slug', $perms)->pluck('id');
                $role->permissions()->sync($permIds);
                // Also sync role_has_permissions
                DB::table('role_has_permissions')->where('role_id', $role->id)->delete();
                foreach ($permIds as $pid) {
                    DB::table('role_has_permissions')->insert([
                        'role_id'       => $role->id,
                        'permission_id' => $pid,
                    ]);
                }
            }
        }
    }
}
