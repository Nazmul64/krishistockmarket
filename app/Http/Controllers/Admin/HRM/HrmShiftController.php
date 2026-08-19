<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmShift;
use Illuminate\Http\Request;

class HrmShiftController extends Controller
{
    public function index()
    {
        $shifts = HrmShift::withCount('employeeProfiles')->latest()->get();

        return view('admin.hrm.shifts.index', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_time_minutes' => 'nullable|numeric|min:0',
            'grace_time_minutes' => 'nullable|numeric|min:0',
        ]);

        HrmShift::create([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_time_minutes' => $request->break_time_minutes ?? 60,
            'grace_time_minutes' => $request->grace_time_minutes ?? 15,
            'overtime_enabled' => $request->has('overtime_enabled'),
            'status' => 'active',
        ]);

        return back()->with('success', 'শিফট সফলভাবে তৈরি করা হয়েছে।');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_time_minutes' => 'nullable|numeric|min:0',
            'grace_time_minutes' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $shift = HrmShift::findOrFail($id);
        $shift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_time_minutes' => $request->break_time_minutes ?? 60,
            'grace_time_minutes' => $request->grace_time_minutes ?? 15,
            'overtime_enabled' => $request->has('overtime_enabled'),
            'status' => $request->status,
        ]);

        return back()->with('success', 'শিফট সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $shift = HrmShift::findOrFail($id);
        $shift->delete();

        return back()->with('success', 'শিফট সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
