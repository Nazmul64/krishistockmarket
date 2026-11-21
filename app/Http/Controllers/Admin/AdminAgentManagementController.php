<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\AgentPointDispatch;
use App\Models\AgentPointDispatchItem;
use App\Models\AgentPointInventory;
use App\Models\AgentPointStockLedger;
use App\Models\MonthlyMarketRequest;
use App\Models\SupplierProfile;
use App\Models\SupplierSupply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminAgentManagementController extends Controller
{
    /**
     * Display all Agents and Agent Points with modern search, filters, and stats.
     */
    public function index(Request $request)
    {
        $query = AgentPoint::with(['agentUser', 'inventories'])
            ->withCount(['assignedCardHolders', 'dispatches', 'marketRequests']);

        // Search by keyword
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('agent_name', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%")
                  ->orWhere('area', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhereHas('agentUser', function ($uq) use ($search) {
                      $uq->where('email', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by District
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }

        $agents = $query->latest()->paginate(15)->withQueryString();

        // Calculate KPI Statistics
        $totalAgents = AgentPoint::count();
        $activeAgents = AgentPoint::where('status', 'active')->count();
        $inactiveAgents = AgentPoint::where('status', 'inactive')->count();
        $totalAssignedCardHolders = User::whereNotNull('assigned_agent_point_id')->count();
        $totalDistricts = AgentPoint::distinct('district')->whereNotNull('district')->count('district');

        $districts = AgentPoint::select('district')
            ->whereNotNull('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return view('admin.agents.index', compact(
            'agents',
            'totalAgents',
            'activeAgents',
            'inactiveAgents',
            'totalAssignedCardHolders',
            'totalDistricts',
            'districts'
        ));
    }

    /**
     * Show form to create new Agent and Agent Point.
     */
    public function create()
    {
        $nextCount = AgentPoint::count() + 1;
        $autoAgentId = 'AG-' . str_pad($nextCount, 3, '0', STR_PAD_LEFT);

        return view('admin.agents.create', compact('autoAgentId'));
    }

    /**
     * Store new Agent user and Agent Point.
     */
    public function store(Request $request)
    {
        $request->validate([
            'agent_point_name' => 'required|string|max:255',
            'agent_name'       => 'required|string|max:255',
            'company_name'     => 'nullable|string|max:255',
            'mobile_number'    => 'required|string|max:20',
            'alt_phone'        => 'nullable|string|max:20',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|string|min:6',
            'district'         => 'nullable|string|max:100',
            'thana_upazila'    => 'nullable|string|max:100',
            'area'             => 'required|string|max:255',
            'address'          => 'nullable|string',
            'opening_date'     => 'nullable|date',
            'status'           => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create Agent User Account
            $baseUsername = preg_replace('/[^a-zA-Z0-9_]/', '', explode('@', $request->email)[0]) ?: ('agent_' . time());
            $username = $baseUsername;
            if (User::where('username', $username)->exists()) {
                $username .= '_' . rand(100, 999);
            }

            $user = User::create([
                'name'      => $request->agent_name,
                'email'     => strtolower(trim($request->email)),
                'username'  => $username,
                'phone'     => $request->mobile_number,
                'alt_phone' => $request->alt_phone,
                'role'      => 'agent',
                'password'  => Hash::make($request->password),
                'status'    => $request->status,
            ]);

            // Auto Agent ID
            $nextCount = AgentPoint::count() + 1;
            $code = $request->agent_code ?: ('AG-' . str_pad($nextCount, 3, '0', STR_PAD_LEFT));

            // 2. Create Agent Point
            $agentPoint = AgentPoint::create([
                'code'           => $code,
                'name'           => $request->agent_point_name,
                'agent_name'     => $request->agent_name,
                'company_name'   => $request->company_name,
                'agent_user_id'  => $user->id,
                'area'           => $request->area,
                'district'       => $request->district,
                'thana_upazila'  => $request->thana_upazila,
                'address'        => $request->address,
                'contact_number' => $request->mobile_number,
                'alt_phone'      => $request->alt_phone,
                'status'         => $request->status,
                'capacity'       => $request->capacity ?? 500,
                'opening_date'   => $request->opening_date ?? now()->toDateString(),
            ]);

            // 3. Link Agent Point to User
            $user->assigned_agent_point_id = $agentPoint->id;
            $user->save();

            DB::commit();

            return redirect()->route('admin.agents.index')
                ->with('success', "নতুন এজেন্ট পয়েন্ট [{$agentPoint->name}] এবং এজেন্ট অ্যাকাউন্ট [{$user->name}] সফলভাবে তৈরি করা হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'এজেন্ট তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Show Agent & Point Profile, Stock, and Activity.
     */
    public function show($id)
    {
        $agentPoint = AgentPoint::with([
            'agentUser',
            'inventories',
            'assignedCardHolders' => function ($q) {
                $q->latest()->take(20);
            },
            'dispatches' => function ($q) {
                $q->with('items')->latest()->take(10);
            },
            'marketRequests' => function ($q) {
                $q->with('items')->latest()->take(10);
            }
        ])->withCount(['assignedCardHolders', 'dispatches', 'marketRequests'])->findOrFail($id);

        $totalStockAvailable = $agentPoint->inventories->sum('available_stock');
        $totalStockReceived = $agentPoint->inventories->sum('received_stock');
        $totalStockDistributed = $agentPoint->inventories->sum('distributed_stock');

        return view('admin.agents.show', compact(
            'agentPoint',
            'totalStockAvailable',
            'totalStockReceived',
            'totalStockDistributed'
        ));
    }

    /**
     * Show Edit form for Agent & Agent Point.
     */
    public function edit($id)
    {
        $agentPoint = AgentPoint::with('agentUser')->findOrFail($id);
        return view('admin.agents.edit', compact('agentPoint'));
    }

    /**
     * Update Agent and Agent Point.
     */
    public function update(Request $request, $id)
    {
        $agentPoint = AgentPoint::findOrFail($id);
        $user = $agentPoint->agentUser;

        $request->validate([
            'agent_point_name' => 'required|string|max:255',
            'agent_name'       => 'required|string|max:255',
            'company_name'     => 'nullable|string|max:255',
            'mobile_number'    => 'required|string|max:20',
            'alt_phone'        => 'nullable|string|max:20',
            'email'            => 'required|email|unique:users,email,' . ($user ? $user->id : 0),
            'password'         => 'nullable|string|min:6',
            'district'         => 'nullable|string|max:100',
            'thana_upazila'    => 'nullable|string|max:100',
            'area'             => 'required|string|max:255',
            'address'          => 'nullable|string',
            'opening_date'     => 'nullable|date',
            'status'           => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            // Update Agent Point
            $agentPoint->update([
                'name'           => $request->agent_point_name,
                'agent_name'     => $request->agent_name,
                'company_name'   => $request->company_name,
                'area'           => $request->area,
                'district'       => $request->district,
                'thana_upazila'  => $request->thana_upazila,
                'address'        => $request->address,
                'contact_number' => $request->mobile_number,
                'alt_phone'      => $request->alt_phone,
                'status'         => $request->status,
                'capacity'       => $request->capacity ?? $agentPoint->capacity,
                'opening_date'   => $request->opening_date ?? $agentPoint->opening_date,
            ]);

            // Update or create User
            if ($user) {
                $userData = [
                    'name'      => $request->agent_name,
                    'email'     => strtolower(trim($request->email)),
                    'phone'     => $request->mobile_number,
                    'alt_phone' => $request->alt_phone,
                    'status'    => $request->status,
                ];
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
                $user->update($userData);
            }

            DB::commit();

            return redirect()->route('admin.agents.index')
                ->with('success', "এজেন্ট পয়েন্ট [{$agentPoint->name}] সফলভাবে আপডেট করা হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'আপডেট করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Toggle Agent active/inactive status.
     */
    public function toggleStatus($id)
    {
        $point = AgentPoint::findOrFail($id);
        $newStatus = $point->status === 'active' ? 'inactive' : 'active';
        $point->status = $newStatus;
        $point->save();

        if ($point->agentUser) {
            $point->agentUser->status = $newStatus;
            $point->agentUser->save();
        }

        return redirect()->back()->with('success', "এজেন্ট পয়েন্ট [{$point->name}] এর স্ট্যাটাস '{$newStatus}' করা হয়েছে!");
    }

    /**
     * Reset Password for Agent.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $point = AgentPoint::findOrFail($id);
        if (!$point->agentUser) {
            return back()->with('error', 'এই পয়েন্টের সাথে কোনো ইউজার অ্যাকাউন্ট যুক্ত নেই!');
        }

        $point->agentUser->password = Hash::make($request->new_password);
        $point->agentUser->save();

        return back()->with('success', "এজেন্ট [{$point->agentUser->name}] এর পাসওয়ার্ড সফলভাবে রিসেট করা হয়েছে!");
    }

    /**
     * Delete Agent Point.
     */
    public function destroy($id)
    {
        $point = AgentPoint::findOrFail($id);
        $name = $point->name;

        if ($point->agentUser) {
            $point->agentUser->status = 'inactive';
            $point->agentUser->save();
        }

        $point->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', "এজেন্ট পয়েন্ট [{$name}] সফলভাবে মুছে ফেলা হয়েছে!");
    }

    /**
     * View to assign Card Holders to Agent Points.
     */
    public function assignCardHoldersView(Request $request)
    {
        $query = User::whereIn('role', ['customer', 'user'])
            ->with('assignedAgentPoint');

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('card_number', 'like', "%{$s}%")
                  ->orWhere('area', 'like', "%{$s}%")
                  ->orWhere('district', 'like', "%{$s}%");
            });
        }

        if ($request->filled('agent_point_id')) {
            if ($request->agent_point_id == 'unassigned') {
                $query->whereNull('assigned_agent_point_id');
            } else {
                $query->where('assigned_agent_point_id', $request->agent_point_id);
            }
        }

        $cardHolders = $query->paginate(20)->withQueryString();
        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();

        $totalCardHolders = User::whereIn('role', ['customer', 'user'])->count();
        $assignedCount = User::whereIn('role', ['customer', 'user'])->whereNotNull('assigned_agent_point_id')->count();
        $unassignedCount = $totalCardHolders - $assignedCount;

        return view('admin.agents.assign_cardholders', compact(
            'cardHolders',
            'agentPoints',
            'totalCardHolders',
            'assignedCount',
            'unassignedCount'
        ));
    }

    /**
     * Process bulk or single Card Holder assignment to an Agent Point.
     */
    public function assignCardHoldersStore(Request $request)
    {
        $request->validate([
            'agent_point_id' => 'required|exists:agent_points,id',
            'user_ids'       => 'required|array',
            'user_ids.*'     => 'exists:users,id',
        ]);

        $point = AgentPoint::findOrFail($request->agent_point_id);

        User::whereIn('id', $request->user_ids)->update([
            'assigned_agent_point_id' => $point->id,
        ]);

        $count = count($request->user_ids);
        return back()->with('success', "{$count} জন কার্ডহোল্ডারকে সফলভাবে [{$point->name}] পয়েন্টে অ্যাসাইন করা হয়েছে!");
    }

    /**
     * View for Product Distribution & Allocation from Suppliers or Central Warehouse to Agent Points.
     */
    public function supplyAllocationView(Request $request)
    {
        $dispatches = AgentPointDispatch::with(['agentPoint', 'supplier', 'items'])
            ->latest()
            ->paginate(15);

        $agentPoints = AgentPoint::where('status', 'active')->orderBy('name')->get();
        $suppliers = SupplierProfile::where('status', 'active')->orderBy('company_name')->get();
        $approvedSupplies = SupplierSupply::where('status', 'approved')->latest()->take(30)->get();

        return view('admin.agents.supply_allocation', compact(
            'dispatches',
            'agentPoints',
            'suppliers',
            'approvedSupplies'
        ));
    }

    /**
     * Create Dispatch/Allocation of products to an Agent Point (Option 1 Warehouse vs Option 2 Direct Supplier).
     */
    public function supplyAllocationStore(Request $request)
    {
        $request->validate([
            'agent_point_id'      => 'required|exists:agent_points,id',
            'supply_flow'         => 'required|in:central_warehouse,direct_agent_supply',
            'supplier_id'         => 'nullable|exists:supplier_profiles,id',
            'supplier_supply_id'  => 'nullable|exists:supplier_supplies,id',
            'vehicle_info'        => 'nullable|string|max:255',
            'driver_info'         => 'nullable|string|max:255',
            'driver_mobile'       => 'nullable|string|max:50',
            'dispatch_note'       => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.product_name'=> 'required|string|max:255',
            'items.*.unit'        => 'required|string|max:50',
            'items.*.quantity'    => 'required|numeric|min:0.1',
        ]);

        DB::beginTransaction();
        try {
            $dispatchCount = AgentPointDispatch::count() + 1;
            $dispatchNo = 'DSP-' . date('Ym') . '-' . str_pad($dispatchCount, 4, '0', STR_PAD_LEFT);

            $dispatch = AgentPointDispatch::create([
                'dispatch_no'        => $dispatchNo,
                'distribution_month' => date('F'),
                'distribution_year'  => (int) date('Y'),
                'agent_point_id'     => $request->agent_point_id,
                'supplier_id'        => $request->supplier_id,
                'supplier_supply_id' => $request->supplier_supply_id,
                'supply_flow'        => $request->supply_flow,
                'area'               => AgentPoint::find($request->agent_point_id)->area ?? '',
                'vehicle_info'       => $request->vehicle_info,
                'driver_info'        => $request->driver_info,
                'driver_mobile'      => $request->driver_mobile,
                'dispatch_note'      => $request->dispatch_note,
                'dispatch_date'      => now()->toDateString(),
                'dispatched_by'      => auth()->id(),
                'status'             => 'in_transit',
            ]);

            foreach ($request->items as $item) {
                if (!empty($item['product_name']) && !empty($item['quantity'])) {
                    AgentPointDispatchItem::create([
                        'dispatch_id'   => $dispatch->id,
                        'product_name'  => $item['product_name'],
                        'unit'          => $item['unit'] ?? 'kg',
                        'sent_quantity' => (float) $item['quantity'],
                        'received_quantity' => 0,
                        'short_quantity'    => 0,
                        'damaged_quantity'  => 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.agents.supply_allocation')
                ->with('success', "চালান নং [{$dispatchNo}] সফলভাবে ইস্যু করা হয়েছে এবং এজেন্ট পয়েন্টে পাঠানো হয়েছে!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'চালান তৈরিতে ত্রুটি: ' . $e->getMessage());
        }
    }
}
