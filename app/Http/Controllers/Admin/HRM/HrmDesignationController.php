<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\HrmDesignation;
use Illuminate\Http\Request;

class HrmDesignationController extends Controller
{
    public function index()
    {
        $designations = HrmDesignation::with('department')->latest()->get();
        $departments = HrmDepartment::where('status', 'active')->get();

        return view('admin.hrm.designations.index', compact('designations', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:hrm_departments,id',
            'description' => 'nullable|string',
        ]);

        HrmDesignation::create([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return back()->with('success', 'পদবী সফলভাবে তৈরি করা হয়েছে।');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:hrm_departments,id',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $designation = HrmDesignation::findOrFail($id);
        $designation->update($request->only(['name', 'department_id', 'description', 'status']));

        return back()->with('success', 'পদবী সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $designation = HrmDesignation::findOrFail($id);
        $designation->delete();

        return back()->with('success', 'পদবী সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
