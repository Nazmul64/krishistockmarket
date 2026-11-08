<?php

namespace Database\Seeders;

use App\Models\RbacPermission;
use Illuminate\Database\Seeder;

class PermissionCatalogueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 1. Dashboard
            ['code' => 'dashboard.view', 'slug' => 'dashboard.view', 'module' => 'Dashboard', 'action' => 'view', 'name' => 'নিজস্ব Role-এর dashboard দেখা', 'description' => 'View role-specific dashboard'],

            // 2. Users Management
            ['code' => 'users.manage', 'slug' => 'users.manage', 'module' => 'Users', 'action' => 'manage', 'name' => 'ব্যবহারকারী সামগ্রিক ব্যবস্থাপনা', 'description' => 'Full User lifecycle management (Create/Update/Role)'],
            ['code' => 'users.view', 'slug' => 'users.view', 'module' => 'Users', 'action' => 'view', 'name' => 'ব্যবহারকারী তালিকা দেখা', 'description' => 'View users list'],
            ['code' => 'users.create', 'slug' => 'users.create', 'module' => 'Users', 'action' => 'create', 'name' => 'নতুন ব্যবহারকারী তৈরি করা', 'description' => 'Create new user'],
            ['code' => 'users.update', 'slug' => 'users.update', 'module' => 'Users', 'action' => 'update', 'name' => 'ব্যবহারকারী তথ্য আপডেট করা', 'description' => 'Update user details'],
            ['code' => 'users.deactivate', 'slug' => 'users.deactivate', 'module' => 'Users', 'action' => 'deactivate', 'name' => 'ব্যবহারকারী নিষ্ক্রিয়/লক করা', 'description' => 'Deactivate or lock user account'],
            ['code' => 'users.restore', 'slug' => 'users.restore', 'module' => 'Users', 'action' => 'restore', 'name' => 'ব্যবহারকারী সক্রিয়/পুনরুদ্ধার করা', 'description' => 'Restore or activate user account'],
            ['code' => 'users.assign_role', 'slug' => 'users.assign_role', 'module' => 'Users', 'action' => 'assign_role', 'name' => 'ব্যবহারকারীকে রোল অর্পণ করা', 'description' => 'Assign role to user'],
            ['code' => 'users.reset_password', 'slug' => 'users.reset_password', 'module' => 'Users', 'action' => 'reset_password', 'name' => 'পাসওয়ার্ড রিসেট লিংক প্রেরণ', 'description' => 'Reset user password or send invite'],
            ['code' => 'users.force_logout', 'slug' => 'users.force_logout', 'module' => 'Users', 'action' => 'force_logout', 'name' => 'সেশন বন্ধ/ফোর্স লগআউট করা', 'description' => 'Force logout active sessions'],

            // 3. Our Packages Module (Granular Actions)
            ['code' => 'packages.view', 'slug' => 'packages.view', 'module' => 'Our Packages', 'action' => 'view', 'name' => 'প্যাকেজ তালিকা দেখা', 'description' => 'View packages list and gallery'],
            ['code' => 'packages.create', 'slug' => 'packages.create', 'module' => 'Our Packages', 'action' => 'create', 'name' => 'নতুন প্যাকেজ তৈরি করা', 'description' => 'Create new packages'],
            ['code' => 'packages.upload_image', 'slug' => 'packages.upload_image', 'module' => 'Our Packages', 'action' => 'upload_image', 'name' => 'প্যাকেজে ছবি বা ব্যানার যোগ করা', 'description' => 'Upload package images or banners (Can be granted ad-hoc to employees)'],
            ['code' => 'packages.publish', 'slug' => 'packages.publish', 'module' => 'Our Packages', 'action' => 'publish', 'name' => 'প্যাকেজ লাইভ/অনলাইন করা', 'description' => 'Publish package online or toggle visibility'],
            ['code' => 'packages.manage', 'slug' => 'packages.manage', 'module' => 'Our Packages', 'action' => 'manage', 'name' => 'প্যাকেজ পূর্ণ ব্যবস্থাপনা (সম্পাদনা ও ডিলিট)', 'description' => 'Full package management including edit and delete'],

            // 4. Live Chat & Support Module
            ['code' => 'chat.view', 'slug' => 'chat.view', 'module' => 'Live Chat', 'action' => 'view', 'name' => 'চলমান লাইভ চ্যাট পর্যবেক্ষণ করা', 'description' => 'Observe active live chat sessions (View only)'],
            ['code' => 'chat.reply', 'slug' => 'chat.reply', 'module' => 'Live Chat', 'action' => 'reply', 'name' => 'গ্রাহকের সাথে সরাসরি চ্যাটে কথা বলা', 'description' => 'Reply to customers in live chat in real time'],
            ['code' => 'tickets.view', 'slug' => 'tickets.view', 'module' => 'Support', 'action' => 'view', 'name' => 'সাপোর্ট টিকিট দেখা', 'description' => 'View customer support tickets'],
            ['code' => 'tickets.reply', 'slug' => 'tickets.reply', 'module' => 'Support', 'action' => 'reply', 'name' => 'টিকিটে উত্তর প্রদান করা', 'description' => 'Reply to customer tickets'],
            ['code' => 'tickets.assign', 'slug' => 'tickets.assign', 'module' => 'Support', 'action' => 'assign', 'name' => 'টিকেট অন্য এজেন্টের কাছে পাঠানো', 'description' => 'Assign tickets to other agents or leads'],
            ['code' => 'tickets.close', 'slug' => 'tickets.close', 'module' => 'Support', 'action' => 'close', 'name' => 'টিকেট সম্পন্ন/বন্ধ করা', 'description' => 'Close resolved tickets'],
            ['code' => 'tickets.escalate', 'slug' => 'tickets.escalate', 'module' => 'Support', 'action' => 'escalate', 'name' => 'টিকেট ঊর্ধ্বতন কর্মকর্তার কাছে পাঠানো', 'description' => 'Escalate tickets to lead or admin'],

            // 5. Security & Cyber Alerts Module
            ['code' => 'cyber_alerts.view', 'slug' => 'cyber_alerts.view', 'module' => 'Cyber Alerts', 'action' => 'view', 'name' => 'সিকিউরিটি অ্যালার্ট লগ দেখা', 'description' => 'View security and cyber alerts log'],
            ['code' => 'cyber_alerts.acknowledge', 'slug' => 'cyber_alerts.acknowledge', 'module' => 'Cyber Alerts', 'action' => 'acknowledge', 'name' => 'সাইবার অ্যালার্ট গ্রহণ করা', 'description' => 'Acknowledge cyber alerts'],
            ['code' => 'cyber_alerts.escalate', 'slug' => 'cyber_alerts.escalate', 'module' => 'Cyber Alerts', 'action' => 'escalate', 'name' => 'সাইবার হুমকি এস্কেলেট করা', 'description' => 'Escalate security threats'],
            ['code' => 'blocklist.view', 'slug' => 'blocklist.view', 'module' => 'Blocklist', 'action' => 'view', 'name' => 'ব্লকলিস্ট/আইপি তালিকা দেখা', 'description' => 'View IP and account blocklist'],
            ['code' => 'blocklist.add', 'slug' => 'blocklist.add', 'module' => 'Blocklist', 'action' => 'add', 'name' => 'ক্ষতিকর আইপি বা ইউজার ব্লক করা', 'description' => 'Add malicious IP or user to blocklist'],
            ['code' => 'blocklist.remove', 'slug' => 'blocklist.remove', 'module' => 'Blocklist', 'action' => 'remove', 'name' => 'ব্লকলিস্ট থেকে অবমুক্ত করা', 'description' => 'Unblock IP or user'],
            ['code' => 'blocklist.manage', 'slug' => 'blocklist.manage', 'module' => 'Blocklist', 'action' => 'manage', 'name' => 'ব্লকলিস্ট সম্পূর্ণ নিয়ন্ত্রণ', 'description' => 'Full blocklist management'],
            ['code' => 'fraud_cases.view', 'slug' => 'fraud_cases.view', 'module' => 'Fraud', 'action' => 'view', 'name' => 'ফ্রড কেস দেখা', 'description' => 'View fraud detection cases'],
            ['code' => 'fraud_cases.assign', 'slug' => 'fraud_cases.assign', 'module' => 'Fraud', 'action' => 'assign', 'name' => 'ফ্রড কেস এসাইন করা', 'description' => 'Assign fraud case to analyst'],
            ['code' => 'fraud_cases.investigate', 'slug' => 'fraud_cases.investigate', 'module' => 'Fraud', 'action' => 'investigate', 'name' => 'ফ্রড অনুসন্ধান পরিচালনা', 'description' => 'Investigate fraud cases and add notes'],
            ['code' => 'fraud_cases.resolve', 'slug' => 'fraud_cases.resolve', 'module' => 'Fraud', 'action' => 'resolve', 'name' => 'ফ্রড কেস সমাধান/ক্লোজ', 'description' => 'Resolve or escalate fraud cases'],

            // 6. Audit Logs Module
            ['code' => 'audit_logs.view', 'slug' => 'audit_logs.view', 'module' => 'Audit Logs', 'action' => 'view', 'name' => 'সিস্টেম অডিট ট্রেইল দেখা', 'description' => 'View tamper-evident immutable audit logs'],
            ['code' => 'audit_logs.export', 'slug' => 'audit_logs.export', 'module' => 'Audit Logs', 'action' => 'export', 'name' => 'অডিট লগ এক্সপোর্ট করা', 'description' => 'Export tamper-evident audit logs'],

            // 7. Roles & Permissions Management
            ['code' => 'roles.view', 'slug' => 'roles.view', 'module' => 'Roles', 'action' => 'view', 'name' => 'রোল ক্যাটালগ দেখা', 'description' => 'View roles list and permissions'],
            ['code' => 'roles.create', 'slug' => 'roles.create', 'module' => 'Roles', 'action' => 'create', 'name' => 'নতুন রোল তৈরি করা', 'description' => 'Create new role'],
            ['code' => 'roles.update', 'slug' => 'roles.update', 'module' => 'Roles', 'action' => 'update', 'name' => 'রোল সম্পাদনা করা', 'description' => 'Update role information'],
            ['code' => 'roles.clone', 'slug' => 'roles.clone', 'module' => 'Roles', 'action' => 'clone', 'name' => 'রোল ক্লোন করা', 'description' => 'Clone existing role'],
            ['code' => 'roles.archive', 'slug' => 'roles.archive', 'module' => 'Roles', 'action' => 'archive', 'name' => 'রোল আর্কাইভ করা', 'description' => 'Archive custom role'],
            ['code' => 'permissions.view', 'slug' => 'permissions.view', 'module' => 'Permissions', 'action' => 'view', 'name' => 'পারমিশন তালিকা দেখা', 'description' => 'View all permissions'],
            ['code' => 'permissions.assign', 'slug' => 'permissions.assign', 'module' => 'Permissions', 'action' => 'assign', 'name' => 'পারমিশন অর্পণ করা', 'description' => 'Grant permissions to role or user'],
            ['code' => 'permissions.revoke', 'slug' => 'permissions.revoke', 'module' => 'Permissions', 'action' => 'revoke', 'name' => 'পারমিশন প্রত্যাহার করা', 'description' => 'Revoke permissions'],

            // 8. Other Operations
            ['code' => 'employees.view', 'slug' => 'employees.view', 'module' => 'Employees', 'action' => 'view', 'name' => 'এমপ্লয়ী রেকর্ড দেখা', 'description' => 'View employee records'],
            ['code' => 'employees.create', 'slug' => 'employees.create', 'module' => 'Employees', 'action' => 'create', 'name' => 'এমপ্লয়ী যোগ করা', 'description' => 'Create new employee'],
            ['code' => 'employees.update', 'slug' => 'employees.update', 'module' => 'Employees', 'action' => 'update', 'name' => 'এমপ্লয়ী তথ্য সংশোধন', 'description' => 'Update employee info'],
            ['code' => 'employees.export', 'slug' => 'employees.export', 'module' => 'Employees', 'action' => 'export', 'name' => 'এমপ্লয়ী ডাটা এক্সপোর্ট', 'description' => 'Export employee records'],
            ['code' => 'customers.view', 'slug' => 'customers.view', 'module' => 'Customers', 'action' => 'view', 'name' => 'গ্রাহক তথ্য দেখা', 'description' => 'View customer profile and stats'],
            ['code' => 'customers.verify', 'slug' => 'customers.verify', 'module' => 'Customers', 'action' => 'verify', 'name' => 'গ্রাহক পরিচিতি যাচাই (KYC)', 'description' => 'Verify customer identity'],
            ['code' => 'customers.restrict', 'slug' => 'customers.restrict', 'module' => 'Customers', 'action' => 'restrict', 'name' => 'গ্রাহক অ্যাকাউন্ট রেস্ট্রিক্ট করা', 'description' => 'Restrict customer operations'],
            ['code' => 'customers.export', 'slug' => 'customers.export', 'module' => 'Customers', 'action' => 'export', 'name' => 'গ্রাহক তালিকা এক্সপোর্ট', 'description' => 'Export customer data'],
            ['code' => 'reports.view', 'slug' => 'reports.view', 'module' => 'Reports', 'action' => 'view', 'name' => 'সিস্টেম রিপোর্ট দেখা', 'description' => 'View analytical reports'],
            ['code' => 'reports.export', 'slug' => 'reports.export', 'module' => 'Reports', 'action' => 'export', 'name' => 'রিপোর্ট এক্সপোর্ট করা', 'description' => 'Export financial and operational reports'],
            ['code' => 'settings.view', 'slug' => 'settings.view', 'module' => 'Settings', 'action' => 'view', 'name' => 'সিস্টেম সেটিংস দেখা', 'description' => 'View global system settings'],
            ['code' => 'settings.update', 'slug' => 'settings.update', 'module' => 'Settings', 'action' => 'update', 'name' => 'সিস্টেম সেটিংস পরিবর্তন করা', 'description' => 'Modify system configurations'],
            ['code' => 'stocks.manage', 'slug' => 'stocks.manage', 'module' => 'Stocks', 'action' => 'manage', 'name' => 'স্টক ক্রয়-বিক্রয় ও বরাদ্দ', 'description' => 'Manage inventory stocks'],
            ['code' => 'finance.withdraw', 'slug' => 'finance.withdraw', 'module' => 'Finance', 'action' => 'withdraw', 'name' => 'উত্তোলন আবেদন অনুমোদন', 'description' => 'Process withdraw requests'],
            ['code' => 'finance.deposit', 'slug' => 'finance.deposit', 'module' => 'Finance', 'action' => 'deposit', 'name' => 'ডিপোজিট আবেদন অনুমোদন', 'description' => 'Process deposit requests'],
        ];

        foreach ($permissions as $p) {
            RbacPermission::updateOrCreate(['code' => $p['code']], $p);
        }
    }
}
