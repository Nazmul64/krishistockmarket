<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AgentStock;
use App\Models\AgentStockTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeStockLedgerController extends Controller
{
    public function index(Request $request)
    {
        $agent_id = Auth::id();
        $agent_stocks = AgentStock::where('agent_id', $agent_id)->get();

        $query = AgentStockTransaction::where('agent_id', $agent_id);

        if ($request->filter == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($request->filter == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month)
                  ->whereYear('created_at', Carbon::now()->year);
        } elseif ($request->filter == 'year') {
            $query->whereYear('created_at', Carbon::now()->year);
        }

        $transactions = $query->latest()->get();

        $total_allocated = AgentStock::where('agent_id', $agent_id)->sum('allocated_quantity');
        $total_sold = AgentStock::where('agent_id', $agent_id)->sum('sold_quantity');
        $available_stock = $total_allocated - $total_sold;

        $today_sales = AgentStockTransaction::where('agent_id', $agent_id)
            ->where('transaction_type', 'sale')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        $monthly_sales = AgentStockTransaction::where('agent_id', $agent_id)
            ->where('transaction_type', 'sale')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        $yearly_sales = AgentStockTransaction::where('agent_id', $agent_id)
            ->where('transaction_type', 'sale')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_price');

        return view('employee.stock_ledger.index', compact(
            'agent_stocks',
            'transactions',
            'total_allocated',
            'total_sold',
            'available_stock',
            'today_sales',
            'monthly_sales',
            'yearly_sales'
        ));
    }

    public function sellStock(Request $request)
    {
        $request->validate([
            'agent_stock_id' => 'required|exists:agent_stocks,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:255',
            'customer_card_number' => 'nullable|string|max:255',
        ]);

        $agent_stock = AgentStock::where('id', $request->agent_stock_id)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $available = $agent_stock->allocated_quantity - $agent_stock->sold_quantity;
        if ($available < $request->quantity) {
            return redirect()->back()->with('error', 'পর্যাপ্ত স্টক নেই! আপনার লাইভ স্টক অবশিষ্ট: ' . $available . ' টি');
        }

        $total_price = $request->quantity * $request->unit_price;

        $agent_stock->increment('sold_quantity', $request->quantity);

        AgentStockTransaction::create([
            'agent_id' => Auth::id(),
            'stock_id' => $agent_stock->id,
            'stock_name' => $agent_stock->stock_name,
            'transaction_type' => 'sale',
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $total_price,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_card_number' => $request->customer_card_number,
            'notes' => $request->notes,
            'transaction_date' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'স্টক বিক্রি সফলভাবে রেকর্ড করা হয়েছে!');
    }
}
