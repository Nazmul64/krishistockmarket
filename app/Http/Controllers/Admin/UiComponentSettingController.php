<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ui\UiComponentSetting;
use Illuminate\Support\Facades\Cache;

class UiComponentSettingController extends Controller
{
    /**
     * Display the UI Component Customization Panel.
     */
    public function index()
    {
        $rawSettings = UiComponentSetting::all()->keyBy('key');
        $cachedSettings = UiComponentSetting::getAllCached();
        $apiPayload = UiComponentSetting::getApiPayload();

        $groups = [
            'product_card' => [
                'title_bn' => 'প্রোডাক্ট কার্ড ডিজাইন (ডেস্কটপ ও মোবাইল সাইজ)',
                'title_en' => 'Product Card Typography & Dimensions (Desktop & Mobile)',
                'icon'     => 'fa fa-shopping-bag',
                'color'    => 'text-success',
            ],
            'preloader' => [
                'title_bn' => 'মাল্টিপল প্রিলোডার ও অন/অফ সুইচ',
                'title_en' => 'Multiple Preloader Styles & On/Off Toggle',
                'icon'     => 'fa fa-spinner',
                'color'    => 'text-warning',
            ],
            'contact_section' => [
                'title_bn' => 'Get in Touch With Our Team (কন্টাক্ট সেকশন অন/অফ)',
                'title_en' => 'Contact Section Toggle (Web & Apps)',
                'icon'     => 'fa fa-envelope',
                'color'    => 'text-info',
            ],
            'buttons' => [
                'title_bn' => 'সিস্টেম বাটন কালার (সব বাটন এক রঙ)',
                'title_en' => 'Global Button Styling (Unified Color & Radius)',
                'icon'     => 'fa fa-hand-pointer-o',
                'color'    => 'text-primary',
            ],
            'search_bar' => [
                'title_bn' => 'সার্চ বার ও QSearch বাটন কালার',
                'title_en' => 'Search Bar & Submit Button Styling',
                'icon'     => 'fa fa-search',
                'color'    => 'text-danger',
            ],
            'floating_triggers' => [
                'title_bn' => 'লাইভ চ্যাট ও স্ক্রল-আপ অ্যারো বাটন',
                'title_en' => 'Floating Live Chat & Scroll-to-Top Triggers',
                'icon'     => 'fa fa-comments',
                'color'    => 'text-dark',
            ],
        ];

        return view('admin.appearance.components', compact('rawSettings', 'cachedSettings', 'apiPayload', 'groups'));
    }

    /**
     * Save/Update UI Component Settings.
     */
    public function saveSettings(Request $request)
    {
        $submitted = $request->except(['_token']);

        // Handle unchecked boolean checkboxes
        $booleanKeys = [
            'preloader_enabled',
            'contact_section_enabled',
            'global_button_animation',
            'chat_button_enabled',
            'scroll_button_enabled',
        ];

        foreach ($booleanKeys as $bKey) {
            $submitted[$bKey] = $request->has($bKey) ? '1' : '0';
        }

        foreach ($submitted as $key => $value) {
            UiComponentSetting::set($key, $value);
        }

        UiComponentSetting::clearCache();

        return redirect()->back()->with('success', 'ইউআই কম্পোনেন্ট সেটিংস সফলভাবে সংরক্ষিত ও কার্যকর করা হয়েছে! ওয়েবসাইট ও মোবাইল অ্যাপে তাত্ক্ষণিক পরিবর্তন ঘটেছে।');
    }

    /**
     * RESTful API: Get all component settings for Mobile Apps & Web.
     */
    public function getApiConfig()
    {
        return response()->json(UiComponentSetting::getApiPayload());
    }

    /**
     * RESTful API: Update component settings programmatically.
     */
    public function updateApiConfig(Request $request)
    {
        $data = $request->all();
        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'No data provided'], 400);
        }

        foreach ($data as $key => $val) {
            UiComponentSetting::set($key, $val);
        }

        UiComponentSetting::clearCache();

        return response()->json([
            'success' => true,
            'message' => 'UI Component settings updated successfully',
            'data'    => UiComponentSetting::getApiPayload(),
        ]);
    }
}
