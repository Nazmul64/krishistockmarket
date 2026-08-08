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
