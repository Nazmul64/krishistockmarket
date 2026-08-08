<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
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
        $items = MonthlyBazaarItem::where('status', 'active')->where('quantity', '>', 0)->get();
        $payment_systems = SitePaymentSystem::all();
        return view('users.monthly_bazaar.index', compact('items', 'payment_systems'));
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:monthly_bazaar_items,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $item = MonthlyBazaarItem::findOrFail($request->item_id);

        if (($item->quantity - $item->sold_quantity) < $request->quantity) {
            return redirect()->back()->with('error', 'অর্ডারটির স্টক পর্যাপ্ত নেই!');
        }

        $screenshotName = null;
        if ($request->hasFile('screenshot')) {
            $screenshotName = time() . Str::random(5) . '.' . $request->file('screenshot')->getClientOriginalExtension();
            Image::make($request->file('screenshot'))->save(base_path('public/upload/payment/' . $screenshotName));
        }

        $total_price = $item->price * $request->quantity;

        $status = 'pending';

        if ($request->payment_method === 'Customer Wallet Balance' || $request->payment_method === 'Wallet Balance') {
            $user = Auth::user();
            if ($user->balance < $total_price) {
                return redirect()->back()->with('error', 'আপনার ওয়ালেটে পর্যাপ্ত ব্যালেন্স নেই! আপনার বর্তমান ব্যালেন্স: ৳' . number_format($user->balance, 2) . '। অনুগ্রহ করে ডিপোজিট করুন।');
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
            $item->increment('sold_quantity', $request->quantity);
            $item->decrement('quantity', $request->quantity);
        }

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
        ]);

        return redirect()->route('user.monthly_bazaar.my_orders')->with('success', 'আপনার মাসিক বাজার রিকোয়েস্ট সফলভাবে জমা হয়েছে!');
    }

    public function myOrders()
    {
        $orders = MonthlyBazaarOrder::where('user_id', Auth::id())->with('item')->latest()->get();
        return view('users.monthly_bazaar.my_orders', compact('orders'));
    }
}
