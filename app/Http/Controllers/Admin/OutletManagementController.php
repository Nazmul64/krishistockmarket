<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuperShop\Branch;
use Illuminate\Http\Request;

class OutletManagementController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Branch::orderBy('sort_order', 'asc')->orderBy('id', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }

        $outlets = $query->paginate(25)->withQueryString();
        return view('admin.outlets.index', compact('outlets', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'weekly_off_day' => 'nullable|string|max:50',
            'open_days_text' => 'nullable|string|max:50',
            'google_map_url' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        Branch::create([
            'name' => $request->name,
            'code' => $request->code,
            'type' => 'super_shop',
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'area' => $request->area,
            'district' => $request->district,
            'division' => $request->division,
            'weekly_off_day' => $request->weekly_off_day ?: 'Daily Open',
            'open_days_text' => $request->open_days_text ?: 'Open 7 Days',
            'google_map_url' => $request->google_map_url,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?: 0,
        ]);

        return back()->with('success', 'নতুন আউটলেট সফলভাবে যুক্ত করা হয়েছে।');
    }

    public function update(Request $request, $id)
    {
        $outlet = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code,' . $outlet->id,
            'phone' => 'required|string|max:50',
            'address' => 'required|string|max:500',
            'weekly_off_day' => 'nullable|string|max:50',
            'open_days_text' => 'nullable|string|max:50',
            'google_map_url' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer',
        ]);

        $outlet->update([
            'name' => $request->name,
            'code' => $request->code,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'area' => $request->area,
            'district' => $request->district,
            'division' => $request->division,
            'weekly_off_day' => $request->weekly_off_day ?: 'Daily Open',
            'open_days_text' => $request->open_days_text ?: 'Open 7 Days',
            'google_map_url' => $request->google_map_url,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?: 0,
        ]);

        return back()->with('success', 'আউটলেটের তথ্য সফলভাবে আপডেট করা হয়েছে।');
    }

    public function destroy($id)
    {
        $outlet = Branch::findOrFail($id);
        $outlet->delete();

        return back()->with('success', 'আউটলেটটি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
