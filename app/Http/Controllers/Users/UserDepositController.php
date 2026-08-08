<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\SitePaymentSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class UserDepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::where('user_id', Auth::id())->latest()->get();
        $payment_systems = SitePaymentSystem::all();
        $user_card = GetUserCardNumber(Auth::id());
        return view('users.deposit.index', compact('deposits', 'payment_systems', 'user_card'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'deposit_amount' => 'required|numeric|min:10',
            'payment_method' => 'required|string',
            'pay_from_number' => 'nullable|string',
            'trx_number' => 'nullable|string',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $screenshotName = null;
        if ($request->hasFile('screenshot')) {
            $screenshotName = time() . Str::random(5) . '.' . $request->file('screenshot')->getClientOriginalExtension();
            Image::make($request->file('screenshot'))->save(base_path('public/upload/payment/' . $screenshotName));
        }

        Deposit::create([
            'user_id' => Auth::id(),
            'card_number' => GetUserCardNumber(Auth::id()),
            'deposit_amount' => $request->deposit_amount,
            'payment_method' => $request->payment_method,
            'pay_from_number' => $request->pay_from_number,
            'trx_number' => $request->trx_number,
            'screenshot' => $screenshotName,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'আপনার ডিপোজিট রিকোয়েস্ট সফলভাবে জমা হয়েছে! এডমিন ভেরিফাই করে অ্যাপ্রুভ করবেন।');
    }
}
