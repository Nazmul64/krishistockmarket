<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpCompanyProfile;
use App\Models\ERP\ErpWarehouse;
use App\Models\ERP\ErpItemCategory;
use App\Models\ERP\ErpItemSubcategory;
use App\Models\ERP\ErpItemUnit;
use App\Models\ERP\ErpItem;
use App\Models\ERP\ErpSupplier;
use App\Models\ERP\ErpAssetCategory;
use App\Models\ERP\ErpAsset;
use App\Models\ERP\ErpVehicle;
use App\Models\ERP\ErpVehicleDocument;
use App\Models\ERP\ErpChartOfAccount;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpExpenseCategory;
use App\Models\ERP\ErpIncomeCategory;
use App\Models\ERP\ErpBudget;
use App\Models\ERP\ErpBill;
use App\Models\ERP\ErpVoucher;
use App\Models\ERP\ErpVoucherEntry;
use App\Models\ERP\ErpGeneralLedgerEntry;
use App\Models\ERP\ErpMoneyReceipt;
use App\Models\ERP\ErpCashMemo;
use App\Models\ERP\ErpCashMemoItem;
use App\Models\ERP\ErpMaintenanceRequest;
use App\Models\ERP\ErpMaintenanceSchedule;

class ErpEnterpriseSystemSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Company Profile
        if (ErpCompanyProfile::count() === 0) {
            ErpCompanyProfile::create([
                'company_name' => 'eKrishiSheba Corporate Enterprise Limited',
                'company_name_bn' => 'ই-কৃষি সেবা কর্পোরেট এন্টারপ্রাইজ লিমিটেড',
                'address' => 'House 42, Road 11, Block D, Banani, Dhaka-1213, Bangladesh',
                'corporate_address' => 'Level 8, Crystal Tower, Gulshan-2, Dhaka-1212',
                'phone' => '+880 1700-000000',
                'email' => 'corporate@ekrishisheba.com',
                'website' => 'https://ekrishisheba.com',
                'trade_license' => 'TRAD/DNCC/049182/2024',
                'tin_no' => '583920194821',
                'bin_no' => '002948192-0101',
                'registration_no' => 'C-184920/2024',
                'fiscal_year' => '2026-2027',
                'currency' => 'BDT (৳)',
                'description' => 'সমন্বিত এগ্রো-ইনভেস্টমেন্ট, সাপ্লাই চেইন, প্রপার্টি ও কর্পোরেট ইআরপি ম্যানেজমেন্ট সিস্টেম।',
                'authorized_person' => 'Managing Director & CEO',
            ]);
        }

        // 2. Warehouses
        $warehouses = [
            ['name' => 'Main Central Warehouse', 'name_bn' => 'সেন্ট্রাল প্রধান গুদাম (ঢাকা HQ)', 'code' => 'WH-MAIN', 'warehouse_type' => 'central', 'location' => 'ঢাকা কেন্দ্রীয় হেড অফিস, বনানী', 'manager_name' => 'মোঃ রফিকুল ইসলাম', 'contact_phone' => '01711000001', 'capacity' => '5,000 Sq Ft'],
            ['name' => 'IT & Electronics Store', 'name_bn' => 'আইটি ও টেকনোলজি স্টোর', 'code' => 'WH-IT', 'warehouse_type' => 'it', 'location' => 'লেভেল ৫, আইটি ডিপার্টমেন্ট', 'manager_name' => 'ইঞ্জিঃ তানভীর আহমেদ', 'contact_phone' => '01711000002', 'capacity' => '1,200 Sq Ft'],
            ['name' => 'Office Stationery Store', 'name_bn' => 'স্টেশনারি ও অফিস সাপ্লাই গোডাউন', 'code' => 'WH-STAT', 'warehouse_type' => 'stationery', 'location' => 'গ্রাউন্ড ফ্লোর, রুম ১০২', 'manager_name' => 'আনোয়ার হোসেন', 'contact_phone' => '01711000003', 'capacity' => '800 Sq Ft'],
            ['name' => 'Furniture & Fixtures Store', 'name_bn' => 'আসবাবপত্র ও ফার্নিচার গুদাম', 'code' => 'WH-FURN', 'warehouse_type' => 'furniture', 'location' => 'বেসমেন্ট ২, সেন্ট্রাল ডিপো', 'manager_name' => 'কামাল উদ্দিন', 'contact_phone' => '01711000004', 'capacity' => '2,500 Sq Ft'],
            ['name' => 'Spare Parts & Maintenance Store', 'name_bn' => 'স্পেয়ার পার্টস ও ওয়ার্কশপ স্টোর', 'code' => 'WH-MAINT', 'warehouse_type' => 'vehicle_parts', 'location' => 'ওয়ার্কশপ জোন, তেজগাঁও হাব', 'manager_name' => 'মোঃ জহিরুল হক', 'contact_phone' => '01711000005', 'capacity' => '1,500 Sq Ft'],
        ];

        foreach ($warehouses as $wh) {
            ErpWarehouse::firstOrCreate(['code' => $wh['code']], $wh);
        }

        // 3. Units of Measurement
        $units = [
            ['name' => 'Piece', 'symbol' => 'PCS'],
            ['name' => 'Box', 'symbol' => 'BOX'],
            ['name' => 'Packet', 'symbol' => 'PKT'],
            ['name' => 'Dozen', 'symbol' => 'DZN'],
            ['name' => 'Ream', 'symbol' => 'REAM'],
            ['name' => 'Meter', 'symbol' => 'MTR'],
            ['name' => 'Kilogram', 'symbol' => 'KG'],
            ['name' => 'Liter', 'symbol' => 'LTR'],
            ['name' => 'Set', 'symbol' => 'SET'],
            ['name' => 'Pair', 'symbol' => 'PAIR'],
            ['name' => 'Carton', 'symbol' => 'CTN'],
        ];

        foreach ($units as $u) {
            ErpItemUnit::firstOrCreate(['symbol' => $u['symbol']], $u);
        }

        $pcsUnit = ErpItemUnit::where('symbol', 'PCS')->first();
        $boxUnit = ErpItemUnit::where('symbol', 'BOX')->first();
        $reamUnit = ErpItemUnit::where('symbol', 'REAM')->first();
        $ltrUnit = ErpItemUnit::where('symbol', 'LTR')->first();
        $setUnit = ErpItemUnit::where('symbol', 'SET')->first();

        // 4. Item Categories & Subcategories
        $categoriesData = [
            ['name' => 'Office Stationery', 'name_bn' => 'অফিস স্টেশনারি ও খাতা-কলম', 'code' => 'CAT-STAT', 'type' => 'stationery'],
            ['name' => 'Corporate Furniture', 'name_bn' => 'কর্পোরেট আসবাবপত্র ও ফার্নিচার', 'code' => 'CAT-FURN', 'type' => 'furniture'],
            ['name' => 'IT & Electronics Equipment', 'name_bn' => 'আইটি ও ইলেকট্রনিক্স সরঞ্জাম', 'code' => 'CAT-IT', 'type' => 'it_equipment'],
            ['name' => 'Electrical & Backup Power', 'name_bn' => 'ইলেকট্রিক্যাল ও ব্যাকআপ পাওয়ার', 'code' => 'CAT-ELEC', 'type' => 'electrical'],
            ['name' => 'Kitchen & Utilities', 'name_bn' => 'কিচেন ও অফিস ইউটিলিটিজ', 'code' => 'CAT-KITCH', 'type' => 'kitchen_utilities'],
            ['name' => 'Vehicle Parts & Fuel', 'name_bn' => 'গাড়ি ও পরিবহন সরঞ্জাম', 'code' => 'CAT-VEH', 'type' => 'vehicle_items'],
            ['name' => 'Maintenance Materials', 'name_bn' => 'মেইনটেন্যান্স ও মেরামত সামগ্রী', 'code' => 'CAT-MAINT', 'type' => 'maintenance_materials'],
        ];

        foreach ($categoriesData as $c) {
            ErpItemCategory::firstOrCreate(['code' => $c['code']], $c);
        }

        $statCat = ErpItemCategory::where('code', 'CAT-STAT')->first();
        $furnCat = ErpItemCategory::where('code', 'CAT-FURN')->first();
        $itCat = ErpItemCategory::where('code', 'CAT-IT')->first();
        $elecCat = ErpItemCategory::where('code', 'CAT-ELEC')->first();
        $vehCat = ErpItemCategory::where('code', 'CAT-VEH')->first();
        $maintCat = ErpItemCategory::where('code', 'CAT-MAINT')->first();

        $mainWh = ErpWarehouse::where('code', 'WH-MAIN')->first();
        $itWh = ErpWarehouse::where('code', 'WH-IT')->first();
        $statWh = ErpWarehouse::where('code', 'WH-STAT')->first();
        $furnWh = ErpWarehouse::where('code', 'WH-FURN')->first();
        $maintWh = ErpWarehouse::where('code', 'WH-MAINT')->first();

        // 5. Items (Store Inventory Catalog)
        $itemsData = [
            [
                'name' => 'Double A A4 Copy Paper 80GSM',
                'name_bn' => 'ডাবল এ এ৪ ফটোকপি কাগজ (৮০ জিএসএম)',
                'code' => 'ITM-PAP-001',
                'category_id' => $statCat->id,
                'unit_id' => $reamUnit->id,
                'default_warehouse_id' => $statWh->id,
                'tracking_type' => 'quantity',
                'purchase_price' => 520.00,
                'selling_price' => 580.00,
                'current_stock' => 120.00,
                'reorder_level' => 20.00,
                'brand' => 'Double A',
                'supplier_name' => 'মেঘনা পেপার অ্যান্ড স্টেশনারি',
            ],
            [
                'name' => 'Matador Pinpoint Ball Pen 0.5mm (Box of 20)',
                'name_bn' => 'ম্যাটাডোর পিনপয়েন্ট বলপেন বক্স (২০ টি)',
                'code' => 'ITM-PEN-002',
                'category_id' => $statCat->id,
                'unit_id' => $boxUnit->id,
                'default_warehouse_id' => $statWh->id,
                'tracking_type' => 'quantity',
                'purchase_price' => 120.00,
                'selling_price' => 140.00,
                'current_stock' => 85.00,
                'reorder_level' => 15.00,
                'brand' => 'Matador',
                'supplier_name' => 'মেঘনা পেপার অ্যান্ড স্টেশনারি',
            ],
            [
                'name' => 'Executive Ergonomic Mesh Chair',
                'name_bn' => 'এক্সিকিউটিভ এরগনোমিক রিভলভিং চেয়ার',
                'code' => 'ITM-CHR-003',
                'category_id' => $furnCat->id,
                'unit_id' => $pcsUnit->id,
                'default_warehouse_id' => $furnWh->id,
                'tracking_type' => 'asset',
                'purchase_price' => 12500.00,
                'selling_price' => 14000.00,
                'current_stock' => 18.00,
                'reorder_level' => 4.00,
                'brand' => 'Hatil Corporate',
                'supplier_name' => 'ব্রাদার্স ফার্নিচার কমপ্লেক্স',
            ],
            [
                'name' => 'Modular Conference Table (12 Seater)',
                'name_bn' => 'মডুলার কনফারেন্স টেবিল (১২ সিটার ওক কাঠ)',
                'code' => 'ITM-TBL-004',
                'category_id' => $furnCat->id,
                'unit_id' => $pcsUnit->id,
                'default_warehouse_id' => $furnWh->id,
                'tracking_type' => 'asset',
                'purchase_price' => 65000.00,
                'selling_price' => 72000.00,
                'current_stock' => 3.00,
                'reorder_level' => 1.00,
                'brand' => 'Otobi Prime',
                'supplier_name' => 'ব্রাদার্স ফার্নিচার কমপ্লেক্স',
            ],
            [
                'name' => 'HP ProBook 450 G10 Core i5 Laptop',
                'name_bn' => 'এইচপি প্রোবুক ল্যাপটপ কোর আই৫ (১৬জিবি/৫১২জিবি)',
                'code' => 'ITM-LAP-005',
                'category_id' => $itCat->id,
                'unit_id' => $pcsUnit->id,
                'default_warehouse_id' => $itWh->id,
                'tracking_type' => 'asset',
                'purchase_price' => 88500.00,
                'selling_price' => 95000.00,
                'current_stock' => 12.00,
                'reorder_level' => 3.00,
                'brand' => 'HP',
                'supplier_name' => 'ইউনিক টেকনোলজিস লিঃ',
            ],
            [
                'name' => 'HP LaserJet Pro MFP M428fdw All-in-One Printer',
                'name_bn' => 'এইচপি লেজারজেট প্রো অল-ইন-ওয়ান প্রিন্টার',
                'code' => 'ITM-PRN-006',
                'category_id' => $itCat->id,
                'unit_id' => $pcsUnit->id,
                'default_warehouse_id' => $itWh->id,
                'tracking_type' => 'asset',
                'purchase_price' => 48000.00,
                'selling_price' => 52000.00,
                'current_stock' => 6.00,
                'reorder_level' => 2.00,
                'brand' => 'HP',
                'supplier_name' => 'ইউনিক টেকনোলজিস লিঃ',
            ],
            [
                'name' => 'Gree 1.5 Ton Inverter Split Air Conditioner',
                'name_bn' => 'গ্রী ১.৫ টন ইনভার্টার স্প্লিট এয়ার কন্ডিশনার',
                'code' => 'ITM-AC-007',
                'category_id' => $elecCat->id,
                'unit_id' => $pcsUnit->id,
                'default_warehouse_id' => $mainWh->id,
                'tracking_type' => 'asset',
                'purchase_price' => 64000.00,
                'selling_price' => 70000.00,
                'current_stock' => 8.00,
                'reorder_level' => 2.00,
                'brand' => 'Gree',
                'supplier_name' => 'অটো পাওয়ার অ্যান্ড ইঞ্জিনিয়ারিং',
            ],
            [
                'name' => 'Toyota 5W-30 Full Synthetic Motor Oil (4L)',
                'name_bn' => 'টয়োটা ৫W-৩০ ফুল সিন্থেটিক ইঞ্জিন অয়েল (৪ লিটার)',
                'code' => 'ITM-OIL-008',
                'category_id' => $vehCat->id,
                'unit_id' => $ltrUnit->id,
                'default_warehouse_id' => $maintWh->id,
                'tracking_type' => 'quantity',
                'purchase_price' => 4200.00,
                'selling_price' => 4800.00,
                'current_stock' => 24.00,
                'reorder_level' => 5.00,
                'brand' => 'Toyota Genuine',
                'supplier_name' => 'পদ্মা অটোমোবাইলস সার্ভিসিং হাব',
            ],
        ];

        foreach ($itemsData as $item) {
            ErpItem::firstOrCreate(['code' => $item['code']], $item);
        }

        // 6. Suppliers
        $suppliers = [
            ['supplier_name' => 'Unique Technologies Ltd.', 'supplier_code' => 'SUP-TECH-01', 'contact_person' => 'মাহবুবুর রহমান', 'phone' => '01712000010', 'email' => 'sales@uniquetech.com.bd', 'address' => 'আইডিবি ভবন, ঢাকা', 'opening_balance' => 0, 'current_balance' => 45000.00, 'payment_terms' => 'Net 30'],
            ['supplier_name' => 'Brothers Furniture Complex', 'supplier_code' => 'SUP-FURN-02', 'contact_person' => 'নুরুল ইসলাম', 'phone' => '01712000011', 'email' => 'info@brothersfurniture.com', 'address' => 'পান্থপথ, ঢাকা', 'opening_balance' => 0, 'current_balance' => 65000.00, 'payment_terms' => 'COD'],
            ['supplier_name' => 'Meghna Paper & Stationery', 'supplier_code' => 'SUP-STAT-03', 'contact_person' => 'ফারুক হোসেন', 'phone' => '01712000012', 'email' => 'order@meghnapaper.com', 'address' => 'নবাবপুর, ঢাকা', 'opening_balance' => 0, 'current_balance' => 12500.00, 'payment_terms' => 'Net 15'],
            ['supplier_name' => 'Auto Power & Engineering', 'supplier_code' => 'SUP-ELEC-04', 'contact_person' => 'ইঞ্জিঃ জাহিদ হাসান', 'phone' => '01712000013', 'email' => 'power@autopower.com.bd', 'address' => 'তেজগাঁও শিল্প এলাকা, ঢাকা', 'opening_balance' => 0, 'current_balance' => 80000.00, 'payment_terms' => 'Net 30'],
            ['supplier_name' => 'Padma Automobiles & Service Hub', 'supplier_code' => 'SUP-AUTO-05', 'contact_person' => 'শফিকুল আলম', 'phone' => '01712000014', 'email' => 'padmaauto@gmail.com', 'address' => 'মহাখালী, ঢাকা', 'opening_balance' => 0, 'current_balance' => 18000.00, 'payment_terms' => 'Cash'],
        ];

        foreach ($suppliers as $sup) {
            ErpSupplier::firstOrCreate(['supplier_code' => $sup['supplier_code']], $sup);
        }

        // 7. Fixed Asset Categories & Assets
        $assetCats = [
            ['name' => 'Office Furniture & Fixtures', 'name_bn' => 'অফিস আসবাবপত্র ও ফিটিংস', 'code' => 'AST-CAT-FURN', 'useful_life_years' => 10, 'default_depreciation_rate' => 10.00],
            ['name' => 'IT & Computer Equipment', 'name_bn' => 'আইটি ও কম্পিউটার সরঞ্জাম', 'code' => 'AST-CAT-IT', 'useful_life_years' => 5, 'default_depreciation_rate' => 20.00],
            ['name' => 'Electrical Equipment & Generators', 'name_bn' => 'ইলেকট্রিক্যাল ও জেনারেটর', 'code' => 'AST-CAT-ELEC', 'useful_life_years' => 8, 'default_depreciation_rate' => 12.50],
            ['name' => 'Motor Vehicles & Fleet', 'name_bn' => 'মোটরযান ও পরিবহন বহর', 'code' => 'AST-CAT-VEH', 'useful_life_years' => 7, 'default_depreciation_rate' => 15.00],
            ['name' => 'Office Appliances & Utilities', 'name_bn' => 'অফিস যন্ত্রপাতি ও কিচেন সামগ্রী', 'code' => 'AST-CAT-APP', 'useful_life_years' => 5, 'default_depreciation_rate' => 20.00],
        ];

        foreach ($assetCats as $ac) {
            ErpAssetCategory::firstOrCreate(['code' => $ac['code']], $ac);
        }

        $acIt = ErpAssetCategory::where('code', 'AST-CAT-IT')->first();
        $acFurn = ErpAssetCategory::where('code', 'AST-CAT-FURN')->first();
        $acElec = ErpAssetCategory::where('code', 'AST-CAT-ELEC')->first();

        $assetsData = [
            [
                'asset_code' => 'AST-2026-0001',
                'asset_name' => 'HP ProBook 450 G10 Core i5',
                'asset_name_bn' => 'এইচপি প্রোবুক ল্যাপটপ (আইটি হেড বরাদ্দ)',
                'category_id' => $acIt->id,
                'brand' => 'HP',
                'model' => 'ProBook 450 G10',
                'serial_number' => '5CD349281X',
                'qr_code' => 'AST-2026-0001-QR',
                'purchase_date' => '2026-01-15',
                'purchase_price' => 88500.00,
                'salvage_value' => 8850.00,
                'useful_life_years' => 5,
                'depreciation_method' => 'straight_line',
                'current_book_value' => 81125.00,
                'accumulated_depreciation' => 7375.00,
                'department_name' => 'IT Department',
                'assigned_employee_name' => 'ইঞ্জিঃ তানভীর আহমেদ (IT Head)',
                'assigned_date' => '2026-01-16',
                'condition' => 'excellent',
                'status' => 'assigned',
                'location_detail' => 'Level 5, Room 502, Desk IT-01',
                'warranty_expiry' => '2028-01-14',
                'supplier_name' => 'Unique Technologies Ltd.',
            ],
            [
                'asset_code' => 'AST-2026-0002',
                'asset_name' => 'Executive Leather Recliner Chair',
                'asset_name_bn' => 'এক্সিকিউটিভ রিক্লাইনিং রিভলভিং চেয়ার (এমডি রুম)',
                'category_id' => $acFurn->id,
                'brand' => 'Hatil Corporate',
                'model' => 'Presidential-09',
                'serial_number' => 'HTL-89410',
                'qr_code' => 'AST-2026-0002-QR',
                'purchase_date' => '2026-02-01',
                'purchase_price' => 28000.00,
                'salvage_value' => 2800.00,
                'useful_life_years' => 10,
                'depreciation_method' => 'straight_line',
                'current_book_value' => 26600.00,
                'accumulated_depreciation' => 1400.00,
                'department_name' => 'Executive Suite',
                'assigned_employee_name' => 'ব্যবস্থাপনা পরিচালক (MD Suite)',
                'assigned_date' => '2026-02-05',
                'condition' => 'excellent',
                'status' => 'assigned',
                'location_detail' => 'Level 8, Managing Director Suite',
                'warranty_expiry' => '2027-02-01',
                'supplier_name' => 'Brothers Furniture Complex',
            ],
            [
                'asset_code' => 'AST-2026-0003',
                'asset_name' => 'Gree 1.5 Ton Inverter AC',
                'asset_name_bn' => 'গ্রী ১.৫ টন ইনভার্টার স্প্লিট এসি (বোর্ড রুম)',
                'category_id' => $acElec->id,
                'brand' => 'Gree',
                'model' => 'GS-18XPUV32',
                'serial_number' => 'GR-9481920',
                'qr_code' => 'AST-2026-0003-QR',
                'purchase_date' => '2026-01-10',
                'purchase_price' => 64000.00,
                'salvage_value' => 6400.00,
                'useful_life_years' => 8,
                'depreciation_method' => 'straight_line',
                'current_book_value' => 59500.00,
                'accumulated_depreciation' => 4500.00,
                'department_name' => 'Corporate Board',
                'assigned_employee_name' => 'বোর্ড মিটিং রুম',
                'assigned_date' => '2026-01-12',
                'condition' => 'good',
                'status' => 'in_use',
                'location_detail' => 'Level 7, Board Conference Hall A',
                'warranty_expiry' => '2031-01-10',
                'supplier_name' => 'Auto Power & Engineering',
            ],
            [
                'asset_code' => 'AST-2026-0004',
                'asset_name' => 'Dell OptiPlex 7000 Tower Core i7',
                'asset_name_bn' => 'ডেল অপটিপ্লেক্স আই৭ ওয়ার্কস্টেশন পিসি',
                'category_id' => $acIt->id,
                'brand' => 'Dell',
                'model' => 'OptiPlex 7000 MT',
                'serial_number' => 'DL-98410294',
                'qr_code' => 'AST-2026-0004-QR',
                'purchase_date' => '2026-02-10',
                'purchase_price' => 115000.00,
                'salvage_value' => 11500.00,
                'useful_life_years' => 5,
                'depreciation_method' => 'straight_line',
                'current_book_value' => 106000.00,
                'accumulated_depreciation' => 9000.00,
                'department_name' => 'Finance & Accounts',
                'assigned_employee_name' => 'মোঃ শাহ আলম (Senior Accountant)',
                'assigned_date' => '2026-02-12',
                'condition' => 'excellent',
                'status' => 'assigned',
                'location_detail' => 'Level 4, Accounts Room 404',
                'warranty_expiry' => '2029-02-10',
                'supplier_name' => 'Unique Technologies Ltd.',
            ],
        ];

        foreach ($assetsData as $ast) {
            ErpAsset::firstOrCreate(['asset_code' => $ast['asset_code']], $ast);
        }

        // 8. Vehicles (Fleet)
        $vehiclesData = [
            [
                'vehicle_number' => 'ঢাকা মেট্রো-চ ৫৪-৮৯২১',
                'vehicle_type' => 'microbus',
                'brand' => 'Toyota',
                'model' => 'Hiace GL 2023',
                'manufacturing_year' => 2023,
                'purchase_date' => '2024-03-15',
                'purchase_price' => 4850000.00,
                'chassis_no' => 'TRH200-0192841',
                'engine_no' => '1TR-FE-928172',
                'fuel_type' => 'octane',
                'current_mileage' => 24850.00,
                'driver_name' => 'মোঃ আব্দুল কাদের',
                'driver_phone' => '01819000001',
                'department_name' => 'Operations & Protocol',
                'status' => 'active',
            ],
            [
                'vehicle_number' => 'ঢাকা মেট্রো-গ ৩২-১১০৪',
                'vehicle_type' => 'car',
                'brand' => 'Toyota',
                'model' => 'Corolla Altis 2022',
                'manufacturing_year' => 2022,
                'purchase_date' => '2023-08-10',
                'purchase_price' => 3450000.00,
                'chassis_no' => 'ZRE210-4820194',
                'engine_no' => '2ZR-FE-382910',
                'fuel_type' => 'octane',
                'current_mileage' => 31200.00,
                'driver_name' => 'মোঃ জসিম উদ্দিন',
                'driver_phone' => '01819000002',
                'department_name' => 'Managing Director Pool',
                'status' => 'active',
            ],
            [
                'vehicle_number' => 'ঢাকা মেট্রো-ম ১৮-৯০৪২',
                'vehicle_type' => 'pickup',
                'brand' => 'Tata',
                'model' => 'Ace Mega 1 Ton',
                'manufacturing_year' => 2024,
                'purchase_date' => '2024-06-20',
                'purchase_price' => 1650000.00,
                'chassis_no' => 'TAT-492019401',
                'engine_no' => 'TAT-ENG-849102',
                'fuel_type' => 'diesel',
                'current_mileage' => 14300.00,
                'driver_name' => 'মোঃ রমিজ আলী',
                'driver_phone' => '01819000003',
                'department_name' => 'Monthly Bazaar Logistics',
                'status' => 'active',
            ],
        ];

        foreach ($vehiclesData as $v) {
            $veh = ErpVehicle::firstOrCreate(['vehicle_number' => $v['vehicle_number']], $v);

            // Add sample vehicle documents
            if ($veh->documents()->count() === 0) {
                ErpVehicleDocument::create([
                    'vehicle_id' => $veh->id,
                    'document_type' => 'fitness',
                    'document_number' => 'FIT-2025-' . rand(1000, 9999),
                    'issue_date' => '2025-06-01',
                    'expiry_date' => '2027-05-31',
                    'status' => 'valid',
                ]);
                ErpVehicleDocument::create([
                    'vehicle_id' => $veh->id,
                    'document_type' => 'tax_token',
                    'document_number' => 'TAX-2025-' . rand(1000, 9999),
                    'issue_date' => '2025-07-01',
                    'expiry_date' => '2026-12-31',
                    'status' => 'valid',
                ]);
                ErpVehicleDocument::create([
                    'vehicle_id' => $veh->id,
                    'document_type' => 'insurance',
                    'document_number' => 'INS-GRE-' . rand(10000, 99999),
                    'issue_date' => '2026-01-01',
                    'expiry_date' => '2027-01-01',
                    'status' => 'valid',
                ]);
            }
        }

        // 9. Chart of Accounts (COA - Hierarchical Double Entry)
        $coaData = [
            // Assets (1000-1999)
            ['account_code' => '1000', 'account_name' => 'Assets', 'account_name_bn' => 'সম্পদ', 'root_type' => 'asset', 'normal_balance' => 'debit', 'is_system' => true],
            ['account_code' => '1100', 'account_name' => 'Current Assets', 'account_name_bn' => 'চলতি সম্পদ', 'root_type' => 'asset', 'normal_balance' => 'debit', 'parent_code' => '1000'],
            ['account_code' => '1110', 'account_name' => 'Cash in Hand (Petty Cash)', 'account_name_bn' => 'হাতে নগদ ও পেটি ক্যাশ', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 50000.00, 'current_balance' => 125000.00, 'parent_code' => '1100'],
            ['account_code' => '1120', 'account_name' => 'Bank Accounts', 'account_name_bn' => 'ব্যাংক হিসাব', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 1200000.00, 'current_balance' => 3850000.00, 'parent_code' => '1100'],
            ['account_code' => '1130', 'account_name' => 'Accounts Receivable (Customers)', 'account_name_bn' => 'প্রাপ্য হিসাব (গ্রাহক দেনা)', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 180000.00, 'current_balance' => 420000.00, 'parent_code' => '1100'],
            ['account_code' => '1140', 'account_name' => 'Store & Product Inventory', 'account_name_bn' => 'স্টোর ও পণ্য মজুত', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 850000.00, 'current_balance' => 1480000.00, 'parent_code' => '1100'],
            ['account_code' => '1200', 'account_name' => 'Fixed Assets (Property, Plant, Equip)', 'account_name_bn' => 'স্থায়ী সম্পদ', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 8500000.00, 'current_balance' => 12500000.00, 'parent_code' => '1000'],
            ['account_code' => '1210', 'account_name' => 'Office Furniture & Fixtures', 'account_name_bn' => 'আসবাবপত্র ও ফিটিংস', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 450000.00, 'current_balance' => 650000.00, 'parent_code' => '1200'],
            ['account_code' => '1220', 'account_name' => 'IT & Computer Equipment', 'account_name_bn' => 'আইটি ও কম্পিউটার সরঞ্জাম', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 680000.00, 'current_balance' => 1120000.00, 'parent_code' => '1200'],
            ['account_code' => '1230', 'account_name' => 'Motor Vehicles Fleet', 'account_name_bn' => 'মোটরযান ও গাড়ি বহর', 'root_type' => 'asset', 'normal_balance' => 'debit', 'opening_balance' => 8200000.00, 'current_balance' => 9950000.00, 'parent_code' => '1200'],

            // Liabilities (2000-2999)
            ['account_code' => '2000', 'account_name' => 'Liabilities', 'account_name_bn' => 'দায় ও দেনা', 'root_type' => 'liability', 'normal_balance' => 'credit', 'is_system' => true],
            ['account_code' => '2100', 'account_name' => 'Current Liabilities', 'account_name_bn' => 'চলতি দায়', 'root_type' => 'liability', 'normal_balance' => 'credit', 'parent_code' => '2000'],
            ['account_code' => '2110', 'account_name' => 'Accounts Payable (Vendors)', 'account_name_bn' => 'প্রদেয় হিসাব (সরবরাহকারী পাওনা)', 'root_type' => 'liability', 'normal_balance' => 'credit', 'opening_balance' => 320000.00, 'current_balance' => 540000.00, 'parent_code' => '2100'],
            ['account_code' => '2120', 'account_name' => 'Accrued Utility Bills & Rent', 'account_name_bn' => 'বকেয়া বিল ও ভাড়া দায়', 'root_type' => 'liability', 'normal_balance' => 'credit', 'opening_balance' => 45000.00, 'current_balance' => 82000.00, 'parent_code' => '2100'],

            // Equity (3000-3999)
            ['account_code' => '3000', 'account_name' => 'Equity', 'account_name_bn' => 'মালিকানা স্বত্ব ও শেয়ার মূলধন', 'root_type' => 'equity', 'normal_balance' => 'credit', 'is_system' => true],
            ['account_code' => '3100', 'account_name' => 'Share Capital & Paid-in Equity', 'account_name_bn' => 'পরিশোধিত শেয়ার মূলধন', 'root_type' => 'equity', 'normal_balance' => 'credit', 'opening_balance' => 10000000.00, 'current_balance' => 15000000.00, 'parent_code' => '3000'],
            ['account_code' => '3200', 'account_name' => 'Retained Earnings', 'account_name_bn' => 'সংরক্ষিত আয়', 'root_type' => 'equity', 'normal_balance' => 'credit', 'opening_balance' => 850000.00, 'current_balance' => 1650000.00, 'parent_code' => '3000'],

            // Income (4000-4999)
            ['account_code' => '4000', 'account_name' => 'Income', 'account_name_bn' => 'রাজস্ব ও মোট আয়', 'root_type' => 'income', 'normal_balance' => 'credit', 'is_system' => true],
            ['account_code' => '4100', 'account_name' => 'Monthly Bazaar Product Sales', 'account_name_bn' => 'মাসিক বাজার পণ্য বিক্রয় রাজস্ব', 'root_type' => 'income', 'normal_balance' => 'credit', 'opening_balance' => 0, 'current_balance' => 2450000.00, 'parent_code' => '4000'],
            ['account_code' => '4200', 'account_name' => 'Corporate Membership & Services Income', 'account_name_bn' => 'মেম্বারশিপ ও করপোরেট সার্ভিস ফি', 'root_type' => 'income', 'normal_balance' => 'credit', 'opening_balance' => 0, 'current_balance' => 820000.00, 'parent_code' => '4000'],

            // Expenses (5000-5999)
            ['account_code' => '5000', 'account_name' => 'Expenses', 'account_name_bn' => 'পরিচালন ও ব্যবসায়িক ব্যয়', 'root_type' => 'expense', 'normal_balance' => 'debit', 'is_system' => true],
            ['account_code' => '5100', 'account_name' => 'Salaries & Staff Welfare', 'account_name_bn' => 'কর্মকর্তা-কর্মচারী বেতন ও ভাতা', 'root_type' => 'expense', 'normal_balance' => 'debit', 'opening_balance' => 0, 'current_balance' => 650000.00, 'parent_code' => '5000'],
            ['account_code' => '5200', 'account_name' => 'Office Rent & Utility Bills', 'account_name_bn' => 'অফিস ভাড়া ও ইউটিলিটি বিল', 'root_type' => 'expense', 'normal_balance' => 'debit', 'opening_balance' => 0, 'current_balance' => 220000.00, 'parent_code' => '5000'],
            ['account_code' => '5300', 'account_name' => 'Vehicle Fuel & Fleet Maintenance', 'account_name_bn' => 'গাড়ি জ্বালানি ও মেইনটেন্যান্স খরচ', 'root_type' => 'expense', 'normal_balance' => 'debit', 'opening_balance' => 0, 'current_balance' => 85000.00, 'parent_code' => '5000'],
            ['account_code' => '5400', 'account_name' => 'Office Stationery & Supplies Expense', 'account_name_bn' => 'স্টেশনারি ও অফিস সাপ্লাই খরচ', 'root_type' => 'expense', 'normal_balance' => 'debit', 'opening_balance' => 0, 'current_balance' => 38000.00, 'parent_code' => '5000'],
            ['account_code' => '5500', 'account_name' => 'Depreciation Expense', 'account_name_bn' => 'সম্পদের অবচয় ও মূল্যহ্রাস ব্যয়', 'root_type' => 'expense', 'normal_balance' => 'debit', 'opening_balance' => 0, 'current_balance' => 45000.00, 'parent_code' => '5000'],
        ];

        // Seed parents first then children
        foreach ($coaData as $c) {
            $parentId = null;
            if (isset($c['parent_code'])) {
                $p = ErpChartOfAccount::where('account_code', $c['parent_code'])->first();
                $parentId = $p ? $p->id : null;
                unset($c['parent_code']);
            }
            $c['parent_id'] = $parentId;
            ErpChartOfAccount::firstOrCreate(['account_code' => $c['account_code']], $c);
        }

        // 10. Cash & Bank Accounts
        $bankAccounts = [
            ['account_type' => 'bank', 'account_name' => 'Dhaka Bank Corporate Account', 'account_number' => '204-100-849201', 'bank_name' => 'Dhaka Bank PLC', 'branch_name' => 'Gulshan Corporate Branch', 'routing_number' => '08526194', 'opening_balance' => 2500000.00, 'current_balance' => 2850000.00],
            ['account_type' => 'bank', 'account_name' => 'Dutch-Bangla Bank Operational Fund', 'account_number' => '115-120-384910', 'bank_name' => 'Dutch-Bangla Bank PLC', 'branch_name' => 'Motijheel Main Branch', 'routing_number' => '09027184', 'opening_balance' => 800000.00, 'current_balance' => 1000000.00],
            ['account_type' => 'cash', 'account_name' => 'Head Office Petty Cash', 'account_number' => 'CASH-HQ-01', 'bank_name' => 'Central Cash Vault', 'branch_name' => 'Banani HQ', 'routing_number' => null, 'opening_balance' => 50000.00, 'current_balance' => 125000.00],
            ['account_type' => 'mfs', 'account_name' => 'bKash Corporate Merchant Gateway', 'account_number' => '01700-000000', 'bank_name' => 'bKash Limited', 'branch_name' => 'Corporate MFS', 'routing_number' => null, 'opening_balance' => 45000.00, 'current_balance' => 180000.00],
        ];

        foreach ($bankAccounts as $ba) {
            ErpCashBankAccount::firstOrCreate(['account_name' => $ba['account_name']], $ba);
        }

        // 11. Expense & Income Categories
        $expCats = [
            ['name' => 'Office Rent & Space', 'name_bn' => 'অফিস ভাড়া ও স্পেস চার্জ', 'code' => 'EXP-RENT'],
            ['name' => 'Electricity & Power Bill', 'name_bn' => 'বিদ্যুৎ বিল ও ব্যাকআপ পাওয়ার', 'code' => 'EXP-ELEC'],
            ['name' => 'Internet & Telecommunication', 'name_bn' => 'ইন্টারনেট ও টেলিফোন বিল', 'code' => 'EXP-NET'],
            ['name' => 'Vehicle Fuel & Lubricants', 'name_bn' => 'গাড়ি জ্বালানি ও মবিল', 'code' => 'EXP-FUEL'],
            ['name' => 'Office Stationery & Printing', 'name_bn' => 'স্টেশনারি ও প্রিন্টিং ব্যয়', 'code' => 'EXP-STAT'],
            ['name' => 'Staff Welfare & Entertainment', 'name_bn' => 'কর্মী কল্যাণ ও আপ্যায়ন', 'code' => 'EXP-WELFARE'],
            ['name' => 'Equipment Repair & Maintenance', 'name_bn' => 'যন্ত্রপাতি ও সম্পদ মেরামত', 'code' => 'EXP-REPAIR'],
        ];

        foreach ($expCats as $ec) {
            ErpExpenseCategory::firstOrCreate(['code' => $ec['code']], $ec);
        }

        $incCats = [
            ['name' => 'Agro Product Sales', 'name_bn' => 'এগ্রো ও মাসিক বাজার পণ্য বিক্রয়', 'code' => 'INC-SALES'],
            ['name' => 'Corporate Membership Card Fees', 'name_bn' => 'মেম্বারশিপ কার্ড ও সার্ভিস ফি', 'code' => 'INC-CARD'],
            ['name' => 'Property Booking & Management Fee', 'name_bn' => 'প্রপার্টি বুকিং ও সার্ভিস চার্জ', 'code' => 'INC-PROP'],
            ['name' => 'Asset Disposal & Scrap Income', 'name_bn' => 'সম্পদ বিক্রয় ও স্ক্র্যাপ আয়', 'code' => 'INC-SCRAP'],
        ];

        foreach ($incCats as $ic) {
            ErpIncomeCategory::firstOrCreate(['code' => $ic['code']], $ic);
        }

        // 12. Budgets
        $budgets = [
            ['fiscal_year' => '2026-2027', 'department_name' => 'IT Department', 'budget_head' => 'IT Equipment & Cloud Infrastructure', 'budget_amount' => 500000.00, 'spent_amount' => 285000.00, 'remaining_amount' => 215000.00, 'alert_threshold_percentage' => 85.00],
            ['fiscal_year' => '2026-2027', 'department_name' => 'Administration', 'budget_head' => 'Office Stationery & Daily Utilities', 'budget_amount' => 300000.00, 'spent_amount' => 182000.00, 'remaining_amount' => 118000.00, 'alert_threshold_percentage' => 90.00],
            ['fiscal_year' => '2026-2027', 'department_name' => 'Transport & Fleet', 'budget_head' => 'Vehicle Fuel & Servicing Budget', 'budget_amount' => 450000.00, 'spent_amount' => 240000.00, 'remaining_amount' => 210000.00, 'alert_threshold_percentage' => 85.00],
        ];

        foreach ($budgets as $b) {
            ErpBudget::firstOrCreate(['budget_head' => $b['budget_head']], $b);
        }

        // 13. Bills (Sample Utility & Stationery Bills)
        if (ErpBill::count() === 0) {
            ErpBill::create([
                'bill_number' => 'BILL-2026-0001',
                'bill_type' => 'utility_electricity',
                'vendor_name' => 'ঢাকা পাওয়ার ডিস্ট্রিবিউশন কোম্পানি (DPDC)',
                'department_name' => 'Administration',
                'reference_no' => 'DPDC-CONS-4820194',
                'bill_date' => '2026-02-05',
                'due_date' => '2026-02-25',
                'subtotal' => 42500.00,
                'discount' => 0,
                'vat_tax' => 2125.00,
                'total_amount' => 44625.00,
                'paid_amount' => 44625.00,
                'due_amount' => 0,
                'payment_status' => 'paid',
                'approval_status' => 'approved',
                'description' => 'হেড অফিস বনানী ভবনের জানুয়ারি ২০২৬ মাসের বিদ্যুৎ বিল।',
                'approved_by' => 'Finance Director',
            ]);

            ErpBill::create([
                'bill_number' => 'BILL-2026-0002',
                'bill_type' => 'stationery',
                'vendor_name' => 'মেঘনা পেপার অ্যান্ড স্টেশনারি',
                'department_name' => 'Central Store',
                'reference_no' => 'MEG-INV-84910',
                'bill_date' => '2026-02-12',
                'due_date' => '2026-03-05',
                'subtotal' => 35000.00,
                'discount' => 1000.00,
                'vat_tax' => 1750.00,
                'total_amount' => 35750.00,
                'paid_amount' => 15000.00,
                'due_amount' => 20750.00,
                'payment_status' => 'partially_paid',
                'approval_status' => 'approved',
                'description' => '১০০ রিম এ৪ পেপার ও ৫০ বক্স বলপেন ক্রয়ের চালান বিল।',
                'approved_by' => 'Accounts Manager',
            ]);
        }

        // 14. Vouchers & Double Entry Ledger (Sample Payment Voucher)
        $dhakaBank = ErpCashBankAccount::where('account_type', 'bank')->first();
        $stationeryExpAcc = ErpChartOfAccount::where('account_code', '5400')->first();
        $bankAssetAcc = ErpChartOfAccount::where('account_code', '1120')->first();

        if (ErpVoucher::count() === 0) {
            $pv = ErpVoucher::create([
                'voucher_number' => 'PV-2026-0001',
                'voucher_type' => 'payment',
                'voucher_date' => '2026-02-15',
                'payee_or_payer' => 'মেঘনা পেপার অ্যান্ড স্টেশনারি',
                'payment_method' => 'bank',
                'bank_account_id' => $dhakaBank->id,
                'amount' => 15000.00,
                'amount_in_words' => 'Fifteen Thousand Taka Only (পনেরো হাজার টাকা মাত্র)',
                'reference_no' => 'BILL-2026-0002',
                'description' => 'মেঘনা পেপার অ্যান্ড স্টেশনারির আংশিক বিল পরিশোধ (চেক নং: ডিবি-৮৪৯২০১)।',
                'status' => 'posted',
                'prepared_by' => 'মোঃ শাহ আলম (Accountant)',
                'checked_by' => 'মুস্তাফিজুর রহমান (Accounts Head)',
                'approved_by' => 'এম. এ. জলিল (Finance Director)',
            ]);

            // Voucher lines (Debit Stationery / Credit Bank)
            ErpVoucherEntry::create([
                'voucher_id' => $pv->id,
                'account_id' => $stationeryExpAcc->id,
                'debit' => 15000.00,
                'credit' => 0,
                'description' => 'Office Stationery Purchase Bill Payment',
            ]);
            ErpVoucherEntry::create([
                'voucher_id' => $pv->id,
                'account_id' => $bankAssetAcc->id,
                'debit' => 0,
                'credit' => 15000.00,
                'description' => 'Paid from Dhaka Bank Corp Account',
            ]);

            // Post to General Ledger
            ErpGeneralLedgerEntry::create([
                'transaction_date' => '2026-02-15',
                'voucher_id' => $pv->id,
                'account_id' => $stationeryExpAcc->id,
                'reference_type' => 'payment_voucher',
                'reference_number' => $pv->voucher_number,
                'debit' => 15000.00,
                'credit' => 0,
                'balance_after' => 38000.00,
                'description' => 'Office Stationery Purchase - Bill BILL-2026-0002',
            ]);
            ErpGeneralLedgerEntry::create([
                'transaction_date' => '2026-02-15',
                'voucher_id' => $pv->id,
                'account_id' => $bankAssetAcc->id,
                'reference_type' => 'payment_voucher',
                'reference_number' => $pv->voucher_number,
                'debit' => 0,
                'credit' => 15000.00,
                'balance_after' => 3850000.00,
                'description' => 'Payment to Meghna Paper & Stationery',
            ]);
        }

        // 15. Money Receipt (Sample Income Receipt)
        if (ErpMoneyReceipt::count() === 0) {
            ErpMoneyReceipt::create([
                'receipt_number' => 'MR-2026-0001',
                'receipt_date' => '2026-02-16',
                'customer_name' => 'মোঃ হাসানুজ্জামান',
                'customer_phone' => '01715000001',
                'customer_address' => 'মিরপুর ১০, ঢাকা',
                'payment_purpose' => 'SME Gold ডিজিটাল মেম্বারশিপ কার্ড ও এগ্রো প্যাকেজ ফি',
                'payment_method' => 'bank',
                'bank_account_id' => $dhakaBank->id,
                'received_amount' => 5000.00,
                'previous_due' => 0,
                'remaining_due' => 0,
                'amount_in_words' => 'Five Thousand Taka Only (পাঁচ হাজার টাকা মাত্র)',
                'received_by' => 'হিসাব শাখা (Accounts)',
                'remarks' => 'অনলাইন ব্যাংকিং ট্রান্সফারের মাধ্যমে প্রাপ্ত।',
            ]);
        }

        // 16. Cash Memo (POS Direct Sale Sample)
        if (ErpCashMemo::count() === 0) {
            $cm = ErpCashMemo::create([
                'memo_number' => 'CM-2026-0001',
                'memo_date' => '2026-02-18',
                'customer_name' => 'তারেক মাহমুদ',
                'customer_phone' => '01912000001',
                'customer_address' => 'উত্তরা সেক্টর ৭, ঢাকা',
                'subtotal' => 2900.00,
                'discount' => 100.00,
                'vat' => 0,
                'grand_total' => 2800.00,
                'paid_amount' => 2800.00,
                'due_amount' => 0,
                'payment_method' => 'cash',
                'sales_person_name' => 'সেলস কাউন্টার ১',
                'status' => 'paid',
                'remarks' => 'নগদে পণ্য বিক্রয় সম্পন্ন।',
            ]);

            $paperItem = ErpItem::where('code', 'ITM-PAP-001')->first();
            $penItem = ErpItem::where('code', 'ITM-PEN-002')->first();

            ErpCashMemoItem::create([
                'cash_memo_id' => $cm->id,
                'item_id' => $paperItem ? $paperItem->id : null,
                'item_name' => 'Double A A4 Copy Paper 80GSM (4 Reams)',
                'quantity' => 4,
                'unit_price' => 580.00,
                'subtotal' => 2320.00,
                'discount' => 0,
                'total' => 2320.00,
            ]);

            ErpCashMemoItem::create([
                'cash_memo_id' => $cm->id,
                'item_id' => $penItem ? $penItem->id : null,
                'item_name' => 'Matador Pinpoint Ball Pen (4 Boxes)',
                'quantity' => 4,
                'unit_price' => 145.00,
                'subtotal' => 580.00,
                'discount' => 100.00,
                'total' => 480.00,
            ]);
        }

        // 17. Maintenance Requests & Schedules
        $sampleAsset = ErpAsset::first();
        if (ErpMaintenanceRequest::count() === 0 && $sampleAsset) {
            ErpMaintenanceRequest::create([
                'request_number' => 'MNT-2026-0001',
                'asset_id' => $sampleAsset->id,
                'department_name' => 'IT Department',
                'maintenance_type' => 'preventive',
                'priority' => 'medium',
                'problem_description' => 'ল্যাপটপ কুলিং ফ্যান ক্লিন ও থার্মাল পেস্ট রিপ্লেসমেন্ট এবং ওএস অপটিমাইজেশন।',
                'requested_by' => 'ইঞ্জিঃ তানভীর আহমেদ',
                'request_date' => '2026-02-14',
                'estimated_cost' => 1500.00,
                'technician_name' => 'আব্দুর রহিম (Hardware Tech)',
                'service_provider' => 'ইন-হাউস আইটি টিম',
                'status' => 'completed',
                'completion_date' => '2026-02-15',
                'spare_parts_cost' => 500.00,
                'labor_cost' => 1000.00,
                'total_cost' => 1500.00,
                'remarks' => 'রক্ষণাবেক্ষণ সফলভাবে সম্পন্ন হয়েছে এবং পারফরম্যান্স স্বাভাবিক।',
            ]);

            ErpMaintenanceSchedule::create([
                'asset_id' => $sampleAsset->id,
                'service_name' => 'Periodic Hardware Inspection & Cleaning',
                'interval_type' => 'days',
                'interval_value' => 180,
                'last_service_date' => '2026-02-15',
                'next_due_date' => '2026-08-15',
                'alert_before_days' => 15,
                'status' => 'active',
            ]);
        }
    }
}

