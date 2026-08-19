<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\SupplierPayment;
use App\Models\SupplierSupply;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierSupplyController extends Controller
{
    public function dashboard()
    {
        $supplierId = Auth::id();
        $user = User::with('supplierProfile')->findOrFail($supplierId);

        $supplies = SupplierSupply::where('supplier_id', $supplierId)->get();
        $approvedSupplies = $supplies->where('status', 'approved');
        $payments = SupplierPayment::where('supplier_id', $supplierId)->get();

        $totalSuppliesCount = $supplies->count();
        $approvedCount = $approvedSupplies->count();
        $pendingCount = $supplies->where('status', 'pending')->count();

        $totalQuantitySupplied = $approvedSupplies->sum('quantity');
        $totalSupplyAmount = $approvedSupplies->sum('total_amount');
        $totalPaidAmount = $payments->sum('amount');

        $openingBalance = $user->supplierProfile ? $user->supplierProfile->opening_balance : 0;
        $totalDue = ($openingBalance + $totalSupplyAmount) - $totalPaidAmount;

        $recentSupplies = $supplies->sortByDesc('created_at')->take(5);
        $recentPayments = $payments->sortByDesc('created_at')->take(5);

        return view('supplier.dashboard', compact(
            'user',
            'totalSuppliesCount',
            'approvedCount',
            'pendingCount',
            'totalQuantitySupplied',
            'totalSupplyAmount',
            'totalPaidAmount',
            'totalDue',
            'recentSupplies',
            'recentPayments'
        ));
    }

    public function index()
    {
        $supplies = SupplierSupply::where('supplier_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('supplier.supplies.index', compact('supplies'));
    }

    public function create()
    {
        return view('supplier.supplies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|max:50',
            'rate' => 'required|numeric|min:0.01',
            'supply_date' => 'required|date',
            'invoice_file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120',
            'note' => 'nullable|string',
        ]);

        $totalAmount = $request->quantity * $request->rate;
        $invoiceFilePath = null;

        if ($request->hasFile('invoice_file')) {
            $file = $request->file('invoice_file');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/invoices'), $fileName);
            $invoiceFilePath = 'upload/invoices/' . $fileName;
        }

        SupplierSupply::create([
            'supplier_id' => Auth::id(),
            'invoice_no' => $request->invoice_no,
            'product_name' => $request->product_name,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'rate' => $request->rate,
            'total_amount' => $totalAmount,
            'supply_date' => $request->supply_date,
            'invoice_file' => $invoiceFilePath,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('supplier.supplies.index')->with('success', 'পণ্য সরবরাহ এন্ট্রি সফলভাবে সম্পন্ন হয়েছে! অ্যাডমিন অনুমোদনের জন্য পেন্ডিং রয়েছে।');
    }

    public function statement()
    {
        $supplierId = Auth::id();
        $supplier = User::with(['supplierProfile'])->findOrFail($supplierId);

        $openingBalance = $supplier->supplierProfile ? $supplier->supplierProfile->opening_balance : 0;
        $approvedSupplies = SupplierSupply::where('supplier_id', $supplierId)->where('status', 'approved')->orderBy('supply_date', 'asc')->get();
        $payments = SupplierPayment::where('supplier_id', $supplierId)->orderBy('payment_date', 'asc')->get();

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

        return view('supplier.statement', compact('supplier', 'openingBalance', 'totalSupply', 'totalPaid', 'currentBalance', 'ledger'));
    }

    public function printInvoice($id)
    {
        $supply = SupplierSupply::where('supplier_id', Auth::id())->with('supplier.supplierProfile')->findOrFail($id);
        return view('admin.suppliers.print_invoice', compact('supply'));
    }
}
