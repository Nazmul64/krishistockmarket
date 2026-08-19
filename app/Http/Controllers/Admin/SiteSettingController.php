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
        $sliders = [];
        for ($i = 1; $i <= 4; $i++) {
            $sliders[$i] = [
                'id' => $i,
                'text' => setting('slider' . $i . '_text'),
                'description' => setting('slider' . $i . '_description'),
                'img' => setting('slider' . $i . '_img'),
            ];
        }
        return view('admin.setting.slider', compact('sliders'));
    }

    public function SliderSettingPost(Request $request)
    {
        for ($i = 1; $i <= 4; $i++) {
            $imgKey = 'slider' . $i . '_img';
            $textKey = 'slider' . $i . '_text';
            $descKey = 'slider' . $i . '_description';

            if ($request->hasFile($imgKey)) {
                $setting = SiteSetting::where('name', $imgKey)->first();
                if ($setting && $setting->value) {
                    $oldPath = base_path('public/upload/slider/' . $setting->value);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $uploadDir = base_path('public/upload/slider');
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $imgName = time() . '_' . $i . '_' . Str::random(5) . '.' . $request->file($imgKey)->getClientOriginalExtension();
                Image::make($request->file($imgKey))->save($uploadDir . '/' . $imgName);

                SiteSetting::updateOrCreate(
                    ['name' => $imgKey],
                    ['value' => $imgName]
                );
            }

            if ($request->has($textKey)) {
                SiteSetting::updateOrCreate(
                    ['name' => $textKey],
                    ['value' => $request->input($textKey) ?? '']
                );
            }

            if ($request->has($descKey)) {
                SiteSetting::updateOrCreate(
                    ['name' => $descKey],
                    ['value' => trim($request->input($descKey)) ?? '']
                );
            }
        }

        return back()->with('success', 'Slider Settings Updated Successfully');
    }

    public function SliderDelete($num)
    {
        $imgKey = 'slider' . $num . '_img';
        $setting = SiteSetting::where('name', $imgKey)->first();
        if ($setting && $setting->value) {
            $oldPath = base_path('public/upload/slider/' . $setting->value);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $setting->delete();
        }

        SiteSetting::where('name', 'slider' . $num . '_text')->delete();
        SiteSetting::where('name', 'slider' . $num . '_description')->delete();

        return back()->with('success', 'Slider ' . $num . ' deleted successfully');
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

    public function OfferBannerSetting()
    {
        return view('admin.setting.offer_banner');
    }

    public function OfferBannerPost(Request $request)
    {
        $request->validate([
            'offer_banner_img' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'offer_banner_link' => 'nullable|string|max:500',
            'offer_banner_title' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('offer_banner_img')) {
            $setting = SiteSetting::where('name', 'offer_banner_img')->first();
            if ($setting && $setting->value) {
                $oldPath = base_path('public/upload/slider/' . $setting->value);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }
            $uploadDir = base_path('public/upload/slider');
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $imgName = 'offer_banner_' . time() . '_' . Str::random(5) . '.' . $request->file('offer_banner_img')->getClientOriginalExtension();
            Image::make($request->file('offer_banner_img'))->save($uploadDir . '/' . $imgName);

            SiteSetting::updateOrCreate(
                ['name' => 'offer_banner_img'],
                ['value' => $imgName]
            );
        }

        if ($request->has('offer_banner_link')) {
            SiteSetting::updateOrCreate(
                ['name' => 'offer_banner_link'],
                ['value' => $request->input('offer_banner_link') ?? '']
            );
        }

        if ($request->has('offer_banner_title')) {
            SiteSetting::updateOrCreate(
                ['name' => 'offer_banner_title'],
                ['value' => $request->input('offer_banner_title') ?? '']
            );
        }

        return back()->with('success', 'অফার ব্যানার সফলভাবে আপডেট করা হয়েছে');
    }

    public function OfferBannerDelete()
    {
        $setting = SiteSetting::where('name', 'offer_banner_img')->first();
        if ($setting && $setting->value) {
            $oldPath = base_path('public/upload/slider/' . $setting->value);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
            $setting->delete();
        }
        SiteSetting::where('name', 'offer_banner_link')->delete();
        SiteSetting::where('name', 'offer_banner_title')->delete();

        return back()->with('success', 'অফার ব্যানার মুছে ফেলা হয়েছে');
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
