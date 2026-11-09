<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPropertyManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/admin');
    }

    /**
     * Master Real Estate & Property Management Hub
     */
    public function index()
    {
        return view('admin.property_management.index');
    }

    /**
     * Properties List & Add Property
     */
    public function properties(Request $request)
    {
        $query = \App\Models\Property::query();
        if ($request->filled('q')) {
            $q = trim($request->input('q'));
            $query->where(function($sub) use ($q) {
                $sub->where('property_name', 'like', "%{$q}%")
                    ->orWhere('property_code', 'like', "%{$q}%")
                    ->orWhere('dag_number', 'like', "%{$q}%")
                    ->orWhere('mouza', 'like', "%{$q}%");
            });
        }
        $properties = $query->latest()->paginate(15);
        return view('admin.property_management.properties', compact('properties'));
    }

    /**
     * Store new Property in Database
     */
    public function storeProperty(Request $request)
    {
        $request->validate([
            'property_name' => 'required|string|max:255',
            'property_code' => 'required|string|max:100|unique:properties,property_code',
            'property_type_name' => 'required|string',
            'division' => 'required|string',
            'district' => 'required|string',
            'upazila' => 'required|string',
            'total_area' => 'required|numeric',
            'measurement_unit' => 'required|string',
            'selling_price' => 'required|numeric',
        ]);

        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $destination = public_path('upload/properties');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $featuredImage = asset('upload/properties/' . $filename);
        }

        \App\Models\Property::create([
            'property_name' => $request->input('property_name'),
            'property_code' => $request->input('property_code'),
            'property_type_name' => $request->input('property_type_name'),
            'division' => $request->input('division'),
            'district' => $request->input('district'),
            'upazila' => $request->input('upazila'),
            'mouza' => $request->input('mouza'),
            'village' => $request->input('village'),
            'dag_number' => $request->input('dag_number'),
            'khatian_number' => $request->input('khatian_number'),
            'jl_number' => $request->input('jl_number'),
            'total_area' => $request->input('total_area'),
            'measurement_unit' => $request->input('measurement_unit'),
            'purchase_price' => $request->input('purchase_price', 0),
            'selling_price' => $request->input('selling_price', 0),
            'booking_amount' => $request->input('booking_amount', 0),
            'current_valuation' => $request->input('current_valuation', $request->input('selling_price', 0)),
            'description' => $request->input('description'),
            'features' => $request->input('features'),
            'status' => $request->input('status', 'available'),
            'is_featured' => $request->has('is_featured'),
            'featured_image' => $featuredImage ?: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&auto=format&fit=crop&q=60',
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'নতুন প্রপার্টি সফলভাবে ডাটাবেজে সংরক্ষিত হয়েছে এবং ফ্রন্টএন্ডে প্রদর্শিত হবে!');
    }

    /**
     * Property Units & Flats
     */
    public function units(Request $request)
    {
        return view('admin.property_management.units');
    }

    /**
     * Projects Management
     */
    public function projects(Request $request)
    {
        return view('admin.property_management.projects');
    }

    /**
     * Investment Offers & Campaigns
     */
    public function investments(Request $request)
    {
        return view('admin.property_management.investments');
    }

    /**
     * Investors Directory & KYC Verification Queue
     */
    public function investors(Request $request)
    {
        return view('admin.property_management.investors');
    }

    /**
     * Sales Bookings & Installments
     */
    public function salesBookings(Request $request)
    {
        return view('admin.property_management.sales_bookings');
    }

    /**
     * Profit & Loss Engine with Dual Maker-Checker
     */
    public function profit(Request $request)
    {
        return view('admin.property_management.profit');
    }

    /**
     * Accounting & Chart of Accounts
     */
    public function accounting(Request $request)
    {
        return view('admin.property_management.accounting');
    }

    /**
     * Payment Verification
     */
    public function payments(Request $request)
    {
        return view('admin.property_management.payments');
    }

    /**
     * Rental & Tenant Management
     */
    public function rentals(Request $request)
    {
        return view('admin.property_management.rentals');
    }

    /**
     * Land Owners & Joint Ventures
     */
    public function landOwners(Request $request)
    {
        return view('admin.property_management.land_owners');
    }

    /**
     * Brokers & Agents Network
     */
    public function brokers(Request $request)
    {
        return view('admin.property_management.brokers');
    }

    /**
     * Legal Document Vault
     */
    public function legalDocuments(Request $request)
    {
        return view('admin.property_management.legal_documents');
    }
}
