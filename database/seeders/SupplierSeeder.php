<?php

namespace Database\Seeders;

use App\Models\SupplierPayment;
use App\Models\SupplierProfile;
use App\Models\SupplierSupply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create Supplier 1: M/S Rahim Traders
        $user1 = User::create([
            'name' => 'মো: রহিম উদ্দিন',
            'email' => 'rahimtraders@gmail.com',
            'username' => 'rahim_traders',
            'phone' => '01711223344',
            'password' => Hash::make('123456'),
            'role' => 'supplier',
        ]);

        SupplierProfile::create([
            'user_id' => $user1->id,
            'supplier_code' => 'SUP-0001',
            'company_name' => 'মেসার্স রহিম ট্রেডার্স',
            'district_thana' => 'নরসিংদী সদর, নরসিংদী',
            'address' => 'স্টেশন রোড, খাদ্য গুদাম সংলগ্ন',
            'opening_balance' => 50000.00,
            'opening_date' => '2026-08-01',
            'notes' => 'ধান ও চাল সরবরাহকারী প্রতিষ্ঠান',
        ]);

        // Supplies for Supplier 1
        $s1 = SupplierSupply::create([
            'supplier_id' => $user1->id,
            'invoice_no' => 'INV-2026-001',
            'product_name' => 'চাল',
            'category' => 'খাদ্যশস্য',
            'quantity' => 1.00,
            'unit' => 'Metric Ton (MT)',
            'rate' => 50000.00,
            'total_amount' => 50000.00,
            'supply_date' => '2026-08-02',
            'note' => 'মিনিকেট চাল ১ম গ্রেড',
            'status' => 'approved',
            'approved_at' => Carbon::now(),
        ]);

        $s2 = SupplierSupply::create([
            'supplier_id' => $user1->id,
            'invoice_no' => 'INV-2026-002',
            'product_name' => 'চিনি',
            'category' => 'খাদ্যপণ্য',
            'quantity' => 4.00,
            'unit' => 'Metric Ton (MT)',
            'rate' => 65000.00,
            'total_amount' => 260000.00,
            'supply_date' => '2026-08-05',
            'note' => 'সাদা দানাদার চিনি',
            'status' => 'approved',
            'approved_at' => Carbon::now(),
        ]);

        $s3 = SupplierSupply::create([
            'supplier_id' => $user1->id,
            'invoice_no' => 'INV-2026-003',
            'product_name' => 'ডাল',
            'category' => 'খাদ্যশস্য',
            'quantity' => 2.00,
            'unit' => 'Metric Ton (MT)',
            'rate' => 90000.00,
            'total_amount' => 180000.00,
            'supply_date' => '2026-08-08',
            'note' => 'দেশি মসুর ডাল',
            'status' => 'approved',
            'approved_at' => Carbon::now(),
        ]);

        // Pending supply for Supplier 1
        SupplierSupply::create([
            'supplier_id' => $user1->id,
            'invoice_no' => 'INV-2026-004',
            'product_name' => 'আটা',
            'category' => 'খাদ্যপণ্য',
            'quantity' => 5.00,
            'unit' => 'Bag',
            'rate' => 1500.00,
            'total_amount' => 7500.00,
            'supply_date' => '2026-08-15',
            'note' => 'সুপার ফাইন আটা বস্তা',
            'status' => 'pending',
        ]);

        // Payments for Supplier 1
        SupplierPayment::create([
            'supplier_id' => $user1->id,
            'payment_date' => '2026-08-06',
            'payment_method' => 'cash',
            'amount' => 100000.00,
            'note' => 'ক্যাশ অগ্রিম পরিশোধ',
        ]);

        SupplierPayment::create([
            'supplier_id' => $user1->id,
            'payment_date' => '2026-08-10',
            'payment_method' => 'bank',
            'amount' => 200000.00,
            'bank_name' => 'ইসলামী ব্যাংক বাংলাদেশ লিমিটেড',
            'account_name' => 'M/S Rahim Traders',
            'account_number' => '205012345678',
            'transaction_id' => 'IBBL-TXN-998877',
            'note' => 'ব্যাংক চেক মারফত আংশিক পেমেন্ট',
        ]);


        // 2. Create Supplier 2: M/S Bismillah Enterprise
        $user2 = User::create([
            'name' => 'মো: করিম হোসেন',
            'email' => 'bismillah.enterprise@gmail.com',
            'username' => 'bismillah_ent',
            'phone' => '01822334455',
            'password' => Hash::make('123456'),
            'role' => 'supplier',
        ]);

        SupplierProfile::create([
            'user_id' => $user2->id,
            'supplier_code' => 'SUP-0002',
            'company_name' => 'মেসার্স বিসমিল্লাহ এন্টারপ্রাইজ',
            'district_thana' => 'কালীগঞ্জ, গাজীপুর',
            'address' => 'বাজার রোড, কালীগঞ্জ',
            'opening_balance' => 0.00,
            'opening_date' => '2026-08-05',
            'notes' => 'সয়াবিন তেল ও মসলা সরবরাহকারী',
        ]);

        SupplierSupply::create([
            'supplier_id' => $user2->id,
            'invoice_no' => 'INV-2026-010',
            'product_name' => 'সয়াবিন তেল',
            'category' => 'ভোজ্য তেল',
            'quantity' => 500.00,
            'unit' => 'Litre',
            'rate' => 160.00,
            'total_amount' => 80000.00,
            'supply_date' => '2026-08-12',
            'note' => 'রূপচাঁদা সয়াবিন তেল',
            'status' => 'approved',
            'approved_at' => Carbon::now(),
        ]);

        SupplierPayment::create([
            'supplier_id' => $user2->id,
            'payment_date' => '2026-08-14',
            'payment_method' => 'cash',
            'amount' => 50000.00,
            'note' => 'ক্যাশ আংশিক পরিশোধ',
        ]);
    }
}
