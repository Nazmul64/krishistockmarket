<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('ui_component_settings')) {
            Schema::create('ui_component_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->longText('value')->nullable();
                $table->string('group', 50)->index();
                $table->string('type', 20)->default('string'); // string, color, number, boolean, json, select
                $table->string('title_bn')->nullable();
                $table->string('title_en')->nullable();
                $table->string('device', 20)->default('all'); // all, desktop, mobile
                $table->json('options')->nullable();
                $table->timestamps();
            });

            // Seed initial defaults
            $defaults = [
                // 1. Product Card Typography & Dimensions
                [
                    'key' => 'product_title_size_desktop',
                    'value' => '15',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'প্রোডাক্ট টাইটেল সাইজ (ডেস্কটপ)',
                    'title_en' => 'Product Title Size (Desktop px)',
                    'device' => 'desktop',
                    'options' => json_encode(['min' => 10, 'max' => 28, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_title_size_mobile',
                    'value' => '13',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'প্রোডাক্ট টাইটেল সাইজ (মোবাইল)',
                    'title_en' => 'Product Title Size (Mobile px)',
                    'device' => 'mobile',
                    'options' => json_encode(['min' => 10, 'max' => 24, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_title_color',
                    'value' => '#1e293b',
                    'group' => 'product_card',
                    'type' => 'color',
                    'title_bn' => 'প্রোডাক্ট টাইটেল রঙ',
                    'title_en' => 'Product Title Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'product_title_weight',
                    'value' => '700',
                    'group' => 'product_card',
                    'type' => 'select',
                    'title_bn' => 'প্রোডাক্ট টাইটেল ফন্ট ওয়েট',
                    'title_en' => 'Product Title Font Weight',
                    'device' => 'all',
                    'options' => json_encode(['400' => 'Normal (400)', '500' => 'Medium (500)', '600' => 'Semi-Bold (600)', '700' => 'Bold (700)', '800' => 'Extra-Bold (800)']),
                ],
                [
                    'key' => 'product_price_size_desktop',
                    'value' => '18',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'প্রোডাক্ট মূল্য সাইজ (ডেস্কটপ)',
                    'title_en' => 'Product Price Size (Desktop px)',
                    'device' => 'desktop',
                    'options' => json_encode(['min' => 12, 'max' => 32, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_price_size_mobile',
                    'value' => '15',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'প্রোডাক্ট মূল্য সাইজ (মোবাইল)',
                    'title_en' => 'Product Price Size (Mobile px)',
                    'device' => 'mobile',
                    'options' => json_encode(['min' => 10, 'max' => 26, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_price_color',
                    'value' => '#10b981',
                    'group' => 'product_card',
                    'type' => 'color',
                    'title_bn' => 'প্রোডাক্ট মূল্যের রঙ',
                    'title_en' => 'Product Price Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'product_price_weight',
                    'value' => '700',
                    'group' => 'product_card',
                    'type' => 'select',
                    'title_bn' => 'প্রোডাক্ট মূল্য ফন্ট ওয়েট',
                    'title_en' => 'Product Price Font Weight',
                    'device' => 'all',
                    'options' => json_encode(['600' => 'Semi-Bold (600)', '700' => 'Bold (700)', '800' => 'Extra-Bold (800)']),
                ],
                [
                    'key' => 'product_order_btn_text',
                    'value' => 'Order Now',
                    'group' => 'product_card',
                    'type' => 'string',
                    'title_bn' => 'অর্ডার নাও বাটন টাইটেল / লেখা',
                    'title_en' => 'Order Now Button Title/Text',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'product_order_btn_size_desktop',
                    'value' => '14',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'অর্ডার বাটন ফন্ট সাইজ (ডেস্কটপ)',
                    'title_en' => 'Order Button Font Size (Desktop px)',
                    'device' => 'desktop',
                    'options' => json_encode(['min' => 10, 'max' => 22, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_order_btn_size_mobile',
                    'value' => '12',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'অর্ডার বাটন ফন্ট সাইজ (মোবাইল)',
                    'title_en' => 'Order Button Font Size (Mobile px)',
                    'device' => 'mobile',
                    'options' => json_encode(['min' => 9, 'max' => 18, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_img_height_desktop',
                    'value' => '145',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'ইমেজ উচ্চতা / Height (ডেস্কটপ)',
                    'title_en' => 'Product Image Height (Desktop px)',
                    'device' => 'desktop',
                    'options' => json_encode(['min' => 80, 'max' => 300, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_img_width_desktop',
                    'value' => '100',
                    'group' => 'product_card',
                    'type' => 'string',
                    'title_bn' => 'ইমেজ প্রস্থ / Width (ডেস্কটপ)',
                    'title_en' => 'Product Image Width (Desktop)',
                    'device' => 'desktop',
                    'options' => json_encode(['unit' => '%']),
                ],
                [
                    'key' => 'product_img_height_mobile',
                    'value' => '115',
                    'group' => 'product_card',
                    'type' => 'number',
                    'title_bn' => 'ইমেজ উচ্চতা / Height (মোবাইল)',
                    'title_en' => 'Product Image Height (Mobile px)',
                    'device' => 'mobile',
                    'options' => json_encode(['min' => 60, 'max' => 220, 'unit' => 'px']),
                ],
                [
                    'key' => 'product_img_width_mobile',
                    'value' => '100',
                    'group' => 'product_card',
                    'type' => 'string',
                    'title_bn' => 'ইমেজ প্রস্থ / Width (মোবাইল)',
                    'title_en' => 'Product Image Width (Mobile)',
                    'device' => 'mobile',
                    'options' => json_encode(['unit' => '%']),
                ],
                [
                    'key' => 'product_img_fit',
                    'value' => 'contain',
                    'group' => 'product_card',
                    'type' => 'select',
                    'title_bn' => 'ইমেজ প্রদর্শন মোড (Object Fit)',
                    'title_en' => 'Image Object Fit',
                    'device' => 'all',
                    'options' => json_encode(['contain' => 'Contain (সম্পূর্ণ ফ্রেম ফিট)', 'cover' => 'Cover (ক্রপ ফিল)', 'scale-down' => 'Scale Down']),
                ],

                // 2. Preloader Controls (On/Off + Multiple Preloaders)
                [
                    'key' => 'preloader_enabled',
                    'value' => '1',
                    'group' => 'preloader',
                    'type' => 'boolean',
                    'title_bn' => 'প্রিলোডার চালু / বন্ধ (On/Off)',
                    'title_en' => 'Preloader Enabled (On/Off)',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'preloader_type',
                    'value' => 'cinematic_orbital',
                    'group' => 'preloader',
                    'type' => 'select',
                    'title_bn' => 'মাল্টিপল প্রিলোডার ডিজাইন পছন্দ',
                    'title_en' => 'Preloader Style Selector',
                    'device' => 'all',
                    'options' => json_encode([
                        'cinematic_orbital' => '১. অরবিটাল গ্লোয়িং রিং (Cinematic Orbital Ring)',
                        'spinner_circular'  => '২. গোল সার্কুলার স্পিনার (Circular Dual Spinner)',
                        'pulse_logo'        => '৩. পালস লোগো রিপল (Pulse Logo Wave)',
                        'leaf_agriculture'  => '৪. কৃষি পাতা ও গ্রোথ লোডার (Eco Leaf & Growth)',
                        'progress_bar'      => '৫. মিনিমাল টপ প্রোগ্রেস লাইন (Minimal Top Bar)',
                    ]),
                ],
                [
                    'key' => 'preloader_primary_color',
                    'value' => '#ea580c',
                    'group' => 'preloader',
                    'type' => 'color',
                    'title_bn' => 'প্রিলোডার প্রধান রঙ',
                    'title_en' => 'Preloader Primary Accent Color',
                    'device' => 'all',
                    'options' => null,
                ],

                // 3. Contact Section (Get in touch with our team) On/Off
                [
                    'key' => 'contact_section_enabled',
                    'value' => '1',
                    'group' => 'contact_section',
                    'type' => 'boolean',
                    'title_bn' => 'Get in Touch With Our Team সেকশন চালু / বন্ধ',
                    'title_en' => 'Contact Section Enabled (On/Off)',
                    'device' => 'all',
                    'options' => null,
                ],

                // 4. Global Buttons (সমস্ত বাটন এক রঙ)
                [
                    'key' => 'global_button_bg_color',
                    'value' => '#007e8a',
                    'group' => 'buttons',
                    'type' => 'color',
                    'title_bn' => 'সকল সিস্টেম বাটনের ব্যাকগ্রাউন্ড রঙ',
                    'title_en' => 'Global Button Background Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'global_button_text_color',
                    'value' => '#ffffff',
                    'group' => 'buttons',
                    'type' => 'color',
                    'title_bn' => 'বাটনের লেখার রঙ (Text Color)',
                    'title_en' => 'Global Button Text Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'global_button_hover_bg_color',
                    'value' => '#005f69',
                    'group' => 'buttons',
                    'type' => 'color',
                    'title_bn' => 'বাটনে হোভার কালার (Hover Color)',
                    'title_en' => 'Button Hover Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'global_button_border_radius',
                    'value' => '50',
                    'group' => 'buttons',
                    'type' => 'number',
                    'title_bn' => 'বাটনের কোণ রাউন্ড / গোলত্ব (Radius px)',
                    'title_en' => 'Button Border Radius (px)',
                    'device' => 'all',
                    'options' => json_encode(['min' => 0, 'max' => 50, 'unit' => 'px']),
                ],
                [
                    'key' => 'global_button_animation',
                    'value' => '1',
                    'group' => 'buttons',
                    'type' => 'boolean',
                    'title_bn' => 'বাটনের অ্যানিমেশন / পালস ইফেক্ট (Pulse)',
                    'title_en' => 'Button Pulse Animation',
                    'device' => 'all',
                    'options' => null,
                ],

                // 5. Search Bar Customization
                [
                    'key' => 'search_bar_bg_color',
                    'value' => '#ffffff',
                    'group' => 'search_bar',
                    'type' => 'color',
                    'title_bn' => 'সার্চ বারের ব্যাকগ্রাউন্ড কালার',
                    'title_en' => 'Search Bar Background Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'search_bar_border_color',
                    'value' => '#e2e8f0',
                    'group' => 'search_bar',
                    'type' => 'color',
                    'title_bn' => 'সার্চ বারের বর্ডার কালার',
                    'title_en' => 'Search Bar Border Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'search_button_bg_color',
                    'value' => '#ea580c',
                    'group' => 'search_bar',
                    'type' => 'color',
                    'title_bn' => 'সার্চ বাটন (QSearch) ব্যাকগ্রাউন্ড কালার',
                    'title_en' => 'Search Submit Button Background Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'search_button_text_color',
                    'value' => '#ffffff',
                    'group' => 'search_bar',
                    'type' => 'color',
                    'title_bn' => 'সার্চ বাটন লেখার কালার',
                    'title_en' => 'Search Button Text Color',
                    'device' => 'all',
                    'options' => null,
                ],

                // 6. Floating Triggers: Live Chat & Scroll to Top Arrow
                [
                    'key' => 'chat_button_enabled',
                    'value' => '1',
                    'group' => 'floating_triggers',
                    'type' => 'boolean',
                    'title_bn' => 'লাইভ চ্যাট বাটন প্রদর্শন (On/Off)',
                    'title_en' => 'Live Chat Button Visible',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'chat_button_bg_color',
                    'value' => '#ea580c',
                    'group' => 'floating_triggers',
                    'type' => 'color',
                    'title_bn' => 'লাইভ চ্যাট বাটন ব্যাকগ্রাউন্ড কালার',
                    'title_en' => 'Live Chat Button Background Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'chat_button_icon_color',
                    'value' => '#ffffff',
                    'group' => 'floating_triggers',
                    'type' => 'color',
                    'title_bn' => 'লাইভ চ্যাট আইকন কালার',
                    'title_en' => 'Live Chat Icon Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'scroll_button_enabled',
                    'value' => '1',
                    'group' => 'floating_triggers',
                    'type' => 'boolean',
                    'title_bn' => 'স্ক্রল অ্যারো বাটন প্রদর্শন (On/Off)',
                    'title_en' => 'Scroll-to-Top Arrow Visible',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'scroll_button_bg_color',
                    'value' => '#ea580c',
                    'group' => 'floating_triggers',
                    'type' => 'color',
                    'title_bn' => 'স্ক্রল অ্যারো বাটন ব্যাকগ্রাউন্ড কালার',
                    'title_en' => 'Scroll-to-Top Button Background Color',
                    'device' => 'all',
                    'options' => null,
                ],
                [
                    'key' => 'scroll_button_icon_color',
                    'value' => '#ffffff',
                    'group' => 'floating_triggers',
                    'type' => 'color',
                    'title_bn' => 'স্ক্রল অ্যারো আইকন কালার',
                    'title_en' => 'Scroll-to-Top Arrow Icon Color',
                    'device' => 'all',
                    'options' => null,
                ],
            ];

            $now = now();
            foreach ($defaults as &$item) {
                $item['created_at'] = $now;
                $item['updated_at'] = $now;
            }

            DB::table('ui_component_settings')->insert($defaults);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_component_settings');
    }
};
