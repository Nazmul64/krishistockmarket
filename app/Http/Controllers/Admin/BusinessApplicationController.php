<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use Illuminate\Http\Request;

class BusinessApplicationController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $status = $request->get('status');
        $search = $request->get('search');

        $query = BusinessApplication::latest();

        if ($type && $type !== 'all') {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(20)->withQueryString();

        $counts = [
            'all' => BusinessApplication::count(),
            'investor' => BusinessApplication::where('type', 'investor')->count(),
            'wholesale' => BusinessApplication::where('type', 'wholesale')->count(),
            'franchise' => BusinessApplication::where('type', 'franchise')->count(),
            'umrah_haj' => BusinessApplication::where('type', 'umrah_haj')->count(),
            'pending' => BusinessApplication::where('status', 'pending')->count(),
        ];

        return view('admin.business_applications.index', compact('applications', 'type', 'status', 'search', 'counts'));
    }

    public function show($id)
    {
        $application = BusinessApplication::findOrFail($id);
        return response()->json($application);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,contacted,approved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $application = BusinessApplication::findOrFail($id);
        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'আবেদনের স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $application = BusinessApplication::findOrFail($id);
        $application->delete();

        return back()->with('success', 'আবেদনটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
