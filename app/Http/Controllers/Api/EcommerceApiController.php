<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\Shop\ShopCategory;
use App\Models\Shop\ShopSubcategory;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopProductVariant;
use App\Models\Shop\ShopOrder;
use App\Models\Shop\ShopOrderItem;
use App\Models\Shop\ShopSlider;
use App\Models\Shop\ShopBanner;
use App\Models\Shop\ShopBrand;
use App\Models\Shop\ShopShippingZone;
use App\Models\Shop\ShopPaymentMethod;
use App\Models\Shop\ShopCoupon;
use App\Models\Shop\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EcommerceApiController extends Controller
{
    /**
     * Helper to format an image path into a full reachable URL.
     */
    protected function formatImageUrl($path)
    {
        if (empty($path)) {
            return null;
        }
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }
        return url(ltrim($path, '/'));
    }

    /**
     * Format a product model into standard API JSON format.
     */
    protected function transformProduct($product, $detailed = false)
    {
        $mainImageUrl = $this->formatImageUrl($product->image);

        // Gather all gallery image URLs
        $galleryUrls = [];
        if (!empty($product->image)) {
            $galleryUrls[] = $mainImageUrl;
        }
        if ($product->relationLoaded('images') || $detailed) {
            foreach ($product->images as $img) {
                $u = $this->formatImageUrl($img->image);
                if ($u && !in_array($u, $galleryUrls)) {
                    $galleryUrls[] = $u;
                }
            }
        }
        if (!empty($product->gallery)) {
            $extra = is_array($product->gallery) ? $product->gallery : json_decode($product->gallery, true);
            if (is_array($extra)) {
                foreach ($extra as $g) {
                    $u = $this->formatImageUrl($g);
                    if ($u && !in_array($u, $galleryUrls)) {
                        $galleryUrls[] = $u;
                    }
                }
            }
        }

        $price = (float)$product->price;
        $oldPrice = $product->old_price ? (float)$product->old_price : null;
        $savings = ($oldPrice && $oldPrice > $price) ? ($oldPrice - $price) : 0;
        $discountPercent = ($oldPrice && $oldPrice > $price) ? round((($oldPrice - $price) / $oldPrice) * 100) : 0;

        $data = [
            'id' => $product->id,
            'name' => $product->name,
            'name_bn' => $product->name_bn ?: $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'brand' => $product->brand,
            'price' => $price,
            'old_price' => $oldPrice,
            'formatted_price' => number_format($price, 0) . '৳',
            'formatted_old_price' => $oldPrice ? number_format($oldPrice, 0) . '৳' : null,
            'discount_savings' => $savings,
            'discount_percentage' => $discountPercent,
            'unit' => $product->unit ?: '1 Pcs',
            'delivery_time' => $product->delivery_time ?: 'Delivery 1–2 hours',
            'stock_quantity' => (int)$product->stock_quantity,
            'is_in_stock' => (int)$product->stock_quantity > 0,
            'min_order_qty' => (int)($product->min_order_qty ?: 1),
            'max_order_qty' => $product->max_order_qty ? (int)$product->max_order_qty : null,
            'image' => $product->image,
            'image_url' => $mainImageUrl,
            'gallery_images' => $galleryUrls,
            'category_id' => $product->category_id,
            'category_name' => $product->category ? $product->category->name : null,
            'subcategory_id' => $product->subcategory_id,
            'subcategory_name' => $product->subcategory ? $product->subcategory->name : null,
            'has_variants' => (bool)$product->has_variants,
            'is_exclusive' => (bool)$product->is_exclusive,
            'is_featured' => (bool)$product->is_featured,
            'is_new_arrival' => (bool)$product->is_new_arrival,
            'is_trending' => (bool)$product->is_trending,
            'is_popular' => (bool)$product->is_popular,
            'is_daily_offer' => (bool)$product->is_daily_offer,
            'is_combo_offer' => (bool)$product->is_combo_offer,
            'is_winter_essential' => (bool)$product->is_winter_essential,
            'is_monthly_bazar' => (bool)$product->is_monthly_bazar,
        ];

        if ($detailed) {
            $data['description'] = $product->description;
            $data['variants'] = $product->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'attribute_summary' => $variant->attribute_summary,
                    'price' => (float)$variant->price,
                    'old_price' => $variant->old_price ? (float)$variant->old_price : null,
                    'stock_quantity' => (int)$variant->stock_quantity,
                    'is_in_stock' => (int)$variant->stock_quantity > 0,
                    'image_url' => $this->formatImageUrl($variant->image),
                ];
            });
        }

        return $data;
    }

    /**
     * Complete Storefront Home API endpoint.
     * Bundles banners, categories, and top product sections for optimal mobile performance.
     */
    public function home(Request $request)
    {
        $sliders = ShopSlider::active()->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'subtitle' => $s->subtitle,
                'image' => $s->image,
                'image_url' => $this->formatImageUrl($s->image),
                'button_text' => $s->button_text,
                'button_link' => $s->button_link,
                'hotline' => $s->hotline,
            ];
        });

        $banners = ShopBanner::active()->get()->map(function ($b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'position' => $b->position,
                'image' => $b->image,
                'image_url' => $this->formatImageUrl($b->image),
                'link' => $b->link,
            ];
        });

        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])
        ->where('is_active', true)
        ->orderBy('order_num', 'asc')
        ->get()
        ->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'name_bn' => $cat->name_bn ?: $cat->name,
                'slug' => $cat->slug,
                'icon' => $cat->icon,
                'image' => $cat->image,
                'image_url' => $this->formatImageUrl($cat->image),
                'subcategories' => $cat->subcategories->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'name_bn' => $sub->name_bn ?: $sub->name,
                        'slug' => $sub->slug,
                        'image_url' => $this->formatImageUrl($sub->image),
                    ];
                }),
            ];
        });

        $exclusiveDeals = ShopProduct::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_exclusive', true)
                  ->orWhere('is_combo_offer', true)
                  ->orWhere('is_daily_offer', true)
                  ->orWhereNotNull('old_price');
            })
            ->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
            ->orderBy('id', 'asc')
            ->take(12)
            ->get()
            ->map(fn($p) => $this->transformProduct($p));

        $featuredProducts = ShopProduct::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_featured', true)
                  ->orWhere('is_popular', true);
            })
            ->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
            ->orderBy('id', 'asc')
            ->take(12)
            ->get()
            ->map(fn($p) => $this->transformProduct($p));

        $newArrivals = ShopProduct::where('is_active', true)
            ->where('is_new_arrival', true)
            ->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
            ->orderBy('id', 'asc')
            ->take(12)
            ->get()
            ->map(fn($p) => $this->transformProduct($p));

        $trendingProducts = ShopProduct::where('is_active', true)
            ->where('is_trending', true)
            ->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
            ->orderBy('price', 'desc')
            ->take(12)
            ->get()
            ->map(fn($p) => $this->transformProduct($p));

        // Category-wise product rows (Food, Baby Food & Care, Beauty & Health, etc.)
        $categorySections = ShopCategory::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)
                  ->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
                  ->orderBy('id', 'asc')
                  ->take(12);
            }])
            ->orderBy('order_num', 'asc')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'name_bn' => $cat->name_bn ?: $cat->name,
                    'slug' => $cat->slug,
                    'image' => $cat->image,
                    'image_url' => $this->formatImageUrl($cat->image),
                    'products' => $cat->products->map(fn($p) => $this->transformProduct($p))->values(),
                ];
            })
            ->filter(fn($c) => count($c['products']) > 0)
            ->values();

        $settings = ShopSetting::all()->pluck('value', 'key');

        return response()->json([
            'success' => true,
            'sliders' => $sliders,
            'banners' => $banners,
            'categories' => $categories,
            'exclusive_deals' => $exclusiveDeals,
            'featured_products' => $featuredProducts,
            'new_arrivals' => $newArrivals,
            'trending_products' => $trendingProducts,
            'category_sections' => $categorySections,
            'quick_badges' => [
                ['key' => 'winter-essentials', 'label_en' => 'Winter Essentials', 'label_bn' => 'শীতকালীন বাজার', 'icon' => 'uploads/icons/winter_essentials.png', 'banner' => 'uploads/banner/winter-essentials-banner.svg'],
                ['key' => 'monthly-bazar', 'label_en' => 'Monthly Bazar', 'label_bn' => 'মাসিক বাজার', 'icon' => 'uploads/icons/icon-monthly-bazar.png', 'banner' => 'uploads/banner/monthly-bazar-banner.svg'],
                ['key' => 'combo-offers', 'label_en' => 'Combo Offers', 'label_bn' => 'কম্বো অফার', 'icon' => 'uploads/icons/combo_offer.jpg', 'banner' => 'uploads/banner/combo-offers-banner.svg'],
                ['key' => 'daily-offers', 'label_en' => 'Daily Offers', 'label_bn' => 'দৈনিক অফার', 'icon' => 'uploads/icons/offer.gif', 'banner' => 'uploads/banner/daily-offers-banner.svg'],
                ['key' => 'popular', 'label_en' => 'Popular', 'label_bn' => 'জনপ্রিয়', 'icon' => 'uploads/icons/heart.gif', 'banner' => 'uploads/banner/popular-banner.svg'],
                ['key' => 'brands', 'label_en' => 'Shop by Brands', 'label_bn' => 'ব্র্যান্ড শপ', 'icon' => 'uploads/icons/shop_by_brand.svg', 'banner' => null],
            ],
            'sidebar_brand_banner' => 'uploads/icons/szbd_long.png',
            'store_info' => [
                'name' => 'E-Krishi Sheba Superstore',
                'hotline' => $settings['hotline'] ?? '09617900200',
                'whatsapp' => $settings['whatsapp'] ?? '01805020340',
                'delivery_notice' => 'এখন ঢাকায় মাত্র ১-২ ঘণ্টায় হোম ডেলিভারি! সীমিত সময়ের অফার চলছে।',
            ],
        ]);
    }

    /**
     * Banners & Sliders endpoint.
     */
    public function banners(Request $request)
    {
        $sliders = ShopSlider::active()->get()->map(function ($s) {
            return [
                'id' => $s->id,
                'title' => $s->title,
                'subtitle' => $s->subtitle,
                'image' => $s->image,
                'image_url' => $this->formatImageUrl($s->image),
                'button_text' => $s->button_text,
                'button_link' => $s->button_link,
                'hotline' => $s->hotline,
            ];
        });

        $banners = ShopBanner::active()->get()->map(function ($b) {
            return [
                'id' => $b->id,
                'title' => $b->title,
                'position' => $b->position,
                'image' => $b->image,
                'image_url' => $this->formatImageUrl($b->image),
                'link' => $b->link,
            ];
        });

        return response()->json([
            'success' => true,
            'sliders' => $sliders,
            'banners' => $banners,
        ]);
    }

    /**
     * Categories endpoint with subcategories.
     */
    public function categories(Request $request)
    {
        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])
        ->where('is_active', true)
        ->orderBy('order_num', 'asc')
        ->get()
        ->map(function ($cat) {
            return [
                'id' => $cat->id,
                'name' => $cat->name,
                'name_bn' => $cat->name_bn ?: $cat->name,
                'slug' => $cat->slug,
                'icon' => $cat->icon,
                'image' => $cat->image,
                'image_url' => $this->formatImageUrl($cat->image),
                'products_count' => $cat->products()->where('is_active', true)->count(),
                'subcategories' => $cat->subcategories->map(function ($sub) {
                    return [
                        'id' => $sub->id,
                        'name' => $sub->name,
                        'name_bn' => $sub->name_bn ?: $sub->name,
                        'slug' => $sub->slug,
                        'image_url' => $this->formatImageUrl($sub->image),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'categories' => $categories,
        ]);
    }

    /**
     * Products listing with search, category, badge filters, sorting and pagination.
     */
    public function products(Request $request)
    {
        $query = ShopProduct::with(['category', 'subcategory'])->where('is_active', true);

        // Search filter
        $search = trim($request->get('search', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('name_bn', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter (by ID or Slug)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        } elseif ($request->filled('category')) {
            $catSlug = $request->get('category');
            $cat = ShopCategory::where('slug', $catSlug)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        // Subcategory filter (by ID or Slug)
        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->get('subcategory_id'));
        } elseif ($request->filled('subcategory')) {
            $subSlug = $request->get('subcategory');
            $sub = ShopSubcategory::where('slug', $subSlug)->first();
            if ($sub) {
                $query->where('subcategory_id', $sub->id);
            }
        }

        // Badge filter (exclusive, monthly-bazar, combo-offers, etc.)
        $badge = $request->get('badge');
        if ($badge) {
            if ($badge === 'exclusive' || $badge === 'exclusive-deals') {
                $query->where(function ($q) {
                    $q->where('is_exclusive', true)
                      ->orWhere('is_combo_offer', true)
                      ->orWhere('is_daily_offer', true)
                      ->orWhereNotNull('old_price');
                });
            } elseif ($badge === 'featured') {
                $query->where('is_featured', true);
            } elseif ($badge === 'popular') {
                $query->where('is_popular', true);
            } elseif ($badge === 'new-arrivals') {
                $query->where('is_new_arrival', true);
            } elseif ($badge === 'trending') {
                $query->where('is_trending', true);
            } elseif ($badge === 'best-selling') {
                $query->where('is_best_selling', true);
            } elseif ($badge === 'daily-offers') {
                $query->where(function ($q) {
                    $q->where('is_daily_offer', true)
                      ->orWhere('is_exclusive', true);
                });
            } elseif ($badge === 'combo-offers') {
                $query->where(function ($q) {
                    $q->where('is_combo_offer', true)
                      ->orWhereNotNull('old_price');
                });
            } elseif ($badge === 'winter-essentials') {
                $query->where(function ($q) {
                    $q->where('is_winter_essential', true)
                      ->orWhere('name', 'like', '%oil%')
                      ->orWhere('name', 'like', '%rice%')
                      ->orWhere('is_exclusive', true);
                });
            } elseif ($badge === 'monthly-bazar') {
                $query->where(function ($q) {
                    $q->where('is_monthly_bazar', true)
                      ->orWhere('is_featured', true);
                });
            }
        }

        // Sorting
        $sort = $request->get('sort', 'default');
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->orderBy('id', 'desc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } else {
            // Default: Prioritize products with real images
            $query->orderByRaw("CASE WHEN image LIKE 'uploads/products/%' THEN 0 ELSE 1 END")
                  ->orderBy('id', 'asc');
        }

        $perPage = min(100, max(5, (int)$request->get('per_page', 20)));
        $paginated = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'current_page' => $paginated->currentPage(),
            'last_page' => $paginated->lastPage(),
            'per_page' => $paginated->perPage(),
            'total' => $paginated->total(),
            'products' => collect($paginated->items())->map(fn($p) => $this->transformProduct($p)),
        ]);
    }

    /**
     * Single product details with gallery, variants, and related products.
     */
    public function productDetail(Request $request, $idOrSlug)
    {
        $product = ShopProduct::with([
                'category',
                'subcategory',
                'images',
                'variants' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->where(function ($q) use ($idOrSlug) {
                if (is_numeric($idOrSlug)) {
                    $q->where('id', (int)$idOrSlug)->orWhere('slug', $idOrSlug);
                } else {
                    $q->where('slug', $idOrSlug);
                }
            })
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'পণ্যটি খুঁজে পাওয়া যায়নি!',
            ], 404);
        }

        // Fetch related products
        $relatedProducts = ShopProduct::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->category_id) {
                    $q->where('category_id', $product->category_id);
                }
            })
            ->take(8)
            ->get();

        if ($relatedProducts->count() < 4) {
            $moreProducts = ShopProduct::where('is_active', true)
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->take(8 - $relatedProducts->count())
                ->get();
            $relatedProducts = $relatedProducts->merge($moreProducts);
        }

        return response()->json([
            'success' => true,
            'product' => $this->transformProduct($product, true),
            'related_products' => $relatedProducts->map(fn($p) => $this->transformProduct($p)),
        ]);
    }

    /**
     * Related products endpoint.
     */
    public function relatedProducts(Request $request, $idOrSlug)
    {
        $product = ShopProduct::where(function ($q) use ($idOrSlug) {
                if (is_numeric($idOrSlug)) {
                    $q->where('id', (int)$idOrSlug)->orWhere('slug', $idOrSlug);
                } else {
                    $q->where('slug', $idOrSlug);
                }
            })
            ->where('is_active', true)
            ->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $related = ShopProduct::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where(function ($q) use ($product) {
                if ($product->category_id) {
                    $q->where('category_id', $product->category_id);
                }
            })
            ->take(8)
            ->get()
            ->map(fn($p) => $this->transformProduct($p));

        return response()->json([
            'success' => true,
            'related_products' => $related,
        ]);
    }

    /**
     * Shipping methods / zones endpoint.
     */
    public function shippingMethods(Request $request)
    {
        $zones = ShopShippingZone::active()->get()->map(function ($z) {
            return [
                'id' => $z->id,
                'name' => $z->name,
                'charge' => (float)$z->charge,
                'charge_formatted' => ((float)$z->charge == 0) ? 'ফ্রি' : ('৳ ' . number_format($z->charge, 0)),
                'estimated_time' => $z->estimated_time ?: '১-২ কার্যদিবস',
                'min_order_amount' => (float)$z->min_order_amount,
                'free_shipping_min_amount' => (float)$z->free_shipping_min_amount,
            ];
        });

        return response()->json([
            'success' => true,
            'shipping_methods' => $zones,
        ]);
    }

    /**
     * Payment methods endpoint.
     */
    public function paymentMethods(Request $request)
    {
        $methods = ShopPaymentMethod::where('is_active', true)
            ->orderBy('order_num', 'asc')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'code' => $p->code,
                    'merchant_number' => $p->merchant_number,
                    'instructions' => $p->instructions,
                    'logo_url' => $this->formatImageUrl($p->logo),
                    'is_manual' => (bool)$p->is_manual,
                ];
            });

        return response()->json([
            'success' => true,
            'payment_methods' => $methods,
        ]);
    }

    /**
     * Apply coupon verification.
     */
    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('code', '')));
        $subtotal = (float)$request->input('subtotal', 0);

        if (!$code) {
            return response()->json(['success' => false, 'message' => 'কুপন কোড প্রদান করুন'], 422);
        }

        $coupon = ShopCoupon::where('code', $code)->where('is_active', true)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'অবৈধ অথবা মেয়াদোত্তীর্ণ কুপন কোড!'], 404);
        }

        $today = date('Y-m-d');
        if ($coupon->start_date && $today < $coupon->start_date) {
            return response()->json(['success' => false, 'message' => 'এই কুপনের অফার এখনো শুরু হয়নি!'], 400);
        }
        if ($coupon->end_date && $today > $coupon->end_date) {
            return response()->json(['success' => false, 'message' => 'এই কুপনটির মেয়াদ শেষ হয়ে গেছে!'], 400);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'এই কুপন ব্যবহারের সর্বোচ্চ সীমা শেষ হয়েছে!'], 400);
        }

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return response()->json([
                'success' => false, 
                'message' => 'এই কুপন ব্যবহারের জন্য সর্বনিম্ন ৳' . number_format($coupon->min_order_amount, 0) . ' টাকার পণ্য অর্ডার করতে হবে!'
            ], 400);
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->discount_type === 'percentage') {
            $discount = ($subtotal * $coupon->discount_amount) / 100;
            if ($coupon->max_discount && $discount > $coupon->max_discount) {
                $discount = (float)$coupon->max_discount;
            }
        } else {
            $discount = (float)$coupon->discount_amount;
        }

        $discount = min($discount, $subtotal);

        return response()->json([
            'success' => true,
            'code' => $coupon->code,
            'discount' => round($discount, 2),
            'discount_formatted' => '৳ ' . number_format($discount, 0),
            'message' => 'কুপন সফলভাবে প্রয়োগ হয়েছে! আপনি ৳' . number_format($discount, 0) . ' ছাড় পেয়েছেন।'
        ]);
    }

    /**
     * Place order from Mobile Application.
     * Directly inserts into database (ShopOrder & ShopOrderItem).
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'required|string|max:25',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'items' => 'required',
        ]);

        $rawItems = $request->items;
        if (is_string($rawItems)) {
            $itemsData = json_decode($rawItems, true) ?? [];
        } else {
            $itemsData = (array)$rawItems;
        }

        if (empty($itemsData)) {
            return response()->json(['success' => false, 'message' => 'আপনার ব্যাগে কোনো পণ্য নেই!'], 422);
        }

        // Shipping Zone determination
        $shippingZoneId = $request->shipping_zone_id;
        $shippingMethod = $request->shipping_method ?: 'Inside Dhaka';
        $shippingCharge = 0;

        if ($shippingZoneId) {
            $zone = ShopShippingZone::find($shippingZoneId);
            if ($zone) {
                $shippingCharge = (float)$zone->charge;
                $shippingMethod = $zone->name;
            }
        } else {
            if (Str::contains(strtolower($shippingMethod), 'inside')) {
                $shippingCharge = 60;
            } elseif (Str::contains(strtolower($shippingMethod), 'outside')) {
                $shippingCharge = 120;
            } else {
                $shippingCharge = 0;
            }
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsToCreate = [];

            foreach ($itemsData as $item) {
                $productId = $item['id'] ?? null;
                $quantity = max(1, (int)($item['quantity'] ?? 1));
                $price = (float)($item['price'] ?? 0);
                $productName = $item['name'] ?? 'Product';
                $sku = $item['sku'] ?? null;
                $variantDetails = $item['variant_details'] ?? null;

                // Sync with latest DB price and reduce stock
                if ($productId) {
                    $dbProduct = ShopProduct::find($productId);
                    if ($dbProduct) {
                        $price = (float)$dbProduct->price;
                        $productName = $dbProduct->name;
                        $sku = $dbProduct->sku ?: $sku;

                        // Safely deduct stock
                        $dbProduct->stock_quantity = max(0, $dbProduct->stock_quantity - $quantity);
                        $dbProduct->save();
                    }
                }

                $lineTotal = $price * $quantity;
                $subtotal += $lineTotal;

                $orderItemsToCreate[] = [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'sku' => $sku,
                    'variant_details' => is_array($variantDetails) ? json_encode($variantDetails) : $variantDetails,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $lineTotal,
                ];
            }

            // Coupon discount calculation
            $discountAmount = 0;
            $couponCode = null;
            if ($request->filled('coupon_code')) {
                $coupon = ShopCoupon::where('code', strtoupper(trim($request->coupon_code)))
                    ->where('is_active', true)
                    ->first();
                if ($coupon) {
                    if ($coupon->discount_type === 'percentage') {
                        $discountAmount = ($subtotal * $coupon->discount_amount) / 100;
                        if ($coupon->max_discount && $discountAmount > $coupon->max_discount) {
                            $discountAmount = (float)$coupon->max_discount;
                        }
                    } else {
                        $discountAmount = (float)$coupon->discount_amount;
                    }
                    $discountAmount = min($discountAmount, $subtotal);
                    $couponCode = $coupon->code;
                    $coupon->increment('used_count');
                }
            }

            $totalAmount = max(0, ($subtotal - $discountAmount)) + $shippingCharge;
            $orderNumber = 'ASMI-' . date('ymd') . '-' . strtoupper(Str::random(4));

            $payMethod = $request->payment_method ?: 'cod';
            $payLabel = ($payMethod === 'cod') ? 'ক্যাশ অন ডেলিভারি (Cash on Delivery)' : ('অনলাইন পেমেন্ট: ' . strtoupper($payMethod));

            $initialHistory = [
                [
                    'status' => 'pending',
                    'comment' => "গ্রাহক মোবাইল অ্যাপস থেকে নতুন অর্ডার সফলভাবে তৈরি করেছেন। [পদ্ধতি: {$payLabel}]",
                    'time' => now()->toDateTimeString(),
                    'changed_by' => 'Mobile App User'
                ]
            ];

            $order = ShopOrder::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id() ?? null,
                'shipping_zone_id' => $shippingZoneId,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'platform' => 'Mobile App',
                'address' => $request->address,
                'city' => $request->city ?? 'Dhaka',
                'shipping_method' => $shippingMethod,
                'shipping_charge' => $shippingCharge,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'payment_method' => $payMethod,
                'payment_status' => ($payMethod === 'cod') ? 'pending' : 'paid',
                'payment_transaction_id' => $request->payment_transaction_id,
                'order_status' => 'pending',
                'status_history' => $initialHistory,
                'notes' => $request->notes,
            ]);

            foreach ($orderItemsToCreate as $oi) {
                $oi['order_id'] = $order->id;
                ShopOrderItem::create($oi);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'অর্ডার সফলভাবে সম্পন্ন হয়েছে!',
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount,
                'subtotal' => $order->subtotal,
                'shipping_charge' => $order->shipping_charge,
                'discount_amount' => $order->discount_amount,
                'created_at' => $order->created_at->toDateTimeString(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'অর্ডার প্রক্রিয়াকরণে সমস্যা হয়েছে: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track Order / Order details API endpoint.
     */
    public function trackOrder(Request $request)
    {
        $queryParam = trim($request->get('order_number') ?: $request->get('q', ''));
        $phone = trim($request->get('phone', ''));

        if (!$queryParam && !$phone) {
            return response()->json(['success' => false, 'message' => 'অর্ডার আইডি অথবা ফোন নম্বর দিন'], 422);
        }

        $query = ShopOrder::with('items.product')->where('order_status', '!=', 'incomplete');

        if ($queryParam) {
            $cleanId = ltrim($queryParam, '#');
            $query->where(function ($q) use ($cleanId) {
                $q->where('order_number', $cleanId)
                  ->orWhere('order_number', 'like', "%{$cleanId}%")
                  ->orWhere('id', $cleanId);
            });
        }

        if ($phone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            $last10 = (strlen($cleanPhone) >= 10) ? substr($cleanPhone, -10) : $cleanPhone;
            $query->where(function ($q) use ($phone, $cleanPhone, $last10) {
                $q->where('phone', 'like', "%{$phone}%");
                if ($cleanPhone) $q->orWhere('phone', 'like', "%{$cleanPhone}%");
                if ($last10) $q->orWhere('phone', 'like', "%{$last10}%");
            });
        }

        $orders = $query->latest()->take(5)->get();

        if ($orders->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'কোনো অর্ডার খুঁজে পাওয়া যায়নি। অনুগ্রহ করে সঠিক অর্ডার আইডি অথবা ফোন নম্বর দিন।'
            ], 404);
        }

        $results = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'first_name' => $order->first_name,
                'last_name' => $order->last_name,
                'customer_name' => trim($order->first_name . ' ' . $order->last_name),
                'phone' => $order->phone,
                'email' => $order->email,
                'address' => $order->address,
                'city' => $order->city,
                'shipping_method' => $order->shipping_method,
                'shipping_charge' => (float)$order->shipping_charge,
                'subtotal' => (float)$order->subtotal,
                'discount_amount' => (float)$order->discount_amount,
                'total_amount' => (float)$order->total_amount,
                'payment_method' => $order->payment_method,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'status_history' => $order->status_history ?: [],
                'created_at' => $order->created_at->toDateTimeString(),
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product_name,
                        'sku' => $item->sku,
                        'price' => (float)$item->price,
                        'quantity' => (int)$item->quantity,
                        'total' => (float)$item->total,
                        'image_url' => ($item->product && $item->product->image) ? $this->formatImageUrl($item->product->image) : null,
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'orders' => $results,
            'primary_order' => $results->first(),
        ]);
    }

    /**
     * Live Chat: Fetch conversation messages for session.
     */
    public function getChatMessages(Request $request)
    {
        $sessionId = $request->get('session_id') ?: $request->input('session_id');
        if (!$sessionId) {
            return response()->json(['success' => false, 'message' => 'Session ID required'], 422);
        }

        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'session_id' => $msg->session_id,
                    'sender_type' => $msg->sender_type, // 'user', 'admin', 'bot'
                    'sender_name' => $msg->sender_name,
                    'message' => $msg->message,
                    'is_read' => (bool)$msg->is_read,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'messages' => $messages,
        ]);
    }

    /**
     * Live Chat: Send message from mobile app.
     * Records to website database ChatMessage and triggers AI bot response if no admin has replied yet.
     */
    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'session_id' => 'required|string',
        ]);

        $sessionId = trim($request->input('session_id'));
        $userName = trim($request->input('sender_name', 'Mobile App Visitor'));

        // Save User Message
        ChatMessage::create([
            'session_id' => $sessionId,
            'user_id' => Auth::id() ?? null,
            'sender_type' => 'user',
            'sender_name' => $userName ?: 'Mobile App Visitor',
            'user_role' => 'user',
            'message' => trim($request->input('message')),
            'is_read' => false,
        ]);

        // Check if an admin has already replied
        $hasAdminReplied = ChatMessage::where('session_id', $sessionId)
            ->where('sender_type', 'admin')
            ->exists();

        // If no admin has replied yet, generate AI reply
        if (!$hasAdminReplied) {
            $aiReplyText = $this->generateAiReply(trim($request->input('message')), $sessionId);
            if ($aiReplyText) {
                ChatMessage::create([
                    'session_id' => $sessionId,
                    'user_id' => null,
                    'sender_type' => 'bot',
                    'sender_name' => 'কৃষি পরিবার AI Assistant',
                    'user_role' => 'user',
                    'message' => $aiReplyText,
                    'is_read' => true,
                ]);
            }
        }

        // Return updated message list
        $messages = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'session_id' => $msg->session_id,
                    'sender_type' => $msg->sender_type,
                    'sender_name' => $msg->sender_name,
                    'message' => $msg->message,
                    'is_read' => (bool)$msg->is_read,
                    'created_at' => $msg->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'messages' => $messages,
        ]);
    }

    /**
     * AI Assistant Bengali/English reply generator.
     */
    protected function generateAiReply($text, $sessionId = null)
    {
        $lower = mb_strtolower($text, 'UTF-8');

        $previousBotReplies = $sessionId ? ChatMessage::where('session_id', $sessionId)->where('sender_type', 'bot')->pluck('message')->toArray() : [];
        $hasGreeted = false;
        $hasSentFallback = false;
        foreach ($previousBotReplies as $prev) {
            if (Str::contains($prev, 'আসসালামু আলাইকুম')) {
                $hasGreeted = true;
            }
            if (Str::contains($prev, 'ধন্যবাদ আপনার বার্তার জন্য')) {
                $hasSentFallback = true;
            }
        }

        if (Str::contains($lower, ['অর্ডার', 'ডেলিভারি', 'শিপিং', 'order', 'delivery', 'shipping'])) {
            return "🤖 **অর্ডার ও ডেলিভারি তথ্য:**\nঢাকা সিটিতে মাত্র ১-২ ঘণ্টায় এক্সপ্রেস হোম ডেলিভারি দেওয়া হয়। ডেলিভারি চার্জ মাত্র ৬০ টাকা (৫০০+ টাকার অর্ডারে ফ্রি)। ঢাকার বাইরে ২-৩ কার্যদিবস সময় লাগে। আপনার অর্ডারের বর্তমান অবস্থা জানতে অ্যাপের 'অর্ডার ট্র্যাক' অপশন ব্যবহার করুন।";
        }

        if (Str::contains($lower, ['পণ্য', 'প্রোডাক্ট', 'চাল', 'তেল', 'মধু', 'product', 'price', 'দাম'])) {
            return "🤖 **পণ্য ও মূল্য তথ্য:**\nআমাদের সুপারস্টোরে সব পণ্যের ১০০% খাঁটি মান নিশ্চিত করা হয়। আপনি 'Our Products' পেজে গিয়ে সকল ক্যাটাগরির পণ্য ও ডিসকাউন্ট মূল্য সরাসরি দেখতে পারবেন।";
        }

        if (Str::contains($lower, ['পেমেন্ট', 'বিকাশ', 'নগদ', 'payment', 'bkash', 'nagad', 'cod'])) {
            return "🤖 **পেমেন্ট পদ্ধতি:**\nআমরা ক্যাশ অন ডেলিভারি (পণ্য হাতে পেয়ে মূল্য পরিশোধ) এবং বিকাশ/নগদ ম্যানুয়াল পেমেন্ট গ্রহণ করি।";
        }

        if (Str::contains($lower, ['ফোন', 'হেল্পলাইন', 'যোগাযোগ', 'ঠিকানা', 'contact', 'phone', 'number', 'help'])) {
            return "🤖 **কাস্টমার সাপোর্ট হেল্পলাইন:**\nহটলাইন: 09617900200\nহোয়াটসঅ্যাপ: 01805020340\nআমাদের প্রতিনিধি খুব শীঘ্রই লাইভ চ্যাটে আপনার সাথে যুক্ত হবেন।";
        }

        if (Str::contains($lower, ['hi', 'hello', 'হাই', 'হ্যালো', 'সালাম', 'assalamu', 'salam'])) {
            if ($hasGreeted) {
                return null;
            }
            return "🤖 **আসসালামু আলাইকুম!** ই-কৃষি সেবা সুপারস্টোর লাইভ সাপোর্টে আপনাকে স্বাগতম। আপনাকে কীভাবে সাহায্য করতে পারি?";
        }

        if ($hasSentFallback) {
            return null;
        }

        return "🤖 ধন্যবাদ আপনার বার্তার জন্য! আমাদের কৃষি সেবা সাপোর্ট প্রতিনিধি খুব শীঘ্রই আপনার প্রশ্নের উত্তর দেবেন। যেকোনো অর্ডার তথ্যের জন্য আপনার অর্ডার আইডি বা ফোন নম্বর জানাতে পারেন।";
    }
}
