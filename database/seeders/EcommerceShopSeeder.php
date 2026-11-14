<?php

namespace Database\Seeders;

use App\Models\Shop\ShopCategory;
use App\Models\Shop\ShopSubcategory;
use App\Models\Shop\ShopProduct;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EcommerceShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories and Subcategories
        $categoriesData = [
            [
                'name' => 'Food',
                'name_bn' => 'খাবার ও মুদি সামগ্রী',
                'slug' => 'food',
                'icon' => 'fa-cutlery',
                'order_num' => 1,
                'subcategories' => [
                    ['name' => 'Cooking & Oil', 'name_bn' => 'ভোজ্য তেল ও ঘি', 'slug' => 'cooking-oil'],
                    ['name' => 'Rice & Flour', 'name_bn' => 'চাল, ডাল ও আটা', 'slug' => 'rice-flour'],
                    ['name' => 'Dairy & Eggs', 'name_bn' => 'দুধ ও ডিম', 'slug' => 'dairy-eggs'],
                    ['name' => 'Spices & Masala', 'name_bn' => 'মশলা ও রান্নার উপকরণ', 'slug' => 'spices-masala'],
                    ['name' => 'Meat & Fish', 'name_bn' => 'মাছ ও মাংস', 'slug' => 'meat-fish'],
                    ['name' => 'Snacks & Beverages', 'name_bn' => 'নাস্তা ও কোমল পানীয়', 'slug' => 'snacks-beverages'],
                ]
            ],
            [
                'name' => 'Baby Food & Care',
                'name_bn' => 'বেবি ফুড ও কেয়ার',
                'slug' => 'baby-food-care',
                'icon' => 'fa-child',
                'order_num' => 2,
                'subcategories' => [
                    ['name' => 'Diapers & Wipes', 'name_bn' => 'ডায়াপার ও ওয়াইপস', 'slug' => 'diapers-wipes'],
                    ['name' => 'Baby Food & Milk', 'name_bn' => 'শিশু খাদ্য ও ফর্মুলা মিল্ক', 'slug' => 'baby-food-milk'],
                    ['name' => 'Baby Skin & Bath', 'name_bn' => 'শিশুর স্কিনকেয়ার ও বাথ', 'slug' => 'baby-skin-bath'],
                ]
            ],
            [
                'name' => 'Home Cleaning',
                'name_bn' => 'হোম ক্লিনিং ও হাইজিন',
                'slug' => 'home-cleaning',
                'icon' => 'fa-tint',
                'order_num' => 3,
                'subcategories' => [
                    ['name' => 'Detergents & Fabric', 'name_bn' => 'ডিটারজেন্ট ও ওয়াশিং', 'slug' => 'detergents-fabric'],
                    ['name' => 'Dishwash & Kitchen', 'name_bn' => 'ডিশওয়াশ ও কিচেন কেয়ার', 'slug' => 'dishwash-kitchen'],
                    ['name' => 'Air Fresheners', 'name_bn' => 'এয়ার ফ্রেশনার ও পেস্ট কন্ট্রোল', 'slug' => 'air-fresheners'],
                ]
            ],
            [
                'name' => 'Beauty & Health',
                'name_bn' => 'রূপচর্চা ও স্বাস্থ্য',
                'slug' => 'beauty-health',
                'icon' => 'fa-heart',
                'order_num' => 4,
                'subcategories' => [
                    ['name' => 'Skincare & Lotions', 'name_bn' => 'স্কিনকেয়ার ও লোশন', 'slug' => 'skincare-lotions'],
                    ['name' => 'Hair Care & Shampoos', 'name_bn' => 'হেয়ার কেয়ার ও শ্যাম্পু', 'slug' => 'haircare-shampoos'],
                    ['name' => 'Oral Care & Soap', 'name_bn' => 'টুথপেস্ট ও সাবান', 'slug' => 'oral-care-soap'],
                ]
            ],
            [
                'name' => 'Fashion & Lifestyle',
                'name_bn' => 'ফ্যাশন ও লাইফস্টাইল',
                'slug' => 'fashion-lifestyle',
                'icon' => 'fa-female',
                'order_num' => 5,
                'subcategories' => [
                    ['name' => 'Men Fashion', 'name_bn' => 'পুরুষদের পোশাক', 'slug' => 'men-fashion'],
                    ['name' => 'Women Fashion', 'name_bn' => 'নারীদের পোশাক', 'slug' => 'women-fashion'],
                    ['name' => 'Footwear & Bags', 'name_bn' => 'জুতো ও ব্যাগ', 'slug' => 'footwear-bags'],
                ]
            ],
            [
                'name' => 'Home & Kitchen',
                'name_bn' => 'হোম ও কিচেন',
                'slug' => 'home-kitchen',
                'icon' => 'fa-home',
                'order_num' => 6,
                'subcategories' => [
                    ['name' => 'Cookware & Pans', 'name_bn' => 'কুকওয়্যার ও পাত্র', 'slug' => 'cookware-pans'],
                    ['name' => 'Storage & Containers', 'name_bn' => 'স্টোরেজ বক্স ও জার', 'slug' => 'storage-containers'],
                    ['name' => 'Kitchen Tools', 'name_bn' => 'কিচেন গ্যাজেট ও টুলস', 'slug' => 'kitchen-tools'],
                ]
            ],
            [
                'name' => 'Stationeries',
                'name_bn' => 'স্টেশনারি ও অফিস সাপ্লাই',
                'slug' => 'stationeries',
                'icon' => 'fa-pencil',
                'order_num' => 7,
                'subcategories' => [
                    ['name' => 'Pens & Pencils', 'name_bn' => 'কলম ও পেন্সিল', 'slug' => 'pens-pencils'],
                    ['name' => 'Paper & Notebooks', 'name_bn' => 'খাতা ও এ৪ পেপার', 'slug' => 'paper-notebooks'],
                    ['name' => 'Office Organizers', 'name_bn' => 'ফাইল ও ডেস্ক অর্গানাইজার', 'slug' => 'office-organizers'],
                ]
            ],
            [
                'name' => 'Toys & Sports',
                'name_bn' => 'খেলনা ও খেলাধুলা',
                'slug' => 'toys-sports',
                'icon' => 'fa-futbol-o',
                'order_num' => 8,
                'subcategories' => [
                    ['name' => 'Kids Educational Toys', 'name_bn' => 'শিক্ষণীয় খেলনা', 'slug' => 'kids-toys'],
                    ['name' => 'Fitness & Sports Gear', 'name_bn' => 'ফিটনেস ও স্পোর্টস সরঞ্জাম', 'slug' => 'fitness-sports'],
                ]
            ],
            [
                'name' => 'Pet Care',
                'name_bn' => 'পোষা প্রাণীর খাদ্য ও যত্ন',
                'slug' => 'pet-care',
                'icon' => 'fa-paw',
                'order_num' => 9,
                'subcategories' => [
                    ['name' => 'Cat Food & Treats', 'name_bn' => 'বিড়ালের খাবার', 'slug' => 'cat-food'],
                    ['name' => 'Dog Food & Accessories', 'name_bn' => 'কুকুরের খাবার ও এক্সেসরিজ', 'slug' => 'dog-food'],
                ]
            ]
        ];

        $catMap = [];
        $subcatMap = [];

        foreach ($categoriesData as $cData) {
            $cat = ShopCategory::updateOrCreate(
                ['slug' => $cData['slug']],
                [
                    'name' => $cData['name'],
                    'name_bn' => $cData['name_bn'],
                    'icon' => $cData['icon'],
                    'order_num' => $cData['order_num'],
                    'is_active' => true,
                ]
            );
            $catMap[$cData['slug']] = $cat->id;

            if (isset($cData['subcategories'])) {
                $subOrder = 1;
                foreach ($cData['subcategories'] as $sData) {
                    $subcat = ShopSubcategory::updateOrCreate(
                        ['category_id' => $cat->id, 'slug' => $sData['slug']],
                        [
                            'name' => $sData['name'],
                            'name_bn' => $sData['name_bn'],
                            'order_num' => $subOrder++,
                            'is_active' => true,
                        ]
                    );
                    $subcatMap[$sData['slug']] = $subcat->id;
                }
            }
        }

        // 2. Products Data (matching user's screenshots exactly)
        $productsData = [
            // Mustard Oil 500ml (Featured from detail page)
            [
                'name' => 'Fortune Mustard Oil 500ml',
                'name_bn' => 'ফরচুন সরিষার তেল ৫০০ মি.লি.',
                'slug' => 'fortune-mustard-oil-500ml',
                'sku' => '8941184001044',
                'brand' => 'Fortune',
                'price' => 195.00,
                'old_price' => 210.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '500ml',
                'stock_quantity' => 120,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
                'description' => 'Experience the bold, pungent kick and traditional aroma of authentic heritage cooking with Fortune Mustard Oil (ফরচুন সরিষার তেল ৫০০ মি.লি.). Cold-pressed from meticulously chosen, prime-quality mustard seeds using traditional crushing techniques (Kachi Ghani style), this pure mustard oil retains its full natural pungency, essential nutrients, and rich golden hue. Refined and filtered under stringent hygienic standards, Fortune Mustard Oil imparts a strong, sharp flavor profile and an invigorating fragrance that instantly uplifts traditional curries, bhartas, pickles, and artisanal gravies. Rich in monounsaturated and polyunsaturated fats (MUFA & PUFA), Omega-3, and natural antioxidants, it supports everyday wellness while honoring traditional Bengali culinary perfection.'
            ],
            // Biryani Rice
            [
                'name' => 'Fortune Biryani Special Basmati Rice 1kg',
                'name_bn' => 'ফরচুন বিরিয়ানি স্পেশাল বাসমতি চাল ১ কেজি',
                'slug' => 'fortune-biryani-special-basmati-rice-1kg',
                'sku' => '8941184001051',
                'brand' => 'Fortune',
                'price' => 400.00,
                'old_price' => 430.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '1kg',
                'stock_quantity' => 85,
                'category_slug' => 'food',
                'subcategory_slug' => 'rice-flour',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=600&auto=format&fit=crop&q=80',
                'description' => 'Premium extra-long grain basmati rice specifically aged and processed for royal biryanis, polao, and festive platters. Grains elongate up to double their length upon cooking without sticking together, releasing a captivating natural floral aroma and pristine fluffy texture.'
            ],
            // Rupchada Chinigura
            [
                'name' => 'Rupchada Chinigura Aromatic Rice 1kg',
                'name_bn' => 'রূপচাঁদা চিনিগুঁড়া সুগন্ধি চাল ১ কেজি',
                'slug' => 'rupchada-chinigura-aromatic-rice-1kg',
                'sku' => '8941184001068',
                'brand' => 'Rupchada',
                'price' => 230.00,
                'old_price' => 245.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '1kg',
                'stock_quantity' => 150,
                'category_slug' => 'food',
                'subcategory_slug' => 'rice-flour',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1536304993881-ff6e9eefa2a6?w=600&auto=format&fit=crop&q=80',
                'description' => 'Direct from the fertile lands of northern Bengal, Rupchada Chinigura Aromatic Rice offers unparalleled small-grain fragrance, ideal for traditional payesh, kheer, fragrant polao, and festive khichuri. 100% sortex clean and free from impurities.'
            ],
            // Rupchada Zirashail 5kg
            [
                'name' => 'Rupchada Zirashail Parboiled Rice 5kg',
                'name_bn' => 'রূপচাঁদা জিরাশাইল সিদ্ধ চাল ৫ কেজি',
                'slug' => 'rupchada-zirashail-parboiled-rice-5kg',
                'sku' => '8941184001075',
                'brand' => 'Rupchada',
                'price' => 480.00,
                'old_price' => 510.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '5kg',
                'stock_quantity' => 90,
                'category_slug' => 'food',
                'subcategory_slug' => 'rice-flour',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1541832676-9b763b0239ab?w=600&auto=format&fit=crop&q=80',
                'description' => 'Nutritious, high-fiber, thin-grain parboiled rice suitable for daily household meals. Carefully steam-parboiled to lock in essential vitamins and minerals, providing wholesome nourishment with clean digestion.'
            ],
            // Rupchada Nazirshail 5kg
            [
                'name' => 'Rupchada Nazirshail Parboiled Rice 5kg',
                'name_bn' => 'রূপচাঁদা নাজিরশাইল সিদ্ধ চাল ৫ কেজি',
                'slug' => 'rupchada-nazirshail-parboiled-rice-5kg',
                'sku' => '8941184001082',
                'brand' => 'Rupchada',
                'price' => 525.00,
                'old_price' => 550.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '5kg',
                'stock_quantity' => 110,
                'category_slug' => 'food',
                'subcategory_slug' => 'rice-flour',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=600&auto=format&fit=crop&q=80',
                'description' => 'Top-tier Nazirshail grain, beloved by Bangladeshi families for its slender structure and delightful taste. Retains firmness and lightness upon cooking, making every lunch and dinner a joyful feast.'
            ],
            // Fortune Mustard Oil 1L
            [
                'name' => 'Fortune Mustard Oil 1L',
                'name_bn' => 'ফরচুন সরিষার তেল ১ লিটার',
                'slug' => 'fortune-mustard-oil-1l',
                'sku' => '8941184001099',
                'brand' => 'Fortune',
                'price' => 370.00,
                'old_price' => 390.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '1L',
                'stock_quantity' => 75,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
                'description' => '1 Liter bottle of Fortune premium cold-pressed Kachi Ghani mustard oil. Packed with authentic sharpness, essential fatty acids, and vibrant natural color.'
            ],
            // Fortune Rice Bran Oil 2L
            [
                'name' => 'Fortune Rice Bran Oil 2L',
                'name_bn' => 'ফরচুন রাইস ব্রান অয়েল ২ লিটার',
                'slug' => 'fortune-rice-bran-oil-2l',
                'sku' => '8941184001105',
                'brand' => 'Fortune',
                'price' => 478.00,
                'old_price' => 510.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '2L',
                'stock_quantity' => 60,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1620706857370-e1b9770e8bb1?w=600&auto=format&fit=crop&q=80',
                'description' => 'Naturally heart-friendly cooking oil rich in Gamma Oryzanol, supporting lower cholesterol and cardiovascular fitness. High smoke point ensures minimal oil absorption in fried delights.'
            ],
            // Related Oil 1: EC Sunflower Oil 1L
            [
                'name' => 'EC Sunflower Oil 1L',
                'name_bn' => 'ইসি সূর্যমুখী তেল ১ লিটার',
                'slug' => 'ec-sunflower-oil-1l',
                'sku' => '8941184001112',
                'brand' => 'EC',
                'price' => 455.00,
                'old_price' => 480.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '1L',
                'stock_quantity' => 45,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => false,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1589733955941-5eeaf752f6dd?w=600&auto=format&fit=crop&q=80',
                'description' => 'Light, clear, and Vitamin E fortified pure sunflower seed oil. Enhances the natural flavors of vegetables, fish, and salads with zero greasy residue.'
            ],
            // Related Oil 2: Bashundhara Fortified Soybean Oil 2L
            [
                'name' => 'Bashundhara Fortified Soybean Oil 2L',
                'name_bn' => 'বসুন্ধরা ফর্টিফাইড সয়াবিন তেল ২ লিটার',
                'slug' => 'bashundhara-fortified-soybean-oil-2l',
                'sku' => '8941184001129',
                'brand' => 'Bashundhara',
                'price' => 408.00,
                'old_price' => 420.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '2L',
                'stock_quantity' => 130,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => false,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1620706857370-e1b9770e8bb1?w=600&auto=format&fit=crop&q=80',
                'description' => 'Vitamin A and D fortified pure soybean oil refined with European state-of-the-art multi-stage de-waxing technology.'
            ],
            // Related Oil 3: Pusti Soybean Oil 2L
            [
                'name' => 'Pusti Soybean Oil 2L',
                'name_bn' => 'পুষ্টি সয়াবিন তেল ২ লিটার',
                'slug' => 'pusti-soybean-oil-2l',
                'sku' => '8941184001136',
                'brand' => 'Pusti',
                'price' => 408.00,
                'old_price' => 420.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '2L',
                'stock_quantity' => 80,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => false,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=600&auto=format&fit=crop&q=80',
                'description' => 'Clean, refined, and budget-friendly pure soybean cooking oil for every kitchen.'
            ],
            // Related Oil 4: Rahul Mustard Oil 5L
            [
                'name' => 'Rahul Mustard Oil 5L',
                'name_bn' => 'রাহুল সরিষার তেল ৫ লিটার জার',
                'slug' => 'rahul-mustard-oil-5l',
                'sku' => '8941184001143',
                'brand' => 'Rahul',
                'price' => 1550.00,
                'old_price' => 1650.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '5L',
                'stock_quantity' => 35,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => true,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
                'description' => 'Large family economy pack 5 Liter jar of traditional kachi ghani mustard oil.'
            ],
            // Related Oil 5: Heartlight Canola Oil Canadian 3L
            [
                'name' => 'Heartlight Canola Oil Canadian 3L',
                'name_bn' => 'হার্টলাইট ক্যানোলা অয়েল কানাডিয়ান ৩ লিটার',
                'slug' => 'heartlight-canola-oil-canadian-3l',
                'sku' => '8941184001150',
                'brand' => 'Heartlight',
                'price' => 2450.00,
                'old_price' => 2600.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '3L',
                'stock_quantity' => 20,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => true,
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1589733955941-5eeaf752f6dd?w=600&auto=format&fit=crop&q=80',
                'description' => 'Imported 100% pure Canadian canola oil. Exceptionally low in saturated fat, cholesterol-free, and ideal for fitness conscious diets.'
            ],
            // Related Oil 6: Terra Delyssa Extra Virgin Olive Oil 1L
            [
                'name' => 'Terra Delyssa Premium Extra Virgin Olive Oil 1L',
                'name_bn' => 'টেরা ডেলিসা এক্সট্রা ভার্জিন অলিভ অয়েল ১ লিটার',
                'slug' => 'terra-delyssa-premium-extra-virgin-olive-oil-1l',
                'sku' => '8941184001167',
                'brand' => 'Terra Delyssa',
                'price' => 2580.00,
                'old_price' => 2750.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '1L',
                'stock_quantity' => 25,
                'category_slug' => 'food',
                'subcategory_slug' => 'cooking-oil',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=600&auto=format&fit=crop&q=80',
                'description' => 'Single-source cold-extracted Mediterranean extra virgin olive oil from sun-drenched Tunisian orchards. Acidity below 0.5%.'
            ],
            // Extra Grocery 1: Dairy & Eggs
            [
                'name' => 'Farm Fresh Organic Brown Eggs (12 Pcs)',
                'name_bn' => 'ফার্ম ফ্রেশ দেশি লাল ডিম (১২টি)',
                'slug' => 'farm-fresh-organic-brown-eggs-12pcs',
                'sku' => '8941184001174',
                'brand' => 'ekrishisheba Farm',
                'price' => 165.00,
                'old_price' => 180.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '12 Pcs',
                'stock_quantity' => 200,
                'category_slug' => 'food',
                'subcategory_slug' => 'dairy-eggs',
                'is_exclusive' => false,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1516467508483-a7212febe31a?w=600&auto=format&fit=crop&q=80',
                'description' => 'Farm fresh, antibiotic-free, calcium-rich organic brown eggs directly harvested from ekrishisheba certified poultry farms.'
            ],
            // Extra Grocery 2: Dano Milk Powder
            [
                'name' => 'Dano Daily Pushti Full Cream Milk Powder 500g',
                'name_bn' => 'ডানো ডেইলি পুষ্টি ফুল ক্রিম মিল্ক পাউডার ৫০০ গ্রাম',
                'slug' => 'dano-daily-pushti-milk-powder-500g',
                'sku' => '8941184001181',
                'brand' => 'Dano',
                'price' => 430.00,
                'old_price' => 450.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '500g',
                'stock_quantity' => 100,
                'category_slug' => 'food',
                'subcategory_slug' => 'dairy-eggs',
                'is_exclusive' => false,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1550583724-b2692b85b150?w=600&auto=format&fit=crop&q=80',
                'description' => 'Wholesome full cream dairy milk powder enriched with 26 essential vitamins and minerals for children and family health.'
            ],
            // Baby Food: Huggies Diapers
            [
                'name' => 'Huggies Wonder Pants Diaper Large (34 Pcs)',
                'name_bn' => 'হাগিস ওয়ান্ডার প্যান্টস ডায়াপার লার্জ (৩৪ পিস)',
                'slug' => 'huggies-wonder-pants-diaper-large-34pcs',
                'sku' => '8941184001198',
                'brand' => 'Huggies',
                'price' => 950.00,
                'old_price' => 1050.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '34 Pcs',
                'stock_quantity' => 40,
                'category_slug' => 'baby-food-care',
                'subcategory_slug' => 'diapers-wipes',
                'is_exclusive' => true,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=600&auto=format&fit=crop&q=80',
                'description' => 'Breathable, bubble bed comfort diapers offering up to 12 hours of overnight dry protection for active toddlers.'
            ],
            // Home Cleaning: Wheel Detergent
            [
                'name' => 'Wheel 2in1 Clean & Fresh Washing Powder 1kg',
                'name_bn' => 'হুইল টু-ইন-ওয়ান ওয়াশিং পাউডার ১ কেজি',
                'slug' => 'wheel-2in1-washing-powder-1kg',
                'sku' => '8941184001204',
                'brand' => 'Wheel',
                'price' => 155.00,
                'old_price' => 165.00,
                'delivery_time' => 'Delivery 1-2 hours',
                'unit' => '1kg',
                'stock_quantity' => 150,
                'category_slug' => 'home-cleaning',
                'subcategory_slug' => 'detergents-fabric',
                'is_exclusive' => false,
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1585421514738-01798e348b17?w=600&auto=format&fit=crop&q=80',
                'description' => 'Lemon and floral freshness power removes stubborn stains while leaving fabrics visibly brighter and fragrant.'
            ]
        ];

        foreach ($productsData as $pData) {
            $catId = $catMap[$pData['category_slug']] ?? null;
            $subcatId = $subcatMap[$pData['subcategory_slug']] ?? null;

            ShopProduct::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'category_id' => $catId,
                    'subcategory_id' => $subcatId,
                    'name' => $pData['name'],
                    'name_bn' => $pData['name_bn'],
                    'sku' => $pData['sku'],
                    'brand' => $pData['brand'],
                    'price' => $pData['price'],
                    'old_price' => $pData['old_price'],
                    'delivery_time' => $pData['delivery_time'],
                    'unit' => $pData['unit'],
                    'stock_quantity' => $pData['stock_quantity'],
                    'image' => $pData['image'],
                    'description' => $pData['description'],
                    'is_exclusive' => $pData['is_exclusive'],
                    'is_featured' => $pData['is_featured'],
                    'is_active' => true,
                ]
            );
        }
    }
}

