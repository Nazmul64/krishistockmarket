<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ui\DesignTemplate;
use App\Models\Ui\UiConfiguration;
use App\Models\Ui\NavigationMenu;
use App\Models\Ui\NavigationMenuItem;
use App\Models\Ui\HomepageSection;

class UiCustomizationSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================================================================
        // 1. Seed 10 Distinct Design Templates
        // =========================================================================
        $templates = [
            [
                'slug' => 'template_01_modern_agriculture',
                'name_en' => 'Template 01: Modern Agriculture',
                'name_bn' => 'টেমপ্লেট ০১: মডার্ন এগ্রিকালচার (ক্লিন ও প্রফেশনাল)',
                'category' => 'Modern Agriculture',
                'preview_thumbnail' => 'uploads/templates/template_01.jpg',
                'description_en' => 'Clean & Professional agricultural UI with balanced card layouts, clear bold typography, and modern responsive grids.',
                'description_bn' => 'সুষম কার্ড লেআউট, স্পষ্ট বোল্ড টাইপোগ্রাফি এবং আধুনিক রেসপনসিভ গ্রিড সহ ক্লিন ও প্রফেশনাল এগ্রি ইউআই।',
                'default_config' => [
                    'primary_color' => '#ea580c',
                    'secondary_color' => '#007e8a',
                    'accent_color' => '#0284c7',
                    'header_bg' => '#ea580c',
                    'button_color' => '#007e8a',
                    'card_radius' => '12px',
                    'header_layout' => 'layout_1',
                ],
            ],
            [
                'slug' => 'template_02_premium_corporate',
                'name_en' => 'Template 02: Premium Corporate',
                'name_bn' => 'টেমপ্লেট ০২: প্রিমিয়াম কর্পোরেট (বিজনেস ফোকাসড)',
                'category' => 'Premium Corporate',
                'preview_thumbnail' => 'uploads/templates/template_02.jpg',
                'description_en' => 'Business-focused corporate design with midnight slate headers, gold accents, institutional typography, and trust badges.',
                'description_bn' => 'মিডনাইট স্লেট হেডার, গোল্ড অ্যাকসেন্ট, প্রাতিষ্ঠানিক টাইপোগ্রাফি এবং ট্রাস্ট ব্যাজ সহ কর্পোরেট ফোকাসড ডিজাইন।',
                'default_config' => [
                    'primary_color' => '#0f172a',
                    'secondary_color' => '#d97706',
                    'accent_color' => '#2563eb',
                    'header_bg' => '#0f172a',
                    'button_color' => '#d97706',
                    'card_radius' => '8px',
                    'header_layout' => 'layout_2',
                ],
            ],
            [
                'slug' => 'template_03_ecommerce_marketplace',
                'name_en' => 'Template 03: E-commerce Marketplace',
                'name_bn' => 'টেমপ্লেট ০৩: ই-কমার্স মার্কেটপ্লেস (প্রোডাক্ট ফোকাসড)',
                'category' => 'E-commerce Marketplace',
                'preview_thumbnail' => 'uploads/templates/template_03.jpg',
                'description_en' => 'High-density product showcase inspired by top retail giants with mega category carousel, sticky cart bar, and flash deal tickers.',
                'description_bn' => 'মেগা ক্যাটাগরি ক্যারোজেল, স্টিকি কার্ট বার এবং ফ্ল্যাশ ডিল টাইমার সহ প্রডাক্ট ফোকাসড সুপারশপ মার্কেটপ্লেস লেআউট।',
                'default_config' => [
                    'primary_color' => '#f97316',
                    'secondary_color' => '#10b981',
                    'accent_color' => '#ef4444',
                    'header_bg' => '#f97316',
                    'button_color' => '#10b981',
                    'card_radius' => '10px',
                    'header_layout' => 'layout_4',
                ],
            ],
            [
                'slug' => 'template_04_green_nature',
                'name_en' => 'Template 04: Green Nature',
                'name_bn' => 'টেমপ্লেট ০৪: গ্রিন নেচার (অর্গানিক এগ্রিকালচার)',
                'category' => 'Green Nature',
                'preview_thumbnail' => 'uploads/templates/template_04.jpg',
                'description_en' => 'Lush forest green aesthetic celebrating organic farming, farm-to-table freshness, and eco-friendly wavy container styles.',
                'description_bn' => 'অর্গানিক ফার্মিং, তাজা শাকসবজি ও প্রাকৃতিক পরিবেশের প্রতীক ডিপ ফরেস্ট গ্রিন ও সফট ওয়েভি কন্টেইনার স্টাইল।',
                'default_config' => [
                    'primary_color' => '#065f46',
                    'secondary_color' => '#047857',
                    'accent_color' => '#84cc16',
                    'header_bg' => '#065f46',
                    'button_color' => '#047857',
                    'card_radius' => '16px',
                    'header_layout' => 'layout_1',
                ],
            ],
            [
                'slug' => 'template_05_dark_premium',
                'name_en' => 'Template 05: Dark Premium',
                'name_bn' => 'টেমপ্লেট ০৫: ডার্ক প্রিমিয়াম (মডার্ন ডার্ক ইউআই)',
                'category' => 'Dark Premium',
                'preview_thumbnail' => 'uploads/templates/template_05.jpg',
                'description_en' => 'Sleek dark theme with neon emerald accents, glassmorphic translucent panels, and high-contrast typography.',
                'description_bn' => 'স্লিক ডার্ক থিম, গ্লাস-মরফিক ট্রান্সলুসেন্ট প্যানেল এবং নিয়ন এমারেল্ড অ্যাকসেন্ট সহ আধুনিক লাক্সারি লুক।',
                'default_config' => [
                    'primary_color' => '#0b1120',
                    'secondary_color' => '#10b981',
                    'accent_color' => '#38bdf8',
                    'header_bg' => '#0b1120',
                    'button_color' => '#10b981',
                    'card_radius' => '14px',
                    'header_layout' => 'layout_5',
                ],
            ],
            [
                'slug' => 'template_06_minimal_light',
                'name_en' => 'Template 06: Minimal Light',
                'name_bn' => 'টেমপ্লেট ০৬: মিনিমাল লাইট (সিম্পল ও লাইটওয়েট)',
                'category' => 'Minimal',
                'preview_thumbnail' => 'uploads/templates/template_06.jpg',
                'description_en' => 'Ultra-clean white design with generous negative space, crisp hairline borders, lightweight assets, and instant loading speeds.',
                'description_bn' => 'পর্যাপ্ত হোয়াইট স্পেস, শার্প হেয়ারলাইন বর্ডার, হালকা অ্যাসেট এবং বিদ্যুৎগতির লোডিং স্পিড সহ মিনিমাল ডিজাইন।',
                'default_config' => [
                    'primary_color' => '#ffffff',
                    'secondary_color' => '#1e293b',
                    'accent_color' => '#475569',
                    'header_bg' => '#ffffff',
                    'button_color' => '#1e293b',
                    'card_radius' => '6px',
                    'header_layout' => 'layout_3',
                ],
            ],
            [
                'slug' => 'template_07_magazine_content',
                'name_en' => 'Template 07: Magazine & Content',
                'name_bn' => 'টেমপ্লেট ০৭: ম্যাগাজিন ও কনটেন্ট (নিউজ ফোকাসড)',
                'category' => 'Magazine & Content',
                'preview_thumbnail' => 'uploads/templates/template_07.jpg',
                'description_en' => 'Editorial magazine presentation featuring top agricultural stories, research breakthroughs, market headlines, and video showcases.',
                'description_bn' => 'শীর্ষ কৃষি সংবাদ, গবেষণার খবর, নোটিস ও বাজার দর সমন্বিত এডিটোরিয়াল ও নিউজলেটার স্টাইলের উপস্থাপনা।',
                'default_config' => [
                    'primary_color' => '#78350f',
                    'secondary_color' => '#b45309',
                    'accent_color' => '#d97706',
                    'header_bg' => '#78350f',
                    'button_color' => '#b45309',
                    'card_radius' => '8px',
                    'header_layout' => 'layout_1',
                ],
            ],
            [
                'slug' => 'template_08_dashboard_data',
                'name_en' => 'Template 08: Dashboard Style',
                'name_bn' => 'টেমপ্লেট ০৮: ড্যাশবোর্ড স্টাইল (ডেটা ও ফাইন্যান্স)',
                'category' => 'Dashboard Style',
                'preview_thumbnail' => 'uploads/templates/template_08.jpg',
                'description_en' => 'Financial intelligence and commodity trading layout featuring real-time ticker bands, live price graphs, and KPI analytics cards.',
                'description_bn' => 'রিয়েল-টাইম টিকার ব্যান্ড, লাইভ প্রাইস গ্রাফ এবং কেপিআই অ্যানালিটিক্স কার্ড সহ ফিনান্সিয়াল ইন্টেলিজেন্স লেআউট।',
                'default_config' => [
                    'primary_color' => '#1e293b',
                    'secondary_color' => '#0284c7',
                    'accent_color' => '#10b981',
                    'header_bg' => '#1e293b',
                    'button_color' => '#0284c7',
                    'card_radius' => '10px',
                    'header_layout' => 'layout_4',
                ],
            ],
            [
                'slug' => 'template_09_colorful_modern',
                'name_en' => 'Template 09: Colorful Modern',
                'name_bn' => 'টেমপ্লেট ০৯: কালারফুল মডার্ন (ডাইনামিক ইউআই)',
                'category' => 'Colorful Modern',
                'preview_thumbnail' => 'uploads/templates/template_09.jpg',
                'description_en' => 'Vibrant gradient styling with playful pill-shaped tags, dynamic micro-interactions, and energetic high-saturation color palette.',
                'description_bn' => 'রঙিন গ্রেডিয়েন্ট, প্লেফুল পিল ট্যাগ এবং অ্যানিমেটেড মাইক্রো-ইন্টারঅ্যাকশন সহ প্রাণবন্ত ডাইনামিক ইউআই।',
                'default_config' => [
                    'primary_color' => '#7c3aed',
                    'secondary_color' => '#ea580c',
                    'accent_color' => '#ec4899',
                    'header_bg' => '#7c3aed',
                    'button_color' => '#ea580c',
                    'card_radius' => '20px',
                    'header_layout' => 'layout_1',
                ],
            ],
            [
                'slug' => 'template_10_custom_premium',
                'name_en' => 'Template 10: Custom Premium',
                'name_bn' => 'টেমপ্লেট ১০: কাস্টম প্রিমিয়াম (অ্যাডভান্সড লেআউট)',
                'category' => 'Custom Premium',
                'preview_thumbnail' => 'uploads/templates/template_10.jpg',
                'description_en' => 'Flagship modular layout with floating island header, asymmetric hero section, premium luxury cards, and custom typography.',
                'description_bn' => 'ফ্লোটিং আইল্যান্ড হেডার, স্প্লিট হিরো সেকশন, লাক্সারি কার্ড এবং নিখুঁত টাইপোগ্রাফি সহ ফ্ল্যাগশিপ মডুলার লেআউট।',
                'default_config' => [
                    'primary_color' => '#047857',
                    'secondary_color' => '#b45309',
                    'accent_color' => '#0284c7',
                    'header_bg' => '#047857',
                    'button_color' => '#b45309',
                    'card_radius' => '16px',
                    'header_layout' => 'layout_5',
                ],
            ],
        ];

        foreach ($templates as $tmpl) {
            DesignTemplate::updateOrCreate(['slug' => $tmpl['slug']], $tmpl);
        }

        // =========================================================================
        // 2. Seed Initial Central UI Configuration
        // =========================================================================
        UiConfiguration::updateOrCreate(
            ['version_code' => 'v1.0.0'],
            [
                'status' => 'published',
                'active_template_slug' => 'template_01_modern_agriculture',
                'global_colors' => [
                    'primary' => '#ea580c',
                    'secondary' => '#007e8a',
                    'accent' => '#0284c7',
                    'background' => '#ffffff',
                    'text' => '#0f172a',
                    'heading' => '#0f172a',
                    'button' => '#007e8a',
                    'button_hover' => '#005f69',
                    'header_bg' => '#ea580c',
                    'footer_bg' => '#0f172a',
                    'card_bg' => '#ffffff',
                    'border_color' => '#e2e8f0',
                ],
                'typography' => [
                    'font_family' => 'Hind Siliguri',
                    'bengali_font' => 'Hind Siliguri',
                    'english_font' => 'Hind Siliguri',
                    'heading_font' => 'Hind Siliguri',
                    'base_font_size' => '15px',
                    'heading_size' => '24px',
                ],
                'header_config' => [
                    'layout' => 'layout_1',
                    'show_search' => true,
                    'search_placeholder_bn' => 'পণ্য, স্টক, প্যাকেজ বা কার্ড খুঁজুন...',
                    'search_placeholder_en' => 'Search products, stocks, packages...',
                    'show_notice' => true,
                    'notice_text' => 'ই-কৃষি সেবায় আপনাকে স্বাগতম! বিশ্বস্ত ডিজিটাল কৃষি ও বিনিয়োগ প্ল্যাটফর্ম।',
                    'show_login' => true,
                    'show_register' => true,
                    'show_dashboard' => true,
                    'show_language_toggle' => true,
                    'show_dark_toggle' => true,
                    'is_sticky' => true,
                    'logo_height' => '44px',
                ],
                'footer_config' => [
                    'layout' => 'four_column',
                    'about_text' => 'ekrishisheba - আধুনিক কৃষি সেবা, ফ্রেশ সুপারশপ পণ্য, লাইভ স্টক ও স্মার্ট বিনিয়োগের বিশ্বস্ত ডিজিটাল সমাধান।',
                    'phone1' => '09617900200',
                    'phone2' => '01700000000',
                    'email1' => 'info@ekrishisheba.com',
                    'address' => 'ঢাকা, বাংলাদেশ',
                    'copyright_text' => '© 2026 ekrishisheba. All Rights Reserved.',
                    'show_social_links' => true,
                    'show_newsletter' => true,
                ],
                'dark_mode_config' => [
                    'enabled' => true,
                    'default_mode' => 'light',
                    'allow_user_toggle' => true,
                    'dark_bg' => '#0b1120',
                    'dark_card_bg' => '#1e293b',
                    'dark_text' => '#f8fafc',
                ],
                'mobile_app_config' => [
                    'app_header_style' => 'standard',
                    'bottom_nav_style' => 'pill',
                    'max_bottom_items' => 5,
                    'drawer_enabled' => true,
                    'show_top_banners' => true,
                ],
                'published_at' => now(),
            ]
        );

        // =========================================================================
        // 3. Seed Navigation Menus & Items
        // =========================================================================
        $headerMenu = NavigationMenu::updateOrCreate(['key' => 'header_menu'], [
            'title' => 'Main Website Header Menu',
            'is_active' => true,
        ]);

        $menuItems = [
            ['title_bn' => 'হোম', 'title_en' => 'Home', 'url' => '/', 'route_name' => '/', 'icon' => 'fa-home', 'sort_order' => 1],
            ['title_bn' => 'আমাদের পণ্য', 'title_en' => 'Our Products', 'url' => '/our-products', 'route_name' => 'products.index', 'icon' => 'fa-shopping-bag', 'badge_text' => 'Shop', 'sort_order' => 2],
            ['title_bn' => 'স্টক মার্কেট', 'title_en' => 'Stock Market', 'url' => '/stock', 'route_name' => 'stock.index', 'icon' => 'fa-line-chart', 'sort_order' => 3],
            ['title_bn' => 'জমি ও প্রপার্টি', 'title_en' => 'Land & Properties', 'url' => '/properties', 'route_name' => 'properties.index', 'icon' => 'fa-building-o', 'sort_order' => 4],
            ['title_bn' => 'প্যাকেজসমূহ', 'title_en' => 'Our Packages', 'url' => '/our-packages', 'route_name' => 'packages.index', 'icon' => 'fa-cube', 'sort_order' => 5],
            ['title_bn' => 'মেম্বারশিপ কার্ড', 'title_en' => 'Membership Cards', 'url' => '/membership-cards', 'route_name' => 'cards.index', 'icon' => 'fa-id-card-o', 'sort_order' => 6],
            ['title_bn' => 'গ্যালারি', 'title_en' => 'Gallery', 'url' => '/gallery', 'route_name' => 'gallery', 'icon' => 'fa-picture-o', 'sort_order' => 7],
            ['title_bn' => 'আমাদের ভিডিও', 'title_en' => 'Our Videos', 'url' => '/our-videos', 'route_name' => 'our_videos', 'icon' => 'fa-video-camera', 'sort_order' => 8],
            ['title_bn' => 'ক্যারিয়ার', 'title_en' => 'Career', 'url' => '/career', 'route_name' => 'career', 'icon' => 'fa-briefcase', 'sort_order' => 9],
            ['title_bn' => 'পরিচিতি', 'title_en' => 'About Us', 'url' => '/about', 'route_name' => 'about', 'icon' => 'fa-info-circle', 'sort_order' => 10],
            ['title_bn' => 'যোগাযোগ', 'title_en' => 'Contact', 'url' => '/contact', 'route_name' => 'contact', 'icon' => 'fa-envelope-o', 'sort_order' => 11],
        ];

        foreach ($menuItems as $item) {
            NavigationMenuItem::updateOrCreate(
                ['menu_id' => $headerMenu->id, 'title_en' => $item['title_en']],
                array_merge($item, [
                    'menu_id' => $headerMenu->id,
                    'show_on_web' => true,
                    'show_on_app' => true,
                    'is_active' => true,
                ])
            );
        }

        // Mobile App Bottom Navigation Menu
        $appBottomMenu = NavigationMenu::updateOrCreate(['key' => 'app_bottom_nav'], [
            'title' => 'Mobile App Bottom Navigation',
            'is_active' => true,
        ]);

        $appBottomItems = [
            ['title_bn' => 'হোম', 'title_en' => 'Home', 'url' => '/', 'icon' => 'home', 'sort_order' => 1],
            ['title_bn' => 'স্টক', 'title_en' => 'Stocks', 'url' => '/stock', 'icon' => 'trending_up', 'sort_order' => 2],
            ['title_bn' => 'বাজার', 'title_en' => 'Shop', 'url' => '/our-products', 'icon' => 'shopping_bag', 'sort_order' => 3],
            ['title_bn' => 'প্যাকেজ', 'title_en' => 'Packages', 'url' => '/our-packages', 'icon' => 'redeem', 'sort_order' => 4],
            ['title_bn' => 'অ্যাকাউন্ট', 'title_en' => 'Account', 'url' => '/customer/dashboard', 'icon' => 'person', 'sort_order' => 5],
        ];

        foreach ($appBottomItems as $item) {
            NavigationMenuItem::updateOrCreate(
                ['menu_id' => $appBottomMenu->id, 'title_en' => $item['title_en']],
                array_merge($item, [
                    'menu_id' => $appBottomMenu->id,
                    'show_on_web' => false,
                    'show_on_app' => true,
                    'is_active' => true,
                ])
            );
        }

        // =========================================================================
        // 4. Seed Homepage Sections (15 Configurable Sections)
        // =========================================================================
        $sections = [
            [
                'section_key' => 'hero_slider',
                'title_bn' => 'হিরো স্লাইডার ও ব্যানার',
                'title_en' => 'Hero Slider & Banners',
                'subtitle_bn' => 'প্রধান অ্যানিমেটেড স্লাইডার ও প্রোমোশনাল ব্যানার',
                'subtitle_en' => 'Main animated hero banner and promotional sliders',
                'component_name' => 'HeroSlider',
                'sort_order_web' => 1,
                'sort_order_app' => 1,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['auto_play' => true, 'duration' => 5000, 'show_indicators' => true],
            ],
            [
                'section_key' => 'monthly_bazar',
                'title_bn' => 'মাসিক বাজার (সুপারশপ অফার)',
                'title_en' => 'Monthly Bazar (SuperShop Deals)',
                'subtitle_bn' => 'নিত্যপ্রয়োজনীয় ফ্রেশ মাসিক বাজার তালিকা ও সাশ্রয়ী মূল্যে পণ্য',
                
                'component_name' => 'MonthlyBazarSection',
                'sort_order_web' => 2,
                'sort_order_app' => 2,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['product_count' => 10, 'show_price' => true, 'show_order_btn' => true],
            ],
            [
                'section_key' => 'stock_market',
                'title_bn' => 'লাইভ কৃষিপণ্য স্টক ও মার্কেট রেট',
                'title_en' => 'Live Agriculture Stock & Market Rates',
                'subtitle_bn' => 'দৈনিক পণ্য ক্রয়-বিক্রয় ও লাভজনক বিনিয়োগ হার',
                'subtitle_en' => 'Monitor daily commodity buying and selling prices',
                'component_name' => 'LiveStockMarket',
                'sort_order_web' => 3,
                'sort_order_app' => 3,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['item_count' => 8, 'show_live_badge' => true],
            ],
            [
                'section_key' => 'our_packages',
                'title_bn' => 'আমাদের জনপ্রিয় প্যাকেজসমূহ',
                'title_en' => 'Our Popular Packages',
                'subtitle_bn' => 'বিশেষ বিনিয়োগ ও কৃষিসেবা প্যাকেজ প্ল্যান',
                'subtitle_en' => 'Special agro-investment packages and flexible dividend returns',
                'component_name' => 'PackageSection',
                'sort_order_web' => 4,
                'sort_order_app' => 4,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['item_count' => 6, 'show_booking_btn' => true],
            ],
            [
                'section_key' => 'membership_cards',
                'title_bn' => 'স্মার্ট মেম্বারশিপ কার্ড ও বিশেষ সুবিধা',
                'title_en' => 'Smart Membership Cards & Privileges',
                'subtitle_bn' => 'গোল্ড ও এসইএমই মেম্বারশিপে আজীবন বিশেষ ছাড় ও কমিশন',
                'subtitle_en' => 'Exclusive member discounts, priority delivery, and loyalty earnings',
                'component_name' => 'MembershipCards',
                'sort_order_web' => 5,
                'sort_order_app' => 5,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['item_count' => 4, 'show_apply_btn' => true],
            ],
            [
                'section_key' => 'land_properties',
                'title_bn' => 'জমি ও রিয়েল এস্টেট প্রপার্টি',
                'title_en' => 'Land & Real Estate Properties',
                'subtitle_bn' => 'নিরাপদ কৃষি জমি ও বাণিজ্যিক রিয়েল এস্টেট শেয়ার',
                'subtitle_en' => 'Verified agricultural land, commercial farming plots, and real estate shares',
                'component_name' => 'PropertiesSection',
                'sort_order_web' => 6,
                'sort_order_app' => 6,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['item_count' => 6, 'show_inquiry_btn' => true],
            ],
            [
                'section_key' => 'gallery',
                'title_bn' => 'ফটো ও প্রোডাক্ট গ্যালারি',
                'title_en' => 'Photo & Product Gallery',
                'subtitle_bn' => 'আমাদের খামার, মাঠ ও সফল কৃষি কার্যক্রমের চিত্র',
                'subtitle_en' => 'Glimpse of our farms, organic processing, and agro projects',
                'component_name' => 'ProductGallery',
                'sort_order_web' => 7,
                'sort_order_app' => 7,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['item_count' => 8, 'grid_columns' => 4],
            ],
            [
                'section_key' => 'our_videos',
                'title_bn' => 'আমাদের ভিডিও কনটেন্ট ও তথ্যচিত্র',
                'title_en' => 'Our Videos & Documentaries',
                'subtitle_bn' => 'কৃষি প্রযুক্তি, খামার ব্যবস্থাপনা ও সফলতার ভিডিও',
                'subtitle_en' => 'Agro tutorials, farmer guidance, and project success stories',
                'component_name' => 'VideosSection',
                'sort_order_web' => 8,
                'sort_order_app' => 8,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['item_count' => 4, 'allow_in_app_player' => true],
            ],
            [
                'section_key' => 'career',
                'title_bn' => 'ক্যারিয়ার ও চাকরির সুযোগ',
                'title_en' => 'Career & Job Opportunities',
                'subtitle_bn' => 'কৃষি সেবায় যুক্ত হয়ে ভবিষ্যৎ গড়ুন',
                'subtitle_en' => 'Join our growing team and shape the future of digital agriculture',
                'component_name' => 'CareerSection',
                'sort_order_web' => 9,
                'sort_order_app' => 9,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['show_apply_button' => true],
            ],
            [
                'section_key' => 'about_us',
                'title_bn' => 'পরিচিতি ও লক্ষ্য',
                'title_en' => 'About Our Mission',
                'subtitle_bn' => 'ই-কৃষি সেবা সম্পর্কে বিস্তারিত জানুন',
                'subtitle_en' => 'Empowering farmers, traders, and agricultural investors nationwide',
                'component_name' => 'AboutSection',
                'sort_order_web' => 10,
                'sort_order_app' => 10,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['show_stats' => true],
            ],
            [
                'section_key' => 'why_choose_us',
                'title_bn' => 'কেন আমাদের বেছে নেবেন?',
                'title_en' => 'Why Choose Us?',
                'subtitle_bn' => 'আমাদের অনন্য সেবাসমূহ ও সুযোগ-সুবিধা',
                'subtitle_en' => 'Trusted ecosystem, transparent returns, and direct farmer support',
                'component_name' => 'WhyChooseUs',
                'sort_order_web' => 11,
                'sort_order_app' => 11,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['card_count' => 4],
            ],
            [
                'section_key' => 'testimonials',
                'title_bn' => 'গ্রাহক ও কৃষকদের মতামত',
                'title_en' => 'Testimonials & Reviews',
                'subtitle_bn' => 'আমাদের সফল অংশীদারদের অভিজ্ঞতা',
                'subtitle_en' => 'What our valued farmers, shoppers, and investors say about us',
                'component_name' => 'TestimonialsSection',
                'sort_order_web' => 12,
                'sort_order_app' => 12,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['carousel' => true],
            ],
            [
                'section_key' => 'statistics',
                'title_bn' => 'আমাদের সাফল্যের পরিসংখ্যান',
                'title_en' => 'Key Success Statistics',
                'subtitle_bn' => 'এক নজরে আমাদের অর্জন',
                'subtitle_en' => 'Numbers reflecting farmer trust, volume traded, and active delivery hubs',
                'component_name' => 'StatisticsSection',
                'sort_order_web' => 13,
                'sort_order_app' => 13,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['show_counter_animation' => true],
            ],
            [
                'section_key' => 'news_updates',
                'title_bn' => 'কৃষি সংবাদ ও আপডেট',
                'title_en' => 'Agro News & Latest Updates',
                'subtitle_bn' => 'সাম্প্রতিক সংবাদ, নোটিস ও অফার',
                'subtitle_en' => 'Recent announcements, seasonal farming tips, and market notices',
                'component_name' => 'NewsUpdatesSection',
                'sort_order_web' => 14,
                'sort_order_app' => 14,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['news_count' => 3],
            ],
            [
                'section_key' => 'contact_us',
                'title_bn' => 'যোগাযোগ ও সরাসরি বার্তা',
                'title_en' => 'Get in Touch with Us',
                'subtitle_bn' => 'যেকোনো জিজ্ঞাসা বা সহযোগিতার জন্য বার্তা পাঠান',
                'subtitle_en' => 'Have questions or partnership proposals? Send us a quick message',
                'component_name' => 'ContactSection',
                'sort_order_web' => 15,
                'sort_order_app' => 15,
                'is_enabled_web' => true,
                'is_enabled_app' => true,
                'section_settings' => ['show_map' => true, 'show_quick_form' => true],
            ],
        ];

        foreach ($sections as $sec) {
            HomepageSection::updateOrCreate(['section_key' => $sec['section_key']], $sec);
        }
    }
}
