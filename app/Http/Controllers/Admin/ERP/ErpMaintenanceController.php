<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ERP\ErpMaintenanceRequest;
use App\Models\ERP\ErpMaintenanceSchedule;
use App\Models\ERP\ErpAsset;
use App\Models\ERP\ErpVehicle;

class ErpMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = ErpMaintenanceRequest::with(['asset', 'vehicle']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $requests = $query->latest()->paginate(15);
        $schedules = ErpMaintenanceSchedule::with(['asset', 'vehicle'])->latest()->get();
        $assets = ErpAsset::where('status', '!=', 'disposed')->get();
        $vehicles = ErpVehicle::where('status', 'active')->get();

        $totalRequests = ErpMaintenanceRequest::count();
        $pendingCount = ErpMaintenanceRequest::whereIn('status', ['pending', 'approved', 'in_progress'])->count();
        $completedCount = ErpMaintenanceRequest::where('status', 'completed')->count();
        $totalCost = ErpMaintenanceRequest::sum('total_cost');

        return view('admin.erp.maintenance.index', compact(
            'requests',
            'schedules',
            'assets',
            'vehicles',
            'totalRequests',
            'pendingCount',
            'completedCount',
            'totalCost'
        ));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'problem_description' => 'required|string',
            'request_date' => 'required|date',
            'priority' => 'required|string',
        ]);

        $reqNo = 'MNT-' . date('Ymd') . '-' . rand(100, 999);

        $mReq = ErpMaintenanceRequest::create([
            'request_number' => $reqNo,
            'asset_id' => $request->asset_id,
            'vehicle_id' => $request->vehicle_id,
            'department_name' => $request->department_name,
            'maintenance_type' => $request->maintenance_type ?? 'corrective',
            'priority' => $request->priority,
            'problem_description' => $request->problem_description,
            'requested_by' => $request->requested_by ?? auth()->user()->name,
            'request_date' => $request->request_date,
            'estimated_cost' => $request->estimated_cost ?? 0,
            'technician_name' => $request->technician_name,
            'service_provider' => $request->service_provider,
            'status' => 'pending',
        ]);

        // If an asset is selected, mark its status as under_maintenance
        if ($request->filled('asset_id')) {
            $asset = ErpAsset::find($request->asset_id);
            if ($asset) {
                $asset->update(['status' => 'under_maintenance', 'condition' => 'needs_repair']);
            }
        }

        return redirect()->back()->with('success', "মেরামত/সার্ভিসিং রিকোয়েস্ট সফলভাবে জমা হয়েছে (নম্বর: {$reqNo})।");
    }

    public function updateRequestStatus(Request $request, $id)
    {
        $mReq = ErpMaintenanceRequest::findOrFail($id);
        $action = $request->action; // approve, start, complete, reject

        if ($action === 'approve') {
            $mReq->update([
                'status' => 'approved',
                'technician_name' => $request->technician_name ?? $mReq->technician_name,
                'service_provider' => $request->service_provider ?? $mReq->service_provider,
            ]);
            return redirect()->back()->with('success', 'রিকোয়েস্ট অনুমোদন করা হয়েছে।');
        }

        if ($action === 'start') {
            $mReq->update(['status' => 'in_progress']);
            return redirect()->back()->with('success', 'মেরামত কাজ শুরু হয়েছে (In Progress)।');
        }

        if ($action === 'complete') {
            $sparePartsCost = $request->spare_parts_cost ?? 0;
            $laborCost = $request->labor_cost ?? 0;
            $totalCost = $sparePartsCost + $laborCost;

            $mReq->update([
                'status' => 'completed',
                'completion_date' => $request->completion_date ?? now()->toDateString(),
                'spare_parts_cost' => $sparePartsCost,
                'labor_cost' => $laborCost,
                'total_cost' => $totalCost,
                'remarks' => $request->remarks,
            ]);

            // Restore asset condition & status
            if ($mReq->asset_id) {
                $asset = ErpAsset::find($mReq->asset_id);
                if ($asset) {
                    $asset->update(['status' => 'available', 'condition' => 'good']);
                }
            }

            return redirect()->back()->with('success', "রক্ষণাবেক্ষণ সফলভাবে সম্পন্ন হয়েছে (মোট খরচ: ৳" . number_format($totalCost, 2) . ")।");
        }

        if ($action === 'reject') {
            $mReq->update(['status' => 'rejected', 'remarks' => $request->remarks]);
            return redirect()->back()->with('success', 'রিকোয়েস্ট বাতিল করা হয়েছে।');
        }

        return redirect()->back();
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:255',
            'next_due_date' => 'required|date',
            'interval_value' => 'required|numeric|min:1',
        ]);

        ErpMaintenanceSchedule::create([
            'asset_id' => $request->asset_id,
            'vehicle_id' => $request->vehicle_id,
            'service_name' => $request->service_name,
            'interval_type' => $request->interval_type ?? 'days',
            'interval_value' => $request->interval_value,
            'last_service_date' => $request->last_service_date,
            'next_due_date' => $request->next_due_date,
            'alert_before_days' => $request->alert_before_days ?? 7,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'নিয়মিত রক্ষণাবেক্ষণ সিডিউল সফলভাবে সংরক্ষণ করা হয়েছে।');
    }
}

