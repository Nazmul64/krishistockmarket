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

        // District filter
        if ($request->filled('district') && $request->input('district') !== 'all') {
            $district = trim($request->input('district'));
            $query->where('district', 'like', "%{$district}%");
        }

        // Price range filter
        if ($request->filled('max_price')) {
            $query->where('selling_price', '<=', (float) $request->input('max_price'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('selling_price', 'desc');
        } elseif ($sort === 'area_desc') {
            $query->orderBy('total_area', 'desc');
        } else {
            $query->latest();
        }

        $properties = $query->paginate(9)->withQueryString();
        $totalProperties = Property::where('status', '!=', 'draft')->count();
        $activeProjects = Project::where('status', 'active')->take(3)->get();
        $districts = Property::where('status', '!=', 'draft')
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->pluck('district');

        // Instant AJAX Real-time Search & Filter Response
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'status' => 'success',
                'total' => $properties->total(),
                'count_text' => $isBn ? ('সর্বমোট ' . $properties->total() . ' টি সাইট প্রদর্শিত হচ্ছে') : ('Showing ' . $properties->total() . ' total available sites'),
                'html' => view('frontend.properties.partials.property_grid', compact('properties', 'isBn'))->render(),
                'pagination' => $properties->hasPages() ? $properties->links()->render() : '',
            ]);
        }

        return view('frontend.properties.index', compact(
            'properties',
            'totalProperties',
            'activeProjects',
            'districts',
            'sort',
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
            'user_id' => Auth::id(),
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

        return redirect()->back()->with('success', "ধন্যবাদ! {$propName}-এর জন্য আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে (কোড: {$inquiryCode})। আমাদের প্রতিনিধি খুব শীঘ্রই যোগাযোগ করবেন।");
    }

    /**
     * Mobile REST API: List Properties
     */
    public function apiIndex(Request $request)
    {
        $query = Property::query();

        $query->where(function($q) {
            $q->where('status', 'available')
              ->orWhere('status', 'reserved')
              ->orWhere('status', 'published');
        });

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

        if ($request->filled('type') && $request->input('type') !== 'all') {
            $type = $request->input('type');
            $query->where('property_type_name', 'like', "%{$type}%");
        }

        if ($request->filled('district') && $request->input('district') !== 'all') {
            $district = trim($request->input('district'));
            $query->where('district', 'like', "%{$district}%");
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'price_asc') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('selling_price', 'desc');
        } else {
            $query->latest();
        }

        $properties = $query->paginate(20);

        $data = $properties->map(function($p) {
            $featImg = $p->featured_image;
            $imgUrl = null;
            if ($featImg) {
                $imgUrl = \Illuminate\Support\Str::startsWith($featImg, ['http://', 'https://']) 
                    ? $featImg 
                    : asset(ltrim($featImg, '/'));
            }

            $featuresList = [];
            if (!empty($p->features)) {
                if (is_array($p->features)) {
                    $featuresList = $p->features;
                } else {
                    $decoded = json_decode($p->features, true);
                    $featuresList = is_array($decoded) ? $decoded : array_map('trim', explode(',', $p->features));
                }
            }

            return [
                'id' => $p->id,
                'property_name' => $p->property_name,
                'property_code' => $p->property_code,
                'property_type_name' => $p->property_type_name,
                'division' => $p->division,
                'district' => $p->district,
                'upazila' => $p->upazila,
                'mouza' => $p->mouza,
                'village' => $p->village,
                'dag_number' => $p->dag_number,
                'khatian_number' => $p->khatian_number,
                'jl_number' => $p->jl_number,
                'total_area' => (float)$p->total_area,
                'measurement_unit' => $p->measurement_unit,
                'selling_price' => (float)$p->selling_price,
                'booking_amount' => (float)$p->booking_amount,
                'current_valuation' => (float)$p->current_valuation,
                'featured_image' => $imgUrl,
                'description' => $p->description,
                'features' => $featuresList,
                'status' => $p->status,
                'is_featured' => (bool)$p->is_featured,
            ];
        })->values()->all();

        return response()->json([
            'status' => 'success',
            'total' => $properties->total(),
            'current_page' => $properties->currentPage(),
            'last_page' => $properties->lastPage(),
            'properties' => $data,
        ]);
    }

    /**
     * Mobile REST API: Property Detail
     */
    public function apiShow($id)
    {
        $property = Property::where('id', $id)
            ->orWhere('property_code', $id)
            ->first();

        if (!$property) {
            return response()->json(['status' => 'error', 'message' => 'Property not found'], 404);
        }

        $featImg = $property->featured_image;
        $imgUrl = null;
        if ($featImg) {
            $imgUrl = \Illuminate\Support\Str::startsWith($featImg, ['http://', 'https://']) 
                ? $featImg 
                : asset(ltrim($featImg, '/'));
        }

        $featuresList = [];
        if (!empty($property->features)) {
            if (is_array($property->features)) {
                $featuresList = $property->features;
            } else {
                $decoded = json_decode($property->features, true);
                $featuresList = is_array($decoded) ? $decoded : array_map('trim', explode(',', $property->features));
            }
        }

        $related = Property::where('id', '!=', $property->id)
            ->where('status', 'available')
            ->take(3)
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'property_name' => $p->property_name,
                    'property_code' => $p->property_code,
                    'property_type_name' => $p->property_type_name,
                    'district' => $p->district,
                    'total_area' => (float)$p->total_area,
                    'measurement_unit' => $p->measurement_unit,
                    'selling_price' => (float)$p->selling_price,
                    'featured_image' => $p->featured_image ? asset(ltrim($p->featured_image, '/')) : null,
                ];
            })->values()->all();

        return response()->json([
            'status' => 'success',
            'property' => [
                'id' => $property->id,
                'property_name' => $property->property_name,
                'property_code' => $property->property_code,
                'property_type_name' => $property->property_type_name,
                'division' => $property->division,
                'district' => $property->district,
                'upazila' => $property->upazila,
                'mouza' => $property->mouza,
                'village' => $property->village,
                'dag_number' => $property->dag_number,
                'khatian_number' => $property->khatian_number,
                'jl_number' => $property->jl_number,
                'total_area' => (float)$property->total_area,
                'measurement_unit' => $property->measurement_unit,
                'purchase_price' => (float)$property->purchase_price,
                'selling_price' => (float)$property->selling_price,
                'booking_amount' => (float)$property->booking_amount,
                'current_valuation' => (float)$property->current_valuation,
                'featured_image' => $imgUrl,
                'description' => $property->description,
                'features' => $featuresList,
                'status' => $property->status,
                'is_featured' => (bool)$property->is_featured,
            ],
            'related' => $related,
        ]);
    }

    /**
     * Mobile REST API: Submit Inquiry
     */
    public function apiSubmitInquiry(Request $request)
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

        $inquiryCode = 'INQ-' . strtoupper(\Illuminate\Support\Str::random(6)) . '-' . rand(100, 999);

        $inquiry = PropertyInquiry::create([
            'user_id' => auth()->id() ?? null,
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

        return response()->json([
            'status' => 'success',
            'message' => "ধন্যবাদ {$inquiry->customer_name}! আপনার আবেদনটি সফলভাবে গৃহীত হয়েছে (কোড: {$inquiryCode})।",
            'inquiry_code' => $inquiryCode,
        ]);
    }
}
