<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Ui\DesignTemplate;
use App\Models\Ui\UiConfiguration;
use App\Models\Ui\NavigationMenu;
use App\Models\Ui\NavigationMenuItem;
use App\Models\Ui\HomepageSection;
use App\Models\Ui\ThemeVersion;
use App\Models\Ui\ThemeChangeLog;

class UiCustomizationController extends Controller
{
    /**
     * Appearance & Design Studio Main Dashboard
     */
    public function index()
    {
        $templates = DesignTemplate::orderBy('id', 'asc')->get();
        $config = UiConfiguration::getActivePublished() ?: UiConfiguration::first();
        $draftConfig = UiConfiguration::where('status', 'draft')->latest('id')->first() ?: $config;

        $sections = HomepageSection::orderBy('sort_order_web', 'asc')->get();
        $headerMenu = NavigationMenu::where('key', 'header_menu')->first();
        $appBottomMenu = NavigationMenu::where('key', 'app_bottom_nav')->first();

        $menuItems = $headerMenu ? $headerMenu->allItems()->get() : collect();
        $appMenuItems = $appBottomMenu ? $appBottomMenu->allItems()->get() : collect();

        $versions = ThemeVersion::orderBy('id', 'desc')->take(10)->get();

        return view('admin.appearance.index', compact(
            'templates',
            'config',
            'draftConfig',
            'sections',
            'headerMenu',
            'menuItems',
            'appBottomMenu',
            'appMenuItems',
            'versions'
        ));
    }

