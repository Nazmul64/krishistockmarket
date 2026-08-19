<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CardNumber;
use App\Models\SupplierProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showSupplierRegistrationForm()
    {
        return view('auth.supplier_register');
    }

    public function supplierRegisterPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users,phone|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'district_thana' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'সাপ্লায়ারের নাম আবশ্যিক',
            'company_name.required' => 'প্রতিষ্ঠানের নাম আবশ্যিক',
            'phone.required' => 'মোবাইল নম্বর আবশ্যিক',
            'phone.unique' => 'এই মোবাইল নম্বরটি ইতিমধ্যে নিবন্ধিত',
            'email.unique' => 'এই ইমেইলটি ইতিমধ্যে ব্যবহৃত',
            'password.required' => 'পাসওয়ার্ড আবশ্যিক',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে',
            'password.confirmed' => 'পাসওয়ার্ড নিশ্চিতকরণ মেলেনি',
        ]);

        $registeredUser = null;

        DB::transaction(function () use ($request, &$registeredUser) {
            $userCount = User::where('role', 'supplier')->count();
            $supplierCode = 'SUP-' . str_pad($userCount + 1, 4, '0', STR_PAD_LEFT);
            $username = trim($request->phone);

            $registeredUser = User::create([
                'name' => trim($request->name),
                'email' => $request->filled('email') ? trim($request->email) : ($username . '@ikrishiporibar.com'),
                'username' => $username,
                'phone' => trim($request->phone),
                'password' => Hash::make($request->password),
                'role' => 'supplier',
                'email_verified_at' => Carbon::now(),
            ]);

            SupplierProfile::create([
                'user_id' => $registeredUser->id,
                'supplier_code' => $supplierCode,
                'company_name' => trim($request->company_name),
                'district_thana' => $request->district_thana,
                'address' => $request->address,
                'opening_balance' => 0.00,
                'opening_date' => Carbon::now()->toDateString(),
                'notes' => 'Self Registered Supplier',
            ]);
        });

        if ($registeredUser) {
            Auth::login($registeredUser);
            return redirect()->route('supplier.dashboard')->with('success', 'সাপ্লায়ার অ্যাকাউন্ট সফলভাবে রেজিস্ট্রেশন করা হয়েছে! স্বাগতম।');
        }

        return back()->withInput()->withErrors(['msg' => 'রেজিস্ট্রেশনে সমস্যা দেখা দিয়েছে।']);
    }

    public function registerPost(Request $request)
    {
        $this->validator($request->all())->validate();

        $cardNumber = CardNumber::where('number', trim($request->card_number))
            ->where('is_used', false)
            ->first();

        if (!$cardNumber) {
            return back()->withErrors(['card_number' => 'Invalid or already used 12-Digit Admin Number'])->withInput();
        }

        $phone = trim($request->phone_number);
        $name = trim($request->name);

        $initialBonus = $cardNumber->amount ?? 300.00;
        $cardType = $cardNumber->card_type ?? ($initialBonus >= 1000 ? 'golden' : 'standard');

        $user = User::create([
            'username'             => $phone,
            'name'                 => $name,
            'email'                => $phone . '@ikrishiporibar.com',
            'phone'                => $phone,
            'role'                 => 'user',
            'balance'              => 0.00,
            'locked_balance'       => $initialBonus, // Freeze initial membership balance
            'membership_card_type' => $cardType,
            'password'             => Hash::make($request->password),
            'email_verified_at'    => Carbon::now(),
        ]);

        if ($user) {
            // Mark 12-Digit Admin Number as used
            $cardNumber->update([
                'is_used' => true,
                'used_by' => $user->id,
                'used_at' => Carbon::now(),
            ]);

            // Record Registration Balance Ledger Entry
            RecordWalletLedger($user->id, 'Registration Balance', $initialBonus, 0, 'Registration Bonus (Frozen Membership Fee)', $cardNumber->number);

            $cardName = ($cardType === 'golden') ? 'Golden Card (৳1,000)' : 'Standard Card (৳300)';
            return redirect()->route('login')->with('success', "Registration successful! {$cardName} balance of ৳{$initialBonus} has been credited to your account (frozen until Admin unlocks). Please sign in.");
        } else {
            return back()->withInput();
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'card_number'  => [
                'required',
                'string',
                'digits:12',
                Rule::exists('card_numbers', 'number')->where(function ($query) {
                    $query->where('is_used', false);
                }),
            ],
            'name'         => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255', 'unique:users,phone', 'unique:users,username'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'card_number.required'  => "Card Number is required",
            'card_number.digits'    => "Card Number must be exactly 12 digits",
            'card_number.exists'    => "Invalid or already used Card Number",
            'name.required'         => "Name is required",
            'name.max'              => "Name must not exceed 255 characters",
            'phone_number.required' => "Phone number is required",
            'phone_number.unique'   => "This Phone number is already registered",
            'password.required'     => "Password is required",
            'password.min'          => "Password must be at least 8 characters",
            'password.confirmed'    => "Password confirmation does not match",
        ]);
    }
}
