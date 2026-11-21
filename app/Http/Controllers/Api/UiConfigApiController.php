<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Ui\UiConfiguration;
use App\Models\Ui\DesignTemplate;
use App\Models\Ui\NavigationMenu;
use App\Models\Ui\HomepageSection;

class UiConfigApiController extends Controller
{
    /**
     * Get Centralized Full UI Configuration for Web and Mobile App.
     */
    public function getFullConfig(Request $request)
    {
        $platform = $request->query('platform', 'web'); // 'web' or 'app'
        $isDraft = $request->query('preview', '0') === '1';

        $cacheKey = "ui_config_{$platform}_" . ($isDraft ? 'draft' : 'published');

        // Fast response with cached config in production, bypass when previewing
        if (!$isDraft && Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey));
        }

        $config = $isDraft
            ? (UiConfiguration::where('status', 'draft')->latest('id')->first() ?: UiConfiguration::getActivePublished())
            : UiConfiguration::getActivePublished();

        if (!$config) {
            $config = UiConfiguration::first();
        }

        // Active template info
        $template = DesignTemplate::where('slug', $config->active_template_slug)->first();

        // Navigation Menus
        $menus = NavigationMenu::where('is_active', true)->with(['items' => function ($q) use ($platform) {
            if ($platform === 'app') {
                $q->where('show_on_app', true);
            } else {
                $q->where('show_on_web', true);
            }
            $q->where('is_active', true)->with('children');
        }])->get()->keyBy('key');

        // Homepage Sections (Sorted according to platform)
        $orderField = $platform === 'app' ? 'sort_order_app' : 'sort_order_web';
        $enabledField = $platform === 'app' ? 'is_enabled_app' : 'is_enabled_web';

        $sections = HomepageSection::where($enabledField, true)
            ->orderBy($orderField, 'asc')
            ->get();

        $payload = [
            'success' => true,
            'version' => $config->version_code,
            'status' => $config->status,
            'template' => [
                'slug' => $template ? $template->slug : $config->active_template_slug,
                'name_en' => $template ? $template->name_en : 'Default Template',
                'name_bn' => $template ? $template->name_bn : 'ডিফল্ট টেমপ্লেট',
                'category' => $template ? $template->category : 'General',
                'preview_thumbnail' => $template ? $template->preview_thumbnail : null,
            ],
            'global_colors' => $config->global_colors ?: [],
            'typography' => $config->typography ?: [],
            'header' => $config->header_config ?: [],
            'footer' => $config->footer_config ?: [],
            'dark_mode' => $config->dark_mode_config ?: [],
            'mobile_app' => $config->mobile_app_config ?: [],
            'components' => \App\Models\Ui\UiComponentSetting::getApiPayload(),
            'menus' => $menus,
            'sections' => $sections,
            'published_at' => $config->published_at ? $config->published_at->toIso8601String() : null,
        ];

        if (!$isDraft) {
            Cache::put($cacheKey, $payload, 3600); // 1 hour cache
        }

        return response()->json($payload);
    }

    /**
     * Get list of all 10 templates.
     */
    public function getTemplates()
    {
        $templates = DesignTemplate::where('is_active', true)->get();
        return response()->json([
            'success' => true,
            'templates' => $templates,
        ]);
    }

    /**
     * Get active homepage sections.
     */
    public function getHomeSections(Request $request)
    {
        $platform = $request->query('platform', 'web');
        $orderField = $platform === 'app' ? 'sort_order_app' : 'sort_order_web';
        $enabledField = $platform === 'app' ? 'is_enabled_app' : 'is_enabled_web';

        $sections = HomepageSection::where($enabledField, true)
            ->orderBy($orderField, 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }
}
