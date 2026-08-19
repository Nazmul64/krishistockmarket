<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmAnnouncement;
use App\Models\HRM\HrmDepartment;
use Illuminate\Http\Request;

class HrmAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = HrmAnnouncement::with('targetDepartment')->latest()->get();
        $departments = HrmDepartment::where('status', 'active')->get();

        return view('admin.hrm.announcements.index', compact('announcements', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'publish_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        HrmAnnouncement::create([
            'title' => $request->title,
            'description' => $request->description,
            'target_department_id' => $request->target_department_id,
            'publish_date' => $request->publish_date ?? date('Y-m-d'),
            'expiry_date' => $request->expiry_date,
            'status' => 'active',
        ]);

        return back()->with('success', 'নোটিশ/ঘোষণা সফলভাবে প্রকাশ করা হয়েছে।');
    }
}
