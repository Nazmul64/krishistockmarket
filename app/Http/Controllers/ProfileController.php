<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Validation\Rule;
class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('user-profile');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $user_id = Auth::user()->id;

            $user = User::where('id', $user_id)->first();

        $request->validate([
            'username' => [
                'required',
                'max:255',
                Rule::unique('users')->where(function ($query) use ($user) {
                    $query->where('id', '<>', $user->id);
                }),
            ],
        ], [
            'username.required' => 'Email is required',
            'username.unique' => 'The email is already taken',
        ]);




        if($request->hasFile('avatar')){
            if(User::where('id', $user_id)->first()->avatar != 'default-user.png')
            {
                unlink(base_path('public/upload/userprofile/'.User::where('id', $user_id)->first()->avatar));
            }
            $imgName = time().Str::random(5).'.'.$request->file('avatar')->getClientOriginalExtension();

            Image::make($request->file('avatar'))->save(base_path('public/upload/userprofile/'.$imgName));

            User::where('id', $user_id)->update([
                'avatar' => $imgName,
            ]);
        }

        User::where('id', $user_id)->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Update Successfull');

    }



    public function changePasswordPost(Request $request)
    {

        $check_result = Hash::check($request->oldpassword, Auth::user()->password);

        if ($check_result) {
            User::where('id', Auth::id())->update([
                'password' => Hash::make($request->newpassword)
            ]);
        } else {
            return back()
                    ->with('oldpassword', $request->oldpassword)
                    ->with('newpassword', $request->newpassword)
                    ->with('worngpassword', "Old Password Not Match");
        }


        return back();

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
