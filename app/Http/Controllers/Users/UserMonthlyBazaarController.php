<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\AgentPoint;
use App\Models\MonthlyBazaarItem;
use App\Models\MonthlyBazaarOrder;
use App\Models\SitePaymentSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class UserMonthlyBazaarController extends Controller
{
    public function index()
    {
        $items = MonthlyBazaarItem::where('status', 'active')
            ->where(function($q) {
                $q->where('is_unlimited', 1)->orWhere('quantity', '>', 0);
            })->get();
        $payment_systems = SitePaymentSystem::all();
        $agent_points = AgentPoint::where('status', 'active')->get();
        return view('users.monthly_bazaar.index', compact('items', 'payment_systems', 'agent_points'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:monthly_bazaar_items,id',
            'quantity' => 'required|integer|min:1',
            'request_area' => 'required|string|max:255',
            'agent_point' => 'required|string|max:255',
            'payment_method' => 'required|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ], [
            'request_area.required' => 'রিকোয়েস্টের এলাকা উল্লেখ করা বাধ্যতামূলক!',
            'agent_point.required' => 'সংগ্রহের এজেন্ট পয়েন্ট নির্বাচন করা বাধ্যতামূলক!',
        ]);

        $item = MonthlyBazaarItem::findOrFail($request->item_id);

        if (!$item->is_unlimited && ($item->quantity - $item->sold_quantity) < $request->quantity) {
            return redirect()->back()->with('error', 'অর্ডারটির স্টক পর্যাপ্ত নেই!');
        }

        $screenshotName = null;
        if ($request->hasFile('screenshot')) {
            $uploadDir = base_path('public/upload/payment');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $screenshotName = time() . Str::random(5) . '.' . $request->file('screenshot')->getClientOriginalExtension();
            Image::make($request->file('screenshot'))->save($uploadDir . '/' . $screenshotName);
        }

        $total_price = $item->price * $request->quantity;

        $status = 'pending';
        $distStatus = 'pending';

        $isWalletPayment = Str::contains($request->payment_method, ['Wallet Balance', 'Customer Wallet Balance', 'ওয়ালেট', 'Wallet']);

        if ($isWalletPayment) {
            $user = Auth::user();
            if ($user->balance < $total_price) {
                return redirect()->back()->with('error', 'আপনার ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই! আপনার বর্তমান ব্যালেন্স: ৳' . number_format($user->balance, 2) . '। অনুগ্রহ করে ডিপোজিট করুন অথবা অন্য পেমেন্ট মেথড বা ক্যাশ অন ডেলিভারি বেছে নিন।');
            }

            // Ledger Rule #18 & Section #8: Debit Wallet Balance and record ledger
            RecordWalletLedger(
                $user->id,
                'Monthly Market Purchase',
                0,
                $total_price,
                'Customer Wallet Balance',
                'WLT-' . strtoupper(Str::random(6)),
                'Approved'
            );

            $status = 'approved';
            $distStatus = 'allocated';
            $item->increment('sold_quantity', $request->quantity);
            $item->decrement('quantity', $request->quantity);
        }

        $collectionNote = "আপনি যদি " . $request->agent_point . "-এর আওতাভুক্ত হন, তাহলে আপনার মাসিক বাজার " . $request->agent_point . " (এলাকা: " . $request->request_area . ") থেকে সংগ্রহ করতে হবে।";

        MonthlyBazaarOrder::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'package_title' => $item->title,
            'price' => $item->price,
            'quantity' => $request->quantity,
            'total_price' => $total_price,
            'payment_method' => $request->payment_method,
            'pay_from_number' => $request->pay_from_number ?? Auth::user()->phone,
            'trx_number' => $request->trx_number ?? 'WLT-PAY',
            'screenshot' => $screenshotName,
            'status' => $status,
            'request_area' => $request->request_area,
            'agent_point' => $request->agent_point,
            'allocated_quantity' => $status === 'approved' ? $request->quantity : 0,
            'distribution_status' => $distStatus,
            'collection_note' => $collectionNote,
        ]);

        return redirect()->route('user.monthly_bazaar.my_orders')->with('success', 'আপনার মাসিক বাজার রিকোয়েস্ট সফলভাবে জমা হয়েছে!');
    }

    public function myOrders()
    {
        $orders = MonthlyBazaarOrder::where('user_id', Auth::id())->with('item')->latest()->get();
        return view('users.monthly_bazaar.my_orders', compact('orders'));
    }
}
