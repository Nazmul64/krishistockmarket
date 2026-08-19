<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyBazaarItem;
use App\Models\MonthlyBazaarOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class AdminMonthlyBazaarController extends Controller
{
    public function index()
    {
        $items = MonthlyBazaarItem::latest()->get();
        return view('admin.monthly_bazaar.index', compact('items'));
    }

    public function store(Request $request)
    {
        $isUnlimited = $request->has('is_unlimited') ? 1 : 0;

        $request->validate([
            'title' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => $isUnlimited ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $imageName = null;
        if ($request->hasFile('image')) {
            $uploadDir = base_path('public/upload/monthly_bazaar');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imageName = time() . Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->save($uploadDir . '/' . $imageName);
        }

        MonthlyBazaarItem::create([
            'title' => $request->title,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'quantity' => $isUnlimited ? ($request->quantity ?? 0) : $request->quantity,
            'is_unlimited' => $isUnlimited,
            'description' => $request->description,
            'image' => $imageName,
            'status' => 'active',
        ]);

        $notification = [
            'message' => 'Monthly Grocery Package Created Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function edit($id)
    {
        $item = MonthlyBazaarItem::findOrFail($id);
        return view('admin.monthly_bazaar.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $isUnlimited = $request->has('is_unlimited') ? 1 : 0;

        $request->validate([
            'title' => 'required|string|max:255',
            'package_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => $isUnlimited ? 'nullable|integer|min:0' : 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $item = MonthlyBazaarItem::findOrFail($id);
        $imageName = $item->image;

        if ($request->hasFile('image')) {
            $uploadDir = base_path('public/upload/monthly_bazaar');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            if ($item->image && file_exists($uploadDir . '/' . $item->image)) {
                @unlink($uploadDir . '/' . $item->image);
            }
            $imageName = time() . Str::random(5) . '.' . $request->file('image')->getClientOriginalExtension();
            Image::make($request->file('image'))->save($uploadDir . '/' . $imageName);
        }

        $item->update([
            'title' => $request->title,
            'package_name' => $request->package_name,
            'price' => $request->price,
            'quantity' => $isUnlimited ? ($request->quantity ?? 0) : $request->quantity,
            'is_unlimited' => $isUnlimited,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        $notification = [
            'message' => 'Monthly Grocery Package Updated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->route('admin.monthly_bazaar.index')->with($notification);
    }

    public function destroy($id)
    {
        $item = MonthlyBazaarItem::findOrFail($id);
        if ($item->image && file_exists(public_path('upload/monthly_bazaar/' . $item->image))) {
            @unlink(public_path('upload/monthly_bazaar/' . $item->image));
        }
        $item->delete();

        $notification = [
            'message' => 'Monthly Grocery Package Deleted Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // Orders Requests Management with Area & Agent Point Distribution
    public function orders(Request $request)
    {
        $query = MonthlyBazaarOrder::with(['user', 'item']);

        if ($request->filled('request_area')) {
            $query->where('request_area', $request->request_area);
        }

        if ($request->filled('agent_point')) {
            $query->where('agent_point', $request->agent_point);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        // Area-wise Demand & Agent Point Summary Aggregations (Section 3 & 6 requirement)
        $allOrders = MonthlyBazaarOrder::all();
        $areaSummaries = $allOrders->groupBy('request_area')->map(function ($group, $area) {
            return [
                'area' => $area ?: 'সাধারণ / উল্লেখহীন',
                'total_requests' => $group->count(),
                'total_quantity' => $group->sum('quantity'),
                'allocated_quantity' => $group->sum('allocated_quantity'),
                'total_amount' => $group->sum('total_price'),
            ];
        });

        $agentSummaries = $allOrders->groupBy('agent_point')->map(function ($group, $agent) {
            return [
                'agent_point' => $agent ?: 'অনির্ধারিত Agent Point',
                'total_requests' => $group->count(),
                'total_quantity' => $group->sum('quantity'),
                'allocated_quantity' => $group->sum('allocated_quantity'),
            ];
        });

        $areas = MonthlyBazaarOrder::whereNotNull('request_area')->distinct()->pluck('request_area');
        $agentPoints = MonthlyBazaarOrder::whereNotNull('agent_point')->distinct()->pluck('agent_point');

        return view('admin.monthly_bazaar.orders', compact('orders', 'areaSummaries', 'agentSummaries', 'areas', 'agentPoints'));
    }

    public function approveOrder($id)
    {
        $order = MonthlyBazaarOrder::findOrFail($id);
        $order->update([
            'status' => 'approved',
            'distribution_status' => 'allocated',
            'allocated_quantity' => $order->quantity,
        ]);

        // Update item sold and stock quantity
        $item = MonthlyBazaarItem::find($order->item_id);
        if ($item) {
            $item->increment('sold_quantity', $order->quantity);
            $item->decrement('quantity', $order->quantity);
        }

        $notification = [
            'message' => 'Monthly Grocery Order Approved & Allocated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    public function rejectOrder($id)
    {
        $order = MonthlyBazaarOrder::findOrFail($id);
        $order->update([
            'status' => 'rejected',
            'distribution_status' => 'rejected',
            'allocated_quantity' => 0,
        ]);

        $notification = [
            'message' => 'Monthly Grocery Order Rejected!',
            'alert-type' => 'warning'
        ];

        return redirect()->back()->with($notification);
    }

    public function updateAllocation(Request $request, $id)
    {
        $request->validate([
            'allocated_quantity' => 'required|integer|min:0',
            'distribution_status' => 'required|string',
            'collection_note' => 'nullable|string',
        ]);

        $order = MonthlyBazaarOrder::findOrFail($id);
        $order->update([
            'allocated_quantity' => $request->allocated_quantity,
            'distribution_status' => $request->distribution_status,
            'collection_note' => $request->collection_note,
        ]);

        $notification = [
            'message' => 'Distribution Allocation & Collection Instructions Updated!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // Area-wise Demand & Distribution Report (Section 6)
    public function distributionReports(Request $request)
    {
        $query = MonthlyBazaarOrder::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        if ($request->filled('request_area')) {
            $query->where('request_area', $request->request_area);
        }

        $allOrders = $query->get();

        // Group by Area & Agent Point
        $reportData = $allOrders->groupBy(function ($item) {
            return ($item->request_area ?: 'সাধারণ') . '___' . ($item->agent_point ?: 'Central Point');
        })->map(function ($group, $key) {
            list($area, $agent) = explode('___', $key);
            return [
                'area' => $area,
                'agent_point' => $agent,
                'total_cardholders' => $group->pluck('user_id')->unique()->count(),
                'total_requests' => $group->count(),
                'total_demand_qty' => $group->sum('quantity'),
                'allocated_qty' => $group->sum('allocated_quantity'),
                'distributed_qty' => $group->where('distribution_status', 'distributed')->sum('allocated_quantity'),
                'pending_qty' => $group->sum('quantity') - $group->sum('allocated_quantity'),
                'total_amount' => $group->sum('total_price'),
            ];
        });

        $areas = MonthlyBazaarOrder::whereNotNull('request_area')->distinct()->pluck('request_area');

        return view('admin.monthly_bazaar.reports', compact('reportData', 'areas'));
    }
}
