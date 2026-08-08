<?php

namespace App\Http\Controllers;

use App\Models\AdminBuyStock;
use App\Models\BuyStock;
use App\Models\SellStock;
use App\Models\User;
use App\Models\UserProfite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){

        $user_info = User::where('id', Auth::user()->id)->first();
        if ($user_info->role == "admin") {

            $all_user = User::where('role', "user")->get();
            $employee = User::where('role', "employee")->get();
            $admin = User::where('role', "admin")->get();
            $all_sell = SellStock::where("status",'aproved')->sum('selled_price');
            $all_buy = BuyStock::where("status",'aproved')->sum('buyed_price');

            return view('admin.admin-home',[
                'all_user' => $all_user,
                'employee' => $employee,
                'admin' => $admin,
                'all_sell' => $all_sell,
                'all_buy' => $all_buy,
            ]);
        } elseif($user_info->role == "employee") {
            return view('employee.employee-home');
        }else{

            $all_sell = SellStock::where('user_id', Auth::user()->id)->where("status",'aproved')->sum('selled_price');
            $all_buy = BuyStock::where('user_id', Auth::user()->id)->where("status",'aproved')->sum('buyed_price');
            $user_profit = UserProfite::where('user_id', Auth::user()->id)->sum('profit');
            return view('users.user-home',[
                'all_sell' => $all_sell,
                'all_buy' => $all_buy,
                'user_profit' => $user_profit,
            ]);
        }
    }






}
