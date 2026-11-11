<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminCareerApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = CareerApplication::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('position_applied', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(15);

        $counts = [
            'total' => CareerApplication::count(),
            'pending' => CareerApplication::where('status', 'pending')->count(),
            'reviewed' => CareerApplication::where('status', 'reviewed')->count(),
            'shortlisted' => CareerApplication::where('status', 'shortlisted')->count(),
            'rejected' => CareerApplication::where('status', 'rejected')->count(),
        ];

        return view('admin.career.index', compact('applications', 'counts'));
    }

    public function show($id)
    {
        $application = CareerApplication::findOrFail($id);

        if ($application->status === 'pending') {
            $application->update(['status' => 'reviewed']);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $application->id,
                'full_name' => $application->full_name,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'phone' => $application->phone,
                'email' => $application->email,
                'address' => $application->address,
                'position_applied' => $application->position_applied,
                'cover_letter' => $application->cover_letter,
                'status' => $application->status,
                'resume_url' => asset($application->resume),
                'created_at' => $application->created_at->format('d M, Y h:i A'),
            ]
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $application = CareerApplication::findOrFail($id);
        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes ?? $application->admin_notes,
        ]);

        return redirect()->back()->with('success', 'আবেদনকারীর স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $application = CareerApplication::findOrFail($id);

        if ($application->resume && File::exists(public_path($application->resume))) {
            File::delete(public_path($application->resume));
        }

        $application->delete();

        return redirect()->back()->with('success', 'ক্যারিয়ার আবেদনটি সফলভাবে ডিলিট করা হয়েছে।');
    }
}

