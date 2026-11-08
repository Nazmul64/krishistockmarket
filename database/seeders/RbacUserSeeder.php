<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RbacUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $internalType = UserType::where('code', 'internal')->first();
        $externalType = UserType::where('code', 'external')->first();

        $users = [
            [
                'name'         => 'সুপার এডমিন (Super Admin)',
                'username'     => 'superadmin',
                'email'        => 'admin@gmail.com',
                'phone'        => '01711000001',
                'password'     => 'admin@gmail.com',
                'role'         => 'super_admin',
                'role_code'    => 'super_admin',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'all',
                'status'       => 'active',
            ],
            [
                'name'         => 'সিস্টেম এডমিন (System Admin)',
                'username'     => 'admin_user',
                'email'        => 'admin.user@ikrishiporibar.com',
                'phone'        => '01711000002',
                'password'     => '12345678',
                'role'         => 'admin',
                'role_code'    => 'admin',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'all',
                'status'       => 'active',
            ],
            [
                'name'         => 'ব্রাঞ্চ ও টিম ম্যানেজার (Branch Manager)',
                'username'     => 'manager_user',
                'email'        => 'manager@ikrishiporibar.com',
                'phone'        => '01711000003',
                'password'     => '12345678',
                'role'         => 'manager',
                'role_code'    => 'manager',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'team',
                'status'       => 'active',
            ],
            [
                'name'         => 'বিপণন এজেন্ট ১ (Marketing Agent)',
                'username'     => '01700000000',
                'email'        => 'agent@ikrishiporibar.com',
                'phone'        => '01700000000',
                'password'     => '12345678',
                'role'         => 'employee',
                'role_code'    => 'employee',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'own',
                'status'       => 'active',
            ],
            [
                'name'         => 'অপারেশনাল স্টাফ (Operations Staff)',
                'username'     => 'staff_user',
                'email'        => 'staff@ikrishiporibar.com',
                'phone'        => '01711000005',
                'password'     => '12345678',
                'role'         => 'staff',
                'role_code'    => 'staff',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'assigned',
                'status'       => 'active',
            ],
            [
                'name'         => 'সাপোর্ট টিম লিড (Support Lead)',
                'username'     => 'support_lead',
                'email'        => 'supportlead@ikrishiporibar.com',
                'phone'        => '01711000006',
                'password'     => '12345678',
                'role'         => 'support_lead',
                'role_code'    => 'support_lead',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'team',
                'status'       => 'active',
            ],
            [
                'name'         => 'সাপোর্ট স্পেশালিস্ট (Support Agent)',
                'username'     => 'support_agent',
                'email'        => 'support@ikrishiporibar.com',
                'phone'        => '01711000007',
                'password'     => '12345678',
                'role'         => 'support_agent',
                'role_code'    => 'support_agent',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'assigned',
                'status'       => 'active',
            ],
            [
                'name'         => 'সাইবার ও ফ্রড এনালিস্ট (Security Analyst)',
                'username'     => 'security_analyst',
                'email'        => 'security@ikrishiporibar.com',
                'phone'        => '01711000008',
                'password'     => '12345678',
                'role'         => 'security_analyst',
                'role_code'    => 'security_analyst',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'security',
                'status'       => 'active',
            ],
            [
                'name'         => 'কমপ্লায়েন্স ও অডিটর (Auditor)',
                'username'     => 'auditor_user',
                'email'        => 'auditor@ikrishiporibar.com',
                'phone'        => '01711000009',
                'password'     => '12345678',
                'role'         => 'auditor',
                'role_code'    => 'auditor',
                'user_type_id' => $internalType ? $internalType->id : null,
                'data_scope'   => 'all',
                'status'       => 'active',
            ],
            [
                'name'         => 'মেসার্স রহিম ট্রেডার্স (Supplier/Seller)',
                'username'     => 'rahim_traders',
                'email'        => 'rahimtraders@gmail.com',
                'phone'        => '01811223344',
                'password'     => '12345678',
                'role'         => 'supplier',
                'role_code'    => 'supplier',
                'user_type_id' => $externalType ? $externalType->id : null,
                'data_scope'   => 'own',
                'status'       => 'active',
            ],
            [
                'name'         => 'মো: আরিফুল ইসলাম (Customer/User)',
                'username'     => 'customer_user',
                'email'        => 'customer@ikrishiporibar.com',
                'phone'        => '01911223344',
                'password'     => '12345678',
                'role'         => 'user',
                'role_code'    => 'user',
                'user_type_id' => $externalType ? $externalType->id : null,
                'data_scope'   => 'own',
                'status'       => 'active',
            ],
        ];

        foreach ($users as $u) {
            $roleCode = $u['role_code'];
            unset($u['role_code']);

            $role = Role::where('code', $roleCode)->first();
            $u['role_id'] = $role ? $role->id : null;
            $password = $u['password'];
            $u['password'] = Hash::make($password);
            $u['email_verified_at'] = now();

            $existing = User::where('username', $u['username'])
                ->orWhere('email', $u['email'])
                ->first();

            $isInternal = in_array($roleCode, ['super_admin', 'admin', 'manager', 'employee', 'staff', 'support_lead', 'support_agent', 'security_analyst', 'auditor']);
            $u['user_type'] = $isInternal ? 'internal' : 'external';
            $u['is_active'] = true;

            if ($existing) {
                $existing->update([
                    'name'         => $u['name'],
                    'role'         => $u['role'],
                    'role_id'      => $u['role_id'],
                    'user_type'    => $u['user_type'],
                    'is_active'    => $u['is_active'],
                    'user_type_id' => $u['user_type_id'],
                    'data_scope'   => $u['data_scope'],
                    'status'       => $u['status'],
                    'password'     => $u['password'],
                    'phone'        => $u['phone'],
                ]);
                $userObj = $existing;
            } else {
                $userObj = User::create($u);
            }

            // Sync pivot user_roles and model_has_roles
            if ($role) {
                DB::table('user_roles')->updateOrInsert(
                    ['user_id' => $userObj->id, 'role_id' => $role->id]
                );
                DB::table('model_has_roles')->updateOrInsert(
                    ['model_id' => $userObj->id, 'model_type' => 'App\\Models\\User'],
                    ['role_id' => $role->id]
                );
            }
        }
    }
}
