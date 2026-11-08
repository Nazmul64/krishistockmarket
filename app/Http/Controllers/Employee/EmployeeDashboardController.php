<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AgentStock;
use App\Models\AgentStockTransaction;
use App\Models\AuditLog;
use App\Models\HRM\HrmAnnouncement;
use App\Models\HRM\HrmAttendance;
use App\Models\OurPackage;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class EmployeeDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/employee');
    }

    public function index()
    {
        $employee = Auth::user();

        // Scope: Own Records
        $referralCount = count(function_exists('ReferalUser') ? ReferalUser($employee->id) : []);

        // Agent Live Stocks
        $agentStocks = AgentStock::where('agent_id', $employee->id)
            ->where('allocated_quantity', '>', 0)
            ->get();

        // Recent Transactions
        $recentTransactions = AgentStockTransaction::with('stock')
            ->where('agent_id', $employee->id)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        // Notices
        $announcements = class_exists(HrmAnnouncement::class)
            ? HrmAnnouncement::where('status', 'published')->orderBy('id', 'desc')->take(3)->get()
            : collect();

        $siteSetting = class_exists(SiteSetting::class) ? SiteSetting::first() : null;

        // Packages count
        $packagesCount = OurPackage::count();

        return view('employee.employee-dashboard', compact(
            'employee',
            'referralCount',
            'agentStocks',
            'recentTransactions',
            'announcements',
            'siteSetting',
            'packagesCount'
        ));
    }

    /**
     * View packages within employee portal.
     */
    public function packages()
    {
        $employee = Auth::user();
        if (!$employee->hasPermission('packages.view')) {
            abort(403, "Action Forbidden: 'packages.view' পারমিশন নেই।");
        }

        $packages = OurPackage::latest()->get();

        return view('employee.packages', compact('packages', 'employee'));
    }

    /**
     * Store new package image (Requires packages.upload_image or packages.create).
     */
    public function storePackage(Request $request)
    {
        $employee = Auth::user();

        if (!$employee->hasPermission('packages.upload_image') && !$employee->hasPermission('packages.create')) {
            AuditLog::log(
                'ACTION_FORBIDDEN',
                $employee,
                null,
                ['required_permission' => 'packages.upload_image'],
                "Employee {$employee->username} attempted unauthorized package image upload"
            );

            return back()->with('error', "Action Forbidden: 'packages.upload_image' পারমিশন নেই।");
        }

        $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $uploadDir = public_path('upload/our_packages');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $imageFile = $request->file('image');
        $imgName = time() . '_' . Str::random(6) . '.' . $imageFile->getClientOriginalExtension();

        if (class_exists('Intervention\Image\Facades\Image')) {
            try {
                Image::make($imageFile)->save($uploadDir . '/' . $imgName);
            } catch (\Exception $e) {
                $imageFile->move($uploadDir, $imgName);
            }
        } else {
            $imageFile->move($uploadDir, $imgName);
        }

        $pkg = OurPackage::create([
            'image'  => 'upload/our_packages/' . $imgName,
            'status' => 1,
        ]);

        AuditLog::log(
            'PACKAGE_IMAGE_UPLOADED',
            $pkg,
            null,
            ['uploaded_by' => $employee->id, 'image' => $pkg->image],
            "Package image uploaded by employee {$employee->name}"
        );

        return back()->with('success', 'প্যাকেজের ছবি সফলভাবে আপলোড করা হয়েছে!');
    }
}
