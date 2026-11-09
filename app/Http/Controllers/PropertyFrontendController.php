<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyInquiry;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PropertyFrontendController extends Controller
{
    /**
     * Public Properties & Land Showcase Catalog
     */
    public function index(Request $request)
    {
        $siteTheme = $request->cookie('ekrishi_theme', 'light');
        $siteLang = $request->cookie('ekrishi_lang', 'en');
        $isBn = ($siteLang === 'bn');

        $query = Property::query();

        // Status filter (exclude drafts)
        $query->where(function($q) {
            $q->where('status', 'available')
              ->orWhere('status', 'reserved')
              ->orWhere('status', 'published');
        });

        // Search filter
        if ($request->filled('q')) {
            $search = trim($request->input('q'));
            $query->where(function($q) use ($search) {
                $q->where('property_name', 'like', "%{$search}%")
                  ->orWhere('property_code', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhere('upazila', 'like', "%{$search}%")
                  ->orWhere('mouza', 'like', "%{$search}%")
                  ->orWhere('dag_number', 'like', "%{$search}%")
                  ->orWhere('khatian_number', 'like', "%{$search}%");
            });
        }

        // Category/Type filter
        if ($request->filled('type') && $request->input('type') !== 'all') {
            $type = $request->input('type');
            $query->where('property_type_name', 'like', "%{$type}%");
        }

        // Price range filter
        if ($request->filled('max_price')) {
            $query->where('selling_price', '<=', (float) $request->input('max_price'));
        }

        $properties = $query->latest()->paginate(9)->withQueryString();
        $totalProperties = Property::where('status', '!=', 'draft')->count();
        $activeProjects = Project::where('status', 'active')->take(3)->get();

        return view('frontend.properties.index', compact(
            'properties',
            'totalProperties',
            'activeProjects',
            'isBn',
            'siteTheme'
        ));
    }

    /**
     * Single Property Details View
     */
    public function show($id, Request $request)
    {
        $siteTheme = $request->cookie('ekrishi_theme', 'light');
        $siteLang = $request->cookie('ekrishi_lang', 'en');
        $isBn = ($siteLang === 'bn');

        $property = Property::where('id', $id)
            ->orWhere('property_code', $id)
            ->firstOrFail();

        $relatedProperties = Property::where('id', '!=', $property->id)
            ->where('status', 'available')
            ->take(3)
            ->get();

        return view('frontend.properties.show', compact(
            'property',
            'relatedProperties',
            'isBn',
            'siteTheme'
        ));
    }

    /**
     * Submit Booking or Site Visit Request from Customer
     */
    public function submitInquiry(Request $request)
    {
        $request->validate([
            'property_id' => 'nullable|exists:properties,id',
            'customer_name' => 'required|string|max:150',
            'customer_phone' => 'required|string|max:30',
            'customer_email' => 'nullable|email|max:100',
            'inquiry_type' => 'required|string|in:site_visit,booking_request,price_inquiry',
            'preferred_date' => 'nullable|date',
            'message' => 'nullable|string|max:1000',
        ]);

        $inquiryCode = 'INQ-' . strtoupper(Str::random(6)) . '-' . rand(100, 999);

        $inquiry = PropertyInquiry::create([
            'property_id' => $request->input('property_id'),
            'inquiry_code' => $inquiryCode,
            'customer_name' => trim($request->input('customer_name')),
            'customer_phone' => trim($request->input('customer_phone')),
            'customer_email' => $request->input('customer_email'),
            'inquiry_type' => $request->input('inquiry_type'),
            'preferred_date' => $request->input('preferred_date'),
            'message' => $request->input('message'),
            'status' => 'pending',
        ]);

        $property = $inquiry->property;
        $propName = $property ? $property->property_name : 'জমি ও প্রপার্টি';

        if ($request->wantsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'status' => 'success',
                'message' => "ধন্যবাদ {$inquiry->customer_name}! আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে (ট্র্যাকিং কোড: {$inquiryCode})। আমাদের টিম দ্রুত আপনার সাথে যোগাযোগ করবে।",
                'inquiry' => $inquiry,
                'inquiry_code' => $inquiryCode
            ]);
        }
                'inquiry' => $inquiry
            ]);
        }

        return redirect()->back()->with('success', "ধন্যবাদ! {$propName}-এর জন্য আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে (কোড: {$inquiryCode})। আমাদের প্রতিনিধি খুব শীঘ্রই যোগাযোগ করবেন।");
    }
}
