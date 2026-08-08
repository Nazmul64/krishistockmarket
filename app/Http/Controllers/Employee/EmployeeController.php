<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class EmployeeController extends Controller
{

    public function Referal(){
        return view('employee.referal-user');
    }




    public function profileBusiness(){
        $user_business_info = EmployeeInfo::where('user_id', Auth::user()->id)->first();

        return view('employee.business-profile',[
            'user_business_info' => $user_business_info
        ]);
    }




    public function profileBusinessSubmit(Request $request){

        $check_old_data =  EmployeeInfo::where('user_id', Auth::user()->id)->exists();
        if ($check_old_data) {
            EmployeeInfo::where('user_id', Auth::user()->id)->update([
                'e_nid_number'=>$request->e_nid_number,
                'e_bath_number'=>$request->e_bath_number,
                'e_office_id_number'=>$request->e_office_id_number,
                'e_signature'=>$request->e_signature,
                'e_father_name'=>$request->e_father_name,
                'e_mother_name'=>$request->e_mother_name,
                'e_gender'=>$request->e_gender,
                'e_age'=>$request->e_age,
            ]);
        } else {
            EmployeeInfo::insert([
                'user_id' => Auth::user()->id,
                'e_nid_number'=> $request->e_nid_number,
                'e_bath_number'=>$request->e_bath_number,
                'e_office_id_number'=>$request->e_office_id_number,
                'e_signature'=>$request->e_signature,
                'e_father_name'=>$request->e_father_name,
                'e_mother_name'=>$request->e_mother_name,
                'e_gender'=>$request->e_gender,
                'e_age'=>$request->e_age,
            ]);
        }


        $old_data =  EmployeeInfo::where('user_id', Auth::user()->id)->first();

        if($request->hasFile('e_nid_img')){
            if(!empty($old_data->e_nid_img)){
                unlink(base_path('public/upload/employee/'.$old_data->e_nid_img));
            }
            $e_nid_img = time().Str::random(5).'.'.$request->file('e_nid_img')->getClientOriginalExtension();
            Image::make($request->file('e_nid_img'))->save(base_path('public/upload/employee/'.$e_nid_img));
            EmployeeInfo::where('user_id', Auth::user()->id)->update([
                'e_nid_img' => $e_nid_img
            ]);
        }

        if($request->hasFile('e_bath_img')){
            if(!empty($old_data->e_nid_img)){
                unlink(base_path('public/upload/employee/'.$old_data->e_bath_img));
            }
            $e_bath_img = time().Str::random(5).'.'.$request->file('e_bath_img')->getClientOriginalExtension();
            Image::make($request->file('e_bath_img'))->save(base_path('public/upload/employee/'.$e_bath_img));
            EmployeeInfo::where('user_id', Auth::user()->id)->update([
                'e_bath_img' => $e_bath_img
            ]);
        }



        if($request->hasFile('e_office_id_img')){
            if(!empty($old_data->e_office_id_img)){
                unlink(base_path('public/upload/employee/'.$old_data->e_office_id_img));
            }
            $e_office_id_img = time().Str::random(5).'.'.$request->file('e_office_id_img')->getClientOriginalExtension();
            Image::make($request->file('e_office_id_img'))->save(base_path('public/upload/employee/'.$e_office_id_img));
            EmployeeInfo::where('user_id', Auth::user()->id)->update([
                'e_office_id_img' => $e_office_id_img
            ]);
        }

        if($request->hasFile('e_cv')){
            if(!empty($old_data->e_cv)){
                unlink(base_path('public/upload/employee/'.$old_data->e_cv));
            }
            $e_cv = time().Str::random(5).'.'.$request->file('e_cv')->getClientOriginalExtension();
            Image::make($request->file('e_cv'))->save(base_path('public/upload/employee/'.$e_cv));
            EmployeeInfo::where('user_id', Auth::user()->id)->update([
                'e_cv' => $e_cv
            ]);
        }

        return back();

    }

}
