<?php

namespace App\Http\Controllers\Admin\ERP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ERP\ErpItem;
use App\Models\ERP\ErpStockTransaction;
use App\Models\ERP\ErpAsset;
use App\Models\ERP\ErpVehicle;
use App\Models\ERP\ErpVehicleTrip;
use App\Models\ERP\ErpVehicleFuel;
use App\Models\ERP\ErpMaintenanceRequest;
use App\Models\ERP\ErpGeneralLedgerEntry;
use App\Models\ERP\ErpCashBankAccount;
use App\Models\ERP\ErpCompanyProfile;

class ErpReportsController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->get('type', 'stock_valuation');
        $company = ErpCompanyProfile::first() ?? new ErpCompanyProfile();

        $data = [];

        if ($reportType === 'stock_valuation') {
            $data['items'] = ErpItem::with(['category', 'unit', 'defaultWarehouse'])->get();
            $data['totalValuation'] = ErpItem::selectRaw('SUM(current_stock * purchase_price) as total_val')->value('total_val') ?? 0;
            $data['totalQuantity'] = ErpItem::sum('current_stock');
        } elseif ($reportType === 'asset_register') {
            $data['assets'] = ErpAsset::with('category')->get();
            $data['totalPurchase'] = ErpAsset::sum('purchase_price');
            $data['totalBookValue'] = ErpAsset::sum('current_book_value');
            $data['totalDepreciation'] = ErpAsset::sum('accumulated_depreciation');
        } elseif ($reportType === 'vehicle_expenses') {
            $data['vehicles'] = ErpVehicle::with(['trips', 'fuels', 'maintenances'])->get();
            $data['totalFuelCost'] = ErpVehicleFuel::sum('total_amount');
            $data['totalMaintenanceCost'] = ErpMaintenanceRequest::whereNotNull('vehicle_id')->sum('total_cost');
        } elseif ($reportType === 'audit_trail') {
            $data['transactions'] = ErpStockTransaction::with(['item', 'warehouse'])->latest()->take(50)->get();
        }

        return view('admin.erp.reports.index', compact('reportType', 'data', 'company'));
    }
}

