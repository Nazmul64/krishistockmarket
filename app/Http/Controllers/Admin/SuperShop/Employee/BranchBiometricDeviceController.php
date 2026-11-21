<?php

namespace App\Http\Controllers\Admin\SuperShop\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SuperShop\Branch;
use App\Models\Employee\AttendanceDevice;
use App\Models\Employee\AttendanceDeviceLog;
use App\Models\Employee\EmployeeActivityLog;
use App\Services\BranchSecurityService;
use App\Services\BiometricAttendanceService;

class BranchBiometricDeviceController extends Controller
{
    /**
     * Biometric Attendance Machines List & Hub (Section 8.1)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $query = AttendanceDevice::with('branch')->withCount('logs');
        $query = BranchSecurityService::applyBranchScope($query, 'branch_id', $user);

        $devices = $query->orderBy('id', 'desc')->get();

        $branches = $isAdmin ? Branch::where('status', 'active')->get() : Branch::where('id', $userBranchId)->get();

        $recentLogs = AttendanceDeviceLog::with(['device', 'branch'])
            ->orderBy('id', 'desc');
        $recentLogs = BranchSecurityService::applyBranchScope($recentLogs, 'branch_id', $user)
            ->take(20)
            ->get();

        return view('admin.supershop.employees.devices.index', [
            'devices' => $devices,
            'branches' => $branches,
            'recentLogs' => $recentLogs,
            'isAdmin' => $isAdmin,
            'userBranchId' => $userBranchId,
        ]);
    }

    /**
     * Store New Biometric Machine Configuration (Section 8.1 & 8.3)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $isAdmin = BranchSecurityService::isAdmin($user);
        $userBranchId = BranchSecurityService::getUserBranchId($user);

        $branchId = $isAdmin ? ($request->input('branch_id') ?: $userBranchId) : $userBranchId;
        BranchSecurityService::enforceBranchAccess($branchId);

        $request->validate([
            'device_name' => 'required|string|max:255',
            'device_id' => 'required|string|unique:attendance_devices,device_id',
            'ip_address' => 'required|string|max:100',
            'port' => 'required|integer',
        ]);

        $device = AttendanceDevice::create([
            'branch_id' => $branchId,
            'device_name' => $request->input('device_name'),
            'device_id' => $request->input('device_id'),
            'serial_number' => $request->input('serial_number'),
            'model' => $request->input('model', 'ZKTeco Multi-Biometric'),
            'device_type' => $request->input('device_type', 'zkteco'),
            'ip_address' => $request->input('ip_address'),
            'port' => $request->input('port', 4370),
            'connection_type' => $request->input('connection_type', 'LAN / TCP-IP'),
            'api_protocol' => $request->input('api_protocol', 'TCP/IP'),
            'username' => $request->input('username', 'admin'),
            'password_encrypted' => $request->filled('password') ? bcrypt($request->input('password')) : null,
            'time_zone' => $request->input('time_zone', 'Asia/Dhaka'),
            'sync_interval_minutes' => $request->input('sync_interval_minutes', 15),
            'status' => 'disconnected',
            'is_active' => true,
        ]);

        EmployeeActivityLog::log(
            'Attendance Device Added',
            'device',
            $branchId,
            null,
            "নতুন বায়োমেট্রিক মেশিন কনফিগারেশন যোগ করা হয়েছে: {$device->device_name} (ID: {$device->device_id}, IP: {$device->ip_address})"
        );

        return back()->with('success', "বায়োমেট্রিক ডিভাইস '{$device->device_name}' সফলভাবে সংরক্ষিত হয়েছে!");
    }

    /**
     * Update Biometric Machine Configuration
     */
    public function update(Request $request, $id)
    {
        $device = AttendanceDevice::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($device->branch_id);

        $request->validate([
            'device_name' => 'required|string|max:255',
            'ip_address' => 'required|string|max:100',
            'port' => 'required|integer',
        ]);

        $device->update($request->only([
            'device_name', 'serial_number', 'model', 'device_type',
            'ip_address', 'port', 'connection_type', 'api_protocol',
            'username', 'time_zone', 'sync_interval_minutes', 'is_active'
        ]));

        if ($request->filled('password')) {
            $device->password_encrypted = bcrypt($request->input('password'));
            $device->save();
        }

        EmployeeActivityLog::log(
            'Attendance Device Updated',
            'device',
            $device->branch_id,
            null,
            "বায়োমেট্রিক মেশিন কনফিগারেশন আপডেট করা হয়েছে: {$device->device_name}"
        );

        return back()->with('success', 'বায়োমেট্রিক ডিভাইস কনফিগারেশন সফলভাবে আপডেট হয়েছে!');
    }

    /**
     * Test Connection to Biometric Device (Section 8.1 & 8.3)
     */
    public function testConnection($id)
    {
        $device = AttendanceDevice::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($device->branch_id);

        $res = BiometricAttendanceService::testConnection($device);

        if ($res['success']) {
            return back()->with('success', $res['message']);
        } else {
            return back()->with('error', $res['message']);
        }
    }

    /**
     * Trigger Manual Sync from Biometric Device (Section 8.2)
     */
    public function syncNow($id)
    {
        $device = AttendanceDevice::findOrFail($id);
        BranchSecurityService::enforceBranchAccess($device->branch_id);

        $res = BiometricAttendanceService::syncDevice($device);

        if ($res['success']) {
            return back()->with('success', $res['message']);
        } else {
            return back()->with('error', 'সিঙ্ক ব্যর্থ হয়েছে: ' . ($res['message'] ?? 'অজ্ঞাত ত্রুটি'));
        }
    }

    /**
     * API Webhook: Biometric Device Cloud Push Endpoint (Section 8.2)
     */
    public function pushWebhook(Request $request)
    {
        $deviceId = $request->input('device_id') ?: $request->header('X-Device-Id');
        if (!$deviceId) {
            return response()->json(['status' => 'error', 'message' => 'Device ID required'], 400);
        }

        $device = AttendanceDevice::where('device_id', $deviceId)->where('is_active', true)->first();
        if (!$device) {
            return response()->json(['status' => 'error', 'message' => 'Device not registered'], 404);
        }

        $punches = $request->input('punches', []);
        if (empty($punches) && $request->filled('employee_id')) {
            $punches = [
                [
                    'employee_id' => $request->input('employee_id'),
                    'punch_time' => $request->input('punch_time', now()->toDateTimeString()),
                    'punch_state' => $request->input('punch_state', 'check_in'),
                    'verify_type' => $request->input('verify_type', 'fingerprint'),
                ]
            ];
        }

        $res = BiometricAttendanceService::syncDevice($device, $punches);

        return response()->json([
            'status' => 'success',
            'data' => $res,
        ]);
    }
}
