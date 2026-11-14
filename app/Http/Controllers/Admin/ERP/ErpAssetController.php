<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ERP\ErpAssetCategory;
use App\Models\ERP\ErpAsset;
use App\Models\ERP\ErpAssetAssignment;
use App\Models\ERP\ErpAssetTransfer;
use App\Models\ERP\ErpAssetDepreciation;
use App\Models\ERP\ErpAssetDisposal;
use App\Models\ERP\ErpWarehouse;
use App\Models\User;

class ErpAssetController extends Controller
{
    // ==========================================
    // 1. FIXED ASSET REGISTER
    // ==========================================
    public function assetsIndex(Request $request)
    {
        $query = ErpAsset::with(['category', 'assignments']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('asset_name', 'like', "%$s%")
                  ->orWhere('asset_name_bn', 'like', "%$s%")
                  ->orWhere('asset_code', 'like', "%$s%")
                  ->orWhere('serial_number', 'like', "%$s%")
                  ->orWhere('assigned_employee_name', 'like', "%$s%");
            });
        }

        $assets = $query->latest()->paginate(15);
        $categories = ErpAssetCategory::all();
        $warehouses = ErpWarehouse::where('is_active', true)->get();
        $users = User::select('id', 'name', 'phone', 'email')->get();

        $totalAssetsCount = ErpAsset::count();
        $totalPurchaseValue = ErpAsset::sum('purchase_price');
        $totalBookValue = ErpAsset::sum('current_book_value');
        $assignedCount = ErpAsset::where('status', 'assigned')->count();
        $inMaintenanceCount = ErpAsset::where('status', 'under_maintenance')->count();

        return view('admin.erp.assets.index', compact(
            'assets',
            'categories',
            'warehouses',
            'users',
            'totalAssetsCount',
            'totalPurchaseValue',
            'totalBookValue',
            'assignedCount',
            'inMaintenanceCount'
        ));
    }

    public function storeAsset(Request $request)
    {
        $request->validate([
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:erp_asset_categories,id',
            'purchase_date' => 'required|date',
            'purchase_price' => 'required|numeric|min:0',
        ]);

        $code = $request->asset_code;
        if (empty($code)) {
            $lastId = ErpAsset::max('id') ?? 0;
            $code = 'AST-' . date('Y') . '-' . str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
        }

        $qrCode = $code . '-QR';
        $purchasePrice = $request->purchase_price;
        $salvageValue = $request->salvage_value ?? ($purchasePrice * 0.1);
        $usefulLife = $request->useful_life_years ?? 5;

        $asset = new ErpAsset();
        $asset->asset_code = $code;
        $asset->asset_name = $request->asset_name;
        $asset->asset_name_bn = $request->asset_name_bn;
        $asset->category_id = $request->category_id;
        $asset->brand = $request->brand;
        $asset->model = $request->model;
        $asset->serial_number = $request->serial_number;
        $asset->qr_code = $qrCode;
        $asset->purchase_date = $request->purchase_date;
        $asset->purchase_price = $purchasePrice;
        $asset->salvage_value = $salvageValue;
        $asset->useful_life_years = $usefulLife;
        $asset->depreciation_method = $request->depreciation_method ?? 'straight_line';
        $asset->current_book_value = $purchasePrice;
        $asset->accumulated_depreciation = 0;
        $asset->warehouse_id = $request->warehouse_id;
        $asset->department_name = $request->department_name;
        $asset->location_detail = $request->location_detail;
        $asset->condition = $request->condition ?? 'good';
        $asset->status = 'available';
        $asset->warranty_expiry = $request->warranty_expiry;
        $asset->supplier_name = $request->supplier_name;
        $asset->invoice_no = $request->invoice_no;
        $asset->notes = $request->notes;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'asset_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/assets'), $filename);
            $asset->image = 'upload/erp/assets/' . $filename;
        }

        $asset->save();

        return redirect()->back()->with('success', "স্থায়ী সম্পদ সফলভাবে নিবন্ধিত হয়েছে (কোড: {$code})।");
    }

    public function updateAsset(Request $request, $id)
    {
        $asset = ErpAsset::findOrFail($id);

        $request->validate([
            'asset_name' => 'required|string|max:255',
            'category_id' => 'required|exists:erp_asset_categories,id',
        ]);

        $asset->asset_name = $request->asset_name;
        $asset->asset_name_bn = $request->asset_name_bn;
        $asset->category_id = $request->category_id;
        $asset->brand = $request->brand;
        $asset->model = $request->model;
        $asset->serial_number = $request->serial_number;
        $asset->location_detail = $request->location_detail;
        $asset->department_name = $request->department_name;
        $asset->condition = $request->condition ?? $asset->condition;
        $asset->status = $request->status ?? $asset->status;
        $asset->warranty_expiry = $request->warranty_expiry;
        $asset->supplier_name = $request->supplier_name;
        $asset->notes = $request->notes;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'asset_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/assets'), $filename);
            $asset->image = 'upload/erp/assets/' . $filename;
        }

        $asset->save();

        return redirect()->back()->with('success', 'সম্পদের বিবরণ সফলভাবে আপডেট করা হয়েছে।');
    }

    // ==========================================
    // 2. ASSET ASSIGNMENT & HANDOVER
    // ==========================================
    public function assignmentsIndex()
    {
        $assignments = ErpAssetAssignment::with('asset')->latest()->paginate(15);
        $assets = ErpAsset::whereIn('status', ['available', 'assigned'])->get();
        $users = User::select('id', 'name', 'phone', 'email')->get();

        return view('admin.erp.assets.assignments', compact('assignments', 'assets', 'users'));
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:erp_assets,id',
            'employee_name' => 'required|string|max:255',
            'assignment_date' => 'required|date',
        ]);

        $asset = ErpAsset::findOrFail($request->asset_id);

        DB::beginTransaction();
        try {
            ErpAssetAssignment::create([
                'asset_id' => $asset->id,
                'employee_name' => $request->employee_name,
                'department_name' => $request->department_name,
                'assignment_date' => $request->assignment_date,
                'condition_at_handover' => $request->condition_at_handover ?? 'good',
                'handover_by' => auth()->user()->name ?? 'Administrator',
                'status' => 'active',
                'remarks' => $request->remarks,
            ]);

            $asset->update([
                'status' => 'assigned',
                'assigned_employee_name' => $request->employee_name,
                'department_name' => $request->department_name,
                'assigned_date' => $request->assignment_date,
            ]);

            DB::commit();
            return redirect()->back()->with('success', "সম্পদ সফলভাবে কর্মচারীকে হস্তান্তর (Assign) করা হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'হস্তান্তর ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function returnAssignment(Request $request, $id)
    {
        $assignment = ErpAssetAssignment::findOrFail($id);
        $asset = ErpAsset::findOrFail($assignment->asset_id);

        $assignment->update([
            'return_date' => $request->return_date ?? now()->toDateString(),
            'condition_at_return' => $request->condition_at_return ?? 'good',
            'received_by' => auth()->user()->name ?? 'Administrator',
            'status' => 'returned',
            'remarks' => $request->remarks,
        ]);

        $asset->update([
            'status' => 'available',
            'assigned_employee_name' => null,
            'assigned_date' => null,
            'condition' => $request->condition_at_return ?? $asset->condition,
        ]);

        return redirect()->back()->with('success', "সম্পদ ফেরত গ্রহণ (Asset Return) সফলভাবে সম্পন্ন হয়েছে।");
    }

    // ==========================================
    // 3. DEPRECIATION & DISPOSAL
    // ==========================================
    public function depreciationIndex()
    {
        $depreciations = ErpAssetDepreciation::with('asset')->latest()->paginate(15);
        $disposals = ErpAssetDisposal::with('asset')->latest()->paginate(15);
        $assets = ErpAsset::where('status', '!=', 'disposed')->get();

        return view('admin.erp.assets.depreciation', compact('depreciations', 'disposals', 'assets'));
    }

    public function calculateDepreciation(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:erp_assets,id',
            'fiscal_year' => 'required|string',
        ]);

        $asset = ErpAsset::findOrFail($request->asset_id);
        $annualDepreciation = ($asset->purchase_price - $asset->salvage_value) / ($asset->useful_life_years ?: 5);

        // Ensure we don't depreciate below salvage value
        $maxDepreciable = $asset->current_book_value - $asset->salvage_value;
        $actualDepreciation = min($annualDepreciation, max(0, $maxDepreciable));

        if ($actualDepreciation <= 0) {
            return redirect()->back()->with('error', 'এই সম্পদের অবচয় সীমা ইতিমধ্যেই শেষ হয়ে গেছে (Salvage Value অর্জিত)।');
        }

        DB::beginTransaction();
        try {
            $newBookValue = $asset->current_book_value - $actualDepreciation;
            $newAccumulated = $asset->accumulated_depreciation + $actualDepreciation;

            ErpAssetDepreciation::create([
                'asset_id' => $asset->id,
                'fiscal_year' => $request->fiscal_year,
                'depreciation_date' => now()->toDateString(),
                'method' => $asset->depreciation_method,
                'opening_value' => $asset->current_book_value,
                'depreciation_amount' => $actualDepreciation,
                'closing_value' => $newBookValue,
                'created_by' => auth()->id(),
            ]);

            $asset->update([
                'current_book_value' => $newBookValue,
                'accumulated_depreciation' => $newAccumulated,
            ]);

            DB::commit();
            return redirect()->back()->with('success', "অর্থবছর {$request->fiscal_year}-এর জন্য ৳" . number_format($actualDepreciation, 2) . " অবচয় হিসাব সফলভাবে সম্পন্ন হয়েছে। বর্তমান বুক ভ্যালু: ৳" . number_format($newBookValue, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'অবচয় গণনা ব্যর্থ: ' . $e->getMessage());
        }
    }

    public function storeDisposal(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:erp_assets,id',
            'disposal_date' => 'required|date',
            'reason' => 'required|string',
            'sale_value' => 'nullable|numeric|min:0',
        ]);

        $asset = ErpAsset::findOrFail($request->asset_id);
        $saleVal = $request->sale_value ?? 0;
        $gainLoss = $saleVal - $asset->current_book_value;

        DB::beginTransaction();
        try {
            ErpAssetDisposal::create([
                'asset_id' => $asset->id,
                'disposal_date' => $request->disposal_date,
                'reason' => $request->reason,
                'original_cost' => $asset->purchase_price,
                'accumulated_depreciation' => $asset->accumulated_depreciation,
                'book_value_at_disposal' => $asset->current_book_value,
                'sale_value' => $saleVal,
                'gain_loss' => $gainLoss,
                'approved_by' => auth()->user()->name ?? 'Administrator',
                'remarks' => $request->remarks,
            ]);

            $asset->update([
                'status' => 'disposed',
                'condition' => 'damaged',
            ]);

            DB::commit();
            $glMsg = $gainLoss >= 0 ? 'মুনাফা: ৳' . number_format($gainLoss, 2) : 'লোকসান: ৳' . number_format(abs($gainLoss), 2);
            return redirect()->back()->with('success', "সম্পদ নিষ্পত্তি/বিক্রয় (Disposal) সফলভাবে সম্পন্ন হয়েছে ({$glMsg})।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'নিষ্পত্তি ব্যর্থ: ' . $e->getMessage());
        }
    }
}

