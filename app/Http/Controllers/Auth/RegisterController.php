<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CardNumber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        $user = User::create([
            'username'          => $phone,
            'name'              => $name,
            'email'             => $phone . '@ikrishiporibar.com',
            'phone'             => $phone,
            'role'              => 'user',
            'balance'           => 0.00,
            'password'          => Hash::make($request->password),
            'email_verified_at' => Carbon::now(),
        ]);

        if ($user) {
            // Mark 12-Digit Admin Number as used
            $cardNumber->update([
                'is_used' => true,
                'used_by' => $user->id,
                'used_at' => Carbon::now(),
            ]);

            // Record Registration Balance Ledger Entry (Previous 0 -> Credit 300 -> New 300)
            RecordWalletLedger($user->id, 'Registration Balance', $initialBonus, 0, 'Registration Bonus', $cardNumber->number);

            return redirect()->route('login')->with('success', 'Registration successful! 300 TK has been credited to your balance. Please sign in with your phone number.');
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
