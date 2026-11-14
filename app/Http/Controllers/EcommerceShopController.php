<?php

namespace App\Http\Controllers;

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
use App\Models\Shop\ShopComboOffer;
use App\Models\Shop\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EcommerceShopController extends Controller
{
    /**
     * Display the main shop storefront.
     */
    public function index(Request $request)
    {
        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])->where('is_active', true)->orderBy('order_num', 'asc')->get();

        $sliders = ShopSlider::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $banners = ShopBanner::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $brands = ShopBrand::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $settings = ShopSetting::all()->pluck('value', 'key');

        $searchQuery = trim($request->get('search', ''));
        $categorySlug = $request->get('category');
        $subcategorySlug = $request->get('subcategory');
        $brandSlug = $request->get('brand');
        $badgeFilter = $request->get('badge');

        $activeCategory = null;
        $activeSubcategory = null;
        $activeBrand = null;

        // Base query for filtered listing
        $query = ShopProduct::where('is_active', true);

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('name_bn', 'like', "%{$searchQuery}%")
                  ->orWhere('sku', 'like', "%{$searchQuery}%")
                  ->orWhere('brand', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        if ($categorySlug) {
            $activeCategory = ShopCategory::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }

        if ($subcategorySlug) {
            $activeSubcategory = ShopSubcategory::where('slug', $subcategorySlug)->first();
            if ($activeSubcategory) {
                $query->where('subcategory_id', $activeSubcategory->id);
            }
        }

        if ($brandSlug) {
            $activeBrand = ShopBrand::where('slug', $brandSlug)->first();
            if ($activeBrand) {
                $query->where('brand_id', $activeBrand->id);
            }
        }

        if ($badgeFilter) {
            if ($badgeFilter === 'winter-essentials') {
                $query->where(function ($q) {
                    $q->where('is_winter_essential', true)
                      ->orWhere('name', 'like', '%oil%')
                      ->orWhere('name', 'like', '%rice%')
                      ->orWhere('is_exclusive', true);
                });
            } elseif ($badgeFilter === 'monthly-bazar') {
                $query->where(function ($q) {
                    $q->where('is_monthly_bazar', true)
                      ->orWhere('is_featured', true);
                });
            } elseif ($badgeFilter === 'combo-offers') {
                $query->where(function ($q) {
                    $q->where('is_combo_offer', true)
                      ->orWhereNotNull('old_price');
                });
            } elseif ($badgeFilter === 'daily-offers') {
                $query->where(function ($q) {
                    $q->where('is_daily_offer', true)
                      ->orWhere('is_exclusive', true);
                });
            } elseif ($badgeFilter === 'popular') {
                $query->where(function ($q) {
                    $q->where('is_popular', true)
                      ->orWhere('is_featured', true);
                });
            } elseif ($badgeFilter === 'new-arrivals') {
                $query->where('is_new_arrival', true);
            } elseif ($badgeFilter === 'trending') {
                $query->where('is_trending', true);
            } elseif ($badgeFilter === 'best-selling') {
                $query->where('is_best_selling', true);
            }
        }

        $isFiltered = ($searchQuery !== '' || $categorySlug || $subcategorySlug || $brandSlug || $badgeFilter);

        // Exclusive deals for slider / section
        $exclusiveDeals = ShopProduct::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_exclusive', true)
                  ->orWhere('is_daily_offer', true)
                  ->orWhereNotNull('old_price');
            })
            ->orderBy('id', 'asc')
            ->take(12)
            ->get();

        // Featured products
        $featuredProducts = ShopProduct::where('is_active', true)
            ->where(function ($q) {
                $q->where('is_featured', true)
                  ->orWhere('is_popular', true);
            })
            ->orderBy('id', 'asc')
            ->take(12)
            ->get();

        // Explore Our Products collections
        $newArrivalProducts = ShopProduct::where('is_active', true)
            ->where('is_new_arrival', true)
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();
        if ($newArrivalProducts->count() < 6) {
            $newArrivalProducts = ShopProduct::where('is_active', true)->orderBy('id', 'desc')->take(12)->get();
        }

        $trendingProducts = ShopProduct::where('is_active', true)
            ->where('is_trending', true)
            ->orderBy('id', 'asc')
            ->take(12)
            ->get();
        if ($trendingProducts->count() < 6) {
            $trendingProducts = ShopProduct::where('is_active', true)->orderBy('price', 'desc')->take(12)->get();
        }

        $popularProducts = ShopProduct::where('is_active', true)
            ->where('is_popular', true)
            ->orderBy('id', 'desc')
            ->take(12)
            ->get();
        if ($popularProducts->count() < 6) {
            $popularProducts = ShopProduct::where('is_active', true)->orderBy('price', 'asc')->take(12)->get();
        }

        // Category-wise product rows (Food, Baby Food & Care, Beauty & Health, etc.)
        $categorySections = ShopCategory::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->take(12);
            }])
            ->orderBy('order_num', 'asc')
            ->get();

        // Paginated products (for browse / filter mode)
        $products = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();

        // Specific Banner for badge / collection page (e.g. Monthly Bazar, Daily Offers)
        $pageBanner = null;
        if ($badgeFilter) {
            $posKey = str_replace('-', '_', $badgeFilter);
            $pageBanner = ShopBanner::where('position', $posKey)->where('is_active', true)->orderBy('order_num', 'asc')->first();
        }

        return view('frontend.shop.index', compact(
            'categories',
            'sliders',
            'banners',
            'brands',
            'settings',
            'exclusiveDeals',
            'featuredProducts',
            'newArrivalProducts',
            'trendingProducts',
            'popularProducts',
            'categorySections',
            'products',
            'isFiltered',
            'searchQuery',
            'activeCategory',
            'activeSubcategory',
            'activeBrand',
            'badgeFilter',
            'pageBanner'
        ));
    }

    /**
     * Show single product details.
     */
    public function show($slug)
    {
        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])->where('is_active', true)->orderBy('order_num', 'asc')->get();

        $settings = ShopSetting::all()->pluck('value', 'key');

        $product = ShopProduct::with([
                'category',
                'subcategory',
                'images',
                'variants' => function ($q) {
                    $q->where('is_active', true);
                }
            ])
            ->where(function ($q) use ($slug) {
                $q->where('slug', $slug);
                if (is_numeric($slug)) {
                    $q->orWhere('id', (int)$slug);
                }
            })
            ->where('is_active', true)
            ->firstOrFail();

        // Related products in the same category or subcategory
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

        return view('frontend.shop.show', compact('categories', 'product', 'relatedProducts', 'settings'));
    }

    /**
     * Checkout page.
     */
    public function checkout()
    {
        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])->where('is_active', true)->orderBy('order_num', 'asc')->get();

        $shippingZones = ShopShippingZone::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $paymentMethods = ShopPaymentMethod::where('is_active', true)->orderBy('order_num', 'asc')->get();
        $settings = ShopSetting::all()->pluck('value', 'key');

        $user = Auth::user();

        return view('frontend.shop.checkout', compact('categories', 'user', 'shippingZones', 'paymentMethods', 'settings'));
    }

    /**
     * Apply coupon code endpoint.
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
     * Place an order from checkout.
     */
    public function placeOrder(Request $request)
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
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'আপনার ব্যাগে কোনো পণ্য নেই!'], 422);
            }
            return back()->with('error', 'আপনার ব্যাগে কোনো পণ্য নেই!');
        }

        // Calculate shipping charges dynamically from DB or fallback
        $shippingZoneId = $request->shipping_zone_id;
        $shippingMethod = $request->shipping_method ?: 'dhaka_free';
        $shippingCharge = 0;

        if ($shippingZoneId) {
            $zone = ShopShippingZone::find($shippingZoneId);
            if ($zone) {
                $shippingCharge = (float)$zone->charge;
                $shippingMethod = $zone->name;
            }
        } else {
            if ($shippingMethod === 'inside_dhaka') {
                $shippingCharge = 60;
            } elseif ($shippingMethod === 'outside_dhaka') {
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

                // Verify with DB if product exists
                if ($productId) {
                    $dbProduct = ShopProduct::find($productId);
                    if ($dbProduct) {
                        $price = (float)$dbProduct->price;
                        $productName = $dbProduct->name;
                        $sku = $dbProduct->sku;

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
                    'variant_details' => $variantDetails,
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

            $initialHistory = [
                [
                    'status' => 'pending',
                    'comment' => 'গ্রাহক দ্বারা নতুন অর্ডার সফলভাবে তৈরি করা হয়েছে।',
                    'time' => now()->toDateTimeString()
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
                'platform' => $request->filled('platform') ? $request->platform : ($request->session()->get('ecommerce_platform', 'Website')),
                'address' => $request->address,
                'city' => $request->city ?? 'Dhaka',
                'shipping_method' => $shippingMethod,
                'shipping_charge' => $shippingCharge,
                'discount_amount' => $discountAmount,
                'coupon_code' => $couponCode,
                'subtotal' => $subtotal,
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method ?: 'cod',
                'payment_status' => 'pending',
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

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'অর্ডার সফলভাবে সম্পন্ন হয়েছে!',
                    'order_number' => $order->order_number,
                    'redirect_url' => route('shop.order_success', $order->order_number)
                ]);
            }

            return redirect()->route('shop.order_success', $order->order_number)->with('success', 'আপনার অর্ডারটি সফলভাবে গ্রহণ করা হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'অর্ডার সম্পন্ন করতে সমস্যা হয়েছে: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'অর্ডার সম্পন্ন করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
        }
    }

    /**
     * Order success page.
     */
    public function orderSuccess($orderNumber)
    {
        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])->where('is_active', true)->orderBy('order_num', 'asc')->get();

        $settings = ShopSetting::all()->pluck('value', 'key');
        $order = ShopOrder::with('items.product')->where('order_number', $orderNumber)->firstOrFail();

        return view('frontend.shop.order_success', compact('categories', 'order', 'settings'));
    }

    /**
     * API search autocomplete for quick search bar.
     */
    public function apiSearch(Request $request)
    {
        $q = trim($request->get('q', ''));
        if (strlen($q) < 2) {
            return response()->json(['results' => []]);
        }

        $products = ShopProduct::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('name_bn', 'like', "%{$q}%")
                      ->orWhere('brand', 'like', "%{$q}%");
            })
            ->select('id', 'name', 'name_bn', 'slug', 'price', 'image', 'delivery_time')
            ->take(8)
            ->get();

        return response()->json([
            'results' => $products->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'name_bn' => $item->name_bn,
                    'slug' => $item->slug,
                    'price' => number_format($item->price, 0) . '৳',
                    'raw_price' => $item->price,
                    'image' => $item->image,
                    'url' => route('products.show', $item->slug),
                    'delivery_time' => $item->delivery_time,
                ];
            })
        ]);
    }

    /**
     * Customer order tracking page
     */
    public function trackOrder(Request $request)
    {
        $categories = ShopCategory::with(['subcategories' => function ($q) {
            $q->where('is_active', true)->orderBy('order_num', 'asc');
        }])->where('is_active', true)->orderBy('order_num', 'asc')->get();

        $settings = ShopSetting::all()->pluck('value', 'key');
        $order = null;
        $orderId = trim($request->get('order_id', ''));
        $phone = trim($request->get('phone', ''));

        if ($orderId || $phone) {
            $query = ShopOrder::with('items.product');
            if ($orderId) {
                $cleanId = ltrim($orderId, '#');
                $query->where(function($q) use ($cleanId) {
                    $q->where('order_number', $cleanId)
                      ->orWhere('order_number', 'like', "%{$cleanId}%")
                      ->orWhere('id', $cleanId);
                });
            }
            if ($phone) {
                $query->where('phone', 'like', "%{$phone}%");
            }
            $order = $query->latest()->first();
        }

        return view('frontend.shop.track_order', compact('categories', 'settings', 'order', 'orderId', 'phone'));
    }

    /**
     * Customer order tracking form POST
     */
    public function trackOrderPost(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'phone' => 'required|string',
        ]);

        return redirect()->route('shop.track_order', [
            'order_id' => trim($request->order_id),
            'phone' => trim($request->phone),
        ]);
    }
}
