<?php

namespace App\Http\Controllers\Admin\SuperShop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperShop\Branch;
use App\Models\SuperShop\BranchUser;
use App\Models\SuperShop\PosCounter;
use App\Models\SuperShop\PosSale;
use App\Models\SuperShop\BranchInventory;
use App\Models\Shop\ShopProduct;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BranchManagementController extends Controller
{
    /**
     * Display all branches with real-time sales accounting, manager credentials, and catalog metrics.
     */
    public function index(Request $request)
    {
        $branches = Branch::with(['manager', 'posCounters', 'employees'])
            ->withCount(['inventories', 'posSales'])
            ->orderBy('id', 'asc')
            ->get();

        $totalEcomProducts = ShopProduct::where('is_active', true)->count();

        // Calculate per-branch real-time sales accounting metrics
        foreach ($branches as $branch) {
            // Today's total completed sales revenue for this branch
            $branch->today_sales = (float) PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', today())
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            // Lifetime total completed sales revenue for this branch
            $branch->total_sales = (float) PosSale::where('branch_id', $branch->id)
                ->where('sale_status', 'completed')
                ->sum('grand_total');

            // Today's total invoices count
            $branch->today_invoices = PosSale::where('branch_id', $branch->id)
                ->whereDate('sale_time', today())
                ->where('sale_status', 'completed')
                ->count();

            // Total catalog products active in this branch
            $branch->catalog_products_count = $totalEcomProducts;
        }

        // Central Aggregate KPIs across all branches
        $totalBranchesCount = $branches->count();
        $todayAllBranchesSales = (float) PosSale::whereDate('sale_time', today())
            ->where('sale_status', 'completed')
            ->sum('grand_total');
        $totalAllBranchesSales = (float) PosSale::where('sale_status', 'completed')
            ->sum('grand_total');
        $activeBranchesCount = $branches->where('status', 'active')->count();

        $users = User::orderBy('name', 'asc')->get();

        return view('admin.supershop.branches.index', compact(
            'branches',
            'users',
            'totalBranchesCount',
            'todayAllBranchesSales',
            'totalAllBranchesSales',
            'activeBranchesCount',
            'totalEcomProducts'
        ));
    }

    /**
     * Store a new branch with picture upload, manager login credentials, and initial catalog mapping.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:branches,code',
            'type' => 'required|string',
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
            'manager_username' => 'nullable|string|max:100|unique:users,username',
            'manager_password' => 'nullable|string|min:6',
        ]);

        // 1. Handle Branch Picture / Logo Upload
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'branch_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('upload/images/branches');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $logoPath = 'upload/images/branches/' . $filename;
        }

        // 2. Create Branch record
        $branch = Branch::create([
            'name' => $request->name,
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type ?: 'super_shop',
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'division' => $request->division ?: 'Dhaka',
            'district' => $request->district ?: 'Dhaka',
            'area' => $request->area,
            'opening_time' => $request->opening_time ?: '08:00:00',
            'closing_time' => $request->closing_time ?: '22:30:00',
            'logo' => $logoPath,
            'status' => $request->status ?: 'active',
        ]);

        // 3. Handle Branch Manager Account (Create New or Assign Existing)
        $managerId = $request->manager_id;
        if ($request->filled('manager_username') && $request->filled('manager_password')) {
            // Create brand-new Manager User with login credentials
            $managerUser = User::create([
                'name' => $request->manager_name ?: ($branch->name . ' Manager'),
                'username' => trim($request->manager_username),
                'email' => $request->manager_email ?: (trim($request->manager_username) . '@ekrishisheba.com'),
                'phone' => $request->manager_phone ?: $branch->phone,
                'password' => Hash::make($request->manager_password),
                'role' => 'manager',
                'user_type' => 'manager',
                'status' => 'active',
                'is_active' => true,
                'branch_id' => $branch->id,
            ]);
            $managerId = $managerUser->id;
        } elseif ($managerId) {
            // Assign existing user as manager and link branch_id
            User::where('id', $managerId)->update([
                'branch_id' => $branch->id,
                'role' => 'manager',
            ]);
        }

        if ($managerId) {
            $branch->manager_id = $managerId;
            $branch->save();
        }

        // 4. Automatically create Counter 01 for this new branch
        PosCounter::create([
            'branch_id' => $branch->id,
            'counter_name' => 'Counter 01',
            'counter_code' => $branch->code . '-C01',
            'status' => 'active',
        ]);

        // 5. Auto-assign existing products with initial default stock if requested
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

        $credMsg = ($request->filled('manager_username'))
            ? " | ম্যানেজার লগইন ইউজারনেম: '{$request->manager_username}'"
            : "";

        return back()->with('success', "নতুন শাখা '{$branch->name}' ({$branch->code}) সফলভাবে তৈরি করা হয়েছে{$credMsg}!");
    }

    /**
     * Update an existing branch including picture and manager settings.
     */
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:200',
            'code' => 'required|string|max:50|unique:branches,code,' . $branch->id,
            'phone' => 'nullable|string|max:30',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:3072',
        ]);

        $updateData = [
            'name' => $request->name,
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type ?: $branch->type,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'division' => $request->division,
            'district' => $request->district,
            'area' => $request->area,
            'opening_time' => $request->opening_time,
            'closing_time' => $request->closing_time,
            'status' => $request->status ?: 'active',
        ];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'branch_' . time() . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('upload/images/branches');
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $filename);
            $updateData['logo'] = 'upload/images/branches/' . $filename;
        }

        // Handle Manager assignment
        if ($request->filled('manager_id')) {
            $updateData['manager_id'] = $request->manager_id;
            User::where('id', $request->manager_id)->update(['branch_id' => $branch->id, 'role' => 'manager']);
        }

        $branch->update($updateData);

        return back()->with('success', "শাখা '{$branch->name}' এর তথ্য সফলভাবে আপডেট করা হয়েছে!");
    }

    /**
     * Reset or update manager password for a branch.
     */
    public function resetManagerPassword(Request $request, $id)
    {
        $branch = Branch::with('manager')->findOrFail($id);

        $request->validate([
            'new_password' => 'required|string|min:6',
        ]);

        if (!$branch->manager) {
            return back()->with('error', "এই শাখায় কোনো ম্যানেজার অ্যাসাইন করা নেই। প্রথমে একজন ম্যানেজার অ্যাসাইন করুন।");
        }

        $branch->manager->password = Hash::make($request->new_password);
        $branch->manager->save();

        return back()->with('success', "শাখা '{$branch->name}' এর ম্যানেজার '{$branch->manager->username}' এর পাসওয়ার্ড সফলভাবে রিসেট করা হয়েছে!");
    }

    /**
     * Deactivate or Delete branch safely.
     */
    public function destroy($id)
    {
        $branch = Branch::withCount('posSales')->findOrFail($id);

        // If the branch has sales history, never delete it; archive/deactivate it
        if ($branch->pos_sales_count > 0) {
            $branch->status = 'inactive';
            $branch->save();
            return back()->with('warning', "শাখাটির বিক্রয় রেকর্ড থাকায় স্থায়ীভাবে ডিলিট না করে সফলভাবে নিষ্ক্রিয় (Inactive/Archived) করা হয়েছে।");
        }

        $branch->delete();
        return back()->with('success', 'শাখাটি সফলভাবে মুছে ফেলা হয়েছে!');
    }
}
