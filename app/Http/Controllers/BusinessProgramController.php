<?php

namespace App\Http\Controllers;

use App\Models\BusinessApplication;
use App\Models\SuperShop\Branch;
use Illuminate\Http\Request;

class BusinessProgramController extends Controller
{
    /**
     * 1. Investor Application Page
     */
    public function investor()
    {
        return view('frontend.business.investor');
    }

    public function submitInvestor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'profession' => 'nullable|string|max:255',
            'amount' => 'required|string|max:100',
            'remarks' => 'nullable|string|max:1000',
        ]);

        BusinessApplication::create([
            'type' => 'investor',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'profession' => $validated['profession'] ?? null,
            'amount' => $validated['amount'],
            'remarks' => $validated['remarks'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার বিনিয়োগ আবেদন সফলভাবে জমা হয়েছে! আমাদের টিম খুব শীঘ্রই আপনার সাথে যোগাযোগ করবে।');
    }

    /**
     * 2. Wholesale Application Page
     */
    public function wholesale()
    {
        return view('frontend.business.wholesale');
    }

    public function submitWholesale(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'profession' => 'nullable|string|max:255',
            'quantity' => 'nullable|string|max:255',
        ]);

        BusinessApplication::create([
            'type' => 'wholesale',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'profession' => $validated['profession'] ?? null,
            'quantity' => $validated['quantity'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার হোলসেল আবেদন সফলভাবে জমা হয়েছে! আমাদের পাইকারি সেলস টিম শীঘ্রই যোগাযোগ করবে।');
    }

    /**
     * 3. Franchise Application Page
     */
    public function franchise()
    {
        return view('frontend.business.franchise');
    }

    public function submitFranchise(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'division' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'upazila' => 'required|string|max:100',
            'showroom_size' => 'required|string|max:100',
            'showroom_location' => 'required|string|max:255',
        ]);

        BusinessApplication::create([
            'type' => 'franchise',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'division' => $validated['division'],
            'district' => $validated['district'],
            'upazila' => $validated['upazila'],
            'showroom_size' => $validated['showroom_size'],
            'showroom_location' => $validated['showroom_location'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার ফ্র্যাঞ্চাইজি শোরুম আবেদন সফলভাবে জমা হয়েছে! আমাদের বিজনেস ডেভেলপমেন্ট টিম শীঘ্রই আপনার সাথে বৈঠক করবে।');
    }

    /**
     * 4. Umrah / Haj Free Application Page
     */
    public function umrahHaj()
    {
        return view('frontend.business.umrah_haj');
    }

    public function submitUmrahHaj(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'whatsapp_number' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|max:500',
            'profession' => 'required|string|max:255',
            'age' => 'required|string|max:50',
            'marital_status' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:50',
            'previous_pilgrimage' => 'nullable|string|max:100',
            'has_passport' => 'required|string|max:50',
            'selection_reason' => 'required|string|max:1000',
            'is_financially_capable' => 'required|string|max:50',
            'source_info' => 'nullable|string|max:255',
        ]);

        BusinessApplication::create([
            'type' => 'umrah_haj',
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'whatsapp_number' => $validated['whatsapp_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'],
            'profession' => $validated['profession'],
            'age' => $validated['age'],
            'marital_status' => $validated['marital_status'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'previous_pilgrimage' => $validated['previous_pilgrimage'] ?? null,
            'has_passport' => $validated['has_passport'],
            'selection_reason' => $validated['selection_reason'],
            'is_financially_capable' => $validated['is_financially_capable'],
            'source_info' => $validated['source_info'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'আপনার ফ্রি উমরাহ/হজ নিবন্ধন সফলভাবে গৃহীত হয়েছে! জাজাকাল্লাহু খাইরান।');
    }

    /**
     * 5. Outlets Page (All branches showcase with live search & map)
     */
    public function outlets(Request $request)
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

        $outlets = $query->orderBy('sort_order', 'asc')->orderBy('id', 'asc')->get();

        return view('frontend.business.outlets', compact('outlets'));
    }
}
