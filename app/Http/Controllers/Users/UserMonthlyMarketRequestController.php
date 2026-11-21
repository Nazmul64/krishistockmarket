<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\MonthlyBazaarItem;
use App\Models\MonthlyMarketRequest;
use App\Models\MonthlyMarketRequestItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserMonthlyMarketRequestController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $agentPoints = AgentPoint::where('status', 'active')->get();
        $availableProducts = MonthlyBazaarItem::where('status', 'active')->get();

        // Default grocery presets for quick selection
        $defaultGroceryItems = [
            ['name' => 'চাল (Miniket Rice)', 'unit' => 'KG', 'default_qty' => 10],
            ['name' => 'মসুর ডাল (Lentil)', 'unit' => 'KG', 'default_qty' => 2],
            ['name' => 'সয়াবিন তেল (Soybean Oil)', 'unit' => 'Liter', 'default_qty' => 2],
            ['name' => 'চিনি (Sugar)', 'unit' => 'KG', 'default_qty' => 1],
            ['name' => 'আটা (Wheat Flour)', 'unit' => 'KG', 'default_qty' => 5],
            ['name' => 'লবণ (Iodized Salt)', 'unit' => 'KG', 'default_qty' => 1],
            ['name' => 'আলু (Potato)', 'unit' => 'KG', 'default_qty' => 5],
            ['name' => 'পেঁয়াজ (Onion)', 'unit' => 'KG', 'default_qty' => 2],
        ];

        $currentMonth = Carbon::now()->format('F');
        $currentYear = Carbon::now()->format('Y');

        // Check if user already submitted for current month/year
        $existingRequest = MonthlyMarketRequest::where('user_id', $user->id)
            ->where('month', $currentMonth)
            ->where('year', $currentYear)
            ->first();

        return view('users.monthly_market.create', compact(
            'user',
            'agentPoints',
            'availableProducts',
            'defaultGroceryItems',
            'currentMonth',
            'currentYear',
            'existingRequest'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'month' => 'required|string',
            'year' => 'required|string',
            'request_area' => 'required|string|max:255',
            'agent_point_id' => 'required|exists:agent_points,id',
            'items' => 'required|array|min:1',
            'items.*.product_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.1',
            'items.*.unit' => 'required|string|max:50',
        ], [
            'request_area.required' => 'রিকোয়েস্টের এলাকা (যেমন: নওগাঁ, বগুড়া, গাজীপুর, ঢাকা) নির্বাচন/উল্লেখ করা বাধ্যতামূলক!',
            'agent_point_id.required' => 'সংগ্রহের এজেন্ট পয়েন্ট নির্বাচন করা বাধ্যতামূলক!',
            'items.required' => 'কমপক্ষে একটি পণ্য ও পরিমাণ উল্লেখ করুন!',
        ]);

        // Duplicate submission prevention for same month and year
        $alreadySubmitted = MonthlyMarketRequest::where('user_id', $user->id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($alreadySubmitted) {
            return redirect()->back()->with('error', $request->month . ' ' . $request->year . ' মাসের জন্য আপনার একটি রিকোয়েস্ট ইতিমধ্যে সংরক্ষিত আছে (Request #' . $alreadySubmitted->request_no . ')।');
        }

        $agentPoint = AgentPoint::findOrFail($request->agent_point_id);
        $requestNo = 'MMR-' . date('ym') . '-' . strtoupper(Str::random(5));

        DB::transaction(function () use ($request, $user, $agentPoint, $requestNo) {
            $totalItems = 0;
            $estimatedAmount = 0;

            $marketRequest = MonthlyMarketRequest::create([
                'request_no' => $requestNo,
                'user_id' => $user->id,
                'card_number' => $user->card_number ?? $user->username ?? ('CRD-' . $user->id),
                'month' => $request->month,
                'year' => $request->year,
                'request_date' => Carbon::now()->toDateString(),
                'request_area' => $request->request_area,
                'district' => $request->district ?? $agentPoint->district,
                'upazila_thana' => $request->upazila_thana ?? $agentPoint->thana_upazila,
                'agent_point_id' => $agentPoint->id,
                'agent_point_name' => $agentPoint->name,
                'total_items' => 0,
                'total_estimated_amount' => 0,
                'status' => 'pending',
                'collection_status' => 'pending_collection',
                'collection_instruction' => 'আপনার মাসিক বাজার ' . $agentPoint->name . ' (' . $agentPoint->address . ') থেকে সংগ্রহ করতে হবে।',
                'admin_note' => $request->additional_note,
            ]);

            foreach ($request->items as $itemData) {
                if (empty($itemData['product_name']) || empty($itemData['quantity'])) {
                    continue;
                }

                $qty = floatval($itemData['quantity']);
                $unit = $itemData['unit'] ?? 'KG';
                $unitPrice = floatval($itemData['unit_price'] ?? 0);
                $totalPrice = $qty * $unitPrice;

                MonthlyMarketRequestItem::create([
                    'request_id' => $marketRequest->id,
                    'product_name' => $itemData['product_name'],
                    'requested_quantity' => $qty,
                    'unit' => $unit,
                    'approved_quantity' => $qty, // default requested
                    'allocated_quantity' => 0,
                    'collected_quantity' => 0,
                    'remaining_quantity' => $qty,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'note' => $itemData['note'] ?? null,
                ]);

                $totalItems++;
                $estimatedAmount += $totalPrice;
            }

            $marketRequest->update([
                'total_items' => $totalItems,
                'total_estimated_amount' => $estimatedAmount,
            ]);
        });

        return redirect()->route('user.monthly_market.my_requests')->with('success', 'আপনার মাসিক বাজার চাহিদা রিকোয়েস্ট সফলভাবে জমা হয়েছে! রিকোয়েস্ট নং: ' . $requestNo);
    }

    public function myRequests()
    {
        $requests = MonthlyMarketRequest::where('user_id', Auth::id())
            ->with(['items', 'agentPoint'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('users.monthly_market.my_requests', compact('requests'));
    }

    public function showSlip($id)
    {
        $request = MonthlyMarketRequest::where('user_id', Auth::id())
            ->with(['items', 'agentPoint', 'user'])
            ->findOrFail($id);

        return view('users.monthly_market.slip', compact('request'));
    }
}
