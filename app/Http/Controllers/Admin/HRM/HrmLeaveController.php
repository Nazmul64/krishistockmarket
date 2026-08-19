<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmLeaveRequest;
use App\Models\HRM\HrmLeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrmLeaveController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status ?? 'all';
        $query = HrmLeaveRequest::with(['user', 'leaveType', 'approver']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $leaveRequests = $query->latest()->paginate(15);
        $leaveTypes = HrmLeaveType::where('status', 'active')->get();
        $employees = User::where('role', 'employee')->get();

        return view('admin.hrm.leave.index', compact('leaveRequests', 'leaveTypes', 'employees', 'status'));
    }

    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'days_allowed' => 'required|numeric|min:1',
        ]);

        HrmLeaveType::create([
            'name' => $request->name,
            'days_allowed' => $request->days_allowed,
            'status' => 'active',
        ]);

        return back()->with('success', 'ছুটির ধরণ সফলভাবে যোগ করা হয়েছে।');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,pending',
        ]);

        $leave = HrmLeaveRequest::findOrFail($id);
        $leave->update([
            'status' => $request->status,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'ছুটির আবেদন স্থিতি পরিবর্তন করা হয়েছে।');
    }
}
