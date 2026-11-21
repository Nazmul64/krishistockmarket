<?php

namespace App\Models\Ui;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Admin\SiteSetting;

class UiComponentSetting extends Model
{
    use HasFactory;

    protected $table = 'ui_component_settings';

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'title_bn',
        'title_en',
        'device',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    const CACHE_KEY = 'ui_component_settings_all_cache';

    /**
     * Get a setting value with caching and automatic type casting.
     */
    public static function get(string $key, $default = null)
    {
        $all = static::getAllCached();
        if (isset($all[$key])) {
            return $all[$key];
        }
        return $default;
    }

    /**
     * Get all settings from cache as key => casted_value array.
     */
    public static function getAllCached(): array
    {
        return Cache::remember(self::CACHE_KEY, 3600, function () {
            $items = static::all();
            $result = [];
            foreach ($items as $item) {
                $val = $item->value;
                if ($item->type === 'boolean') {
                    $val = ($val === '1' || $val === 'true' || $val === true);
                } elseif ($item->type === 'number') {
                    $val = is_numeric($val) ? (float)$val : $val;
                } elseif ($item->type === 'json') {
                    $val = is_string($val) ? json_decode($val, true) : $val;
                }
                $result[$item->key] = $val;
            }
            return $result;
        });
    }

    /**
     * Clear the cache.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget('ui_config_web_published');
        Cache::forget('ui_config_app_published');
        Cache::forget('ui_config_web_draft');
        Cache::forget('ui_config_app_draft');
        Cache::forget('homepage_data_cache_v3');
    }

    /**
     * Set a setting value.
     */
    public static function set(string $key, $value, ?string $group = null, ?string $type = null)
    {
        $stringVal = is_bool($value) ? ($value ? '1' : '0') : (is_array($value) ? json_encode($value) : (string)$value);

        $record = static::where('key', $key)->first();
        if ($record) {
            $record->value = $stringVal;
            if ($group) $record->group = $group;
            if ($type) $record->type = $type;
            $record->save();
        } else {
            static::create([
                'key' => $key,
                'value' => $stringVal,
                'group' => $group ?: 'general',
                'type' => $type ?: 'string',
            ]);
        }

        // Sync with legacy site_settings table if needed
        $syncMap = [
            'global_button_bg_color'   => 'button_color',
            'global_button_animation'  => 'button_animation',
            'preloader_enabled'        => 'preloader_status',
            'search_button_bg_color'   => 'search_button_color',
        ];

        if (isset($syncMap[$key])) {
            $legacyKey = $syncMap[$key];
            SiteSetting::updateOrCreate(['name' => $legacyKey], ['value' => $stringVal]);
        }

        static::clearCache();
        return $stringVal;
    }

    /**
     * Batch update multiple settings at once.
     */
    public static function batchUpdate(array $data): void
    {
        foreach ($data as $k => $v) {
            static::set($k, $v);
        }
    }