    /**
     * Switch / Activate a Template
     */
    public function activateTemplate(Request $request)
    {
        $request->validate([
            'template_slug' => 'required|exists:design_templates,slug',
        ]);

        $template = DesignTemplate::where('slug', $request->template_slug)->firstOrFail();
        $config = UiConfiguration::where('status', 'draft')->first();
        if (!$config) {
            $config = UiConfiguration::getActivePublished() ? clone UiConfiguration::getActivePublished() : new UiConfiguration();
            $config->id = null;
            $config->status = 'draft';
        }

        $config->active_template_slug = $template->slug;
        if (!empty($template->default_config)) {
            $default = $template->default_config;
            $colors = $config->global_colors ?: [];
            if (!empty($default['primary_color'])) $colors['primary'] = $default['primary_color'];
            if (!empty($default['secondary_color'])) $colors['secondary'] = $default['secondary_color'];
            if (!empty($default['header_bg'])) $colors['header_bg'] = $default['header_bg'];
            if (!empty($default['button_color'])) $colors['button'] = $default['button_color'];
            $config->global_colors = $colors;

            $header = $config->header_config ?: [];
            if (!empty($default['header_layout'])) $header['layout'] = $default['header_layout'];
            $config->header_config = $header;
        }

        $config->save();

        ThemeChangeLog::create([
            'user_id' => Auth::id(),
            'action' => 'activate_template',
            'module' => 'templates',
            'diff_payload' => ['template_slug' => $template->slug],
            'ip_address' => $request->ip(),
        ]);

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => "টেমপ্লেট '{$template->name_bn}' সফলভাবে সক্রিয় করা হয়েছে!",
            'template' => $template,
        ]);
    }

    /**
     * Update Homepage Sections Order and Visibility (Drag & Drop)
     */
    public function updateSectionsOrder(Request $request)
    {
        $request->validate([
            'sections' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->sections as $secData) {
                if (isset($secData['id'])) {
                    $sec = HomepageSection::find($secData['id']);
                    if ($sec) {
                        if (isset($secData['sort_order_web'])) $sec->sort_order_web = (int)$secData['sort_order_web'];
                        if (isset($secData['sort_order_app'])) $sec->sort_order_app = (int)$secData['sort_order_app'];
                        if (isset($secData['is_enabled_web'])) $sec->is_enabled_web = (bool)$secData['is_enabled_web'];
                        if (isset($secData['is_enabled_app'])) $sec->is_enabled_app = (bool)$secData['is_enabled_app'];
                        $sec->save();
                    }
                }
            }
        });

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => 'হোমপেজ সেকশনের ক্রম এবং অন/অফ স্ট্যাটাস সফলভাবে সংরক্ষিত হয়েছে!',
        ]);
    }

    /**
     * Update Individual Section Settings (Title, Subtitle, Settings JSON)
     */
    public function updateSectionSettings(Request $request, $id)
    {
        $section = HomepageSection::findOrFail($id);
        $section->title_bn = $request->title_bn ?? $section->title_bn;
        $section->title_en = $request->title_en ?? $section->title_en;
        $section->subtitle_bn = $request->subtitle_bn;
        $section->subtitle_en = $request->subtitle_en;
        if ($request->has('is_enabled_web')) $section->is_enabled_web = (bool)$request->is_enabled_web;
        if ($request->has('is_enabled_app')) $section->is_enabled_app = (bool)$request->is_enabled_app;
        if ($request->has('settings')) {
            $section->section_settings = $request->settings;
        }
        $section->save();

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => "সেকশন '{$section->title_bn}' এর সেটিংস আপডেট হয়েছে!",
            'section' => $section,
        ]);
    }

    /**
     * Update Menu Items Order and Visibility (Drag & Drop)
     */
    public function updateMenuOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->items as $itemData) {
                if (isset($itemData['id'])) {
                    $item = NavigationMenuItem::find($itemData['id']);
                    if ($item) {
                        if (isset($itemData['sort_order'])) $item->sort_order = (int)$itemData['sort_order'];
                        if (isset($itemData['show_on_web'])) $item->show_on_web = (bool)$itemData['show_on_web'];
                        if (isset($itemData['show_on_app'])) $item->show_on_app = (bool)$itemData['show_on_app'];
                        if (isset($itemData['is_active'])) $item->is_active = (bool)$itemData['is_active'];
                        $item->save();
                    }
                }
            }
        });

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => 'মেনু বিন্যাস ও ভিজিবিলিটি সফলভাবে সংরক্ষিত হয়েছে!',
        ]);
    }

    /**
     * Add or Edit a Navigation Menu Item
     */
    public function saveMenuItem(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:navigation_menus,id',
            'title_bn' => 'required|string',
            'title_en' => 'required|string',
        ]);

        $item = $request->id ? NavigationMenuItem::findOrFail($request->id) : new NavigationMenuItem();
        $item->menu_id = $request->menu_id;
        $item->parent_id = $request->parent_id ?: null;
        $item->title_bn = $request->title_bn;
        $item->title_en = $request->title_en;
        $item->url = $request->url ?: '/';
        $item->route_name = $request->route_name ?: null;
        $item->icon = $request->icon ?: null;
        $item->badge_text = $request->badge_text ?: null;
        $item->show_on_web = $request->has('show_on_web') ? (bool)$request->show_on_web : true;
        $item->show_on_app = $request->has('show_on_app') ? (bool)$request->show_on_app : true;
        $item->is_active = $request->has('is_active') ? (bool)$request->is_active : true;
        $item->target = $request->target ?: '_self';
        if (!$request->id) {
            $item->sort_order = NavigationMenuItem::where('menu_id', $request->menu_id)->max('sort_order') + 1;
        }
        $item->save();

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => 'মেনু আইটেম সফলভাবে সংরক্ষিত হয়েছে!',
            'item' => $item,
        ]);
    }

    /**
     * Delete a Navigation Menu Item
     */
    public function deleteMenuItem($id)
    {
        $item = NavigationMenuItem::findOrFail($id);
        $item->delete();

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => 'মেনু আইটেমটি ডিলিট করা হয়েছে!',
        ]);
    }

    /**
     * Save Full Global Customization Settings (Draft or Published)
     */
    public function saveGlobalSettings(Request $request)
    {
        $isPublish = $request->input('action_type') === 'publish';

        $config = UiConfiguration::where('status', 'draft')->first();
        if (!$config) {
            $config = new UiConfiguration();
        }

        if ($request->has('active_template_slug')) {
            $config->active_template_slug = $request->active_template_slug;
        }
        if ($request->has('global_colors')) {
            $config->global_colors = $request->global_colors;
        }
        if ($request->has('typography')) {
            $config->typography = $request->typography;
        }
        if ($request->has('header_config')) {
            $config->header_config = $request->header_config;
        }
        if ($request->has('footer_config')) {
            $config->footer_config = $request->footer_config;
        }
        if ($request->has('dark_mode_config')) {
            $config->dark_mode_config = $request->dark_mode_config;
        }
        if ($request->has('mobile_app_config')) {
            $config->mobile_app_config = $request->mobile_app_config;
        }

        $config->created_by = Auth::id();

        if ($isPublish) {
            // Archive old published configs
            UiConfiguration::where('status', 'published')->update(['status' => 'archived']);

            $newVersion = 'v' . (ThemeVersion::count() + 1) . '.0';
            $config->version_code = $newVersion;
            $config->status = 'published';
            $config->published_at = now();
            $config->save();

            // Create Snapshot in ThemeVersion
            ThemeVersion::create([
                'version_number' => $newVersion,
                'template_slug' => $config->active_template_slug,
                'full_config_snapshot' => $config->toArray(),
                'note' => $request->publish_note ?: 'Published via Appearance Studio',
                'created_by' => Auth::id(),
                'is_active' => true,
            ]);

            ThemeChangeLog::create([
                'user_id' => Auth::id(),
                'action' => 'publish_config',
                'module' => 'global',
                'diff_payload' => ['version' => $newVersion],
                'ip_address' => $request->ip(),
            ]);

            $this->clearUiCache();

            return response()->json([
                'success' => true,
                'message' => "অভিনন্দন! নতুন কনফিগারেশন ({$newVersion}) লাইভ পাবলিশ করা হয়েছে!",
                'version' => $newVersion,
            ]);
        } else {
            $config->status = 'draft';
            $config->save();

            return response()->json([
                'success' => true,
                'message' => 'ড্রাফট সফলভাবে সংরক্ষিত হয়েছে। লাইভ প্রিভিউতে পরিবর্তন দেখতে পারেন।',
            ]);
        }
    }

    /**
     * Rollback to a specific Version
     */
    public function rollbackVersion($versionId)
    {
        $version = ThemeVersion::findOrFail($versionId);
        $snapshot = $version->full_config_snapshot;

        UiConfiguration::where('status', 'published')->update(['status' => 'archived']);

        $restored = new UiConfiguration();
        $restored->version_code = $version->version_number . '-restored';
        $restored->status = 'published';
        $restored->active_template_slug = $snapshot['active_template_slug'] ?? 'template_01_modern_agriculture';
        $restored->global_colors = $snapshot['global_colors'] ?? [];
        $restored->typography = $snapshot['typography'] ?? [];
        $restored->header_config = $snapshot['header_config'] ?? [];
        $restored->footer_config = $snapshot['footer_config'] ?? [];
        $restored->dark_mode_config = $snapshot['dark_mode_config'] ?? [];
        $restored->mobile_app_config = $snapshot['mobile_app_config'] ?? [];
        $restored->created_by = Auth::id();
        $restored->published_at = now();
        $restored->save();

        ThemeVersion::where('is_active', true)->update(['is_active' => false]);
        $version->is_active = true;
        $version->save();

        $this->clearUiCache();

        return response()->json([
            'success' => true,
            'message' => "ভার্সন {$version->version_number}-এ সফলভাবে রোলব্যাক সম্পন্ন হয়েছে!",
        ]);
    }

    /**
     * Clear all cached UI config responses
     */
    private function clearUiCache()
    {
        Cache::forget('ui_config_web_published');
        Cache::forget('ui_config_web_draft');
        Cache::forget('ui_config_app_published');
        Cache::forget('ui_config_app_draft');
    }
}
