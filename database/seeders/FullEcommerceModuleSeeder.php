<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop\ShopBrand;
use App\Models\Shop\ShopSlider;
use App\Models\Shop\ShopBanner;
use App\Models\Shop\ShopAttribute;
use App\Models\Shop\ShopAttributeValue;
use App\Models\Shop\ShopShippingZone;
use App\Models\Shop\ShopPaymentMethod;
use App\Models\Shop\ShopCoupon;
use App\Models\Shop\ShopSetting;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopProductVariant;

class FullEcommerceModuleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Shipping Zones
        $zones = [
            [
                'name' => 'Free Shipping (Dhaka city only)',
                'charge' => 0,
                'estimated_time' => '১-২ ঘণ্টা',
                'min_order_amount' => 500,
                'free_shipping_min_amount' => 500,
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Inside Dhaka (ইনসাইড ঢাকা)',
                'charge' => 60,
                'estimated_time' => '২৪-৪৮ ঘণ্টা',
                'min_order_amount' => 0,
                'free_shipping_min_amount' => 2000,
                'order_num' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Outside Dhaka (আউটসাইড ঢাকা)',
                'charge' => 120,
                'estimated_time' => '২-৩ কার্যদিবস',
                'min_order_amount' => 0,
                'free_shipping_min_amount' => 3000,
                'order_num' => 3,
                'is_active' => true,
            ],
        ];
        foreach ($zones as $z) {
            ShopShippingZone::updateOrCreate(['name' => $z['name']], $z);
        }

        // 2. Payment Methods
        $payments = [
            [
                'name' => 'Cash on Delivery (ক্যাশ অন ডেলিভারি)',
                'code' => 'cod',
                'merchant_number' => null,
                'instructions' => 'পণ্য হাতে পেয়ে চেক করে ডেলিভারিম্যানকে মূল্য পরিশোধ করুন।',
                'is_manual' => true,
                'is_active' => true,
                'order_num' => 1,
            ],
            [
                'name' => 'bKash (বিকাশ মার্চেন্ট/পার্সোনাল)',
                'code' => 'bkash',
                'merchant_number' => '01805020340',
                'instructions' => 'বিকাশ অ্যাপ অথবা *247# ডায়াল করে আমাদের নম্বরে সেন্ডমানি বা পেমেন্ট করে ট্রানজেকশন আইডি প্রদান করুন।',
                'is_manual' => true,
                'is_active' => true,
                'order_num' => 2,
            ],
            [
                'name' => 'Nagad (নগদ)',
                'code' => 'nagad',
                'merchant_number' => '01805020346',
                'instructions' => 'নগদ অ্যাপ অথবা *167# ডায়াল করে পেমেন্ট সম্পূর্ণ করে TrxID লিখে সাবমিট করুন।',
                'is_manual' => true,
                'is_active' => true,
                'order_num' => 3,
            ],
            [
                'name' => 'Rocket (রকেট)',
                'code' => 'rocket',
                'merchant_number' => '018050203409',
                'instructions' => 'রকেট ওয়ালেট থেকে উল্লেখিত নম্বরে পেমেন্ট করুন।',
                'is_manual' => true,
                'is_active' => true,
                'order_num' => 4,
            ],
            [
                'name' => 'Bank Transfer (ব্যাংক ডিপোজিট)',
                'code' => 'bank',
                'merchant_number' => 'A/C: 123456789 (Islami Bank)',
                'instructions' => 'ব্যাংক স্লিপের রেফারেন্স নম্বরে আপনার অর্ডার আইডি উল্লেখ করুন।',
                'is_manual' => true,
                'is_active' => false,
                'order_num' => 5,
            ]
        ];
        foreach ($payments as $p) {
            ShopPaymentMethod::updateOrCreate(['code' => $p['code']], $p);
        }

        // 3. Brands
        $brands = [
            ['name' => 'Fortune', 'slug' => 'fortune', 'description' => 'Premium Edible Oils & Foods'],
            ['name' => 'Rupchada', 'slug' => 'rupchada', 'description' => 'Pure Soybean & Mustard Oil'],
            ['name' => 'Bashundhara', 'slug' => 'bashundhara', 'description' => 'High quality essential food products'],
            ['name' => 'Pusti', 'slug' => 'pusti', 'description' => 'Healthy family nutrition'],
            ['name' => 'Teer', 'slug' => 'teer', 'description' => 'City Group pure food brand'],
            ['name' => 'Dano', 'slug' => 'dano', 'description' => 'Arla Foods high quality milk powder'],
            ['name' => 'Unilever', 'slug' => 'unilever', 'description' => 'Personal care and home cleaning'],
            ['name' => 'Square', 'slug' => 'square', 'description' => 'Trusted toiletries and health care'],
        ];
        foreach ($brands as $i => $b) {
            ShopBrand::updateOrCreate(['slug' => $b['slug']], array_merge($b, ['order_num' => $i + 1, 'is_active' => true]));
        }

        // 4. Attributes & Values (Size, Color, Weight, Capacity)
        $attributesData = [
            [
                'name' => 'Size',
                'slug' => 'size',
                'type' => 'button',
                'values' => ['S', 'M', 'L', 'XL', 'XXL', '38', '39', '40', '41', '42']
            ],
            [
                'name' => 'Color',
                'slug' => 'color',
                'type' => 'color',
                'values' => ['Red', 'Black', 'White', 'Blue', 'Navy Blue', 'Green']
            ],
            [
                'name' => 'Weight',
                'slug' => 'weight',
                'type' => 'button',
                'values' => ['500g', '1kg', '2kg', '5kg', '10kg', '25kg']
            ],
            [
                'name' => 'Capacity',
                'slug' => 'capacity',
                'type' => 'button',
                'values' => ['500ml', '1L', '2L', '3L', '5L']
            ],
        ];

        foreach ($attributesData as $attr) {
            $createdAttr = ShopAttribute::updateOrCreate(['slug' => $attr['slug']], [
                'name' => $attr['name'],
                'type' => $attr['type'],
                'is_active' => true
            ]);
            foreach ($attr['values'] as $idx => $val) {
                ShopAttributeValue::updateOrCreate([
                    'attribute_id' => $createdAttr->id,
                    'value' => $val
                ], [
                    'order_num' => $idx + 1
                ]);
            }
        }

        // 5. Sliders (Full-width Picture Banners)
        $sliders = [
            [
                'title' => 'এখন ঢাকাজুড়ে ফ্রি হোম ডেলিভারি!',
                'subtitle' => 'স্পেশাল সুপারশপ অফার',
                'description' => 'বিশ্বস্ত পণ্য | তাজা ও মানসম্মত | ১-২ ঘণ্টায় দ্রুত ডেলিভারি',
                'image' => 'uploads/slider/slider-1.svg',
                'button_text' => 'এখনই অর্ডার করুন →',
                'button_link' => '#exclusive-deals',
                'hotline' => '09617900200',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'বিশুদ্ধ সরিষার তেল ও সেরা বাসমতী চাল',
                'subtitle' => '১০০% খাঁটি ও স্বাস্থ্যকর',
                'description' => 'ফরচুন ও রূপচাঁদা ব্যান্ডের সেরা বাজার কালেকশন ঘরে বসেই অর্ডার করুন।',
                'image' => 'uploads/slider/slider-2.svg',
                'button_text' => 'অফার দেখুন →',
                'button_link' => '#exclusive-deals',
                'hotline' => '01805-020340',
                'order_num' => 2,
                'is_active' => true,
            ],
        ];
        foreach ($sliders as $s) {
            ShopSlider::updateOrCreate(['title' => $s['title']], $s);
        }

        // 5.1 Collection & Page Banners
        $pageBanners = [
            [
                'title' => 'বাজার হোক সাশ্রয়ী, সেভিংস হোক বেশি! (Monthly Bazar)',
                'position' => 'monthly_bazar',
                'image' => 'uploads/banner/monthly-bazar-banner.svg',
                'link' => '#',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'ডেইলি ফ্ল্যাশ ডিল ও স্পেশাল অফার (Daily Offers)',
                'position' => 'daily_offers',
                'image' => 'uploads/banner/daily-offers-banner.svg',
                'link' => '#',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'স্পেশাল কম্বো অফার প্যাকেজ (Combo Offers)',
                'position' => 'combo_offers',
                'image' => 'uploads/banner/combo-offers-banner.svg',
                'link' => '#',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'শীতকালীন নিত্যপ্রয়োজনীয় স্পেশাল কালেকশন (Winter Essentials)',
                'position' => 'winter_essentials',
                'image' => 'uploads/banner/winter-essentials-banner.svg',
                'link' => '#',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'জনপ্রিয় পণ্যসমূহ (Popular Products)',
                'position' => 'popular',
                'image' => 'uploads/banner/popular-banner.svg',
                'link' => '#',
                'order_num' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'হোমপেজ মিডল স্পেশাল ব্যানার',
                'position' => 'homepage_middle',
                'image' => 'uploads/banner/monthly-bazar-banner.svg',
                'link' => '#',
                'order_num' => 1,
                'is_active' => true,
            ],
        ];
        foreach ($pageBanners as $pb) {
            ShopBanner::updateOrCreate(['position' => $pb['position']], $pb);
        }

        // 6. Coupons
        $coupons = [
            [
                'code' => 'EKRISHI50',
                'discount_type' => 'fixed',
                'discount_amount' => 50,
                'min_order_amount' => 500,
                'usage_limit' => 500,
                'is_active' => true,
            ],
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percentage',
                'discount_amount' => 10,
                'min_order_amount' => 1000,
                'max_discount' => 200,
                'usage_limit' => 200,
                'is_active' => true,
            ],
        ];
        foreach ($coupons as $c) {
            ShopCoupon::updateOrCreate(['code' => $c['code']], $c);
        }

        // 7. General Settings
        $settings = [
            'shop_name' => 'Asmi Supershop | e-Krishi Sheba',
            'shop_tagline' => 'আপনার বিশ্বস্ত অনলাইন সুপারশপ',
            'hotline' => '09617900200',
            'phone' => '01805-020340',
            'whatsapp' => '01805020340',
            'email' => 'support@asmishop.com',
            'currency_symbol' => '৳',
            'currency_code' => 'BDT',
            'address' => 'Plot #45, Probal Tower, Ring Road, Mohammadpur, Dhaka-1207',
            'free_shipping_dhaka' => '1',
            'estimated_delivery' => 'Delivery 1-2 hours',
        ];
        foreach ($settings as $k => $v) {
            ShopSetting::set($k, $v);
        }

        // 8. Add sample variant to T-Shirt or Shoes if products exist
        $tshirt = ShopProduct::where('slug', 'mens-premium-cotton-tshirt')->first();
        if ($tshirt) {
            $tshirt->update(['has_variants' => true]);
            $variants = [
                ['sku' => 'TSHIRT-BLK-M', 'price' => 450, 'stock_quantity' => 20, 'attribute_values' => ['Size' => 'M', 'Color' => 'Black']],
                ['sku' => 'TSHIRT-BLK-L', 'price' => 450, 'stock_quantity' => 25, 'attribute_values' => ['Size' => 'L', 'Color' => 'Black']],
                ['sku' => 'TSHIRT-BLK-XL', 'price' => 480, 'stock_quantity' => 15, 'attribute_values' => ['Size' => 'XL', 'Color' => 'Black']],
                ['sku' => 'TSHIRT-WHT-M', 'price' => 450, 'stock_quantity' => 20, 'attribute_values' => ['Size' => 'M', 'Color' => 'White']],
                ['sku' => 'TSHIRT-WHT-L', 'price' => 450, 'stock_quantity' => 30, 'attribute_values' => ['Size' => 'L', 'Color' => 'White']],
            ];
            foreach ($variants as $v) {
                ShopProductVariant::updateOrCreate(['sku' => $v['sku']], array_merge($v, ['product_id' => $tshirt->id, 'is_active' => true]));
            }
        }
    }
}
