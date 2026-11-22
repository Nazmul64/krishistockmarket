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
        SiteSetting::updateOrCreate(['name' => 'site_font_family'], ['value' => $request->site_font_family ?? 'Hind Siliguri']);
        SiteSetting::updateOrCreate(['name' => 'top_notice'], ['value' => $request->top_notice ?? '']);

        // Theme Colors & Frontend Display Controls (Header, Button, Animation, Preloader, Why Choose Us)
        $headerColor = $request->header_bg_color ?: ($request->header_color ?: '#ea580c');
        SiteSetting::updateOrCreate(['name' => 'header_bg_color'], ['value' => $headerColor]);
        SiteSetting::updateOrCreate(['name' => 'header_color'], ['value' => $headerColor]);

        $buttonColor = $request->button_color ?: '#007e8a';
        SiteSetting::updateOrCreate(['name' => 'button_color'], ['value' => $buttonColor]);

        // Button Animation toggle: 1 (on) or 0 (off)
        $buttonAnimation = ($request->has('button_animation') && in_array($request->button_animation, ['1', 'on', 'true', 1], true)) ? '1' : '0';
        SiteSetting::updateOrCreate(['name' => 'button_animation'], ['value' => $buttonAnimation]);

        // Preloader Status toggle: 1 (on) or 0 (off)
        $preloaderStatus = ($request->has('preloader_status') && in_array($request->preloader_status, ['1', 'on', 'true', 1], true)) ? '1' : '0';
        SiteSetting::updateOrCreate(['name' => 'preloader_status'], ['value' => $preloaderStatus]);

        // Why Choose Us Section toggle: 1 (on) or 0 (off)
        $whyChooseUsStatus = ($request->has('why_choose_us_status') && in_array($request->why_choose_us_status, ['1', 'on', 'true', 1], true)) ? '1' : '0';
        SiteSetting::updateOrCreate(['name' => 'why_choose_us_status'], ['value' => $whyChooseUsStatus]);

        // 3 Header Navigation Controls (On/Off & Custom Titles: Packages, Stock Market, Membership Cards)
        $menuPackagesStatus = ($request->has('menu_packages_status') && in_array($request->menu_packages_status, ['1', 'on', 'true', 1], true)) ? '1' : '0';
        SiteSetting::updateOrCreate(['name' => 'menu_packages_status'], ['value' => $menuPackagesStatus]);
        SiteSetting::updateOrCreate(['name' => 'menu_packages_name_bn'], ['value' => $request->menu_packages_name_bn ?: 'প্যাকেজসমূহ']);
        SiteSetting::updateOrCreate(['name' => 'menu_packages_name_en'], ['value' => $request->menu_packages_name_en ?: 'Our Packages']);

        $menuStockStatus = ($request->has('menu_stock_status') && in_array($request->menu_stock_status, ['1', 'on', 'true', 1], true)) ? '1' : '0';
        SiteSetting::updateOrCreate(['name' => 'menu_stock_status'], ['value' => $menuStockStatus]);
        SiteSetting::updateOrCreate(['name' => 'menu_stock_name_bn'], ['value' => $request->menu_stock_name_bn ?: 'স্টক মার্কেট']);
        SiteSetting::updateOrCreate(['name' => 'menu_stock_name_en'], ['value' => $request->menu_stock_name_en ?: 'Stock Market']);

        $menuMembershipStatus = ($request->has('menu_membership_status') && in_array($request->menu_membership_status, ['1', 'on', 'true', 1], true)) ? '1' : '0';
        SiteSetting::updateOrCreate(['name' => 'menu_membership_status'], ['value' => $menuMembershipStatus]);
        SiteSetting::updateOrCreate(['name' => 'menu_membership_name_bn'], ['value' => $request->menu_membership_name_bn ?: 'মেম্বারশিপ কার্ড']);
        SiteSetting::updateOrCreate(['name' => 'menu_membership_name_en'], ['value' => $request->menu_membership_name_en ?: 'Membership Cards']);

        SiteSetting::updateOrCreate(['name' => 'google_map_embed'], ['value' => $request->google_map_embed ?? '']);
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
        SiteSetting::updateOrCreate(['name' => 'instagram_link'], ['value' => $request->instagram_link ?? '']);
        SiteSetting::updateOrCreate(['name' => 'youtube_link'], ['value' => $request->youtube_link ?? '']);
        SiteSetting::updateOrCreate(['name' => 'footer_about'], ['value' => $request->footer_about ?? '']);
        SiteSetting::updateOrCreate(['name' => 'footer_copyright'], ['value' => $request->footer_copyright ?? '']);
        SiteSetting::updateOrCreate(['name' => 'footer_developed_by'], ['value' => $request->footer_developed_by ?? '']);
        SiteSetting::updateOrCreate(['name' => 'footer_developed_by_url'], ['value' => $request->footer_developed_by_url ?? '']);

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

    public function NoticeSetting()
    {
        $current_notice = setting('top_notice') ?: (setting('notice') ?: '');
        $notice_status = setting('notice_status') !== '0';
        return view('admin.setting.notice', compact('current_notice', 'notice_status'));
    }

    public function NoticeSettingPost(Request $request)
    {
        $request->validate([
            'top_notice' => 'required|string|max:1000',
        ]);

        SiteSetting::updateOrCreate(
            ['name' => 'top_notice'],
            ['value' => trim($request->input('top_notice'))]
        );

        SiteSetting::updateOrCreate(
            ['name' => 'notice_status'],
            ['value' => $request->has('notice_status') ? '1' : '0']
        );

        return back()->with('success', 'মারকুই নোটিশ সফলভাবে আপডেট করা হয়েছে!');
    }

    public function NoticeDelete()
    {
        SiteSetting::where('name', 'top_notice')->delete();
        SiteSetting::where('name', 'notice')->delete();

        return back()->with('success', 'মারকুই নোটিশ সফলভাবে মুছে ফেলা হয়েছে');
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
