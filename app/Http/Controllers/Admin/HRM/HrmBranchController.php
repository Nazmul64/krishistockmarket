<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmBranch;
use App\Models\User;
use Illuminate\Http\Request;

class HrmBranchController extends Controller
{
    public function index()
    {
        $branches = HrmBranch::with(['manager', 'employeeProfiles'])->latest()->get();
        $employees = User::where('role', 'employee')->orWhere('role', 'admin')->get();

        return view('admin.hrm.branches.index', compact('branches', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        HrmBranch::create([
            'name' => $request->name,
            'code' => $request->code,
            'manager_id' => $request->manager_id,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'status' => 'active',
        ]);

        return back()->with('success', 'ব্রাঞ্চ সফলভাবে তৈরি করা হয়েছে।');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'manager_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $branch = HrmBranch::findOrFail($id);
        $branch->update($request->only(['name', 'code', 'manager_id', 'phone', 'email', 'address', 'status']));

        return back()->with('success', 'ব্রাঞ্চ সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $branch = HrmBranch::findOrFail($id);
        $branch->delete();

        return back()->with('success', 'ব্রাঞ্চ সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
