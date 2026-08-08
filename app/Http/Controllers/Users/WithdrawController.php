<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.withdraw');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return view('users.withdraw-form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'payment_system_id' => ['required'],
            'withdraw_amount' => ['required'],
        ], [
            'payment_system_id.required' => 'Please Select Payment method',
            'withdraw_amount.required' => 'Amount is required',
        ]);
        $request->validate([
            'payment_system_id' => ['required'],
            'withdraw_amount' => ['required', 'numeric', 'between:50,500'],
        ], [
            'payment_system_id.required' => 'Please Select Payment method',
            'withdraw_amount.required' => 'Amount is required',
            'withdraw_amount.numeric' => 'Amount must be a number',
            'withdraw_amount.between' => 'Amount must be between :min and :max',
        ]);

        $user_info = User::where('id', Auth::user()->id)->first();
        if ($user_info->balance < $request->withdraw_amount) {
            return back()->with('error', "Not Enough Balance");
        }

        $check_withdraw = Withdraw::where('user_id', Auth::user()->id)->where('status', 'pending')->exists();

        if($check_withdraw){
            return back()->with('error', "Already Have a  request");
        }

        Withdraw::insert([
            'user_id' => Auth::user()->id,
            'amount'=> $request->withdraw_amount,
            'method_id'=> $request->payment_system_id,
            'status' => "pending",
            'created_at' => Carbon::now()
        ]);

        User::where('id', Auth::user()->id)->update([
            'balance' => $user_info->balance - $request->withdraw_amount
        ]);

        return redirect()->route('withdraw.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $withdraw_info = Withdraw::where('id', $id)->first();
        $user_info = User::where('id', Auth::user()->id)->first();
        User::where('id', Auth::user()->id)->update([
            'balance' => $user_info->balance + $withdraw_info->amount
        ]);

        Withdraw::where('id', $id)->delete();

        return back();
    }
}
