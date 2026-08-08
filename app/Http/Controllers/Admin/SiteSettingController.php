<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class SiteSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('admin.setting.index');
    }



    public function SliderSetting(){
        return view('admin.setting.slider');
    }




    public function SliderSettingPost(Request $request)
    {
        if ($request->hasFile('slider1_img')) {
            $setting = SiteSetting::where('name', 'slider1_img')->first();
            if ($setting && $setting->value && $setting->value != 'slider1_img.png') {
                $oldPath = base_path('public/upload/slider/' . $setting->value);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $slider1_imgName = time() . Str::random(5) . '.' . $request->file('slider1_img')->getClientOriginalExtension();

            Image::make($request->file('slider1_img'))->save(base_path('public/upload/slider/' . $slider1_imgName));

            SiteSetting::updateOrCreate(
                ['name' => 'slider1_img'],
                ['value' => $slider1_imgName]
            );
        }

        if ($request->hasFile('slider2_img')) {
            $setting = SiteSetting::where('name', 'slider2_img')->first();
            if ($setting && $setting->value && $setting->value != 'slider2_img.png') {
                $oldPath = base_path('public/upload/slider/' . $setting->value);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $slider2_imgName = time() . Str::random(5) . '.' . $request->file('slider2_img')->getClientOriginalExtension();

            Image::make($request->file('slider2_img'))->save(base_path('public/upload/slider/' . $slider2_imgName));

            SiteSetting::updateOrCreate(
                ['name' => 'slider2_img'],
                ['value' => $slider2_imgName]
            );
        }

        SiteSetting::updateOrCreate(['name' => 'slider1_description'], ['value' => $request->slider1_description ?? '']);
        SiteSetting::updateOrCreate(['name' => 'slider2_description'], ['value' => $request->slider2_description ?? '']);
        SiteSetting::updateOrCreate(['name' => 'slider1_text'], ['value' => $request->slider1_text ?? '']);
        SiteSetting::updateOrCreate(['name' => 'slider2_text'], ['value' => $request->slider2_text ?? '']);

        return back()->with('success', 'Update Successfull');
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'logo' => 'image',
            'favicon' => 'image'
        ]);

        if ($request->hasFile('logo')) {
            $setting = SiteSetting::where('name', 'logo')->first();
            if ($setting && $setting->value && $setting->value != 'default-logo.png') {
                $oldPath = base_path('public/upload/images/backend/logo/' . $setting->value);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $logoName = time() . Str::random(5) . '.' . $request->file('logo')->getClientOriginalExtension();

            Image::make($request->file('logo'))->save(base_path('public/upload/images/backend/logo/' . $logoName));

            SiteSetting::updateOrCreate(
                ['name' => 'logo'],
                ['value' => $logoName]
            );
        }

        if ($request->hasFile('favicon')) {
            $setting = SiteSetting::where('name', 'favicon')->first();
            if ($setting && $setting->value && $setting->value != 'default-favicon.png') {
                $oldPath = base_path('public/upload/images/backend/logo/' . $setting->value);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $favIconName = time() . Str::random(5) . '.' . $request->file('favicon')->getClientOriginalExtension();

            Image::make($request->file('favicon'))->save(base_path('public/upload/images/backend/logo/' . $favIconName));

            SiteSetting::updateOrCreate(
                ['name' => 'favicon'],
                ['value' => $favIconName]
            );
        }

        SiteSetting::updateOrCreate(['name' => 'title'], ['value' => $request->title ?? '']);
        SiteSetting::updateOrCreate(['name' => 'address1'], ['value' => $request->address1 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'address2'], ['value' => $request->address2 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'phone1'], ['value' => $request->phone1 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'phone2'], ['value' => $request->phone2 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'email1'], ['value' => $request->email1 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'email2'], ['value' => $request->email2 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'about_us_text'], ['value' => $request->about_us_text ?? '']);
        SiteSetting::updateOrCreate(['name' => 'facbook_link'], ['value' => $request->facbook_link ?? '']);
        SiteSetting::updateOrCreate(['name' => 'linkedin_link'], ['value' => $request->linkedin_link ?? '']);
        SiteSetting::updateOrCreate(['name' => 'twitter_link'], ['value' => $request->twitter_link ?? '']);
        SiteSetting::updateOrCreate(['name' => 'about_us_text2'], ['value' => $request->about_us_text2 ?? '']);
        SiteSetting::updateOrCreate(['name' => 'terms_conditions'], ['value' => $request->terms_conditions ?? '']);
        SiteSetting::updateOrCreate(['name' => 'privacy_policy'], ['value' => $request->privacy_policy ?? '']);
        SiteSetting::updateOrCreate(['name' => 'about_us_full_text'], ['value' => $request->about_us_full_text ?? '']);

        return back()->with('success', 'Update Successfull');
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
