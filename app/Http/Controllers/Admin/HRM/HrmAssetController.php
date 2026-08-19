<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmCompanyAsset;
use App\Models\User;
use Illuminate\Http\Request;

class HrmAssetController extends Controller
{
    public function index()
    {
        $assets = HrmCompanyAsset::with('assignedUser')->latest()->get();
        $employees = User::where('role', 'employee')->get();

        return view('admin.hrm.assets.index', compact('assets', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'asset_code' => 'required|string|unique:hrm_company_assets,asset_code',
            'category' => 'required|string',
            'value' => 'nullable|numeric|min:0',
        ]);

        HrmCompanyAsset::create([
            'name' => $request->name,
            'asset_code' => $request->asset_code,
            'category' => $request->category,
            'serial_number' => $request->serial_number,
            'purchase_date' => $request->purchase_date,
            'value' => $request->value ?? 0,
            'condition' => $request->condition ?? 'good',
            'assigned_user_id' => $request->assigned_user_id,
            'assigned_date' => $request->assigned_user_id ? date('Y-m-d') : null,
            'status' => $request->assigned_user_id ? 'assigned' : 'available',
        ]);

        return back()->with('success', 'নতুন অ্যাসেট সফলভাবে যুক্ত করা হয়েছে।');
    }

    public function assign(Request $request, $id)
    {
        $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
        ]);

        $asset = HrmCompanyAsset::findOrFail($id);
        $asset->update([
            'assigned_user_id' => $request->assigned_user_id,
            'assigned_date' => date('Y-m-d'),
            'status' => 'assigned',
        ]);

        return back()->with('success', 'অ্যাসেট কর্মকর্তাকে অর্পণ করা হয়েছে।');
    }
}
