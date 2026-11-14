<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ERP\ErpCompanyProfile;
use App\Models\ERP\ErpWarehouse;
use App\Models\ERP\ErpItem;
use App\Models\ERP\ErpStockTransaction;
use App\Models\ERP\ErpStockRequisition;
use App\Models\ERP\ErpAsset;
use App\Models\ERP\ErpVehicle;
use App\Models\ERP\ErpVehicleDocument;
use App\Models\ERP\ErpMaintenanceRequest;
use App\Models\ERP\ErpBill;
use App\Models\ERP\ErpVoucher;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpChartOfAccount;
use App\Models\ERP\ErpExpenseEntry;
use App\Models\ERP\ErpIncomeEntry;
use App\Models\ERP\ErpBudget;
use App\Models\ERP\ErpDocument;
use Carbon\Carbon;

class ErpDashboardController extends Controller
{
    public function index()
    {
        $company = ErpCompanyProfile::first() ?? new ErpCompanyProfile();

        // 1. Store & Inventory Statistics
        $totalWarehouses = ErpWarehouse::count();
        $totalItems = ErpItem::count();
        $totalStockQty = ErpItem::sum('current_stock');
        $totalStockValuation = ErpItem::selectRaw('SUM(current_stock * purchase_price) as total_val')->value('total_val') ?? 0;
        $lowStockCount = ErpItem::whereColumn('current_stock', '<=', 'reorder_level')->count();
        $pendingRequisitionsCount = ErpStockRequisition::where('status', 'pending')->count();

        // 2. Fixed Assets Statistics
        $totalAssetsCount = ErpAsset::count();
        $totalAssetPurchaseValue = ErpAsset::sum('purchase_price');
        $totalAssetBookValue = ErpAsset::sum('current_book_value');
        $totalAccumulatedDepreciation = ErpAsset::sum('accumulated_depreciation');
        $assignedAssetsCount = ErpAsset::where('status', 'assigned')->count();

        // 3. Vehicles Fleet & Transport
        $totalVehicles = ErpVehicle::count();
        $activeVehicles = ErpVehicle::where('status', 'active')->count();
        $today = Carbon::today()->toDateString();
        $expiringDocsCount = ErpVehicleDocument::where('expiry_date', '<=', Carbon::today()->addDays(30)->toDateString())->count();

        // 4. Maintenance & Repairs
        $pendingMaintenanceCount = ErpMaintenanceRequest::whereIn('status', ['pending', 'approved', 'in_progress'])->count();
        $totalMaintenanceCost = ErpMaintenanceRequest::sum('total_cost');

        // 5. Bills, Vouchers & Financial Liquidity
        $pendingBillsCount = ErpBill::where('approval_status', 'pending_approval')->count();
        $unpaidBillsTotal = ErpBill::where('payment_status', '!=', 'paid')->sum('due_amount');
        $totalVouchersCount = ErpVoucher::count();
        $totalCashBankBalance = ErpCashBankAccount::sum('current_balance');
        $totalReceivables = ErpChartOfAccount::where('account_code', '1130')->value('current_balance') ?? 0;
        $totalPayables = ErpChartOfAccount::where('account_code', '2110')->value('current_balance') ?? 0;

        // 6. Recent Activities
        $recentTransactions = ErpStockTransaction::with(['item', 'warehouse'])->latest()->take(6)->get();
        $recentRequisitions = ErpStockRequisition::latest()->take(5)->get();
        $recentBills = ErpBill::latest()->take(5)->get();
        $recentVouchers = ErpVoucher::latest()->take(5)->get();
        $recentAssets = ErpAsset::with('category')->latest()->take(5)->get();

        // 7. Low Stock Alerts
        $lowStockItems = ErpItem::whereColumn('current_stock', '<=', 'reorder_level')->take(5)->get();

        return view('admin.erp.dashboard', compact(
            'company',
            'totalWarehouses',
            'totalItems',
            'totalStockQty',
            'totalStockValuation',
            'lowStockCount',
            'pendingRequisitionsCount',
            'totalAssetsCount',
            'totalAssetPurchaseValue',
            'totalAssetBookValue',
            'totalAccumulatedDepreciation',
            'assignedAssetsCount',
            'totalVehicles',
            'activeVehicles',
            'expiringDocsCount',
            'pendingMaintenanceCount',
            'totalMaintenanceCost',
            'pendingBillsCount',
            'unpaidBillsTotal',
            'totalVouchersCount',
            'totalCashBankBalance',
            'totalReceivables',
            'totalPayables',
            'recentTransactions',
            'recentRequisitions',
            'recentBills',
            'recentVouchers',
            'recentAssets',
            'lowStockItems'
        ));
    }

    public function companyProfile()
    {
        $company = ErpCompanyProfile::first() ?? new ErpCompanyProfile();
        $documents = ErpDocument::latest()->get();
        return view('admin.erp.company_profile', compact('company', 'documents'));
    }

    public function updateCompanyProfile(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
        ]);

        $company = ErpCompanyProfile::first();
        if (!$company) {
            $company = new ErpCompanyProfile();
        }

        $company->company_name = $request->company_name;
        $company->company_name_bn = $request->company_name_bn;
        $company->address = $request->address;
        $company->corporate_address = $request->corporate_address;
        $company->phone = $request->phone;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->trade_license = $request->trade_license;
        $company->tin_no = $request->tin_no;
        $company->bin_no = $request->bin_no;
        $company->registration_no = $request->registration_no;
        $company->fiscal_year = $request->fiscal_year ?? '2026-2027';
        $company->currency = $request->currency ?? 'BDT (৳)';
        $company->description = $request->description;
        $company->authorized_person = $request->authorized_person;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'erp_logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp'), $filename);
            $company->logo = 'upload/erp/' . $filename;
        }

        $company->save();

        return redirect()->back()->with('success', 'কোম্পানি প্রোফাইল সফলভাবে আপডেট করা হয়েছে।');
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'file' => 'required|file|max:10240', // 10MB
        ]);

        $doc = new ErpDocument();
        $doc->title = $request->title;
        $doc->document_number = $request->document_number;
        $doc->category = $request->category;
        $doc->issue_date = $request->issue_date;
        $doc->expiry_date = $request->expiry_date;
        $doc->alert_days = $request->alert_days ?? 30;
        $doc->notes = $request->notes;
        $doc->created_by = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'doc_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/documents'), $filename);
            $doc->file_path = 'upload/erp/documents/' . $filename;
        }

        $doc->save();

        return redirect()->back()->with('success', 'ডকুমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে।');
    }
}

