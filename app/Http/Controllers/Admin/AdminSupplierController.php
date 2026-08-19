<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierPayment;
use App\Models\SupplierProfile;
use App\Models\SupplierSupply;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSupplierController extends Controller
{
    public function index()
    {
        $suppliers = User::where('role', 'supplier')->with('supplierProfile', 'supplies', 'supplierPayments')->get();

        // Calculate combined stats
        $totalSuppliers = $suppliers->count();
        $totalSupplyAmount = 0;
        $totalPaidAmount = 0;
        $totalOpeningBalance = 0;

        foreach ($suppliers as $supplier) {
            $op = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;
            $supAmount = $supplier->supplies->where('status', 'approved')->sum('total_amount');
            $paidAmount = $supplier->supplierPayments->sum('amount');

            $totalOpeningBalance += $op;
            $totalSupplyAmount += $supAmount;
            $totalPaidAmount += $paidAmount;
        }

        $totalDue = ($totalOpeningBalance + $totalSupplyAmount) - $totalPaidAmount;

        return view('admin.suppliers.index', compact(
            'suppliers',
            'totalSuppliers',
            'totalSupplyAmount',
            'totalPaidAmount',
            'totalDue'
        ));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'district_thana' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'opening_balance' => 'nullable|numeric|min:0',
            'password' => 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $userCount = User::where('role', 'supplier')->count();
            $supplierCode = 'SUP-' . str_pad($userCount + 1, 4, '0', STR_PAD_LEFT);
            $username = 'sup_' . time();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email ?? ($username . '@ikrishiporibar.com'),
                'username' => $username,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'supplier',
            ]);

            SupplierProfile::create([
                'user_id' => $user->id,
                'supplier_code' => $supplierCode,
                'company_name' => $request->company_name,
                'district_thana' => $request->district_thana,
                'address' => $request->address,
                'opening_balance' => $request->opening_balance ?? 0,
                'opening_date' => Carbon::now()->toDateString(),
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('admin.suppliers.index')->with('success', 'সাপ্লায়ার অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে!');
    }

    public function show($id, Request $request)
    {
        $supplier = User::where('role', 'supplier')->with(['supplierProfile', 'supplies', 'supplierPayments'])->findOrFail($id);

        $openingBalance = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;

        $approvedSuppliesQuery = SupplierSupply::where('supplier_id', $id)->where('status', 'approved');
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $approvedSuppliesQuery->whereBetween('supply_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('product_name')) {
            $approvedSuppliesQuery->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        $approvedSupplies = $approvedSuppliesQuery->get();

        $paymentsQuery = SupplierPayment::where('supplier_id', $id);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $paymentsQuery->whereBetween('payment_date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('payment_method')) {
            $paymentsQuery->where('payment_method', $request->payment_method);
        }
        $payments = $paymentsQuery->get();

        $totalSupply = $approvedSupplies->sum('total_amount');
        $totalPaid = $payments->sum('amount');
        $cashPaid = $payments->where('payment_method', 'cash')->sum('amount');
        $bankPaid = $payments->where('payment_method', 'bank')->sum('amount');

        $currentBalance = ($openingBalance + $totalSupply) - $totalPaid;

        // Build Chronological Statement Ledger
        $transactions = collect();

        if ($openingBalance > 0) {
            $transactions->push([
                'date' => $supplier->supplierProfile->opening_date ?? $supplier->created_at->toDateString(),
                'type' => 'Opening Balance',
                'description' => 'প্রারম্ভিক জের (Opening Balance)',
                'debit' => $openingBalance,
                'credit' => 0,
                'ref' => '-',
            ]);
        }

        foreach ($approvedSupplies as $sup) {
            $transactions->push([
                'date' => $sup->supply_date,
                'type' => 'Product Supply',
                'description' => $sup->product_name . ' (' . floatval($sup->quantity) . ' ' . $sup->unit . ' @ ৳' . number_format($sup->rate, 2) . ')',
                'debit' => $sup->total_amount,
                'credit' => 0,
                'ref' => 'Challan: ' . $sup->invoice_no,
                'id' => $sup->id,
            ]);
        }

        foreach ($payments as $pay) {
            $methodText = strtoupper($pay->payment_method) . ($pay->bank_name ? ' - ' . $pay->bank_name : '');
            $transactions->push([
                'date' => $pay->payment_date,
                'type' => 'Payment',
                'description' => 'পরিশোধ (' . $methodText . ')' . ($pay->transaction_id ? ' TxID: ' . $pay->transaction_id : ''),
                'debit' => 0,
                'credit' => $pay->amount,
                'ref' => 'Pay ID: #' . $pay->id,
            ]);
        }

        $sortedTransactions = $transactions->sortBy('date')->values();

        // Calculate running balance
        $runningBalance = 0;
        $ledger = $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += ($item['debit'] - $item['credit']);
            $item['balance'] = $runningBalance;
            return $item;
        });

        // Product-wise Summary
        $productSummary = $approvedSupplies->groupBy('product_name')->map(function ($items, $name) {
            $totalQty = $items->sum('quantity');
            $totalAmt = $items->sum('total_amount');
            $unit = $items->first()->unit ?? '';
            $avgRate = $totalQty > 0 ? ($totalAmt / $totalQty) : 0;

            return [
                'product_name' => $name,
                'total_quantity' => $totalQty,
                'unit' => $unit,
                'avg_rate' => $avgRate,
                'total_amount' => $totalAmt,
            ];
        });

        return view('admin.suppliers.show', compact(
            'supplier',
            'openingBalance',
            'totalSupply',
            'totalPaid',
            'cashPaid',
            'bankPaid',
            'currentBalance',
            'ledger',
            'productSummary',
            'approvedSupplies'
        ));
    }

    public function pendingSupplies()
    {
        $supplies = SupplierSupply::with('supplier.supplierProfile')->orderBy('created_at', 'desc')->get();
        return view('admin.suppliers.pending_supplies', compact('supplies'));
    }

    public function approveSupply($id)
    {
        $supply = SupplierSupply::findOrFail($id);
        $supply->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'পণ্য সরবরাহ সফলভাবে অনুমোদন করা হয়েছে!');
    }

    public function rejectSupply($id)
    {
        $supply = SupplierSupply::findOrFail($id);
        $supply->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('error', 'পণ্য সরবরাহ প্রত্যাখান করা হয়েছে!');
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank',
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'nullable|required_if:payment_method,bank|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        SupplierPayment::create([
            'supplier_id' => $request->supplier_id,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'transaction_id' => $request->transaction_id,
            'note' => $request->note,
            'created_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'পেমেন্ট সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    public function reports(Request $request)
    {
        $suppliers = User::where('role', 'supplier')->get();

        $query = SupplierSupply::with('supplier.supplierProfile');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('product_name')) {
            $query->where('product_name', 'like', '%' . $request->product_name . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('supply_date', [$request->start_date, $request->end_date]);
        }

        $supplies = $query->orderBy('supply_date', 'desc')->get();

        return view('admin.suppliers.reports', compact('supplies', 'suppliers'));
    }

    public function printStatement($id)
    {
        $supplier = User::where('role', 'supplier')->with(['supplierProfile', 'supplies', 'supplierPayments'])->findOrFail($id);

        $openingBalance = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;
        $approvedSupplies = SupplierSupply::where('supplier_id', $id)->where('status', 'approved')->orderBy('supply_date', 'asc')->get();
        $payments = SupplierPayment::where('supplier_id', $id)->orderBy('payment_date', 'asc')->get();

        $totalSupply = $approvedSupplies->sum('total_amount');
        $totalPaid = $payments->sum('amount');
        $currentBalance = ($openingBalance + $totalSupply) - $totalPaid;

        $transactions = collect();

        if ($openingBalance > 0) {
            $transactions->push([
                'date' => $supplier->supplierProfile->opening_date ?? $supplier->created_at->toDateString(),
                'type' => 'Opening Balance',
                'description' => 'প্রারম্ভিক জের (Opening Balance)',
                'debit' => $openingBalance,
                'credit' => 0,
                'ref' => '-',
            ]);
        }

        foreach ($approvedSupplies as $sup) {
            $transactions->push([
                'date' => $sup->supply_date,
                'type' => 'Product Supply',
                'description' => $sup->product_name . ' (' . floatval($sup->quantity) . ' ' . $sup->unit . ' @ ৳' . number_format($sup->rate, 2) . ')',
                'debit' => $sup->total_amount,
                'credit' => 0,
                'ref' => 'Challan: ' . $sup->invoice_no,
            ]);
        }

        foreach ($payments as $pay) {
            $methodText = strtoupper($pay->payment_method) . ($pay->bank_name ? ' - ' . $pay->bank_name : '');
            $transactions->push([
                'date' => $pay->payment_date,
                'type' => 'Payment',
                'description' => 'পরিশোধ (' . $methodText . ')' . ($pay->transaction_id ? ' TxID: ' . $pay->transaction_id : ''),
                'debit' => 0,
                'credit' => $pay->amount,
                'ref' => 'Pay ID: #' . $pay->id,
            ]);
        }

        $sortedTransactions = $transactions->sortBy('date')->values();
        $runningBalance = 0;
        $ledger = $sortedTransactions->map(function ($item) use (&$runningBalance) {
            $runningBalance += ($item['debit'] - $item['credit']);
            $item['balance'] = $runningBalance;
            return $item;
        });

        return view('admin.suppliers.print_statement', compact('supplier', 'openingBalance', 'totalSupply', 'totalPaid', 'currentBalance', 'ledger'));
    }

    public function printInvoice($id)
    {
        $supply = SupplierSupply::with('supplier.supplierProfile')->findOrFail($id);
        return view('admin.suppliers.print_invoice', compact('supply'));
    }
}
