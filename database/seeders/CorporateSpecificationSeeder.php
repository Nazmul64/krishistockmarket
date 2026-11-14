<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CorporateSpecificationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ensure Roles
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super_admin', 'code' => 'super_admin', 'portal_prefix' => '/admin'],
            ['name' => 'Admin', 'slug' => 'admin', 'code' => 'admin', 'portal_prefix' => '/admin'],
            ['name' => 'চেয়ারম্যান / বোর্ড সদস্য', 'slug' => 'chairman', 'code' => 'chairman', 'portal_prefix' => '/admin'],
            ['name' => 'ম্যানেজিং ডিরেক্টর (MD)', 'slug' => 'managing_director', 'code' => 'managing_director', 'portal_prefix' => '/admin'],
            ['name' => 'চিফ এক্সিকিউটিভ অফিসার (CEO)', 'slug' => 'ceo', 'code' => 'ceo', 'portal_prefix' => '/admin'],
            ['name' => 'ফাইন্যান্স এডমিন', 'slug' => 'finance_admin', 'code' => 'finance_admin', 'portal_prefix' => '/admin'],
            ['name' => 'বিনিয়োগ ব্যবস্থাপক (Investment Manager)', 'slug' => 'investment_manager', 'code' => 'investment_manager', 'portal_prefix' => '/admin'],
            ['name' => 'এইচ আর ম্যানেজার (HR Manager)', 'slug' => 'hr_manager', 'code' => 'hr_manager', 'portal_prefix' => '/admin'],
            ['name' => 'মার্কেটিং ম্যানেজার (Marketing Manager)', 'slug' => 'marketing_manager', 'code' => 'marketing_manager', 'portal_prefix' => '/admin'],
            ['name' => 'এজেন্ট ও ডিলার (Agent / Dealer)', 'slug' => 'agent', 'code' => 'agent', 'portal_prefix' => '/employee'],
            ['name' => 'কর্মকর্তা-কর্মচারী (Employee)', 'slug' => 'employee', 'code' => 'employee', 'portal_prefix' => '/employee'],
            ['name' => 'শেয়ারহোল্ডার (Shareholder)', 'slug' => 'shareholder', 'code' => 'shareholder', 'portal_prefix' => '/portal'],
            ['name' => 'বিনিয়োগকারী (Investor / Property Member)', 'slug' => 'investor', 'code' => 'investor', 'portal_prefix' => '/portal'],
            ['name' => 'সাধারণ গ্রাহক (Customer / SME)', 'slug' => 'user', 'code' => 'user', 'portal_prefix' => '/portal'],
        ];

        foreach ($roles as $r) {
            DB::table('roles')->updateOrInsert(['slug' => $r['slug']], [
                'name' => $r['name'],
                'code' => $r['code'],
                'portal_prefix' => $r['portal_prefix'],
                'updated_at' => $now,
                'created_at' => $now,
            ]);
        }

        // 2. Board of Directors & Leadership Messages
        DB::table('board_members')->truncate();
        $boardMembers = [
            [
                'name' => 'Dr. A. K. M. Mostafa Kamal',
                'name_bn' => 'ড. এ. কে. এম. মোস্তফা কামাল',
                'designation' => 'Chairman',
                'designation_bn' => 'চেয়ারম্যান, পরিচালনা পর্ষদ',
                'role_type' => 'chairman',
                'photo' => 'assets/images/board/chairman.jpg',
                'bio' => 'কৃষি অর্থনীতি ও টেকসই বিনিয়োগ ব্যবস্থাপনায় ৩০ বছরেরও বেশি অভিজ্ঞতা সম্পন্ন শিক্ষাবিদ ও উদ্যোক্তা।',
                'administrative_role' => 'প্রতিষ্ঠানের সর্বোচ্চ নীতিনির্ধারণ, কৌশলগত নেতৃত্ব ও করপোরেট সুশাসন নিশ্চিতকরণ।',
                'phone' => '+880 1711-000001',
                'email' => 'chairman@ekrishisheba.com',
                'speech_title' => 'টেকসই কৃষি ও সমৃদ্ধ অর্থনীতির পথে আমাদের অঙ্গীকার',
                'speech_message' => 'বিসমিল্লাহির রাহমানির রাহিম। ই-কৃষি সেবা কেবল একটি বাণিজ্যিক প্রতিষ্ঠান নয়, এটি বাংলাদেশের তৃণমূলের কৃষিজীবী মানুষ ও আধুনিক বিনিয়োগকারীদের সেতুবন্ধন। আমরা স্বচ্ছতা, শরিয়াহসম্মত ব্যবসায়িক আচরণ এবং প্রযুক্তিনির্ভর ব্যবস্থাপনার মাধ্যমে প্রতিটি সদস্যের মূলধনের নিরাপত্তা ও যৌক্তিক প্রবৃদ্ধি নিশ্চিত করতে বদ্ধপরিকর। আমাদের প্রতিটি প্রকল্পে রয়েছে সামাজিক দায়বদ্ধতা ও দেশের খাদ্য নিরাপত্তা জোরদারের সুদূরপ্রসারী লক্ষ্য। আপনাদের অটুট আস্থা ও সমর্থনই আমাদের অনুপ্রেরণা।',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Engr. Mohammad Rafiqul Islam',
                'name_bn' => 'প্রকৌশলী মোহাম্মদ রফিকুল ইসলাম',
                'designation' => 'Managing Director',
                'designation_bn' => 'ম্যানেজিং ডিরেক্টর (MD)',
                'role_type' => 'md',
                'photo' => 'assets/images/board/md.jpg',
                'bio' => 'প্রকৌশল ও বৃহদায়তন কৃষি প্রকল্প বাস্তবায়নে ২০ বছরের অভিজ্ঞতা। স্মার্ট ফার্মিং ও কোল্ড চেইনে বিশেষজ্ঞ।',
                'administrative_role' => 'সার্বিক নির্বাহী কার্যক্রম তত্ত্বাবধান, প্রজেক্ট মনিটরিং এবং ব্যবসায়িক প্রবৃদ্ধি পরিচালনা।',
                'phone' => '+880 1711-000002',
                'email' => 'md@ekrishisheba.com',
                'speech_title' => 'উদ্ভাবন ও প্রযুক্তিনির্ভর আধুনিক প্রকল্প বাস্তবায়নে আমাদের অভিযাত্রা',
                'speech_message' => 'সম্মানিত অংশীদার ও সদস্যবৃন্দ, আসসালামু আলাইকুম। মাঠপর্যায়ের কৃষি উৎপাদন থেকে শুরু করে প্রপার্টি ও কোল্ড-স্টোরেজ ব্যবস্থাপনা—প্রতিটি ক্ষেত্রে আমরা আধুনিক আইওটি (IoT), ডাটা অ্যানালিটিক্স এবং অটোমেশন নিশ্চিত করছি। আমাদের লক্ষ্য হলো কৃষকের উৎপাদিত পণ্যের সর্বোচ্চ মূল্য প্রাপ্তি এবং বিনিয়োগকারীদের জন্য একটি সুশৃঙ্খল, ঝুঁকিহীন ও লাভজনক প্ল্যাটফর্ম প্রদান করা। আমাদের নিষ্ঠাবান কর্মীদের পরিশ্রমে আমরা এগিয়ে চলেছি এক অনন্য উচ্চতায়।',
                'order_num' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Md. Nazmul Hasan',
                'name_bn' => 'মো: নাজমুল হাসান',
                'designation' => 'Chief Executive Officer',
                'designation_bn' => 'চিফ এক্সিকিউটিভ অফিসার (CEO)',
                'role_type' => 'ceo',
                'photo' => 'assets/images/board/ceo.jpg',
                'bio' => 'ফিনটেক ও ডিজিটাল প্ল্যাটফর্ম আর্কিটেকচারে দক্ষ স্ট্র্যাটেজিস্ট। করপোরেট অপারেশনে দীর্ঘ সফল ক্যারিয়ার।',
                'administrative_role' => 'দৈনন্দিন কার্যপরিচালনা, ডিজিটাল প্ল্যাটফর্মের উৎকর্ষ সাধন ও গ্রাহক সন্তুষ্টি নিশ্চিতকরণ।',
                'phone' => '+880 1711-000003',
                'email' => 'ceo@ekrishisheba.com',
                'speech_title' => 'গ্রাহককেন্দ্রিক সেবা ও ডিজিটাল বাংলাদেশের অগ্রযাত্রায় ই-কৃষি সেবা',
                'speech_message' => 'প্রিয় শুভানুধ্যায়ী, আমাদের কেন্দ্রীয় সফটওয়্যার আর্কিটেকচার প্রতিটি মেম্বারশিপ কার্ডধারী, বিনিয়োগকারী ও শেয়ারহোল্ডারের হাতে এনে দিয়েছে পূর্ণ নিয়ন্ত্রণ। স্বচ্ছ হিসাবরক্ষণ, রিয়েল-টাইম ব্যালেন্স ট্র্যাকিং এবং ডিজিটাল উত্তোলনের মতো সুবিধাগুলো গ্রাহকদের সময় বাঁচাচ্ছে এবং আস্থাকে সুদৃঢ় করছে। আমরা প্রতিনিয়ত প্ল্যাটফর্মটিকে আরও আধুনিক, গতিশীল ও নিরাপদ করতে নিরলসভাবে কাজ করে যাচ্ছি।',
                'order_num' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Rezwon Karim',
                'name_bn' => 'রেজওয়ান করিম',
                'designation' => 'Head of Marketing',
                'designation_bn' => 'হেড অব মার্কেটিং',
                'role_type' => 'marketing_head',
                'photo' => 'assets/images/board/marketing_head.jpg',
                'bio' => 'এফএমসিজি ও অ্যাগ্রো-মার্কেটিংয়ে ১৫ বছরের অভিজ্ঞতা। ডিলার নেটওয়ার্ক ও ব্র্যান্ডিং বিশেষজ্ঞ।',
                'administrative_role' => 'দেশজুড়ে এজেন্ট ও ডিলার নেটওয়ার্ক বিস্তার, ব্র্যান্ডিং এবং বাজারজাতকরণ স্ট্র্যাটেজি।',
                'phone' => '+880 1711-000004',
                'email' => 'marketing@ekrishisheba.com',
                'speech_title' => 'তৃণমূল থেকে জাতীয় পর্যায়ে বাজার সম্প্রসারণের মেলবন্ধন',
                'speech_message' => 'আমাদের বিপণন ব্যবস্থার মূল দর্শন হলো সরাসরি উৎপাদক ও ভোক্তার মধ্যে যোগসূত্র স্থাপন। ডিলার ও এজেন্ট নেটওয়ার্কের মাধ্যমে গুণগত মানসম্পন্ন বীজ, সার, খাদ্য এবং কৃষি সামগ্রী পৌঁছে দেওয়া হচ্ছে প্রতিটি ইউনিয়নে। একই সাথে আমাদের নিজস্ব প্রকল্পের উৎপাদিত অর্গানিক পণ্য পৌঁছাচ্ছে শহুরে পরিবারের রান্নাঘরে। এই মেলবন্ধনই আমাদের শক্তি।',
                'order_num' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Tanvir Ahmed Chowdhury, FCA',
                'name_bn' => 'তানভীর আহমেদ চৌধুরী, এফসিএ',
                'designation' => 'Head of Finance',
                'designation_bn' => 'হেড অব ফাইন্যান্স',
                'role_type' => 'finance_head',
                'photo' => 'assets/images/board/finance_head.jpg',
                'bio' => 'চার্টার্ড অ্যাকাউন্ট্যান্ট। করপোরেট ফাইন্যান্স, অডিট ট্রেইল ও ট্যাক্সেশন আইন বিশেষজ্ঞ।',
                'administrative_role' => 'আর্থিক স্বচ্ছতা, নিয়মিত অডিট, লভ্যাংশ বণ্টন নীতি ও রাজস্ব পরিকল্পনা নিয়ন্ত্রণ।',
                'phone' => '+880 1711-000005',
                'email' => 'finance@ekrishisheba.com',
                'speech_title' => 'আর্থিক সততা, স্বচ্ছ নিরীক্ষা ও টেকসই রিটার্ন নিশ্চিতকরণ',
                'speech_message' => 'আর্থিক শৃঙ্খলাই যেকোনো বিনিয়োগ প্রতিষ্ঠানের মূলভিত্তি। ই-কৃষি সেবাতে প্রতিটি টাকার হিসাব নির্ভুলভাবে সংরক্ষিত হয় সেন্ট্রাল ফাইন্যান্সিয়াল ম্যানেজমেন্টের মাধ্যমে। আমরা কোনো অবাস্তব বা কাল্পনিক মুনাফার প্রতিশ্রুতি দিই না; বরং প্রতিটি প্রকল্পের প্রকৃত ব্যবসায়িক আয়ের ভিত্তিতে সম্পূর্ণ আইনসম্মত ও নিরীক্ষিত উপায়ে লভ্যাংশ হিসাব ও বন্টন করি।',
                'order_num' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Barrister Mahmudur Rahman',
                'name_bn' => 'ব্যারিস্টার মাহমুদুর রহমান',
                'designation' => 'Legal Advisor & Director',
                'designation_bn' => 'আইনি উপদেষ্টা ও পরিচালক',
                'role_type' => 'director',
                'photo' => 'assets/images/board/legal_director.jpg',
                'bio' => 'সুপ্রিম কোর্টের আইনজীবী, করপোরেট চুক্তি ও রিয়েল এস্টেট আইন বিশেষজ্ঞ।',
                'administrative_role' => 'আইনি পরামর্শ, জমি ও চুক্তিপত্র যাচাই এবং বিধিবদ্ধ কমপ্লায়েন্স নিশ্চিতকরণ।',
                'phone' => '+880 1711-000006',
                'email' => 'legal@ekrishisheba.com',
                'speech_title' => '',
                'speech_message' => null,
                'order_num' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($boardMembers as $bm) {
            DB::table('board_members')->insert(array_merge($bm, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 3. Corporate Profile CMS Content
        DB::table('corporate_profiles')->truncate();
        $profiles = [
            [
                'section_key' => 'history',
                'title_bn' => 'প্রতিষ্ঠানের ইতিহাস ও পটভূমি',
                'title_en' => 'History & Background',
                'content_bn' => 'ই-কৃষি সেবা বাংলাদেশের কৃষি বিপ্লব ও প্রান্তিক মানুষের অর্থনৈতিক মুক্তির স্বপ্ন নিয়ে প্রতিষ্ঠিত একটি যুগান্তকারী সমন্বিত প্রতিষ্ঠান। গ্রামীণ অর্থনীতির পুনরুজ্জীবন এবং আধুনিক প্রযুক্তিনির্ভর কৃষি খামারের অভাব দূরীকরণে একদল দূরদর্শী উদ্যোক্তা ও কৃষিবিজ্ঞানীর যৌথ উদ্যোগে এর সূচনা হয়। শুরুতে ক্ষুদ্র আকারে কৃষি পরামর্শ ও জৈব সার বিতরণ দিয়ে কার্যক্রম শুরু হলেও সময়ের পরিক্রমায় এটি বহুমুখী প্রজেক্ট, মেম্বারশিপ নেটওয়ার্ক, প্রপার্টি ও সমবায়ভিত্তিক যৌথ বিনিয়োগের এক সুদৃঢ় করপোরেট মহীরুহে পরিণত হয়েছে।',
                'content_en' => 'E-Krishi Sheba was established with the visionary goal of empowering rural farmers and connecting urban investors to high-yield agricultural and real estate enterprises.',
                'extra_points' => json_encode([
                    ['year' => '২০১৯', 'title' => 'যাত্রার সূচনা', 'desc' => 'কৃষি পরামর্শ ও প্রান্তিক কৃষক সেবার সূচনা।'],
                    ['year' => '২০২১', 'title' => 'সমন্বিত মডেল', 'desc' => 'পশুপালন ও ডেইরি খামার প্রকল্পের আনুষ্ঠানিক যাত্রা।'],
                    ['year' => '২০২৩', 'title' => 'ডিজিটাল রূপান্তর', 'desc' => 'অনলাইন মেম্বারশিপ কার্ড ও ই-কমার্স সেবা চালু।'],
                    ['year' => '২০২৬', 'title' => 'কেন্দ্রীয় করপোরেট প্ল্যাটফর্ম', 'desc' => 'শেয়ারহোল্ডার, প্রপার্টি কার্ড ও কেন্দ্রীয় অডিট ব্যবস্থার বিস্তার।'],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'section_key' => 'vision_mission',
                'title_bn' => 'ভিশন (Vision) ও মিশন (Mission)',
                'title_en' => 'Vision & Mission',
                'content_bn' => 'ভিশন (Vision): প্রযুক্তিনির্ভর কৃষি, নিরাপদ খাদ্য উৎপাদন এবং টেকসই সমবায়ভিত্তিক বিনিয়োগে বাংলাদেশের শীর্ষ নির্ভরযোগ্য ও দৃষ্টান্তমূলক করপোরেট প্রতিষ্ঠান হিসেবে আত্মপ্রকাশ করা।\n\nমিশন (Mission): প্রতিটি কৃষককে আধুনিক প্রযুক্তি ও ন্যায্য বাজারমূল্যের আওতায় আনা, বিনিয়োগকারীদের জন্য সম্পূর্ণ স্বচ্ছ, জবাবদিহিতামূলক ও হালাল আয়ের প্ল্যাটফর্ম সৃষ্টি করা এবং দেশের খাদ্য নিরাপত্তায় সুদৃঢ় ভূমিকা রাখা।',
                'content_en' => 'Our vision is to be the most trusted sustainable agro-investment and corporate ecosystem in Bangladesh.',
                'extra_points' => json_encode([
                    'কৃষক ও ভোক্তার সরাসরি মেলবন্ধন গড়ে তোলা',
                    'আধুনিক আইওটি প্রযুক্তি ও গ্রিনহাউস চাষাবাদ সম্প্রসারণ',
                    'বিনিয়োগকারীদের জন্য সম্পূর্ণ নিরীক্ষিত ও আইনসম্মত আর্থিক কাঠামো',
                    'নিরাপদ ও পুষ্টিকর খাদ্য সবার দোরগোড়ায় পৌঁছে দেওয়া',
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'section_key' => 'goals_objectives',
                'title_bn' => 'লক্ষ্য, উদ্দেশ্য ও মূলনীতি',
                'title_en' => 'Goals, Objectives & Core Principles',
                'content_bn' => 'আমাদের প্রতিটি কার্যক্রম পরিচালিত হয় সততা, পেশাদারিত্ব এবং সামাজিক দায়িত্বশীলতার সমন্বয়ে। লাভ-লোকসান বণ্টনের প্রতিটি পদক্ষেপে আমরা শরীয়াহসম্মত নীতিমালা ও স্বচ্ছ হিসাবব্যবস্থা অনুসরণ করি।',
                'content_en' => 'Our core values revolve around integrity, transparent double-entry bookkeeping, and social impact.',
                'extra_points' => json_encode([
                    ['principle' => '১০০% আর্থিক স্বচ্ছতা', 'desc' => 'প্রতিটি লেনদেনের ডিজিটাল অডিট ট্রেইল ও ভাউচারিং।'],
                    ['principle' => 'শ্রমিক ও কৃষকের মর্যাদা', 'desc' => 'ন্যায্য পারিশ্রমিক ও অংশীদারিত্ব নিশ্চিতকরণ।'],
                    ['principle' => 'পরিবেশবান্ধব খামার', 'desc' => 'রাসায়নিকমুক্ত ও পরিবেশসম্মত টেকসই উদ্যোগ।'],
                    ['principle' => 'গ্রাহকবান্ধব সেবা', 'desc' => '২৪/৭ ডিজিটাল ড্যাশবোর্ড ও দ্রুততম সাপোর্ট।'],
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'section_key' => 'future_plans',
                'title_bn' => 'প্রতিষ্ঠানের ভবিষ্যৎ পরিকল্পনা',
                'title_en' => 'Future Expansion Plans',
                'content_bn' => 'আগামী পাঁচ বছরে বাংলাদেশের ৬৪টি জেলায় আধুনিক অ্যাগ্রো-হাব স্থাপন, আন্তর্জাতিক মানে সবজি ও ফলমূল রপ্তানি এবং নিজস্ব খাদ্য প্রক্রিয়াজাতকরণ কারখানা স্থাপনের মহাপরিকল্পনা গৃহীত হয়েছে।',
                'content_en' => 'Strategic plans include 64 district agro-hubs, export-oriented processing facilities, and expanding green real estate.',
                'extra_points' => json_encode([
                    'আন্তর্জাতিক বাজারে দেশীয় অর্গানিক ফসল রপ্তানি প্রকল্প',
                    'সৌরবিদ্যুৎ চালিত স্বয়ংক্রিয় গ্রিনহাউস পল্লী নির্মাণ',
                    '১০০টি আধুনিক খামারবাড়ি ও রিসোর্ট সমবায় স্থাপন',
                    'মোবাইল অ্যাপের মাধ্যমে কিউআর কোড ভিত্তিক ট্রেসেবিলিটি',
                ], JSON_UNESCAPED_UNICODE),
            ],
            [
                'section_key' => 'achievements',
                'title_bn' => 'অর্জন ও সাফল্যের বিবরণ',
                'title_en' => 'Achievements & Milestones',
                'content_bn' => 'বিগত কয়েক বছরে ই-কৃষি সেবা হাজারো পরিবারে আর্থিক সচ্ছলতা ও কর্মসংস্থান তৈরি করতে পেরেছে। একাধিক সফল প্রকল্প বাস্তবায়ন আমাদের অন্যতম প্রধান অর্জন।',
                'content_en' => 'Proud milestones achieved through community trust and rigorous execution.',
                'extra_points' => json_encode([
                    ['stat' => '৫,০০০+', 'label' => 'সক্রিয় সদস্য ও কৃষক'],
                    ['stat' => '১৫+', 'label' => 'সফলভাবে বাস্তবায়িত মেগা প্রকল্প'],
                    ['stat' => '৯৮.৫%', 'label' => 'সন্তুষ্ট বিনিয়োগকারী ও শেয়ারহোল্ডার'],
                    ['stat' => '১০০%', 'label' => 'নিয়মিত লভ্যাংশ ও রিটার্ন বিতরণ রেকর্ড'],
                ], JSON_UNESCAPED_UNICODE),
            ],
        ];

        foreach ($profiles as $p) {
            DB::table('corporate_profiles')->insert(array_merge($p, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 4. 10 Investment Sectors (বিনিয়োগ খাতসমূহ)
        DB::table('investment_sectors')->truncate();
        $sectors = [
            [
                'sector_no' => '০১',
                'name_bn' => 'কৃষি ও ফসল উৎপাদন প্রকল্প',
                'name_en' => 'Agriculture & Crop Cultivation Project',
                'investment_range' => '২–১০ লাখ টাকা',
                'min_amount' => 200000,
                'max_amount' => 1000000,
                'duration' => 'কর্তৃপক্ষ নির্ধারিত',
                'icon' => 'fa fa-leaf',
                'description' => 'উচ্চফলনশীল ধান, গম, ডাল ও তেলবীজ আবাদ। বৈজ্ঞানিক পদ্ধতিতে সেচ ও সার ব্যবস্থাপনা।',
                'profit_loss_policy' => 'বাস্তব ফসল কর্তন ও বাজার বিক্রয়ের পর প্রকৃত লাভ-লোকসান অনুপাতে বণ্টনযোগ্য।',
                'order_num' => 1,
            ],
            [
                'sector_no' => '০২',
                'name_bn' => 'মাছ চাষ প্রকল্প',
                'name_en' => 'Aquaculture & Fisheries Project',
                'investment_range' => '২–১০ লাখ টাকা',
                'min_amount' => 200000,
                'max_amount' => 1000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-tint',
                'description' => 'বায়োফ্লক, আরএএস এবং আধুনিক জলাশয়ে কার্প, তেলাপিয়া, পাঙ্গাশ ও শিং মাছের নিবিড় চাষ।',
                'profit_loss_policy' => 'মাছের গ্রোথ সাইকেল সমাপ্তি ও বাজারজাতকরণের পর প্রদেয় লভ্যাংশ।',
                'order_num' => 2,
            ],
            [
                'sector_no' => '০৩',
                'name_bn' => 'পশুপালন ও খামার প্রকল্প',
                'name_en' => 'Livestock & Dairy Farm Project',
                'investment_range' => '২–১০ লাখ টাকা',
                'min_amount' => 200000,
                'max_amount' => 1000000,
                'duration' => '১০০ দিন / নির্ধারিত',
                'icon' => 'fa fa-paw',
                'description' => 'গরু মোটাতাজাকরণ, ডেইরি দুধ উৎপাদন ও ছাগল খামার। কোরবানি ও দৈনন্দিন বাজার লক্ষ্য।',
                'profit_loss_policy' => '১০০ দিনের মোটাতাজাকরণ সাইকেল অথবা বাৎসরিক দুধ বিক্রয় আয়ের নিরীক্ষিত বণ্টন।',
                'order_num' => 3,
            ],
            [
                'sector_no' => '০৪',
                'name_bn' => 'গ্রিনহাউসে সবজি ও ফলমূল চাষ প্রকল্প',
                'name_en' => 'Greenhouse Vegetable & Fruit Farming',
                'investment_range' => '৫–৫০ লাখ টাকা',
                'min_amount' => 500000,
                'max_amount' => 5000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-tree',
                'description' => 'নিয়ন্ত্রিত তাপমাত্রায় ক্যাপসিকাম, চেরি টমেটো, রঙিন ফুলকপি ও স্ট্রবেরি আবাদ।',
                'profit_loss_policy' => 'সুপারশপ ও রপ্তানি বিক্রয় মূল্যের ওপর ভিত্তি করে ত্রৈমাসিক লভ্যাংশ হিসাব।',
                'order_num' => 4,
            ],
            [
                'sector_no' => '০৫',
                'name_bn' => 'আধুনিক খামারবাড়ি প্রকল্প',
                'name_en' => 'Modern Farmhouse Development Project',
                'investment_range' => '৫ লাখ–১ কোটি টাকা',
                'min_amount' => 500000,
                'max_amount' => 10000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-home',
                'description' => 'প্রাকৃতিক পরিবেশে আধুনিক সুবিধাসম্পন্ন কটেজ, ফলজ বাগান ও পারিবারিক অবকাশ কেন্দ্র নির্মাণ।',
                'profit_loss_policy' => 'কটেজ লিজ, এগ্রো-ট্যুরিজম ভিজিট ও ফলজ উৎপাদনের দীর্ঘমেয়াদি অংশীদারিত্ব।',
                'order_num' => 5,
            ],
            [
                'sector_no' => '০৬',
                'name_bn' => 'হোটেল ও রিসোর্ট প্রকল্প',
                'name_en' => 'Eco Hotel & Resort Project',
                'investment_range' => '৫ লাখ–১ কোটি টাকা',
                'min_amount' => 500000,
                'max_amount' => 10000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-bed',
                'description' => 'পর্যটন সম্ভাবনাময় এলাকায় পরিবেশবান্ধব রিসোর্ট, সুইমিংপুল ও অর্গানিক রেস্তোরাঁ স্থাপন।',
                'profit_loss_policy' => 'রিসোর্ট বুকিং রাজস্ব ও ফুড সেলস আয় থেকে বাৎসরিক লভ্যাংশ ঘোষণা।',
                'order_num' => 6,
            ],
            [
                'sector_no' => '০৭',
                'name_bn' => 'ল্যান্ড ও প্রপার্টি প্রকল্প',
                'name_en' => 'Land & Commercial Property Project',
                'investment_range' => '৫ লাখ–১ কোটি টাকা',
                'min_amount' => 500000,
                'max_amount' => 10000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-map',
                'description' => 'কৌশলগত মহাসড়ক সংলগ্ন জমি ক্রয়, উন্নয়ন, সীমানা প্রাচীর ও সাব-প্লটিং বিক্রয়।',
                'profit_loss_policy' => 'জমি বিক্রয় ও মূলধনী মূল্যবৃদ্ধির ওপর ভিত্তি করে চুক্তিভিত্তিক রিটার্ন।',
                'order_num' => 7,
            ],
            [
                'sector_no' => '০৮',
                'name_bn' => 'ফ্ল্যাট নির্মাণ প্রকল্প',
                'name_en' => 'Apartment & Flat Construction Project',
                'investment_range' => '৫ লাখ–১ কোটি টাকা',
                'min_amount' => 500000,
                'max_amount' => 10000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-building',
                'description' => 'শহরাঞ্চলে আধুনিক বহুতল আবাসিক ভবন নির্মাণ ও যৌথ মালিকানায় ফ্ল্যাট বরাদ্দ।',
                'profit_loss_policy' => 'ফ্ল্যাট বুকিং ও কিস্তির বিপরীতে নির্মাণ ব্যয়ের আনুপাতিক শেয়ার মুনাফা।',
                'order_num' => 8,
            ],
            [
                'sector_no' => '০৯',
                'name_bn' => 'আমদানি ও রপ্তানি প্রকল্প',
                'name_en' => 'Agro Import & Export Project',
                'investment_range' => '৫ লাখ–১ কোটি টাকা',
                'min_amount' => 500000,
                'max_amount' => 10000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-ship',
                'description' => 'কৃষিযন্ত্রাংশ, উন্নত বীজ আমদানি এবং প্রক্রিয়াজাত মসলা ও তাজা ফলমূল বহির্বিশ্বে রপ্তানি।',
                'profit_loss_policy' => 'শিপমেন্ট বা এলসি সম্পন্নকরণ সাপেক্ষে নিট মুনাফা বণ্টন।',
                'order_num' => 9,
            ],
            [
                'sector_no' => '১০',
                'name_bn' => 'অগ্রাধিকারভিত্তিক বিনিয়োগ খাত',
                'name_en' => 'Priority Strategic Investment Sector',
                'investment_range' => '৫–৫০ লাখ টাকা',
                'min_amount' => 500000,
                'max_amount' => 5000000,
                'duration' => 'নির্ধারিত',
                'icon' => 'fa fa-star',
                'description' => 'জরুরি মৌসুমি শস্য সংরক্ষণ, কোল্ড স্টোরেজ রিজার্ভ এবং হাই-ডিমান্ড বাজার পণ্য।',
                'profit_loss_policy' => 'স্বল্পমেয়াদি দ্রুত চক্রাকার লেনদেনের লাভ বণ্টন নীতিমালা।',
                'order_num' => 10,
            ],
        ];

        foreach ($sectors as $s) {
            DB::table('investment_sectors')->insert(array_merge($s, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 5. Sample Investment Projects under Sectors
        DB::table('investment_projects')->truncate();
        $sampleProjects = [
            [
                'sector_id' => 1,
                'project_name' => 'মানিকগঞ্জ সুগন্ধি বাসমতী ও কালোজিরা ধান প্রকল্প',
                'project_id_code' => 'PRJ-AGR-001',
                'target_budget' => 5000000,
                'min_investment' => 200000,
                'max_investment' => 1000000,
                'total_raised' => 3200000,
                'investors_count' => 6,
                'duration' => '৬ মাস (বোরো মৌসুম)',
                'progress_percentage' => 75,
                'description' => 'মানিকগঞ্জের ধলেশ্বরী নদী অববাহিকায় ১০০ বিঘা জমিতে প্রিমিয়াম কোয়ালিটি সুগন্ধি ধানের আবাদ।',
                'profit_policy' => 'ধান মাড়াই ও চাল বিক্রয় পরবর্তী মোট নিট মুনাফার ৬০% বিনিয়োগকারীদের মধ্যে শেয়ার আনুপাতে বণ্টন।',
                'progress_report' => 'চারা রোপণ ও নিড়ানি সম্পন্ন হয়েছে। সেচ ও জৈব বালাইনাশক প্রয়োগ চলমান। ফলন আশানুরূপ।',
                'status' => 'running',
            ],
            [
                'sector_id' => 3,
                'project_name' => 'সাভার ডেইরি ও কোরবানি খামার ফেস-২',
                'project_id_code' => 'PRJ-LVS-002',
                'target_budget' => 8000000,
                'min_investment' => 200000,
                'max_investment' => 1000000,
                'total_raised' => 6400000,
                'investors_count' => 11,
                'duration' => '১০০ দিন (নির্ধারিত সাইকেল)',
                'progress_percentage' => 85,
                'description' => '৫০টি উন্নত জাতের ষাঁড় ও ৩০টি শাহীওয়াল গাভীর সমন্বিত পশুপালন কেন্দ্র। শতভাগ দানাদার ও সবুজ ঘাস নির্ভর খাদ্য।',
                'profit_policy' => '১০০ দিনের ওজন পরিমাপ সাপেক্ষে বিক্রিত আয়ের অডিটেড লাভ বণ্টন।',
                'progress_report' => 'পশুদের গড় ওজন দৈনিক ১.২ কেজি বৃদ্ধি পাচ্ছে। নিয়মিত ভ্যাকসিনেশন ও ভেটেরিনারি চেকআপ চলমান।',
                'status' => 'running',
            ],
            [
                'sector_id' => 4,
                'project_name' => 'শ্রীপুর ক্লাইমেট-কন্ট্রোল্ড গ্রিনহাউস সবজি হাব',
                'project_id_code' => 'PRJ-GRN-003',
                'target_budget' => 25000000,
                'min_investment' => 500000,
                'max_investment' => 5000000,
                'total_raised' => 18000000,
                'investors_count' => 8,
                'duration' => '১ বৎসর (নবায়নযোগ্য)',
                'progress_percentage' => 60,
                'description' => 'ইসরায়েলি প্রযুক্তিতে তৈরি আধুনিক গ্রিনহাউসে ড্রিপ ইরিগেশনের মাধ্যমে হাইড্রোফোনিক ক্যাপসিকাম ও লেটুস চাষ।',
                'profit_policy' => 'মাসিক হার্ভেস্ট পরবর্তী সুপারশপ ডেলিভারি আয়ের নিরীক্ষিত লাভ বণ্টন।',
                'progress_report' => 'গ্রিনহাউস অবকাঠামো ও সেন্সর স্থাপন সম্পন্ন। প্রথম ব্যাচের চারা ফলন দেওয়া শুরু করেছে।',
                'status' => 'open',
            ],
            [
                'sector_id' => 7,
                'project_name' => 'পূর্বাচল গ্রিন ভিউ ল্যান্ড শেয়ার প্রকল্প',
                'project_id_code' => 'PRJ-LND-004',
                'target_budget' => 50000000,
                'min_investment' => 500000,
                'max_investment' => 10000000,
                'total_raised' => 35000000,
                'investors_count' => 14,
                'duration' => '২ বৎসর',
                'progress_percentage' => 45,
                'description' => '৩০০ ফুট এক্সপ্রেসওয়ে সংলগ্ন ৫০ বিঘা জমি উন্নয়ন ও রেসিডেনশিয়াল প্লট বিক্রয় কার্যক্রম।',
                'profit_policy' => 'জমি বিক্রয় ও মূলধনী প্রবৃদ্ধির ওপর ভিত্তি করে রেজিস্ট্রিকৃত চুক্তি মোতাবেক লাভ বণ্টন।',
                'progress_report' => 'ভূমি ভরাট কাজ ৭০% সম্পন্ন। সীমানা প্রাচীর ও অভ্যন্তরীণ রাস্তার কাজ চলছে।',
                'status' => 'open',
            ],
        ];

        foreach ($sampleProjects as $sp) {
            DB::table('investment_projects')->insert(array_merge($sp, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 6. Corporate Activities & Project Implementation
        DB::table('corporate_activities')->truncate();
        $activities = [
            [
                'title' => 'স্মার্ট ড্রিপ ইরিগেশন ও ডেমো খামার প্রকল্প',
                'project_code' => 'ACT-CMP-001',
                'category' => 'completed',
                'sector_type' => 'agriculture',
                'location' => 'ধামরাই, ঢাকা',
                'progress_percentage' => 100,
                'budget' => 3500000,
                'description' => '২০ একর জমিতে স্বয়ংক্রিয় সেচ ব্যবস্থার সফল পাইলট বাস্তবায়ন। কৃষকদের বিনামূল্যে প্রশিক্ষণ প্রদান।',
                'field_report' => 'প্রকল্পটি সফলভাবে নির্ধারিত সময়ের আগেই সম্পন্ন হয়েছে। পানি ও বিদ্যুতের অপচয় ৪০% হ্রাস পেয়েছে।',
                'status' => 'completed',
                'is_featured' => true,
            ],
            [
                'title' => 'সমন্বিত নিরাপদ দুধ ও দুগ্ধজাত খাদ্য প্রক্রিয়াজাতকরণ',
                'project_code' => 'ACT-ONG-002',
                'category' => 'ongoing',
                'sector_type' => 'dairy',
                'location' => 'সিরাজগঞ্জ ও পাবনা অঞ্চল',
                'progress_percentage' => 65,
                'budget' => 12000000,
                'description' => '৫টি ইউনিয়নে সরাসরি কৃষকদের নিকট থেকে খাঁটি দুধ সংগ্রহ ও পাস্তুরিত করে প্যাকেটজাতকরণ।',
                'field_report' => 'বর্তমানে দৈনিক ২,৫০০ লিটার দুধ সংগ্রহ কেন্দ্র চালু হয়েছে। কোল্ড চেইন ভ্যান সংযুক্ত করা হয়েছে।',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'title' => 'কক্সবাজার অর্গানিক এগ্রো-রিসোর্ট ও সুস্থ বিনোদন পার্ক',
                'project_code' => 'ACT-PLN-003',
                'category' => 'planned',
                'sector_type' => 'resort',
                'location' => 'ইনানী, কক্সবাজার',
                'progress_percentage' => 15,
                'budget' => 85000000,
                'description' => 'পাহাড় ও সমুদ্রের নৈসর্গিক পরিবেশে ৫০ বিঘা পাহাড়ি জমিতে কটেজ, ফলের বাগান ও ড্রাগন ফলের সমবায় রিসোর্ট।',
                'field_report' => 'ভূমি রেজিস্ট্রেশন ও পরিবেশগত সমীক্ষা সম্পন্ন হয়েছে। স্থাপত্য নকশা চূড়ান্তকরণের কাজ চলছে।',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
        ];

        foreach ($activities as $act) {
            DB::table('corporate_activities')->insert(array_merge($act, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 7. Share Settings (Module 4)
        DB::table('share_settings')->truncate();
        DB::table('share_settings')->insert([
            'total_shares' => 100,
            'price_per_share' => 5000.00,
            'total_share_capital' => 500000.00,
            'office_expense_per_share' => 3000.00,
            'allocated_shares' => 28,
            'registration_fee' => 2000.00,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 8. Notice Board
        DB::table('notices')->truncate();
        $notices = [
            [
                'title' => '২০২৬ সালের ত্রৈমাসিক লভ্যাংশ বণ্টন ও অডিট প্রতিবেদন প্রকাশ',
                'notice_type' => 'announcement',
                'content' => 'সম্মানিত শেয়ারহোল্ডার ও বিনিয়োগকারী সদস্যবৃন্দের অবগতির জন্য জানানো যাচ্ছে যে, ২০২৬ সালের ৩য় কোয়ার্টারের প্রকল্পভিত্তিক আর্থিক অডিট সম্পন্ন হয়েছে। নির্ধারিত নীতিমালাভিত্তিক লভ্যাংশ সদস্যদের ব্যক্তিগত ওয়ালেটে যুক্ত করা হয়েছে। সদস্য ড্যাশবোর্ড থেকে পূর্ণাঙ্গ স্টেটমেন্ট ডাউনলোড করা যাবে।',
                'is_pinned' => true,
                'is_active' => true,
                'publish_date' => $now->toDateString(),
            ],
            [
                'title' => 'জরুরি নিরাপত্তা সতর্কতা: আপনার পিন ও পাসওয়ার্ড কাউকে শেয়ার করবেন না',
                'notice_type' => 'urgent',
                'content' => 'ই-কৃষি সেবার কোনো কর্মকর্তা বা প্রতিনিধি কখনোই আপনার একাউন্টের পাসওয়ার্ড, ওটিপি বা ট্রানজাকশন পিন জানতে চাইবেন না। কোনো প্রতারণামূলক কল বা মেসেজের ফাঁদে পা দেবেন না। যেকোনো প্রয়োজনে অফিসিয়াল হেল্পলাইনে যোগাযোগ করুন।',
                'is_pinned' => true,
                'is_active' => true,
                'publish_date' => $now->subDays(2)->toDateString(),
            ],
            [
                'title' => 'গ্রিনহাউস ও ফলমূল চাষ প্রকল্পে নতুন বিনিয়োগ স্লট উন্মুক্ত',
                'notice_type' => 'project_update',
                'content' => 'শ্রীপুর গ্রিনহাউস ফেস-২ এর সম্প্রসারণ কাজের জন্য নতুন বিনিয়োগ আবেদন গ্রহণ শুরু হয়েছে। প্রপার্টি কার্ডধারী সদস্যগণ সর্বনিম্ন ৫ লাখ টাকা থেকে এই অগ্রাধিকারমূলক প্রকল্পে অংশ নিতে পারবেন।',
                'is_pinned' => false,
                'is_active' => true,
                'publish_date' => $now->subDays(5)->toDateString(),
            ],
        ];

        foreach ($notices as $n) {
            DB::table('notices')->insert(array_merge($n, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 9. Central Financial Ledger Initial Records
        DB::table('central_finances')->truncate();
        $finances = [
            [
                'transaction_code' => 'CF-INC-1001',
                'entry_type' => 'income',
                'category' => 'share_capital',
                'amount' => 140000.00,
                'entry_date' => $now->subDays(10)->toDateString(),
                'payment_method' => 'bank_transfer',
                'description' => 'প্রাথমিক ২৮টি শেয়ার বরাদ্দ বাবদ সংগৃহীত মূলধন',
            ],
            [
                'transaction_code' => 'CF-INC-1002',
                'entry_type' => 'income',
                'category' => 'membership_fee',
                'amount' => 84000.00,
                'entry_date' => $now->subDays(8)->toDateString(),
                'payment_method' => 'bkash',
                'description' => 'SME, Gold ও Property কার্ড সদস্য নিবন্ধন ফি কালেকশন',
            ],
            [
                'transaction_code' => 'CF-INC-1003',
                'entry_type' => 'income',
                'category' => 'investment_collection',
                'amount' => 6400000.00,
                'entry_date' => $now->subDays(5)->toDateString(),
                'payment_method' => 'bank_transfer',
                'description' => 'সাভার ডেইরি খামার ও কৃষি প্রকল্প বিনিয়োগ সংগ্রহ',
            ],
            [
                'transaction_code' => 'CF-EXP-2001',
                'entry_type' => 'expense',
                'category' => 'employee_salary',
                'amount' => 385000.00,
                'entry_date' => $now->subDays(4)->toDateString(),
                'payment_method' => 'bank_transfer',
                'description' => 'কর্মকর্তা-কর্মচারীদের মাসিক বেতন ও ভাতা প্রদান',
            ],
            [
                'transaction_code' => 'CF-EXP-2002',
                'entry_type' => 'expense',
                'category' => 'project_expense',
                'amount' => 1250000.00,
                'entry_date' => $now->subDays(3)->toDateString(),
                'payment_method' => 'bank_transfer',
                'description' => 'মানিকগঞ্জ বাসমতী ধান প্রকল্পের বীজ, সার ও সেচ সরঞ্জাম ক্রয়',
            ],
            [
                'transaction_code' => 'CF-EXP-2003',
                'entry_type' => 'expense',
                'category' => 'office_admin_expense',
                'amount' => 75000.00,
                'entry_date' => $now->subDays(1)->toDateString(),
                'payment_method' => 'cash',
                'description' => 'অফিস ইউটিলিটি, ইন্টারনেট ও প্রশাসনিক ব্যবস্থাপনা খরচ',
            ],
        ];

        foreach ($finances as $f) {
            DB::table('central_finances')->insert(array_merge($f, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 10. Update Card Benefits Table with Full Specifications
        DB::table('card_benefits')->truncate();
        $cardBenefits = [
            [
                'card_name' => 'SME Card / SME Account (সাধারণ)',
                'card_type' => 'sme',
                'badge_text' => 'জনপ্রিয় সাধারণ কার্ড',
                'card_number_sample' => 'SME-2026-89012',
                'validity' => 'আজীবন / নবায়নযোগ্য',
                'card_fee' => '৩০০/- টাকা (বিকল্প প্রাথমিক ফি: ৫০/- টাকা)',
                'investment_limit' => 'পণ্য ক্রয় ও খুচরা অর্ডার',
                'monthly_profit' => 'লেনদেন ছাড় ও ক্যাশব্যাক',
                'withdrawal_notice' => 'ইনস্ট্যান্ট ওয়ালেট উইথড্র',
                'image' => 'assets/images/cards/sme_card.png',
                'short_description' => 'ক্ষুদ্র ও মাঝারি কৃষক, ক্রেতা এবং সাধারণ সদস্যদের জন্য সাশ্রয়ী ডিজিটাল কার্ড।',
                'facilities' => json_encode([
                    'ডিজিটাল SME মেম্বারশিপ কার্ড ও ইউনিক Customer ID',
                    'Account Balance Tracking ও ওয়ালেট সুবিধা',
                    'অ্যাকাউন্ট লেনদেনের রিয়েল-টাইম হিস্ট্রি',
                    'Product Order ও বিশেষ ছাড়ের সুবিধা',
                    'অর্ডার ও পেমেন্ট স্ট্যাটাস ট্র্যাকিং',
                    'সদস্যের ব্যক্তিগত কন্ট্রোল ড্যাশবোর্ড',
                ], JSON_UNESCAPED_UNICODE),
                'card_color_theme' => '#0d9488',
                'action_button_text' => 'SME কার্ডের জন্য আবেদন করুন',
                'action_button_url' => '/apply-card?type=sme',
                'status' => 1,
                'order_num' => 1,
            ],
            [
                'card_name' => 'SME Gold Card Account (প্রিমিয়াম)',
                'card_type' => 'gold',
                'badge_text' => 'গোল্ডেন প্রিমিয়াম সদস্য',
                'card_number_sample' => 'GLD-2026-55421',
                'validity' => 'আজীবন মেম্বারশিপ',
                'card_fee' => '১,০০০/- টাকা',
                'investment_limit' => 'বিনিয়োগ খাত ট্র্যাকিং ও অর্ডার',
                'monthly_profit' => 'প্রাধিকারমূলক সেবা ও ইনভেস্টমেন্ট ট্র্যাকিং',
                'withdrawal_notice' => '২৪ ঘণ্টার মধ্যে উত্তোলন সুবিধা',
                'image' => 'assets/images/cards/gold_card.png',
                'short_description' => 'উন্নত সেবা, বিনিয়োগ খাত পর্যবেক্ষণ এবং অগ্রাধিকারভিত্তিক অর্ডারের গোল্ড মেম্বারশিপ।',
                'facilities' => json_encode([
                    'ডিজিটাল লাক্সারি SME Gold Card ও ইউনিক Customer ID',
                    'উন্নত Account Balance Tracking ও লেনদেন বিবরণী',
                    'Investment Sector Tracking ও হিস্ট্রি',
                    'বিনিয়োগের সার্বিক তথ্য ও অগ্রগতির আপডেট',
                    'Product Order-এ সর্বোচ্চ অগ্রাধিকার ও ডিসকাউন্ট',
                    'ব্যক্তিগত গোল্ড অ্যাকাউন্ট ড্যাশবোর্ড',
                ], JSON_UNESCAPED_UNICODE),
                'card_color_theme' => '#d97706',
                'action_button_text' => 'SME Gold কার্ডের জন্য আবেদন করুন',
                'action_button_url' => '/apply-card?type=gold',
                'status' => 1,
                'order_num' => 2,
            ],
            [
                'card_name' => 'Property Card Account (বিনিয়োগকারী)',
                'card_type' => 'property',
                'badge_text' => 'রিয়েল এস্টেট ও প্রজেক্ট ইনভেস্টর',
                'card_number_sample' => 'PRP-2026-11093',
                'validity' => 'প্রকল্পভিত্তিক ও আজীবন',
                'card_fee' => '৩,০০০/- টাকা',
                'investment_limit' => '২ লাখ থেকে ১ কোটি টাকা পর্যন্ত',
                'monthly_profit' => 'প্রকল্পভিত্তিক লাভ-লোকসান নিরীক্ষিত রিটার্ন',
                'withdrawal_notice' => 'ফান্ড উত্তোলনের আবেদন ও দ্রুত প্রসেসিং',
                'image' => 'assets/images/cards/property_card.png',
                'short_description' => 'কৃষি, ডেইরি, গ্রিনহাউস ও রিয়েল এস্টেট প্রকল্পে সরাসরি বিনিয়োগ ও লভ্যাংশ ব্যবস্থাপনার কার্ড।',
                'facilities' => json_encode([
                    'ডিজিটাল Property Card ও ইউনিক Member Code',
                    'Capital & Investment Overview (মূলধন ও প্রকল্প হিসাব)',
                    '১০টি নির্ধারিত বিনিয়োগ খাতে সরাসরি অংশ নেওয়ার সুযোগ',
                    'Profit & Dividend Management (লভ্যাংশ হিসাব ও হিস্ট্রি)',
                    'Withdrawal Management (ফান্ড উত্তোলনের আবেদন ও অনুমোদন)',
                    'প্রকল্পের অগ্রগতি প্রতিবেদন, ডকুমেন্ট ও মাঠপর্যায়ের আপডেট',
                ], JSON_UNESCAPED_UNICODE),
                'card_color_theme' => '#1e3a8a',
                'action_button_text' => 'Property কার্ডের জন্য আবেদন করুন',
                'action_button_url' => '/apply-card?type=property',
                'status' => 1,
                'order_num' => 3,
            ],
            [
                'card_name' => 'Shareholder Profile (অংশীদারিত্ব ও শেয়ার মূলধন)',
                'card_type' => 'shareholder',
                'badge_text' => 'প্রতিষ্ঠানের শেয়ারহোল্ডার',
                'card_number_sample' => 'SH-2026-00042',
                'validity' => 'স্থায়ী শেয়ারহোল্ডার',
                'card_fee' => 'রেজিস্ট্রেশন ফি: ২,০০০/- টাকা (প্রতি শেয়ার ৫,০০০/- টাকা + অফিস খরচ ৩,০০০/- টাকা)',
                'investment_limit' => '১০০টি শেয়ারের সমন্বিত মূলধন কাঠামো',
                'monthly_profit' => 'বাৎসরিক নিরীক্ষিত ব্যবসায়িক নিট মুনাফা',
                'withdrawal_notice' => 'ঘোষিত লভ্যাংশ উত্তোলন ব্যবস্থা',
                'image' => 'assets/images/cards/shareholder_card.png',
                'short_description' => 'প্রতিষ্ঠানের ১০০টি মূল শেয়ারের অংশীদার হওয়ার সুবর্ণ সুযোগ।',
                'facilities' => json_encode([
                    'শেয়ারহোল্ডার ইউনিক ID ও অফিসিয়াল শেয়ার সার্টিফিকেট',
                    'মোট ১০০টি শেয়ারের বিপরীতে নির্দিষ্ট শেয়ার বরাদ্দ হিসাব',
                    'প্রতি শেয়ারের নির্ধারিত অফিস ব্যবস্থাপনা খরচ ও স্টেটমেন্ট',
                    'বাৎসরিক লাভ-লোকসান ও ঘোষিত লভ্যাংশ উত্তোলনের হিসাব',
                    'শেয়ারহোল্ডারভিত্তিক বিস্তারিত আর্থিক স্টেটমেন্ট ও ডাউনলোড',
                    'কমিউনিটি ফোরাম ও বার্ষিক সাধারণ সভায় ভোটিং অধিকার',
                ], JSON_UNESCAPED_UNICODE),
                'card_color_theme' => '#7c3aed',
                'action_button_text' => 'শেয়ারহোল্ডার হিসেবে যুক্ত হন',
                'action_button_url' => '/apply-card?type=shareholder',
                'status' => 1,
                'order_num' => 4,
            ],
            [
                'card_name' => 'Partnership Membership (ব্যবসায়িক অংশীদার)',
                'card_type' => 'partnership',
                'badge_text' => 'কৌশলগত প্রাতিষ্ঠানিক পার্টনার',
                'card_number_sample' => 'PRT-2026-00109',
                'validity' => 'চুক্তি অনুযায়ী মেয়াদি',
                'card_fee' => 'চুক্তিভিত্তিক মূলধন অবদান',
                'investment_limit' => 'ব্যবসায়িক চুক্তি সাপেক্ষে',
                'monthly_profit' => 'নির্ধারিত লাভ-লোকসান অনুপাত',
                'withdrawal_notice' => 'চুক্তির শর্তাবলী অনুযায়ী',
                'image' => 'assets/images/cards/partner_card.png',
                'short_description' => 'বড় ধরনের যৌথ উদ্যোগ, প্রজেক্ট ভেঞ্চার ও ডিস্ট্রিবিউশন অংশীদারিত্ব।',
                'facilities' => json_encode([
                    'ব্যবসায়িক পার্টনার প্রোফাইল ও ইউনিক Partner Code',
                    'চুক্তিপত্র, মেয়াদ ও আর্থিক অবদানের কেন্দ্রীয় ট্র্যাকিং',
                    'পার্টনারশিপ লাভ-লোকসানের নিরীক্ষিত হিসাব',
                    'কমিউনিটি ফোরাম ও ঊর্ধ্বতন কর্তৃপক্ষের সাথে সরাসরি আলোচনা',
                    'সমন্বিত সেন্ট্রাল ফাইন্যান্সিয়াল ড্যাশবোর্ডে অ্যাক্সেস',
                ], JSON_UNESCAPED_UNICODE),
                'card_color_theme' => '#059669',
                'action_button_text' => 'পার্টনারশিপের জন্য যোগাযোগ করুন',
                'action_button_url' => '/apply-card?type=partner',
                'status' => 1,
                'order_num' => 5,
            ],
        ];

        foreach ($cardBenefits as $cb) {
            DB::table('card_benefits')->insert(array_merge($cb, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }

        // 11. Create Sample Shareholder & Property Member Users if needed
        $demoUsers = [
            [
                'name' => 'আলহাজ্ব মো: কামরুল আহসান',
                'username' => 'shareholder_kamrul',
                'email' => 'kamrul@shareholder.com',
                'phone' => '01811000001',
                'customer_id' => 'SH-2026-00012',
                'role' => 'shareholder',
                'membership_card_type' => 'shareholder',
                'balance' => 45000.00,
                'nid_number' => '19842691234567890',
                'present_address' => 'বাড়ি ১২, রোড ৪, ধানমন্ডি, ঢাকা',
                'permanent_address' => 'গ্রাম: রূপসী, রূপগঞ্জ, নারায়ণগঞ্জ',
                'nominee_name' => 'বেগম ফাতেমা আহসান',
                'nominee_phone' => '01811000002',
                'nominee_relation' => 'স্ত্রী',
            ],
            [
                'name' => 'প্রকৌশলী সাজ্জাদ হোসেন',
                'username' => 'investor_sajjad',
                'email' => 'sajjad@investor.com',
                'phone' => '01811000003',
                'customer_id' => 'PRP-2026-00045',
                'role' => 'investor',
                'membership_card_type' => 'property',
                'balance' => 120000.00,
                'nid_number' => '19902699876543210',
                'present_address' => 'ফ্ল্যাট ৫বি, বনানী, ঢাকা',
                'permanent_address' => 'উপজেলা: কালিহাতী, টাঙ্গাইল',
                'nominee_name' => 'তানজিলা সাজ্জাদ',
                'nominee_phone' => '01811000004',
                'nominee_relation' => 'স্ত্রী',
            ],
            [
                'name' => 'মেসার্স ভাই ভাই এন্টারপ্রাইজ (এজেন্ট)',
                'username' => 'agent_bhaibhai',
                'email' => 'bhaibhai@agent.com',
                'phone' => '01811000005',
                'customer_id' => 'AGT-2026-00101',
                'role' => 'agent',
                'membership_card_type' => 'agent',
                'agent_shop_name' => 'ভাই ভাই এগ্রো ট্রেডার্স',
                'agent_type' => 'general_shop',
                'agent_area' => 'ধামরাই বাজার, ঢাকা',
                'dealer_id' => 'DLR-DHM-01',
                'balance' => 28500.00,
                'nid_number' => '19882695556667770',
                'present_address' => 'ধামরাই বাজার, ঢাকা',
                'permanent_address' => 'ধামরাই, ঢাকা',
            ],
        ];

        foreach ($demoUsers as $du) {
            $existing = DB::table('users')->where('username', $du['username'])->first();
            if (!$existing) {
                DB::table('users')->insert(array_merge($du, [
                    'password' => Hash::make('12345678'),
                    'is_active' => true,
                    'status' => 'active',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }

        // Add Shareholder Profile for demo shareholder
        $shUser = DB::table('users')->where('username', 'shareholder_kamrul')->first();
        if ($shUser) {
            DB::table('shareholder_profiles')->updateOrInsert(['user_id' => $shUser->id], [
                'shareholder_code' => $shUser->customer_id ?? 'SH-2026-00012',
                'shares_count' => 5,
                'total_investment' => 25000.00,
                'office_expense_paid' => 15000.00,
                'registration_fee_paid' => 2000.00,
                'total_dividend_earned' => 8500.00,
                'total_dividend_withdrawn' => 3500.00,
                'available_dividend_balance' => 5000.00,
                'nominee_name' => $shUser->nominee_name,
                'nominee_phone' => $shUser->nominee_phone,
                'nominee_relation' => $shUser->nominee_relation,
                'nid_number' => $shUser->nid_number,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Add Sample User Investment for demo investor
        $invUser = DB::table('users')->where('username', 'investor_sajjad')->first();
        if ($invUser) {
            $prj = DB::table('investment_projects')->first();
            if ($prj) {
                DB::table('user_investments')->updateOrInsert(['investment_code' => 'INV-2026-8801'], [
                    'user_id' => $invUser->id,
                    'project_id' => $prj->id,
                    'sector_id' => $prj->sector_id,
                    'amount' => 500000.00,
                    'return_earned' => 45000.00,
                    'paid_dividend' => 25000.00,
                    'pending_dividend' => 20000.00,
                    'investment_date' => $now->subMonths(3)->toDateString(),
                    'maturity_date' => $now->addMonths(9)->toDateString(),
                    'payment_method' => 'bank_transfer',
                    'transaction_id' => 'TXN-BANK-998822',
                    'status' => 'active',
                    'admin_notes' => 'বিনিয়োগ যাচাইকৃত ও অনুমোদিত। সাভার প্রজেক্টে বরাদ্দকৃত।',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
}
