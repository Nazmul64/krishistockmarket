<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyInquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPropertyController extends Controller
{
    /**
     * Display a listing of customer's property inquiries and site visits.
     */
    public function myBookings(Request $request)
    {
        $user = Auth::user();
        $userPhone = $user->phone;

        $query = PropertyInquiry::where(function($q) use ($user, $userPhone) {
            $q->where('user_id', $user->id);
            if (!empty($userPhone)) {
                $q->orWhere('customer_phone', $userPhone);
            }
        })->with('property');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('inquiry_type', $request->type);
        }

        $inquiries = $query->latest()->paginate(10)->withQueryString();

        // Customer Stats
        $baseQuery = PropertyInquiry::where(function($q) use ($user, $userPhone) {
            $q->where('user_id', $user->id);
            if (!empty($userPhone)) {
                $q->orWhere('customer_phone', $userPhone);
            }
        });

        $total_count = (clone $baseQuery)->count();
        $site_visits_count = (clone $baseQuery)->where('inquiry_type', 'site_visit')->count();
        $bookings_count = (clone $baseQuery)->where('inquiry_type', 'booking_request')->count();
        $confirmed_count = (clone $baseQuery)->whereIn('status', ['confirmed', 'scheduled'])->count();

        return view('users.properties.my_bookings', compact(
            'inquiries',
            'total_count',
            'site_visits_count',
            'bookings_count',
            'confirmed_count'
        ));
    }

    /**
     * Display customer's property shares & investments overview.
     */
    public function investments(Request $request)
    {
        $user = Auth::user();
        $activeProjects = Project::where('status', 'active')->get();
        $investmentProperties = Property::where('status', 'available')
            ->where('is_featured', 1)
            ->take(6)
            ->get();

        return view('users.properties.investments', compact(
            'activeProjects',
            'investmentProperties'
        ));
    }

    /**
     * Cancel an inquiry if still pending.
     */
    public function cancelInquiry(Request $request, $id)
    {
        $user = Auth::user();
        $userPhone = $user->phone;

        $inquiry = PropertyInquiry::where(function($q) use ($user, $userPhone) {
            $q->where('user_id', $user->id);
            if (!empty($userPhone)) {
                $q->orWhere('customer_phone', $userPhone);
            }
        })->where('id', $id)->firstOrFail();

        if ($inquiry->status === 'pending' || $inquiry->status === 'contacted') {
            $inquiry->status = 'cancelled';
            $inquiry->admin_notes = ($inquiry->admin_notes ? $inquiry->admin_notes . "\n" : '') . 'গ্রাহক দ্বারা বাতিল করা হয়েছে (' . now()->format('d M, Y h:i A') . ')';
            $inquiry->save();

            return redirect()->back()->with('success', 'আপনার আবেদনটি সফলভাবে বাতিল করা হয়েছে।');
        }

        return redirect()->back()->with('error', 'এই স্ট্যাটাসে আবেদন বাতিল করা সম্ভব নয়।');
    }
}

