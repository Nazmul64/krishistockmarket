<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Shop\ShopProduct;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\Warehouse;
use App\Models\SuperShop\BranchUser;
use App\Models\SuperShop\EmployeeProfile;
use App\Models\SuperShop\EmployeeAttendance;
use App\Models\SuperShop\BranchInventory;
use App\Models\SuperShop\StockMovement;
use App\Models\SuperShop\InventoryBatch;
use App\Models\SuperShop\Supplier;
use App\Models\SuperShop\PurchaseOrder;
use App\Models\SuperShop\PurchaseOrderItem;
use App\Models\SuperShop\GoodsReceiptNote;
use App\Models\SuperShop\GoodsReceiptNoteItem;
use App\Models\SuperShop\StockTransfer;
use App\Models\SuperShop\StockTransferItem;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\CashierShift;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\PosSaleItem;
use App\Models\SuperShop\PosPayment;
use App\Models\SuperShop\ExpenseCategory;
use App\Models\SuperShop\BranchExpense;
use App\Models\SuperShop\LoyaltyMembership;
use App\Models\SuperShop\LoyaltyPointsLedger;
use Illuminate\Support\Str;

class SuperShopMultiBranchSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        $adminId = $admin ? $admin->id : 1;

        // 1. Seed Branches (6 Branches as per SRS)
        $branchesData = [
            [
                'name' => 'Branch 01 – Dhaka Main (Motijheel)',
                'code' => 'BR-01',
                'type' => 'super_shop',
                'manager_id' => $adminId,
                'phone' => '01711000001',
                'email' => 'motijheel@supershop.com',
                'address' => 'Plot #12, Dilkusha C/A, Motijheel, Dhaka-1000',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Motijheel',
                'opening_time' => '08:00:00',
                'closing_time' => '22:30:00',
                'status' => 'active',
            ],
            [
                'name' => 'Branch 02 – Mirpur (Mirpur-10)',
                'code' => 'BR-02',
                'type' => 'super_shop',
                'manager_id' => $adminId,
                'phone' => '01711000002',
                'email' => 'mirpur@supershop.com',
                'address' => 'House #45, Block #D, Mirpur-10 Golchokkor, Dhaka-1216',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Mirpur',
                'opening_time' => '08:00:00',
                'closing_time' => '23:00:00',
                'status' => 'active',
            ],
            [
                'name' => 'Branch 03 – Uttara (Sector-3)',
                'code' => 'BR-03',
                'type' => 'super_shop',
                'manager_id' => $adminId,
                'phone' => '01711000003',
                'email' => 'uttara@supershop.com',
                'address' => 'Road #7, Sector #3, Jasimuddin Avenue, Uttara, Dhaka-1230',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Uttara',
                'opening_time' => '08:30:00',
                'closing_time' => '23:00:00',
                'status' => 'active',
            ],
            [
                'name' => 'Branch 04 – Dhanmondi (Road-27)',
                'code' => 'BR-04',
                'type' => 'super_shop',
                'manager_id' => $adminId,
                'phone' => '01711000004',
                'email' => 'dhanmondi@supershop.com',
                'address' => 'House #19, Road #27 (Old), Dhanmondi R/A, Dhaka-1209',
                'division' => 'Dhaka',
                'district' => 'Dhaka',
                'area' => 'Dhanmondi',
                'opening_time' => '08:00:00',
                'closing_time' => '23:00:00',
                'status' => 'active',
            ],
            [
                'name' => 'Branch 05 – Chittagong (Agrabad)',
                'code' => 'BR-05',
                'type' => 'super_shop',
                'manager_id' => $adminId,
                'phone' => '01711000005',
                'email' => 'agrabad@supershop.com',
                'address' => 'World Trade Center Annex, Agrabad C/A, Chittagong',
                'division' => 'Chittagong',
                'district' => 'Chittagong',
                'area' => 'Agrabad',
                'opening_time' => '09:00:00',
                'closing_time' => '22:00:00',
                'status' => 'active',
            ],
            [
                'name' => 'Branch 06 – Sylhet (Zindabazar)',
                'code' => 'BR-06',
                'type' => 'super_shop',
                'manager_id' => $adminId,
                'phone' => '01711000006',
                'email' => 'sylhet@supershop.com',
                'address' => 'Al-Hamra Shopping City Ground Floor, Zindabazar, Sylhet',
                'division' => 'Sylhet',
                'district' => 'Sylhet',
                'area' => 'Zindabazar',
                'opening_time' => '09:00:00',
                'closing_time' => '22:00:00',
                'status' => 'active',
            ],
        ];

        $branches = [];
        foreach ($branchesData as $bData) {
            $branches[] = Branch::updateOrCreate(['code' => $bData['code']], $bData);
        }

        // 2. Central Warehouse
        $warehouse = Warehouse::updateOrCreate(
            ['code' => 'WH-CENTRAL'],
            [
                'name' => 'Central Tejgaon Logistics Hub',
                'code' => 'WH-CENTRAL',
                'location' => 'Tejgaon Industrial Area, Dhaka',
                'manager_id' => $adminId,
                'phone' => '01711999900',
                'is_central' => true,
                'status' => 'active',
            ]
        );

        // 3. Seed POS Counters per branch
        foreach ($branches as $br) {
            PosCounter::updateOrCreate(
                ['branch_id' => $br->id, 'counter_name' => 'Counter 01'],
                ['counter_code' => $br->code . '-C01', 'status' => 'active']
            );
            PosCounter::updateOrCreate(
                ['branch_id' => $br->id, 'counter_name' => 'Counter 02 (Express)'],
                ['counter_code' => $br->code . '-C02', 'status' => 'active']
            );
        }

        // 4. Seed Suppliers
        $suppliersData = [
            ['name' => 'ACI Pure Consumer Foods', 'company_name' => 'ACI Limited', 'contact_person' => 'Kamal Hossain', 'phone' => '01811223344', 'email' => 'sales@aci-bd.com', 'payment_terms' => '30 Days Credit'],
            ['name' => 'PRAN-RFL Agro Foods', 'company_name' => 'PRAN Group', 'contact_person' => 'Mahbub Alam', 'phone' => '01811223355', 'email' => 'orders@pranfoods.net', 'payment_terms' => '15 Days Credit'],
            ['name' => 'Square Consumer Products', 'company_name' => 'Square Group', 'contact_person' => 'Zahid Hasan', 'phone' => '01811223366', 'email' => 'supply@square.com.bd', 'payment_terms' => '30 Days Credit'],
            ['name' => 'Meghna Group of Industries (Fresh)', 'company_name' => 'Meghna Group', 'contact_person' => 'Farhan Chowdhury', 'phone' => '01811223377', 'email' => 'fresh@mgi.org', 'payment_terms' => 'Weekly'],
            ['name' => 'City Group (Teer)', 'company_name' => 'City Group', 'contact_person' => 'Tanvir Ahmed', 'phone' => '01811223388', 'email' => 'teer@citygroup.com.bd', 'payment_terms' => 'Cash on Delivery'],
        ];

        $suppliers = [];
        foreach ($suppliersData as $sData) {
            $suppliers[] = Supplier::updateOrCreate(['name' => $sData['name']], $sData);
        }

        // 5. Seed Expense Categories
        $expCats = [
            ['name' => 'দোকান ভাড়া (Shop Rent)', 'code' => 'RENT'],
            ['name' => 'বিদ্যুৎ বিল (Electricity Bill)', 'code' => 'ELECTRICITY'],
            ['name' => 'কর্মচারী বেতন (Staff Salary)', 'code' => 'SALARY'],
            ['name' => 'প্যাকেজিং ও ব্যাগ (Packaging)', 'code' => 'PACKAGING'],
            ['name' => 'মেরামত ও রক্ষণাবেক্ষণ (Maintenance)', 'code' => 'MAINTENANCE'],
            ['name' => 'চা-নাস্তা ও আপ্যায়ন (Entertainment)', 'code' => 'ENTERTAINMENT'],
            ['name' => 'পরিবহন খরচ (Transport)', 'code' => 'TRANSPORT'],
        ];

        foreach ($expCats as $ec) {
            ExpenseCategory::updateOrCreate(['name' => $ec['name']], $ec);
        }

        // 6. Connect Existing E-commerce Products to Branch Inventories (NO DUPLICATION!)
        $allProducts = ShopProduct::all();
        $shelves = ['Aisle 1, Rack A', 'Aisle 1, Rack B', 'Aisle 2, Rack A', 'Aisle 2, Rack C', 'Aisle 3, Rack A', 'Chilled Showcase 1', 'Frozen Freezer 2'];

        foreach ($branches as $bIndex => $branch) {
            foreach ($allProducts as $pIndex => $prod) {
                // Determine varied stock per branch
                $baseStock = match($branch->code) {
                    'BR-01' => rand(100, 300),
                    'BR-02' => rand(80, 220),
                    'BR-03' => rand(60, 180),
                    'BR-04' => rand(50, 150),
                    'BR-05' => rand(40, 120),
                    'BR-06' => rand(30, 90),
                    default => rand(50, 100),
                };

                // Approximate wholesale purchase cost (75% to 85% of selling price)
                $costPrice = round($prod->price * 0.80, 2);

                $shelf = $shelves[($pIndex + $bIndex) % count($shelves)];

                BranchInventory::updateOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'product_id' => $prod->id,
                    ],
                    [
                        'stock_quantity' => $baseStock,
                        'reserved_stock' => rand(0, 5),
                        'reorder_level' => 15,
                        'safety_stock' => 5,
                        'purchase_price' => $costPrice,
                        'selling_price' => null, // Inherits product price
                        'shelf_location' => $shelf,
                        'is_active' => true,
                    ]
                );
            }
        }

        // 7. Seed Staff Profiles
        $staffData = [
            ['name' => 'মোহাম্মদ রফিকুল ইসলাম', 'phone' => '01712000011', 'designation' => 'Branch Manager', 'salary' => 45000, 'branch_id' => $branches[0]->id],
            ['name' => 'তানজিলা আক্তার', 'phone' => '01712000012', 'designation' => 'Chief Cashier', 'salary' => 22000, 'branch_id' => $branches[0]->id],
            ['name' => 'মাহমুদুল হাসান', 'phone' => '01712000013', 'designation' => 'Supervisor', 'salary' => 28000, 'branch_id' => $branches[0]->id],
            ['name' => 'আব্দুর রহিম', 'phone' => '01712000021', 'designation' => 'Branch Manager', 'salary' => 42000, 'branch_id' => $branches[1]->id],
            ['name' => 'সাবরিনা সুলতানা', 'phone' => '01712000022', 'designation' => 'Cashier', 'salary' => 20000, 'branch_id' => $branches[1]->id],
        ];

        foreach ($staffData as $st) {
            EmployeeProfile::updateOrCreate(
                ['phone' => $st['phone']],
                [
                    'branch_id' => $st['branch_id'],
                    'employee_code' => 'EMP-' . rand(1000, 9999),
                    'name' => $st['name'],
                    'phone' => $st['phone'],
                    'email' => Str::slug($st['name']) . '@supershop.com',
                    'designation' => $st['designation'],
                    'salary' => $st['salary'],
                    'joining_date' => now()->subMonths(rand(3, 18))->toDateString(),
                    'status' => 'active',
                ]
            );
        }

        // 8. Seed Sample Cashier Shifts
        $counter1 = PosCounter::where('branch_id', $branches[0]->id)->first();
        $shift = CashierShift::updateOrCreate(
            ['branch_id' => $branches[0]->id, 'status' => 'open'],
            [
                'pos_counter_id' => $counter1 ? $counter1->id : null,
                'cashier_id' => $adminId,
                'opened_at' => now()->startOfDay()->addHours(8),
                'opening_cash' => 5000.00,
                'expected_cash' => 24500.00,
                'actual_cash' => 0.00,
                'cash_difference' => 0.00,
                'total_cash_sales' => 19500.00,
                'total_digital_sales' => 8400.00,
                'total_sales_amount' => 27900.00,
                'total_transactions' => 14,
                'status' => 'open',
            ]
        );

        // 9. Seed Sample POS Sales
        $sampleSaleItems = [
            ['name' => 'Fortune Mustard Oil 5L', 'qty' => 1, 'price' => 1400, 'cost' => 1120],
            ['name' => 'Rupchada Zirashail Parboiled Rice 5kg', 'qty' => 2, 'price' => 480, 'cost' => 380],
            ['name' => 'Fortune Biryani Special Basmati Rice 1kg', 'qty' => 2, 'price' => 400, 'cost' => 320],
        ];

        $firstProduct = $allProducts->first();
        if ($firstProduct) {
            $sale = PosSale::updateOrCreate(
                ['invoice_no' => 'POS-BR01-2611-1001'],
                [
                    'branch_id' => $branches[0]->id,
                    'pos_counter_id' => $counter1 ? $counter1->id : null,
                    'shift_id' => $shift->id,
                    'cashier_id' => $adminId,
                    'customer_name' => 'মোহাম্মদ করিম উল্লাহ',
                    'customer_phone' => '01712345678',
                    'subtotal' => 3160.00,
                    'discount_amount' => 160.00,
                    'tax_amount' => 0.00,
                    'grand_total' => 3000.00,
                    'paid_amount' => 3000.00,
                    'change_amount' => 0.00,
                    'payment_method' => 'cash',
                    'payment_status' => 'paid',
                    'sale_status' => 'completed',
                    'notes' => 'ক্যাশ কাউন্টারে সরাসরি পেমেন্ট ও ইনভয়েস ডেলিভারি।',
                    'sale_time' => now()->subHours(2),
                ]
            );

            PosSaleItem::updateOrCreate(
                ['pos_sale_id' => $sale->id, 'product_id' => $firstProduct->id],
                [
                    'product_name' => $firstProduct->name,
                    'sku' => $firstProduct->sku,
                    'barcode' => $firstProduct->sku,
                    'quantity' => 2,
                    'unit_price' => $firstProduct->price,
                    'cost_price' => round($firstProduct->price * 0.8, 2),
                    'discount' => 0,
                    'tax' => 0,
                    'total_price' => $firstProduct->price * 2,
                ]
            );

            PosPayment::updateOrCreate(
                ['pos_sale_id' => $sale->id],
                [
                    'payment_method' => 'cash',
                    'amount' => 3000.00,
                    'transaction_ref' => 'CASH-REF-' . rand(1000, 9999),
                ]
            );
        }

        // 10. Seed Sample Branch Expenses
        $catRent = ExpenseCategory::where('code', 'RENT')->first();
        $catElec = ExpenseCategory::where('code', 'ELECTRICITY')->first();

        if ($catRent) {
            BranchExpense::updateOrCreate(
                ['branch_id' => $branches[0]->id, 'title' => 'নভেম্বর মাসের দোকান ভাড়া'],
                [
                    'category_id' => $catRent->id,
                    'amount' => 65000.00,
                    'expense_date' => now()->startOfMonth()->toDateString(),
                    'payment_method' => 'Bank Transfer',
                    'description' => 'মতিঝিল আউটলেট নভেম্বর মাসের দোকান স্পেস ভাড়া পরিশোধ।',
                    'status' => 'paid',
                    'created_by' => $adminId,
                    'approved_by' => $adminId,
                ]
            );
        }

        if ($catElec) {
            BranchExpense::updateOrCreate(
                ['branch_id' => $branches[0]->id, 'title' => 'অক্টোবর মাসের ডেসকো বিদ্যুৎ বিল'],
                [
                    'category_id' => $catElec->id,
                    'amount' => 14200.00,
                    'expense_date' => now()->subDays(5)->toDateString(),
                    'payment_method' => 'bKash',
                    'description' => 'আউটলেট ও ফ্রিজার বিদ্যুৎ বিল পরিশোধ।',
                    'status' => 'paid',
                    'created_by' => $adminId,
                    'approved_by' => $adminId,
                ]
            );
        }

        // 11. Seed Loyalty Memberships
        $loyalty = LoyaltyMembership::updateOrCreate(
            ['customer_phone' => '01712345678'],
            [
                'customer_name' => 'মোহাম্মদ করিম উল্লাহ',
                'membership_number' => 'SHW-BR01-8821',
                'tier' => 'gold',
                'total_points' => 450,
                'redeemed_points' => 50,
                'available_points' => 400,
                'status' => 'active',
            ]
        );

        LoyaltyPointsLedger::updateOrCreate(
            ['membership_id' => $loyalty->id, 'type' => 'earned'],
            [
                'points' => 30,
                'notes' => 'POS বিক্রয় বিলের মাধ্যমে অর্জিত লয়্যালটি পয়েন্ট।',
            ]
        );
    }
}