    /**
     * Get structured RESTful API payload for Mobile Apps (Flutter/React Native) and Frontend.
     */
    public static function getApiPayload(): array
    {
        $s = static::getAllCached();

        return [
            'success' => true,
            'product_card' => [
                'title' => [
                    'size_desktop' => (int)($s['product_title_size_desktop'] ?? 15),
                    'size_mobile'  => (int)($s['product_title_size_mobile'] ?? 13),
                    'color'        => $s['product_title_color'] ?? '#1e293b',
                    'font_weight'  => (string)($s['product_title_weight'] ?? '700'),
                ],
                'price' => [
                    'size_desktop' => (int)($s['product_price_size_desktop'] ?? 18),
                    'size_mobile'  => (int)($s['product_price_size_mobile'] ?? 15),
                    'color'        => $s['product_price_color'] ?? '#10b981',
                    'font_weight'  => (string)($s['product_price_weight'] ?? '700'),
                ],
                'order_button' => [
                    'text'         => $s['product_order_btn_text'] ?? 'Order Now',
                    'size_desktop' => (int)($s['product_order_btn_size_desktop'] ?? 14),
                    'size_mobile'  => (int)($s['product_order_btn_size_mobile'] ?? 12),
                    'bg_color'     => $s['global_button_bg_color'] ?? '#007e8a',
                    'text_color'   => $s['global_button_text_color'] ?? '#ffffff',
                ],
                'image' => [
                    'height_desktop' => (int)($s['product_img_height_desktop'] ?? 145),
                    'width_desktop'  => $s['product_img_width_desktop'] ?? '100%',
                    'height_mobile'  => (int)($s['product_img_height_mobile'] ?? 115),
                    'width_mobile'   => $s['product_img_width_mobile'] ?? '100%',
                    'object_fit'     => $s['product_img_fit'] ?? 'contain',
                ],
            ],
            'preloader' => [
                'enabled'       => (bool)($s['preloader_enabled'] ?? true),
                'type'          => $s['preloader_type'] ?? 'cinematic_orbital',
                'primary_color' => $s['preloader_primary_color'] ?? '#ea580c',
                'available_types' => [
                    'cinematic_orbital' => 'Cinematic Orbital Ring',
                    'spinner_circular'  => 'Circular Dual Spinner',
                    'pulse_logo'        => 'Pulse Logo Wave',
                    'leaf_agriculture'  => 'Eco Leaf & Growth',
                    'progress_bar'      => 'Minimal Top Bar',
                ],
            ],
            'contact_section' => [
                'enabled'  => (bool)($s['contact_section_enabled'] ?? true),
                'title'    => 'Get in Touch With Our Team',
                'subtitle' => 'Have any questions or need investment guidance? Feel free to write to us.',
            ],
            'global_buttons' => [
                'bg_color'       => $s['global_button_bg_color'] ?? '#007e8a',
                'text_color'     => $s['global_button_text_color'] ?? '#ffffff',
                'hover_bg_color' => $s['global_button_hover_bg_color'] ?? '#005f69',
                'border_radius'  => (int)($s['global_button_border_radius'] ?? 50),
                'animation'      => (bool)($s['global_button_animation'] ?? true),
            ],
            'search_bar' => [
                'bg_color'          => $s['search_bar_bg_color'] ?? '#ffffff',
                'border_color'      => $s['search_bar_border_color'] ?? '#e2e8f0',
                'button_bg_color'   => $s['search_button_bg_color'] ?? '#ea580c',
                'button_text_color' => $s['search_button_text_color'] ?? '#ffffff',
                'typewriter_phrases' => [
                    'bn' => [
                        'সার্চ করুন স্টক, প্যাকেজ, পণ্য ও কার্ড...',
                        'মাসিক বাজার ও নিত্যপ্রয়োজনীয় সুপারশপ অফার খুঁজুন...',
                        'কৃষি প্যাকেজ, স্টোর ও স্পেশাল ডিসকাউন্ট খুঁজুন...',
                        'লাইভ স্টক মার্কেট রেট ও বাজারদর যাচাই করুন...',
                        'জমি, প্লট, ফ্ল্যাট ও প্রোপার্টি লিস্ট খুঁজুন...',
                        'মেম্বারশিপ কার্ড ও বিশেষ ক্যাশব্যাক অফার খুঁজুন...',
                    ],
                    'en' => [
                        'Search stocks, packages, products & cards...',
                        'Search monthly bazar & daily supermarket items...',
                        'Search agri packages, store items & cart deals...',
                        'Search live stock market & commodity rates...',
                        'Search land, plots, flats & properties...',
                        'Search membership cards & exclusive bonus...',
                    ],
                ],
            ],
            'floating_triggers' => [
                'chat' => [
                    'enabled'    => (bool)($s['chat_button_enabled'] ?? true),
                    'bg_color'   => $s['chat_button_bg_color'] ?? '#ea580c',
                    'icon_color' => $s['chat_button_icon_color'] ?? '#ffffff',
                ],
                'scroll_to_top' => [
                    'enabled'    => (bool)($s['scroll_button_enabled'] ?? true),
                    'bg_color'   => $s['scroll_button_bg_color'] ?? '#ea580c',
                    'icon_color' => $s['scroll_button_icon_color'] ?? '#ffffff',
                ],
            ],
        ];
    }

