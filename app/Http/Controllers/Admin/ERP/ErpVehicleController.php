<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ERP\ErpVehicle;
use App\Models\ERP\ErpVehicleDocument;
use App\Models\ERP\ErpVehicleTrip;
use App\Models\ERP\ErpVehicleFuel;

class ErpVehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = ErpVehicle::with(['documents', 'trips', 'fuels']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('vehicle_type')) {
            $query->where('vehicle_type', $request->vehicle_type);
        }

        $vehicles = $query->latest()->paginate(15);
        $totalVehicles = ErpVehicle::count();
        $activeVehicles = ErpVehicle::where('status', 'active')->count();
        $totalMileage = ErpVehicle::sum('current_mileage');
        $totalFuelCost = ErpVehicleFuel::sum('total_amount');

        return view('admin.erp.vehicles.index', compact(
            'vehicles',
            'totalVehicles',
            'activeVehicles',
            'totalMileage',
            'totalFuelCost'
        ));
    }

    public function storeVehicle(Request $request)
    {
        $request->validate([
            'vehicle_number' => 'required|string|unique:erp_vehicles,vehicle_number',
            'vehicle_type' => 'required|string',
            'brand' => 'required|string',
            'model' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
        ]);

        $vehicle = new ErpVehicle();
        $vehicle->vehicle_number = $request->vehicle_number;
        $vehicle->vehicle_type = $request->vehicle_type;
        $vehicle->brand = $request->brand;
        $vehicle->model = $request->model;
        $vehicle->manufacturing_year = $request->manufacturing_year;
        $vehicle->purchase_date = $request->purchase_date;
        $vehicle->purchase_price = $request->purchase_price ?? 0;
        $vehicle->chassis_no = $request->chassis_no;
        $vehicle->engine_no = $request->engine_no;
        $vehicle->fuel_type = $request->fuel_type ?? 'octane';
        $vehicle->current_mileage = $request->current_mileage ?? 0;
        $vehicle->driver_name = $request->driver_name;
        $vehicle->driver_phone = $request->driver_phone;
        $vehicle->department_name = $request->department_name;
        $vehicle->status = 'active';
        $vehicle->notes = $request->notes;

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'veh_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/vehicles'), $filename);
            $vehicle->photo = 'upload/erp/vehicles/' . $filename;
        }

        $vehicle->save();

        return redirect()->back()->with('success', 'গাড়ি সফলভাবে বহরে (Fleet) যোগ করা হয়েছে।');
    }

    public function storeDocument(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:erp_vehicles,id',
            'document_type' => 'required|string',
            'expiry_date' => 'required|date',
        ]);

        $doc = new ErpVehicleDocument();
        $doc->vehicle_id = $request->vehicle_id;
        $doc->document_type = $request->document_type;
        $doc->document_number = $request->document_number;
        $doc->issue_date = $request->issue_date;
        $doc->expiry_date = $request->expiry_date;
        $doc->alert_days = $request->alert_days ?? 15;
        $doc->status = $doc->isExpired() ? 'expired' : ($doc->isExpiringSoon() ? 'expiring_soon' : 'valid');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = 'vdoc_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/vehicle_docs'), $filename);
            $doc->file_path = 'upload/erp/vehicle_docs/' . $filename;
        }

        $doc->save();

        return redirect()->back()->with('success', 'গাড়ির কাগজপত্রের তথ্য সংরক্ষিত হয়েছে।');
    }

    public function tripsIndex()
    {
        $trips = ErpVehicleTrip::with('vehicle')->latest()->paginate(15);
        $vehicles = ErpVehicle::where('status', 'active')->get();

        return view('admin.erp.vehicles.trips', compact('trips', 'vehicles'));
    }

    public function storeTrip(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:erp_vehicles,id',
            'start_location' => 'required|string',
            'destination' => 'required|string',
            'trip_date' => 'required|date',
            'start_mileage' => 'required|numeric',
            'end_mileage' => 'required|numeric|gte:start_mileage',
        ]);

        $distance = $request->end_mileage - $request->start_mileage;
        $tripNo = 'TRIP-' . date('Ymd') . '-' . rand(100, 999);

        ErpVehicleTrip::create([
            'trip_number' => $tripNo,
            'vehicle_id' => $request->vehicle_id,
            'driver_name' => $request->driver_name,
            'start_location' => $request->start_location,
            'destination' => $request->destination,
            'trip_date' => $request->trip_date,
            'start_mileage' => $request->start_mileage,
            'end_mileage' => $request->end_mileage,
            'distance_km' => $distance,
            'purpose' => $request->purpose,
            'fuel_cost' => $request->fuel_cost ?? 0,
            'toll_cost' => $request->toll_cost ?? 0,
            'other_cost' => $request->other_cost ?? 0,
            'status' => 'completed',
            'approved_by' => auth()->user()->name ?? 'Administrator',
        ]);

        // Update vehicle current mileage
        $vehicle = ErpVehicle::find($request->vehicle_id);
        if ($vehicle && $request->end_mileage > $vehicle->current_mileage) {
            $vehicle->update(['current_mileage' => $request->end_mileage]);
        }

        return redirect()->back()->with('success', "ট্রিপ সফলভাবে সংরক্ষণ করা হয়েছে (দূরত্ব: {$distance} কি.মি.)।");
    }

    public function storeFuel(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:erp_vehicles,id',
            'fuel_date' => 'required|date',
            'fuel_type' => 'required|string',
            'quantity_liters' => 'required|numeric|min:0.1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $totalAmount = $request->quantity_liters * $request->unit_price;

        $fuel = new ErpVehicleFuel();
        $fuel->vehicle_id = $request->vehicle_id;
        $fuel->fuel_date = $request->fuel_date;
        $fuel->fuel_type = $request->fuel_type;
        $fuel->quantity_liters = $request->quantity_liters;
        $fuel->unit_price = $request->unit_price;
        $fuel->total_amount = $totalAmount;
        $fuel->odometer_reading = $request->odometer_reading ?? 0;
        $fuel->fuel_station = $request->fuel_station;
        $fuel->voucher_ref = $request->voucher_ref;

        if ($request->hasFile('slip_file')) {
            $file = $request->file('slip_file');
            $filename = 'fuel_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/erp/fuel_slips'), $filename);
            $fuel->slip_file = 'upload/erp/fuel_slips/' . $filename;
        }

        $fuel->save();

        return redirect()->back()->with('success', "জ্বালানি খরচ সফলভাবে রেকর্ড করা হয়েছে (মোট: ৳" . number_format($totalAmount, 2) . ")।");
    }
}

