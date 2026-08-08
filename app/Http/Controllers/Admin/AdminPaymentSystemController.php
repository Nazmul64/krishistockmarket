<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitePaymentSystem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminPaymentSystemController extends Controller
{

    public function index()
    {
        return view("admin.payment-system");
    }



    public function store(Request $request)
    {
        $request->validate([
            'pay_s_name' => ['required'],
            'pay_s_number' => ['required'],
        ], [
            'pay_s_name.required' => 'Name is required',
            'pay_s_number.required' => 'Number is required',
        ]);

        SitePaymentSystem::insert([
            'pay_s_name' => $request->pay_s_name,
            'pay_s_number' => $request->pay_s_number,
            'created_at' => Carbon::now(),
        ]);

        return back()->with('success', "Add Sucessfully");
    }



    public function destroy($id)
    {
        SitePaymentSystem::where('id', $id)->delete();

        return back()->with('success', "Deleted Sucessfully");
    }
}
