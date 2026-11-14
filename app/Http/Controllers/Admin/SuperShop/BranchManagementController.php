<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchUser;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\BranchInventory;
use App\Models\Shop\ShopProduct;
use App\Models\User;
use Illuminate\Support\Str;

class BranchManagementController extends Controller
{
    /**
     * Display all branches.
     */
    public function index(Request $request)
    {
        $branches = Branch::with(['manager', 'posCounters', 'employees'])
            ->withCount(['inventories', 'posSales'])
            ->orderBy('id', 'asc')
            ->get();

        $users = User::all();

        return view('admin.supershop.branches.index', compact('branches', 'users'));
    }

    /**
     * Store a new branch.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:branches,code',
            'type' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
        ]);

        $branch = Branch::create([
            'name' => $request->name,
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type ?: 'super_shop',
            'manager_id' => $request->manager_id ?: null,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'division' => $request->division ?: 'Dhaka',
            'district' => $request->district ?: 'Dhaka',
            'area' => $request->area,
            'opening_time' => $request->opening_time ?: '08:00:00',
            'closing_time' => $request->closing_time ?: '22:30:00',
            'status' => $request->status ?: 'active',
        ]);

        // Automatically create Counter 01 for this new branch
        PosCounter::create([
            'branch_id' => $branch->id,
            'counter_name' => 'Counter 01',
            'counter_code' => $branch->code . '-C01',
            'status' => 'active',
        ]);

        // Auto-assign existing products with initial default stock if requested
        if ($request->has('assign_all_products')) {
            $products = ShopProduct::all();
            foreach ($products as $p) {
                BranchInventory::create([
                    'branch_id' => $branch->id,
                    'product_id' => $p->id,
                    'stock_quantity' => 50,
                    'reorder_level' => 10,
                    'safety_stock' => 5,
                    'purchase_price' => round($p->price * 0.8, 2),
                    'is_active' => true,
                ]);
            }
        }

        return back()->with('success', "নতুন শাখা '{$branch->name}' ({$branch->code}) সফলভাবে তৈরি করা হয়েছে!");
    }

    /**
     * Update an existing branch.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
        ]);

        $branch->update([
            'name' => $request->name,
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type ?: $branch->type,
            'manager_id' => $request->manager_id ?: $branch->manager_id,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'division' => $request->division,
            'district' => $request->district,
            'area' => $request->area,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'status' => $request->status ?: 'active',
        ]);

        return back()->with('success', "শাখা '{$branch->name}' এর তথ্য সফলভাবে আপডেট করা হয়েছে!");
    }

    /**
     * Deactivate or Delete branch safely.
     */
    public function destroy($id)
    {
        $branch = Branch::withCount('posSales')->findOrFail($id);

        // If the branch has sales history, never delete it; archive/deactivate it as per SRS section 3.4
        if ($branch->pos_sales_count > 0) {
            $branch->status = 'inactive';
            $branch->save();
            return back()->with('warning', "শাখাটির বিক্রয় রেকর্ড থাকায় স্থায়ীভাবে ডিলিট না করে সফলভাবে নিষ্ক্রিয় (Inactive/Archived) করা হয়েছে।");
        }

        $branch->delete();
        return back()->with('success', 'শাখাটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}