    /**
     * Generate ready-to-inject CSS Variables string.
     */
    public static function getCssVariables(): string
    {
        $s = static::getAllCached();

        $titleSizeDesk = ($s['product_title_size_desktop'] ?? 15) . 'px';
        $titleSizeMob  = ($s['product_title_size_mobile'] ?? 13) . 'px';
        $titleColor    = $s['product_title_color'] ?? '#1e293b';
        $titleWeight   = $s['product_title_weight'] ?? '700';

        $priceSizeDesk = ($s['product_price_size_desktop'] ?? 18) . 'px';
        $priceSizeMob  = ($s['product_price_size_mobile'] ?? 15) . 'px';
        $priceColor    = $s['product_price_color'] ?? '#10b981';
        $priceWeight   = $s['product_price_weight'] ?? '700';

        $btnTextSizeDesk = ($s['product_order_btn_size_desktop'] ?? 14) . 'px';
        $btnTextSizeMob  = ($s['product_order_btn_size_mobile'] ?? 12) . 'px';

        $imgHeightDesk = ($s['product_img_height_desktop'] ?? 145) . 'px';
        $imgWidthDesk  = $s['product_img_width_desktop'] ?? '100%';
        if (is_numeric($imgWidthDesk)) $imgWidthDesk .= 'px';

        $imgHeightMob  = ($s['product_img_height_mobile'] ?? 115) . 'px';
        $imgWidthMob   = $s['product_img_width_mobile'] ?? '100%';
        if (is_numeric($imgWidthMob)) $imgWidthMob .= 'px';

        $imgFit        = $s['product_img_fit'] ?? 'contain';

        $btnBgColor    = $s['global_button_bg_color'] ?? '#007e8a';
        $btnTextColor  = $s['global_button_text_color'] ?? '#ffffff';
        $btnHoverBg    = $s['global_button_hover_bg_color'] ?? '#005f69';
        $btnRadius     = ($s['global_button_border_radius'] ?? 50) . 'px';

        $searchBg      = $s['search_bar_bg_color'] ?? '#ffffff';
        $searchBorder  = $s['search_bar_border_color'] ?? '#e2e8f0';
        $searchBtnBg   = $s['search_button_bg_color'] ?? '#ea580c';
        $searchBtnText = $s['search_button_text_color'] ?? '#ffffff';

        $chatBg        = $s['chat_button_bg_color'] ?? '#ea580c';
        $chatIcon      = $s['chat_button_icon_color'] ?? '#ffffff';
        $scrollBg      = $s['scroll_button_bg_color'] ?? '#ea580c';
        $scrollIcon    = $s['scroll_button_icon_color'] ?? '#ffffff';

        $preloaderColor = $s['preloader_primary_color'] ?? '#ea580c';

        return "
        :root {
            /* Product Card Typography & Dimensions (Desktop) */
            --product-title-size: {$titleSizeDesk};
            --product-title-color: {$titleColor};
            --product-title-weight: {$titleWeight};
            --product-price-size: {$priceSizeDesk};
            --product-price-color: {$priceColor};
            --product-price-weight: {$priceWeight};
            --product-btn-size: {$btnTextSizeDesk};
            --product-img-height: {$imgHeightDesk};
            --product-img-width: {$imgWidthDesk};
            --product-img-fit: {$imgFit};

            /* Global Buttons */
            --btn-theme-color: {$btnBgColor};
            --btn-theme-text-color: {$btnTextColor};
            --btn-theme-hover: {$btnHoverBg};
            --btn-theme-radius: {$btnRadius};

            /* Search Bar */
            --search-bar-bg: {$searchBg};
            --search-bar-border: {$searchBorder};
            --search-btn-bg: {$searchBtnBg};
            --search-btn-text: {$searchBtnText};

            /* Floating Triggers */
            --chat-trigger-bg: {$chatBg};
            --chat-trigger-icon: {$chatIcon};
            --scroll-trigger-bg: {$scrollBg};
            --scroll-trigger-icon: {$scrollIcon};

            /* Preloader */
            --preloader-accent: {$preloaderColor};
        }

        @media (max-width: 767.98px) {
            :root {
                --product-title-size: {$titleSizeMob} !important;
                --product-price-size: {$priceSizeMob} !important;
                --product-btn-size: {$btnTextSizeMob} !important;
                --product-img-height: {$imgHeightMob} !important;
                --product-img-width: {$imgWidthMob} !important;
            }
        }
        ";
    }
}
