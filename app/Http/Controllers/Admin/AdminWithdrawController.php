<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class AdminWithdrawController extends Controller
{



    public function index(){
        $all_withdraw_list = Withdraw::all();
        return view('admin.all-withdraw',[
            'all_withdraw_list' => $all_withdraw_list
        ]);
    }

    public function WithdrawRequset(){
        $all_withdraw_request = Withdraw::where("status",'pending')->get();
        return view('admin.request-for-withdraw',[
            "all_withdraw_request" => $all_withdraw_request
        ]);
    }





    public function RejectedWithdraw($id){

        $withdraw_info = Withdraw::where('id', $id)->first();
        $user_info = User::where('id', $withdraw_info->user_id)->first();
        User::where('id', $withdraw_info->user_id)->update([
            'balance' => $user_info->balance + $withdraw_info->amount
        ]);

        Withdraw::where('id', $id)->update([
            "status" => 'rejected',
        ]);
        return back();
    }
    public function AprovedWithdraw($id){

        // $withdraw_info = Withdraw::where('id', $id)->first();
        // $user_info = User::where('id',$withdraw_info->user_id)->first();
        // User::where('id', $withdraw_info->user_id)->update([
        //     'balance' => $user_info->balance - $withdraw_info->amount
        // ]);

        Withdraw::where('id', $id)->update([
            "status" => 'aproved',
        ]);

        return back();

    }


}
