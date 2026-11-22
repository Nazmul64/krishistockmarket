<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BusinessApplication;
use App\Models\SuperShop\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessProgramApiController extends Controller
{
    /**
     * Get all active outlets
     */
    public function getOutlets(Request $request)
    {
        $query = Branch::where('status', 'active');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $outlets = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get()->map(function($o) {
            return [
                'id' => $o->id,
                'code' => $o->code,
                'name' => $o->name,
                'address' => $o->address,
                'phone' => $o->phone,
                'email' => $o->email,
                'area' => $o->area,
                'district' => $o->district,
                'division' => $o->division,
                'weekly_off_day' => $o->weekly_off_day ?: 'Daily Open',
                'open_days_text' => $o->open_days_text ?: 'Open 7 Days',
                'operating_days' => $o->operating_days ? json_decode($o->operating_days, true) : ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'],
                'google_map_url' => $o->google_map_url ?: 'https://maps.google.com/?q=' . urlencode($o->address),
            ];
        });

        return response()->json([
            'status' => true,
            'count' => $outlets->count(),
            'data' => $outlets
        ]);
    }

    /**
     * Submit Business Application from mobile app
     */
    public function submitApplication(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:investor,wholesale,franchise,umrah_haj',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $application = BusinessApplication::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Application submitted successfully',
            'data' => $application
        ], 201);
    }
}
