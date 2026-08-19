<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\HrmPerformance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HrmPerformanceController extends Controller
{
    public function index()
    {
        $performances = HrmPerformance::with(['user', 'reviewer'])->latest()->get();
        $employees = User::where('role', 'employee')->get();

        return view('admin.hrm.performance.index', compact('performances', 'employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'review_period' => 'required|string',
            'overall_rating' => 'required|numeric|min:1|max:5',
            'comments' => 'nullable|string',
        ]);

        HrmPerformance::create([
            'user_id' => $request->user_id,
            'reviewer_id' => Auth::id(),
            'review_period' => $request->review_period,
            'overall_rating' => $request->overall_rating,
            'comments' => $request->comments,
        ]);

        return back()->with('success', 'পারফরম্যান্স রিভিউ সফলভাবে সংরক্ষণ করা হয়েছে।');
    }
}
