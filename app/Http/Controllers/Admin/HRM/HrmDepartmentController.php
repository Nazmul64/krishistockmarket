<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmDepartment;
use App\Models\User;
use Illuminate\Http\Request;

class HrmDepartmentController extends Controller
{
    public function index()
    {
        $departments = HrmDepartment::with(['head', 'designations', 'employeeProfiles'])->latest()->get();
        $employees = User::where('role', 'employee')->orWhere('role', 'admin')->get();

        return view('admin.hrm.departments.index', compact('departments', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'head_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
        ]);

        HrmDepartment::create([
            'name' => $request->name,
            'code' => $request->code,
            'head_id' => $request->head_id,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return back()->with('success', 'ডিপার্টমেন্ট সফলভাবে তৈরি করা হয়েছে।');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'head_id' => 'nullable|exists:users,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $department = HrmDepartment::findOrFail($id);
        $department->update($request->only(['name', 'code', 'head_id', 'description', 'status']));

        return back()->with('success', 'ডিপার্টমেন্ট সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $department = HrmDepartment::findOrFail($id);
        $department->delete();

        return back()->with('success', 'ডিপার্টমেন্ট সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
