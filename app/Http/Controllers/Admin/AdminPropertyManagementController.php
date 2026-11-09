<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPropertyManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('portal:/admin');
    }

    /**
     * Master Real Estate & Property Management Hub
     */
    public function index()
    {
        return view('admin.property_management.index');
    }

    /**
     * Properties List & Add Property
     */
    public function properties(Request $request)
    {
        return view('admin.property_management.properties');
    }

    /**
     * Property Units & Flats
     */
    public function units(Request $request)
    {
        return view('admin.property_management.units');
    }

    /**
     * Projects Management
     */
    public function projects(Request $request)
    {
        return view('admin.property_management.projects');
    }

    /**
     * Investment Offers & Campaigns
     */
    public function investments(Request $request)
    {
        return view('admin.property_management.investments');
    }

    /**
     * Investors Directory & KYC Verification Queue
     */
    public function investors(Request $request)
    {
        return view('admin.property_management.investors');
    }

    /**
     * Sales Bookings & Installments
     */
    public function salesBookings(Request $request)
    {
        return view('admin.property_management.sales_bookings');
    }

    /**
     * Profit & Loss Engine with Dual Maker-Checker
     */
    public function profit(Request $request)
    {
        return view('admin.property_management.profit');
    }

    /**
     * Accounting & Chart of Accounts
     */
    public function accounting(Request $request)
    {
        return view('admin.property_management.accounting');
    }

    /**
     * Payment Verification
     */
    public function payments(Request $request)
    {
        return view('admin.property_management.payments');
    }

    /**
     * Rental & Tenant Management
     */
    public function rentals(Request $request)
    {
        return view('admin.property_management.rentals');
    }

    /**
     * Land Owners & Joint Ventures
     */
    public function landOwners(Request $request)
    {
        return view('admin.property_management.land_owners');
    }

    /**
     * Brokers & Agents Network
     */
    public function brokers(Request $request)
    {
        return view('admin.property_management.brokers');
    }

    /**
     * Legal Document Vault
     */
    public function legalDocuments(Request $request)
    {
        return view('admin.property_management.legal_documents');
    }
}
