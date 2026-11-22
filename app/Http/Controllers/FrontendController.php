<?php

namespace App\Http\Controllers;

use App\Models\CardBenefit;
use App\Models\Feature;
use App\Models\OurPackage;
use App\Models\Stock;
use App\Models\StockGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendController extends Controller
{
    public function getHomepageData()
    {
        // Sliders
        $sliders = [];
        for ($i = 1; $i <= 4; $i++) {
            $img = setting('slider' . $i . '_img');
            if (!empty($img)) {
                $imgPath = null;
                if (file_exists(public_path('upload/slider/' . $img))) {
                    $imgPath = asset('upload/slider/' . $img);
                } elseif (file_exists(public_path('uploads/slider/' . $img))) {
                    $imgPath = asset('uploads/slider/' . $img);
                } elseif (file_exists(public_path(ltrim($img, '/')))) {
                    $imgPath = asset(ltrim($img, '/'));
                } else {
                    $imgPath = asset('upload/slider/' . $img);
                }
                $sliders[] = [
                    'id' => $i,
                    'image' => $imgPath,
                    'title' => setting('slider' . $i . '_text') ?: 'কৃষি পরিবারে স্বাগতম',
                    'description' => setting('slider' . $i . '_description') ?: 'আধুনিক ও নিরাপদ কৃষি বিনিয়োগ প্ল্যাটফর্ম',
                ];
            }
        }
        if (empty($sliders) && class_exists(\App\Models\Shop\ShopSlider::class)) {
            $shopSliders = \App\Models\Shop\ShopSlider::active()->get();
            foreach ($shopSliders as $s) {
                $sliders[] = [
                    'id' => $s->id,
                    'image' => \Illuminate\Support\Str::startsWith($s->image, ['http://', 'https://']) ? $s->image : asset(ltrim($s->image, '/')),
                    'title' => $s->title ?? 'কৃষি পরিবারে স্বাগতম',
                    'description' => $s->subtitle ?? '',
                ];
            }
        }

        // Offer Banner
        $offer_banner = null;
        $banner_img = setting('offer_banner_img');
        if (!empty($banner_img)) {
            $bannerPath = null;
            if (file_exists(public_path('upload/slider/' . $banner_img))) {
                $bannerPath = asset('upload/slider/' . $banner_img);
            } elseif (file_exists(public_path('uploads/banner/' . $banner_img))) {
                $bannerPath = asset('uploads/banner/' . $banner_img);
            } else {
                $bannerPath = asset('upload/slider/' . $banner_img);
            }
            $offer_banner = [
                'image' => $bannerPath,
                'link' => setting('offer_banner_link') ?: '#',
                'title' => setting('offer_banner_title') ?: 'স্পেশাল অফার',
            ];
        }

        // Live Stocks
        $stocks_query = Stock::latest()->take(6)->get();
        $stocks = $stocks_query->map(function ($stock) {
            $gallery = StockGallery::where('stock_id', $stock->id)->first();
            $image_path = $gallery ? asset('upload/stock_images/' . $gallery->image) : asset('upload/images/backend/logo/' . (setting('logo') ?: 'default-logo.png'));
            
            $last_price = function_exists('StockLastPricing') ? StockLastPricing($stock->id) : null;
            $selling_price = $last_price ? (float)$last_price->selling_price : 0;
            $buying_price = $last_price ? (float)$last_price->buying_price : 0;

            return [
                'id' => $stock->id,
                'name' => $stock->stock_name,
                'category' => $stock->stock_type ?? 'কৃষি পণ্য',
                'quantity' => $stock->stock_quantity ?? 'উপলব্ধ',
                'unit' => $stock->unit ?? 'কেজি',
                'selling_price' => $selling_price,
                'buying_price' => $buying_price,
                'image' => $image_path,
                'details_url' => route('stock.detials', $stock->id),
                'is_unlimited' => (bool)($stock->is_unlimited ?? false),
            ];
        })->values()->all();

        // Our Packages
        $packages = OurPackage::where('status', 1)->latest()->get()->map(function ($pkg) {
            return [
                'id' => $pkg->id,
                'title' => $pkg->title ?? 'প্যাকেজ',
                'image' => \Illuminate\Support\Str::startsWith($pkg->image, ['http://', 'https://']) ? $pkg->image : asset(ltrim($pkg->image, '/')),
                'description' => $pkg->description ?? '',
                'price' => $pkg->price ?? '',
            ];
        })->values()->all();

        // Card Benefits / Membership Cards
        $card_benefits = CardBenefit::where('status', 1)->orderBy('order_num', 'asc')->get()->map(function ($card) {
            $facilities = is_array($card->facilities) ? $card->facilities : (is_string($card->facilities) ? json_decode($card->facilities, true) ?? [] : []);
            $cardImg = null;
            if ($card->image) {
                $cardImg = \Illuminate\Support\Str::startsWith($card->image, ['http://', 'https://']) ? $card->image : asset(ltrim($card->image, '/'));
            }
            $brochureImg = null;
            if ($card->brochure_image) {
                $brochureImg = \Illuminate\Support\Str::startsWith($card->brochure_image, ['http://', 'https://']) ? $card->brochure_image : asset(ltrim($card->brochure_image, '/'));
            }
            return [
                'id' => $card->id,
                'card_name' => $card->card_name,
                'card_type' => $card->card_type,
                'card_color_theme' => $card->card_color_theme ?: 'blue',
                'badge_text' => $card->badge_text ?: ($card->card_color_theme === 'gold' ? 'গোল্ডেন মেম্বারশিপ' : 'নরমাল মেম্বারশিপ'),
                'validity' => $card->validity,
                'card_number_sample' => $card->card_number_sample,
                'image' => $cardImg,
                'brochure_image' => $brochureImg,
                'short_description' => $card->short_description,
                'card_fee' => $card->card_fee,
                'investment_limit' => $card->investment_limit,
                'monthly_profit' => $card->monthly_profit,
                'withdrawal_notice' => $card->withdrawal_notice,
                'facilities' => $facilities,
                'action_button_text' => $card->action_button_text ?: 'কার্ডের জন্য আবেদন করুন',
                'action_button_url' => $card->action_button_url ?: '/user/register',
            ];
        })->values()->all();

        // Features (Why Choose Us)
        $features = Feature::all()->map(function ($feat) {
            return [
                'id' => $feat->id,
                'title' => $feat->title,
                'description' => $feat->description,
                'icon' => $feat->icon ?: 'fa-check-circle',
                'color' => $feat->color ?: '#1b88ce',
                'image' => (!empty($feat->image) && file_exists(public_path(ltrim($feat->image, '/')))) ? asset(ltrim($feat->image, '/')) : null,
            ];
        })->values()->all();

        // Product Gallery
        $gallery = StockGallery::take(9)->get()->map(function ($img) {
            return [
                'id' => $img->id,
                'title' => 'কৃষি স্টক পণ্য',
                'image' => asset('upload/stock_images/' . $img->image),
            ];
        })->values()->all();

        // Site Settings & Branding
        $logo = setting('logo');
        $favicon = setting('favicon');
        // Clean up duplicate contact info if settings have same values
        $phone1 = setting('phone1') ?: null;
        $phone2 = setting('phone2') ?: null;
        if ($phone2 && $phone2 === $phone1) {
            $phone2 = null;
        }

        $email1 = setting('email1') ?: null;
        $email2 = setting('email2') ?: null;
        if ($email2 && $email2 === $email1) {
            $email2 = null;
        }

        $address1 = setting('address1') ?: null;
        $address2 = setting('address2') ?: null;
        if ($address2 && $address2 === $address1) {
            $address2 = null;
        }

        $site = [
            'title' => setting('title') ?: 'ekrishisheba',
            'font_family' => setting('site_font_family') ?: 'Hind Siliguri',
            'font_url' => getGoogleFontUrl(),
            'logo' => $logo ? asset('upload/images/backend/logo/' . $logo) : asset('assets/images/logo.png'),
            'favicon' => $favicon ? asset('upload/images/backend/logo/' . $favicon) : null,
            'top_notice' => setting('top_notice') ?: (setting('notice') ?: 'ekrishisheba-তে স্বাগতম! আধুনিক ও নিরাপদ কৃষি প্ল্যাটফর্ম।'),
            'phone1' => $phone1,
            'phone2' => $phone2,
            'email1' => $email1,
            'email2' => $email2,
            'address1' => $address1,
            'address2' => $address2,
            'facebook' => setting('facbook_link'),
            'linkedin' => setting('linkedin_link'),
            'twitter' => setting('twitter_link'),
            'instagram' => setting('instagram_link'),
            'youtube' => setting('youtube_link'),
            'about_us_text' => setting('about_us_text') ?: 'ekrishisheba - আপনার বিশ্বস্ত কৃষি প্ল্যাটফর্ম।',
            'footer_about' => setting('footer_about') ?: (setting('about_us_text') ?: 'ekrishisheba - আপনার বিশ্বস্ত কৃষি প্ল্যাটফর্ম।'),
            'footer_copyright' => setting('footer_copyright') ?: ('© ' . date('Y') . ' ekrishisheba. All Rights Reserved.'),
            'footer_developed_by' => setting('footer_developed_by') ?: 'ekrishisheba',
            'footer_developed_by_url' => setting('footer_developed_by_url') ?: '#',
            'header_color' => (\class_exists(\App\Models\Ui\UiConfiguration::class) && \App\Models\Ui\UiConfiguration::getActivePublished() && !empty(\App\Models\Ui\UiConfiguration::getActivePublished()->global_colors['header_bg']))
                ? \App\Models\Ui\UiConfiguration::getActivePublished()->global_colors['header_bg']
                : (setting('header_bg_color') ?: (setting('header_color') ?: '#ea580c')),
            'header_bg_color' => (\class_exists(\App\Models\Ui\UiConfiguration::class) && \App\Models\Ui\UiConfiguration::getActivePublished() && !empty(\App\Models\Ui\UiConfiguration::getActivePublished()->global_colors['header_bg']))
                ? \App\Models\Ui\UiConfiguration::getActivePublished()->global_colors['header_bg']
                : (setting('header_bg_color') ?: (setting('header_color') ?: '#ea580c')),
            'button_color' => (\class_exists(\App\Models\Ui\UiConfiguration::class) && \App\Models\Ui\UiConfiguration::getActivePublished() && !empty(\App\Models\Ui\UiConfiguration::getActivePublished()->global_colors['button']))
                ? \App\Models\Ui\UiConfiguration::getActivePublished()->global_colors['button']
                : (setting('button_color') ?: '#007e8a'),
            'button_animation' => setting('button_animation', '1') !== '0' ? '1' : '0',
            'preloader_status' => setting('preloader_status', '1') !== '0' ? '1' : '0',
            'why_choose_us_status' => setting('why_choose_us_status', '1') !== '0' ? '1' : '0',

            // Dynamic Header Menu Controls (On/Off & Custom Titles)
            'menu_packages_status' => setting('menu_packages_status', '1') !== '0',
            'menu_packages_name_bn' => setting('menu_packages_name_bn') ?: 'প্যাকেজসমূহ',
            'menu_packages_name_en' => setting('menu_packages_name_en') ?: 'Our Packages',

            'menu_stock_status' => setting('menu_stock_status', '1') !== '0',
            'menu_stock_name_bn' => setting('menu_stock_name_bn') ?: 'স্টক মার্কেট',
            'menu_stock_name_en' => setting('menu_stock_name_en') ?: 'Stock Market',

            'menu_membership_status' => setting('menu_membership_status', '1') !== '0',
            'menu_membership_name_bn' => setting('menu_membership_name_bn') ?: 'মেম্বারশিপ কার্ড',
            'menu_membership_name_en' => setting('menu_membership_name_en') ?: 'Membership Cards',

            'monthly_bazar_status' => setting('monthly_bazar_status', '1') !== '0',

            'ssl_payment_logo' => setting('ssl_payment_logo') ? (
                file_exists(public_path('upload/images/backend/logo/' . setting('ssl_payment_logo')))
                    ? asset('upload/images/backend/logo/' . setting('ssl_payment_logo'))
                    : (file_exists(public_path('upload/payments/' . setting('ssl_payment_logo')))
                        ? asset('upload/payments/' . setting('ssl_payment_logo'))
                        : asset(ltrim(setting('ssl_payment_logo'), '/')))
            ) : null,
            'corporate_support_pdf' => setting('corporate_support_pdf') ? asset('upload/documents/' . setting('corporate_support_pdf')) : route('corporate.support.pdf'),
        ];

        // Auth state & Navigation URLs
        $user = Auth::user();
        $auth = [
            'is_authenticated' => (bool)$user,
            'user_name' => $user ? $user->name : null,
            'user_role' => $user ? $user->role : null,
            'dashboard_url' => route('dashboard'),
            'login_url' => route('login'),
            'register_url' => route('register'),
            'logout_url' => route('logout'),
        ];

        $routes = [
            'home' => route('/'),
            'about' => route('about'),
            'stock_index' => route('stock.index'),
            'our_products' => route('products.index'),
            'gallery' => route('gallery'),
            'contact' => route('contact'),
            'contact_send' => route('contact.send'),
            'terms' => route('terms'),
            'privacy' => route('privacy'),
            'career' => route('career'),
            'our_videos' => route('our_videos'),
            'outlets' => route('business.outlets'),
            'corporate_support' => route('corporate.support.pdf'),
            'investor' => route('business.investor'),
            'wholesale' => route('business.wholesale'),
            'franchise' => route('business.franchise'),
            'umrah_haj' => route('business.umrah_haj'),
            'live_search' => route('api.live_search'),
            'csrf_token' => csrf_token(),
        ];

        // Active Outlets / Showrooms for Homepage Showcase
        $outlets = [];
        if (class_exists(\App\Models\SuperShop\Branch::class)) {
            $outlets = \App\Models\SuperShop\Branch::where('status', 'active')
                ->orderBy('sort_order', 'asc')
                ->take(6)
                ->get()
                ->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'name' => $b->name,
                        'code' => $b->code,
                        'phone' => $b->phone,
                        'address' => $b->address,
                        'google_map_url' => $b->google_map_url ?: ('https://maps.google.com/?q=' . urlencode($b->name . ', ' . $b->address)),
                        'weekly_off_day' => $b->weekly_off_day ?: 'Daily Open',
                        'open_days_text' => $b->open_days_text ?: 'Open 7 Days',
                        'operating_days' => $b->operating_days ?: ['Sat', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                    ];
                })->values()->all();
        }

        // Monthly Bazar Products for Homepage Showcase (Right below slider)
        $monthly_bazar_products = [];
        if (class_exists(\App\Models\Shop\ShopProduct::class)) {
            $monthly_bazar_products = \App\Models\Shop\ShopProduct::where('is_active', true)
                ->where(function ($q) {
                    $q->where('is_monthly_bazar', true)
                      ->orWhere('is_featured', true);
                })
                ->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
                ->orderBy('id', 'asc')
                ->take(12)
                ->get()
                ->map(function ($p) {
                    $img = $p->image ?: 'uploads/products/default.png';
                    $imgUrl = \Illuminate\Support\Str::startsWith($img, ['http://', 'https://']) ? $img : asset(ltrim($img, '/'));
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'slug' => $p->slug,
                        'price' => (float)$p->price,
                        'formatted_price' => '৳ ' . number_format($p->price, 0),
                        'unit' => $p->unit,
                        'delivery_time' => $p->delivery_time ?: 'Delivery 1-2 hours',
                        'image' => $imgUrl,
                        'url' => route('products.show', $p->slug),
                        'stock_quantity' => (int)$p->stock_quantity,
                    ];
                })->values()->all();
        }

        // Centralized Dynamic UI Configuration (Active Template, Dynamic Menus & Homepage Sections)
        $uiConfigPayload = null;
        if (\class_exists(\App\Models\Ui\UiConfiguration::class)) {
            $activeUiConfig = \App\Models\Ui\UiConfiguration::getActivePublished() ?: \App\Models\Ui\UiConfiguration::first();
            $activeTemplate = $activeUiConfig ? \App\Models\Ui\DesignTemplate::where('slug', $activeUiConfig->active_template_slug)->first() : null;
            $homeSectionsWeb = \App\Models\Ui\HomepageSection::orderBy('sort_order_web', 'asc')->get();
            $homeSectionsApp = \App\Models\Ui\HomepageSection::where('is_enabled_app', true)->orderBy('sort_order_app', 'asc')->get();
            $headerMenu = \App\Models\Ui\NavigationMenu::where('key', 'header_menu')->first();
            $appBottomMenu = \App\Models\Ui\NavigationMenu::where('key', 'app_bottom_nav')->first();

            $headerMenuItems = $headerMenu ? $headerMenu->allItems()->where('is_active', true)->where('show_on_web', true)->get() : collect();
            $pkgStatus = setting('menu_packages_status', '1') !== '0';
            $stockStatus = setting('menu_stock_status', '1') !== '0';
            $cardStatus = setting('menu_membership_status', '1') !== '0';
            $whyChooseStatus = setting('why_choose_us_status', '1') !== '0';
            $monthlyBazarStatus = setting('monthly_bazar_status', '1') !== '0';

            // Filter and synchronize homeSectionsWeb with site settings
            $homeSectionsWeb = $homeSectionsWeb->map(function($sec) use ($stockStatus, $pkgStatus, $cardStatus, $whyChooseStatus, $monthlyBazarStatus) {
                if ($sec->section_key === 'stock_market') {
                    $sec->is_enabled_web = (bool)($sec->is_enabled_web && $stockStatus);
                } elseif ($sec->section_key === 'our_packages') {
                    $sec->is_enabled_web = (bool)($sec->is_enabled_web && $pkgStatus);
                } elseif ($sec->section_key === 'membership_cards') {
                    $sec->is_enabled_web = (bool)($sec->is_enabled_web && $cardStatus);
                } elseif ($sec->section_key === 'why_choose_us') {
                    $sec->is_enabled_web = (bool)($sec->is_enabled_web && $whyChooseStatus);
                } elseif ($sec->section_key === 'monthly_bazar') {
                    $sec->is_enabled_web = (bool)($sec->is_enabled_web && $monthlyBazarStatus);
                }
                return $sec;
            });

            $filteredHeaderMenu = $headerMenuItems->filter(function($item) use ($pkgStatus, $stockStatus, $cardStatus) {
                $url = strtolower($item->url ?? '');
                $title = strtolower($item->title_en ?? '') . ' ' . strtolower($item->title_bn ?? '');
                if (!$pkgStatus && (str_contains($url, 'package') || str_contains($title, 'package') || str_contains($title, 'প্যাকেজ'))) return false;
                if (!$stockStatus && (str_contains($url, 'stock') || str_contains($title, 'stock') || str_contains($title, 'স্টক'))) return false;
                if (!$cardStatus && (str_contains($url, 'card') || str_contains($title, 'card') || str_contains($title, 'কার্ড'))) return false;
                return true;
            })->map(function($item) {
                $url = strtolower($item->url ?? '');
                $title = strtolower($item->title_en ?? '');
                if (str_contains($url, 'package') || str_contains($title, 'package')) {
                    $item->title_bn = setting('menu_packages_name_bn') ?: $item->title_bn;
                    $item->title_en = setting('menu_packages_name_en') ?: $item->title_en;
                } elseif (str_contains($url, 'stock') || str_contains($title, 'stock')) {
                    $item->title_bn = setting('menu_stock_name_bn') ?: $item->title_bn;
                    $item->title_en = setting('menu_stock_name_en') ?: $item->title_en;
                } elseif (str_contains($url, 'card') || str_contains($title, 'card')) {
                    $item->title_bn = setting('menu_membership_name_bn') ?: $item->title_bn;
                    $item->title_en = setting('menu_membership_name_en') ?: $item->title_en;
                }
                return $item;
            })->values();

            $uiConfigPayload = [
                'active_template' => $activeTemplate ? $activeTemplate->slug : 'template_01_modern_agriculture',
                'template_name_bn' => $activeTemplate ? $activeTemplate->name_bn : 'মডার্ন এগ্রিকালচার',
                'template_category' => $activeTemplate ? $activeTemplate->category : 'General',
                'global_colors' => $activeUiConfig ? $activeUiConfig->global_colors : [],
                'typography' => $activeUiConfig ? $activeUiConfig->typography : [],
                'header_config' => $activeUiConfig ? $activeUiConfig->header_config : [],
                'footer_config' => $activeUiConfig ? $activeUiConfig->footer_config : [],
                'dark_mode_config' => $activeUiConfig ? $activeUiConfig->dark_mode_config : [],
                'mobile_app_config' => $activeUiConfig ? $activeUiConfig->mobile_app_config : [],
                'sections_web' => $homeSectionsWeb,
                'sections_app' => $homeSectionsApp,
                'menus' => [
                    'header_menu' => $filteredHeaderMenu,
                    'app_bottom_nav' => $appBottomMenu ? $appBottomMenu->allItems()->where('is_active', true)->where('show_on_app', true)->get() : [],
                ],
            ];
        }

        $componentSettings = \class_exists(\App\Models\Ui\UiComponentSetting::class) 
            ? \App\Models\Ui\UiComponentSetting::getApiPayload() 
            : [];

        if (!empty($componentSettings)) {
            $site['contact_section_enabled'] = $componentSettings['contact_section']['enabled'] ?? true;
            $site['preloader_enabled'] = $componentSettings['preloader']['enabled'] ?? true;
            $site['product_order_btn_text'] = $componentSettings['product_card']['order_button']['text'] ?? 'Order Now';
            $site['button_color'] = $componentSettings['global_buttons']['bg_color'] ?? $site['button_color'];
        }

        return [
            'site' => $site,
            'header_color' => $site['header_color'],
            'button_color' => $site['button_color'],
            'button_animation' => $site['button_animation'],
            'preloader_status' => !empty($componentSettings) && !($componentSettings['preloader']['enabled'] ?? true) ? '0' : $site['preloader_status'],
            'why_choose_us_status' => $site['why_choose_us_status'],
            'ui_config' => $uiConfigPayload,
            'component_settings' => $componentSettings,
            'auth' => $auth,
            'routes' => $routes,
            'sliders' => $sliders,
            'offer_banner' => $offer_banner,
            'stocks' => $stocks,
            'packages' => $packages,
            'card_benefits' => $card_benefits,
            'features' => $features,
            'gallery' => $gallery,
            'outlets' => $outlets,
            'monthly_bazar_products' => $monthly_bazar_products,
        ];
    }

    public function index()
    {
        if (setting('coming_soon_status') === '1') {
            $user = Auth::user();
            $isAdmin = $user && in_array($user->role, ['admin', 'super_admin']);
            if (!$isAdmin) {
                return view('frontend.coming_soon');
            }
        }
        $data = $this->getHomepageData();
        return view('frontend.index', compact('data'));
    }

    public function comingSoon()
    {
        return view('frontend.coming_soon');
    }

    public function comingSoonSubscribe(Request $request)
    {
        $email = $request->input('email');
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                \Illuminate\Support\Facades\DB::table('newsletters')->updateOrInsert(
                    ['email' => $email],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            } catch (\Exception $e) {}
        }
        return response()->json(['status' => 'success', 'message' => 'Subscribed successfully']);
    }

    public function apiHomepageData()
    {
        return response()->json($this->getHomepageData());
    }

    public function apiAbout()
    {
        $logo = setting('logo');
        return response()->json([
            'status' => 'success',
            'title' => 'আমাদের সম্পর্কে (About Us)',
            'subtitle' => 'কৃষি পরিবার - আপনার কৃষি সেবা ও শেয়ার বাজারের নির্ভরযোগ্য বিশ্বস্ত প্ল্যাটফর্ম',
            'logo' => $logo ? asset('upload/images/backend/logo/' . $logo) : asset('assets/images/logo.png'),
            'welcome_title' => 'স্বাগতম কৃষি পরিবারে',
            'about_us_text' => setting('about_us_text') ?: 'কৃষি পরিবার একটি আধুনিক ও বিশ্বস্ত ডিজিটাল প্ল্যাটফর্ম। আমাদের মূল লক্ষ্য হল কৃষকদের সর্বোচ্চ সুবিধা প্রদান করা, মানসম্মত কৃষি পণ্য সরবরাহ করা এবং স্টকের সঠিক ও হালনাগাদ বাজারমূল্য নির্ধারণ ও পর্যবেক্ষণে সহায়তা করা।',
            'about_us_full_text' => setting('about_us_full_text'),
            'highlights' => [
                ['icon' => 'check_circle', 'title' => 'স্টকের রিয়েল-টাইম সঠিক ক্রয় ও বিক্রয় মূল্য প্রদর্শন।'],
                ['icon' => 'check_circle', 'title' => 'কৃষিজ পণ্য ও সেরা মানের বীজ/সার সরবরাহ।'],
                ['icon' => 'check_circle', 'title' => '১০০% সুরক্ষিত, স্বচ্ছ ও দ্রুত লেনদেন সেবা।'],
            ],
            'vision' => [
                'title' => 'আমাদের ভিশন',
                'description' => 'কৃষি ও ব্যবসা খাতে আধুনিক প্রযুক্তির ব্যবহার নিশ্চিত করে প্রতিটি কৃষিজ স্টকের সর্বোচ্চ মান ধরে রাখা।',
            ],
            'market_trend' => [
                'title' => 'মার্কেট ট্রেন্ড',
                'description' => 'গ্রাফ চার্ট ও রিয়েল-টাইম ডাটার মাধ্যমে প্রতিটি পণ্যের দৈনিক ও সাপ্তাহিক প্রাইস ট্রেন্ড সহজে পর্যবেক্ষণ।',
            ],
            'customer_service' => [
                'title' => 'গ্রাহক সেবা',
                'description' => 'আমাদের নিবেদিত সাপোর্ট টিম সপ্তাহের ৬ দিন গ্রাহক ও শেয়ারহোল্ডারদের সার্বক্ষণিক সেবা দিতে প্রস্তুত।',
            ],
        ]);
    }

    public function apiLiveSearch(Request $request)
    {
        $q = trim((string)$request->get('q', ''));
        if (mb_strlen($q) < 1) {
            return response()->json([
                'query' => $q,
                'total' => 0,
                'stocks' => [],
                'packages' => [],
                'cards' => [],
                'pages' => [],
            ]);
        }

        $term = '%' . $q . '%';

        // 1. Stocks
        $stocks = Stock::where('stock_name', 'like', $term)
            ->orWhere('description', 'like', $term)
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($s) {
                $gallery = StockGallery::where('stock_id', $s->id)->first();
                $image = $gallery ? asset('upload/stock_images/' . $gallery->image) : asset('upload/images/backend/logo/' . (setting('logo') ?: 'default-logo.png'));
                $last_price = function_exists('StockLastPricing') ? StockLastPricing($s->id) : null;
                $selling_price = $last_price ? (float)$last_price->selling_price : 0;
                $buying_price = $last_price ? (float)$last_price->buying_price : 0;
                return [
                    'id' => $s->id,
                    'type' => 'stock',
                    'title' => $s->stock_name,
                    'subtitle' => $s->description ? \Illuminate\Support\Str::limit(strip_tags($s->description), 35) : 'Agro Commodity',
                    'price' => '৳' . number_format($selling_price, 2),
                    'buying_price' => '৳' . number_format($buying_price, 2),
                    'image' => $image,
                    'url' => route('stock.detials', $s->id),
                ];
            });

        // 2. Packages
        $packages = OurPackage::where('status', 1)
            ->where(function ($sub) use ($term) {
                $sub->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term);
            })
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($pkg) {
                return [
                    'id' => $pkg->id,
                    'type' => 'package',
                    'title' => $pkg->title ?? 'Package',
                    'subtitle' => $pkg->price ? ('Price: ' . $pkg->price) : 'Exclusive Package',
                    'image' => asset($pkg->image),
                    'url' => route('/') . '#packages',
                ];
            });

        // 3. Membership Cards
        $cards = CardBenefit::where('status', 1)
            ->where(function ($sub) use ($term) {
                $sub->where('card_name', 'like', $term)
                    ->orWhere('card_type', 'like', $term)
                    ->orWhere('short_description', 'like', $term);
            })
            ->orderBy('order_num', 'asc')
            ->take(4)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'type' => 'card',
                    'title' => $c->card_name,
                    'subtitle' => $c->badge_text ?: $c->card_type,
                    'image' => $c->image ? asset($c->image) : null,
                    'url' => route('/') . '#card-benefits',
                ];
            });

        // 4. Static Pages & Portals
        $pageDefinitions = [
            ['title_en' => 'Home', 'title_bn' => 'হোম', 'keywords' => 'home main index হোম ekrishisheba', 'url' => route('/')],
            ['title_en' => 'About Us', 'title_bn' => 'আমাদের সম্পর্কে', 'keywords' => 'about us company info আমাদের সম্পর্কে', 'url' => route('about')],
            ['title_en' => 'Stock Market', 'title_bn' => 'স্টক মার্কেট', 'keywords' => 'stock market live price রেট স্টক মার্কেট বাজার দর', 'url' => route('stock.index')],
            ['title_en' => 'Our Packages', 'title_bn' => 'প্যাকেজসমূহ', 'keywords' => 'package offer discount প্যাকেজ আওয়ার প্যাকেজসমূহ', 'url' => route('/') . '#packages'],
            ['title_en' => 'Membership Cards', 'title_bn' => 'মেম্বারশিপ কার্ড', 'keywords' => 'card sme gold golden membership মেম্বারশিপ কার্ড এসএমই গোল্ড', 'url' => route('/') . '#card-benefits'],
            ['title_en' => 'Produce Gallery', 'title_bn' => 'প্রোডাক্ট গ্যালারি', 'keywords' => 'gallery photo images ছবি গ্যালারি প্রোডাক্ট', 'url' => route('gallery')],
            ['title_en' => 'Contact Us', 'title_bn' => 'যোগাযোগ', 'keywords' => 'contact support helpline ফোন ইমেইল যোগাযোগ হেল্পলাইন', 'url' => route('contact')],
            ['title_en' => 'Career', 'title_bn' => 'ক্যারিয়ার', 'keywords' => 'career job path resume cv চাকরি ক্যারিয়ার সার্কুলার নিয়োগ জব আবেদন', 'url' => route('career')],
            ['title_en' => 'Terms & Conditions', 'title_bn' => 'টার্মস এন্ড কন্ডিশন', 'keywords' => 'terms conditions rules নিয়মাবলী শর্তাবলী টার্মস', 'url' => route('terms')],
            ['title_en' => 'Privacy Policy', 'title_bn' => 'প্রাইভেসি পলিসি', 'keywords' => 'privacy policy security গোপনীয়তা প্রাইভেসি পলিসি', 'url' => route('privacy')],
            ['title_en' => 'Login', 'title_bn' => 'লগইন', 'keywords' => 'login signin প্রবেশ লগইন একাউন্ট', 'url' => route('login')],
            ['title_en' => 'Register', 'title_bn' => 'রেজিস্ট্রেশন', 'keywords' => 'register signup join নিবন্ধন রেজিস্ট্রেশন অ্যাকাউন্ট তৈরি', 'url' => route('register')],
        ];

        $pages = [];
        $lowerQ = mb_strtolower($q);
        foreach ($pageDefinitions as $p) {
            if (
                mb_stripos(mb_strtolower($p['title_en']), $lowerQ) !== false ||
                mb_stripos(mb_strtolower($p['title_bn']), $lowerQ) !== false ||
                mb_stripos(mb_strtolower($p['keywords']), $lowerQ) !== false
            ) {
                $pages[] = [
                    'type' => 'page',
                    'title' => $p['title_en'],
                    'title_bn' => $p['title_bn'],
                    'subtitle' => 'Site Page / Navigation',
                    'url' => $p['url'],
                    'icon' => 'fa-external-link',
                ];
            }
        }

        $total = count($stocks) + count($packages) + count($cards) + count($pages);

        return response()->json([
            'query' => $q,
            'total' => $total,
            'stocks' => $stocks,
            'packages' => $packages,
            'cards' => $cards,
            'pages' => $pages,
        ]);
    }

    /**
     * Frontend Gallery page.
     */
    public function gallery()
    {
        $galleries = \App\Models\Gallery::where('status', 1)
            ->orderBy('sort_order', 'asc')
            ->latest()
            ->paginate(16);

        return view('frontend.gallery', compact('galleries'));
    }

    /**
     * Frontend Our Videos page.
     */
    public function ourVideos()
    {
        $videos = \App\Models\OurVideo::where('is_active', true)
            ->orderBy('order_num', 'asc')
            ->latest()
            ->paginate(12);

        return view('frontend.our_videos', compact('videos'));
    }

    /**
     * Mobile App API for Our Videos.
     */
    public function apiOurVideos()
    {
        $videos = \App\Models\OurVideo::where('is_active', true)
            ->orderBy('order_num', 'asc')
            ->latest()
            ->get()
            ->map(function ($video) {
                return [
                    'id' => $video->id,
                    'title' => $video->title,
                    'video_url' => $video->video_url,
                    'embed_url' => $video->embed_url,
                    'thumbnail_url' => $video->thumbnail_url,
                    'youtube_id' => $video->youtube_id,
                    'is_direct_video' => $video->is_direct_video,
                    'description' => $video->description,
                    'button_text' => $video->button_text,
                    'button_link' => $video->button_link,
                ];
            });

        return response()->json([
            'status' => 'success',
            'title' => 'আমাদের ভিডিওসমূহ (Our Videos)',
            'subtitle' => 'ভিডিও গ্যালারি (Video Gallery)',
            'description' => 'ই-কৃষি সেবা ও আমাদের কৃষি পরিবারের কার্যক্রম, পণ্যের পরিচিতি এবং খামার পরিদর্শনের ভিডিও চিত্র',
            'videos' => $videos,
        ]);
    }

    /**
     * View Corporate Support PDF Document.
     */
    public function corporateSupportPdf()
    {
        $pdf = setting('corporate_support_pdf');
        if ($pdf) {
            $path = public_path('upload/documents/' . $pdf);
            if (file_exists($path)) {
                return response()->file($path, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="Corporate_Support_ekrishisheba.pdf"'
                ]);
            }
        }

        $defaultPath = public_path('upload/documents/corporate_support.pdf');
        if (file_exists($defaultPath)) {
            return response()->file($defaultPath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Corporate_Support_ekrishisheba.pdf"'
            ]);
        }

        return redirect()->route('about')->with('info', 'Corporate support PDF will be available soon.');
    }
}
