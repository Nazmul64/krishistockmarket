<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Blocklist;
use App\Models\FraudCase;
use App\Models\SecurityAlert;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Database\Seeder;

class RbacSampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $admin = User::where('role', 'admin')->first();
        $supportAgent = User::where('role', 'support_agent')->first();
        $securityAnalyst = User::where('role', 'security_analyst')->first();
        $customer = User::where('role', 'user')->first();

        // 1. Support Tickets
        if (SupportTicket::count() === 0) {
            $t1 = SupportTicket::create([
                'ticket_number'  => 'TCK-2026-001',
                'user_id'        => $customer ? $customer->id : null,
                'customer_name'  => $customer ? $customer->name : 'আরিফুল ইসলাম',
                'customer_email' => $customer ? $customer->email : 'customer@ikrishiporibar.com',
                'customer_phone' => '01911223344',
                'subject'        => 'মাসিক বাজার অর্ডার ডেলিভারি স্ট্যাটাস অনুসন্ধান',
                'description'    => 'আমার ৩ দিন আগের চাল ও তেলের প্যাকেজটি এখনও ডেলিভারি হয়নি। অনুগ্রহ করে আপডেট জানাবেন।',
                'priority'       => 'high',
                'status'         => 'in_progress',
                'category'       => 'Delivery',
                'assigned_to'    => $supportAgent ? $supportAgent->id : null,
                'last_reply_at'  => now(),
            ]);

            TicketReply::create([
                'ticket_id' => $t1->id,
                'user_id'   => $supportAgent ? $supportAgent->id : null,
                'user_name' => $supportAgent ? $supportAgent->name : 'সাপোর্ট স্পেশালিস্ট',
                'message'   => 'প্রিয় গ্রাহক, আপনার ডেলিভারি রাইডার রওনা দিয়েছেন। আজ বিকাল ৫টার মধ্যে পেয়ে যাবেন।',
                'is_staff'  => true,
            ]);

            $t2 = SupportTicket::create([
                'ticket_number'  => 'TCK-2026-002',
                'user_id'        => $customer ? $customer->id : null,
                'customer_name'  => 'কামাল হোসেন',
                'customer_email' => 'kamal@gmail.com',
                'customer_phone' => '01855667788',
                'subject'        => 'ওয়ালেট ডিপোজিট ভেরিফিকেশন সমস্যা',
                'description'    => 'বিকাশ ট্রানজেকশন আইডি দেওয়ার পরও ব্যালেন্স যুক্ত হয়নি। ট্রানজেকশন আইডি: 9J7821BGH',
                'priority'       => 'urgent',
                'status'         => 'open',
                'category'       => 'Billing',
                'assigned_to'    => $supportAgent ? $supportAgent->id : null,
                'last_reply_at'  => now(),
            ]);

            $t3 = SupportTicket::create([
                'ticket_number'  => 'TCK-2026-003',
                'user_id'        => null,
                'customer_name'  => 'তানজিলা আক্তার',
                'customer_email' => 'tanjila@gmail.com',
                'customer_phone' => '01799887766',
                'subject'        => 'কৃষি কার্ড এক্টিভেশন সহায়তা',
                'description'    => 'আমার ১২ ডিজিটের গোল্ডেন কার্ডটি এক্টিভেট করতে সমস্যা হচ্ছে।',
                'priority'       => 'medium',
                'status'         => 'resolved',
                'category'       => 'Membership',
                'assigned_to'    => $supportAgent ? $supportAgent->id : null,
                'last_reply_at'  => now()->subDays(1),
            ]);
        }

        // 2. Cyber Alerts
        if (SecurityAlert::count() === 0) {
            SecurityAlert::create([
                'title'       => 'Multiple Failed Login Attempts (Brute-force pattern)',
                'severity'    => 'high',
                'source_ip'   => '185.220.101.5',
                'target'      => '/admin/login',
                'status'      => 'active',
                'details'     => 'Detected 45 consecutive failed attempts on admin accounts within 60 seconds.',
            ]);

            SecurityAlert::create([
                'title'       => 'Unusual Geolocation API Session',
                'severity'    => 'medium',
                'source_ip'   => '103.112.54.20',
                'target'      => '/api/v1/user/wallet',
                'status'      => 'acknowledged',
                'details'     => 'Token used from unexpected ASN outside Bangladesh.',
            ]);

            SecurityAlert::create([
                'title'       => 'Privilege Escalation Attempt Blocked',
                'severity'    => 'critical',
                'source_ip'   => '192.168.1.115',
                'target'      => '/admin/roles/permissions',
                'status'      => 'resolved',
                'details'     => 'Employee account attempted unauthorized PUT to grant superadmin role. Access Denied by RBAC policy.',
                'resolved_by' => $securityAnalyst ? $securityAnalyst->id : null,
                'resolved_at' => now(),
            ]);
        }

        // 3. Fraud Cases
        if (FraudCase::count() === 0) {
            FraudCase::create([
                'case_number'   => 'FRD-2026-101',
                'customer_id'   => $customer ? $customer->id : null,
                'customer_name' => 'সন্দেহভাজন একাউন্ট #৮৮৯',
                'title'         => 'Duplicate bKash TrxID Re-submission Pattern',
                'risk_score'    => 88,
                'status'        => 'investigating',
                'notes'         => 'Same transaction ID entered across 3 distinct phone numbers within 5 minutes.',
                'assigned_to'   => $securityAnalyst ? $securityAnalyst->id : null,
            ]);

            FraudCase::create([
                'case_number'   => 'FRD-2026-102',
                'customer_id'   => null,
                'customer_name' => 'আব্দুর রাজ্জাক',
                'title'         => 'High Velocity Withdraw Right After Card Purchase',
                'risk_score'    => 65,
                'status'        => 'open',
                'notes'         => 'Immediate withdraw request without any trading activity.',
                'assigned_to'   => $securityAnalyst ? $securityAnalyst->id : null,
            ]);
        }

        // 4. IP & Account Blocklist
        if (Blocklist::count() === 0) {
            Blocklist::create([
                'type'       => 'ip',
                'value'      => '185.220.101.5',
                'reason'     => 'Brute force credential stuffing attack bot',
                'blocked_by' => $securityAnalyst ? $securityAnalyst->id : ($admin ? $admin->id : 1),
                'status'     => 'active',
            ]);

            Blocklist::create([
                'type'       => 'phone',
                'value'      => '01399887766',
                'reason'     => 'Fake bKash deposit slip submission repeat offender',
                'blocked_by' => $admin ? $admin->id : 1,
                'status'     => 'active',
            ]);
        }

        // 5. Initial Audit Logs
        if (AuditLog::count() === 0) {
            AuditLog::create([
                'actor_id'     => $superAdmin ? $superAdmin->id : 1,
                'actor_name'   => 'সুপার এডমিন (Super Admin)',
                'actor_role'   => 'super_admin',
                'action'       => 'RBAC_SYSTEM_INITIALIZED',
                'subject_type' => 'App\\Models\\Role',
                'subject_id'   => 1,
                'before_state' => null,
                'after_state'  => json_encode(['status' => 'initialized', 'version' => '1.0']),
                'reason'       => 'System deployment and RBAC baseline setup',
                'ip_address'   => '127.0.0.1',
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'request_id'   => 'req_init_001',
            ]);

            AuditLog::create([
                'actor_id'     => $admin ? $admin->id : 2,
                'actor_name'   => 'সিস্টেম এডমিন (System Admin)',
                'actor_role'   => 'admin',
                'action'       => 'ROLE_PERMISSIONS_SYNCED',
                'subject_type' => 'App\\Models\\Role',
                'subject_id'   => 2,
                'before_state' => null,
                'after_state'  => json_encode(['assigned_permissions_count' => 32]),
                'reason'       => 'Synchronized role permission catalog matrix',
                'ip_address'   => '127.0.0.1',
                'user_agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'request_id'   => 'req_init_002',
            ]);
        }
    }
}
