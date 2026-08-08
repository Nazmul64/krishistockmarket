<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentStock;
use App\Models\AgentStockTransaction;
use App\Models\Stock;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminAgentLedgerController extends Controller
{
    public function index()
    {
        $agents = User::where('role', 'employee')->get();
        $all_stocks = Stock::where('status', 'active')->get();

        $agent_summaries = [];
        foreach ($agents as $agent) {
            $total_allocated = AgentStock::where('agent_id', $agent->id)->sum('allocated_quantity');
            $total_sold = AgentStock::where('agent_id', $agent->id)->sum('sold_quantity');
            $available_stock = $total_allocated - $total_sold;
            $total_revenue = AgentStockTransaction::where('agent_id', $agent->id)
                ->where('transaction_type', 'sale')
                ->sum('total_price');

            $agent_summaries[] = [
                'agent' => $agent,
                'total_allocated' => $total_allocated,
                'total_sold' => $total_sold,
                'available_stock' => $available_stock,
                'total_revenue' => $total_revenue,
            ];
        }

        return view('admin.agent_ledger.index', compact('agent_summaries', 'all_stocks', 'agents'));
    }

    public function show($agent_id, Request $request)
    {
        $agent = User::where('role', 'employee')->where('id', $agent_id)->firstOrFail();
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

        return view('admin.agent_ledger.show', compact(
            'agent',
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

    public function allocateStock(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'stock_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $agent_stock = AgentStock::where('agent_id', $request->agent_id)
            ->where('stock_name', $request->stock_name)
            ->first();

        if ($agent_stock) {
            $agent_stock->increment('allocated_quantity', $request->quantity);
            $agent_stock->update(['unit_price' => $request->unit_price]);
        } else {
            $agent_stock = AgentStock::create([
                'agent_id' => $request->agent_id,
                'stock_name' => $request->stock_name,
                'allocated_quantity' => $request->quantity,
                'sold_quantity' => 0,
                'unit_price' => $request->unit_price,
            ]);
        }

        $total_price = $request->quantity * $request->unit_price;

        AgentStockTransaction::create([
            'agent_id' => $request->agent_id,
            'stock_id' => $agent_stock->id,
            'stock_name' => $request->stock_name,
            'transaction_type' => 'allocation',
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_price' => $total_price,
            'notes' => $request->notes ?? 'Stock assigned by Admin',
            'transaction_date' => Carbon::now(),
        ]);

        $notification = [
            'message' => 'Agent Stock Allocated Successfully!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }
}
