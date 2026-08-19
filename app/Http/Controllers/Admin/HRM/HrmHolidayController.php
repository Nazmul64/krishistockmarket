<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmHoliday;
use Illuminate\Http\Request;

class HrmHolidayController extends Controller
{
    public function index()
    {
        $holidays = HrmHoliday::latest()->get();

        return view('admin.hrm.holidays.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        HrmHoliday::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'active',
        ]);

        return back()->with('success', 'ছুটির দিন সফলভাবে তৈরি করা হয়েছে।');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $holiday = HrmHoliday::findOrFail($id);
        $holiday->update($request->only(['name', 'start_date', 'end_date', 'type', 'description', 'status']));

        return back()->with('success', 'ছুটির দিন সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $holiday = HrmHoliday::findOrFail($id);
        $holiday->delete();

        return back()->with('success', 'ছুটির দিন সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
