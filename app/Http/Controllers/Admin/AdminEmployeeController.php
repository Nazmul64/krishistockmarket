<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmployeeInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Permission;

class AdminEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('admin.create-employee');
    }

    public function managePermissions($id)
    {
        $employee = User::findOrFail($id);
        $userPermission = Permission::where('user_id', $id)->first();
        
        $assignedList = [];
        if ($userPermission && !empty($userPermission->permission_list)) {
            $assignedList = json_decode($userPermission->permission_list, true);
            if (!is_array($assignedList)) {
                $assignedList = array_map('trim', explode(',', $userPermission->permission_list));
            }
        }

        $allPermissions = [
            'live_chat' => [
                'title' => 'লাইভ চ্যাট সাপোর্ট (Live Chat Support)',
                'desc' => 'লাইভ চ্যাট সাহায্য কেন্দ্র ও কাস্টমার চ্যাট বার্তা',
                'icon' => 'ti-comments text-warning'
            ],
            'contact_messages' => [
                'title' => 'যোগাযোগের বার্তা (Contact Messages)',
                'desc' => 'কন্টাক্ট ফরম থেকে প্রেরিত কাস্টমার ইনকোয়ারি ও তথ্য ম্যানেজমেন্ট',
                'icon' => 'ti-envelope text-info'
            ],
            'our_packages' => [
                'title' => 'আওয়ার প্যাকেজ (Our Packages)',
                'desc' => 'কৃষি পরিবার প্যাকেজসমূহ তৈরি ও ব্যবস্থাপনা',
                'icon' => 'ti-package text-success'
            ],
            'card_numbers' => [
                'title' => 'নাম্বার জেনারেটর (Number Generator)',
                'desc' => '১২-ডিজিট মেম্বারশিপ কার্ড নাম্বার জেনারেটর',
                'icon' => 'ti-key text-info'
            ],
            'stock_management' => [
                'title' => 'স্টক ম্যানেজমেন্ট (Stock Management)',
                'desc' => 'নতুন স্টক যোগ, সকল স্টক তালিকা ও প্রিসেট',
                'icon' => 'ti-archive text-primary'
            ],
            'set_stock_price' => [
                'title' => 'স্টক প্রাইস সেট (Set Stock Price)',
                'desc' => 'শেয়ার/স্টকের বাই-সেল মূল্য ও দর নির্ধারণ',
                'icon' => 'ti-money text-success'
            ],
            'stock_buy_sell' => [
                'title' => 'স্টক ক্রয়/বিক্রয় (Stock Buy/Sell)',
                'desc' => 'বাই/সেল রিকোয়েস্ট অনুমোদন ও ক্রয়ের তালিকা',
                'icon' => 'ti-exchange-vertical text-warning'
            ],
            'monthly_bazaar' => [
                'title' => 'মাসিক বাজার (Monthly Bazaar)',
                'desc' => 'মাসিক বাজার প্যাকেজ, অর্ডার ডিস্ট্রিবিউশন ও রিপোর্ট',
                'icon' => 'ti-shopping-cart text-danger'
            ],
            'user_financial' => [
                'title' => 'ইউজার ও ফাইনান্সিয়াল (User & Financial)',
                'desc' => 'কাস্টমার তালিকা, ডিপোজিট রিকোয়েস্ট ও এনালিটিক্স রিপোর্ট',
                'icon' => 'ti-user text-primary'
            ],
            'withdraw' => [
                'title' => 'উইথড্র ম্যানেজমেন্ট (Withdraw)',
                'desc' => 'ইউজার উইথড্র রিকোয়েস্ট ও সকল উত্তোলন তালিকা',
                'icon' => 'ti-wallet text-warning'
            ],
            'employee_agent' => [
                'title' => 'এমপ্লয়ী ও এজেন্ট (Employee & Agent)',
                'desc' => 'এমপ্লয়ী তালিকা ও বিপণন এজেন্ট লাইভ হিসাব',
                'icon' => 'ti-id-badge text-info'
            ],
            'supplier_management' => [
                'title' => 'সাপ্লায়ার ম্যানেজমেন্ট (Supplier)',
                'desc' => 'সাপ্লায়ার তৈরি, পণ্য ভেরিফিকেশন ও লেজার',
                'icon' => 'ti-truck text-success'
            ],
            'hrm_management' => [
                'title' => 'এইচআরএম ম্যানেজমেন্ট (HRM System)',
                'desc' => 'কর্মকর্তা/কর্মচারী, ব্রাঞ্চ, শিফট, উপস্থিতি, ছুটি, পে-রোল ও রিক্রুটমেন্ট সিস্টেম',
                'icon' => 'ti-user text-danger'
            ],
            'settings' => [
                'title' => 'সেটিংস ম্যানেজমেন্ট (Settings)',
                'desc' => 'সাইট সেটিংস, স্লাইডার, ব্যানার ও পেমেন্ট মেথড',
                'icon' => 'ti-settings text-secondary'
            ],
        ];

        return view('admin.employee-permissions', compact('employee', 'assignedList', 'allPermissions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $permissions = $request->input('permissions', []);
        $permissionJson = json_encode(array_values($permissions));

        Permission::updateOrCreate(
            ['user_id' => $id],
            ['permission_list' => $permissionJson]
        );

        return back()->with('success', 'এমপ্লয়ী পারমিশন সফলভাবে সংরক্ষণ করা হয়েছে!');
    }





    public function Edit($id){
        $employee_info = User::where('id', $id)->first();
        return view('admin.edit-employee',[
            'employee_info' => $employee_info
        ]);
    }
    public function EditPost($id){

    }



    public function ViewEmployee($id){

        $employeeInfo = EmployeeInfo::where('user_id', $id)->first();
        $user_info = User::where('id', $id)->first();

        return view('admin.employee-view',[
            'employeeInfo' => $employeeInfo,
            'user_info' => $user_info,
        ]);
    }
    public function ViewReferalUser($id){
        $all_user = User::where('referral_id', $id)->get();

        return view('admin.employee-refereal_user',[
            'all_user' => $all_user
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user = User::insert([
            'username' => $request->username,
            'balance' => "0",
            'role' => "employee",
            'name' => $request->name,
            'referral_id' => null,
            'phone' => $request->phone_number,
            'password' => Hash::make($request->password),
            'email_verified_at' => Carbon::now(),
            'created_at' => Carbon::now(),
        ]);

        if ($user) {
            return back()->with('success', "Employee Create Successfuly");
        }else{
            return back();
        }

    }

    protected function validator(array $data){
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['string', 'email', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],[
            'username.required' => "Username is required",
            'username.unique' => "This Username is already exists",
            'name.required' => "First name is required",
            'email.email' => "Please enter valid Email address",
            'phone_number.required' => "Phone number code is required",
            'password.required' => "Password is required",
        ]);
    }




    public function destroy($id)
    {
        User::where('id', $id)->delete();
        return back()->with('success', "Employee Delete Successfully");
    }



}
