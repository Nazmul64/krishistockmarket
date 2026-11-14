<?php

namespace App\Http\Controllers\Admin\Ecommerce;

use App\Http\Controllers\Controller;
use App\Models\Shop\ShopCategory;
use App\Models\Shop\ShopSubcategory;
use App\Models\Shop\ShopBrand;
use App\Models\Shop\ShopProduct;
use App\Models\Shop\ShopProductVariant;
use App\Models\Shop\ShopProductImage;
use App\Models\Shop\ShopAttribute;
use App\Models\Shop\ShopAttributeValue;
use App\Models\Shop\ShopSlider;
use App\Models\Shop\ShopBanner;
use App\Models\Shop\ShopComboOffer;
use App\Models\Shop\ShopCoupon;
use App\Models\Shop\ShopOrder;
use App\Models\Shop\ShopOrderItem;
use App\Models\Shop\ShopShippingZone;
use App\Models\Shop\ShopPaymentMethod;
use App\Models\Shop\ShopSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class EcommerceAdminController extends Controller
{
    /**
     * 1. Dashboard Overview
     */
    public function dashboard()
    {
        $metrics = [
            'total_products' => ShopProduct::count(),
            'active_products' => ShopProduct::where('is_active', true)->count(),
            'total_categories' => ShopCategory::count(),
            'total_brands' => ShopBrand::count(),
            'total_orders' => ShopOrder::count(),
            'pending_orders' => ShopOrder::where('order_status', 'pending')->count(),
            'processing_orders' => ShopOrder::where('order_status', 'processing')->count(),
            'shipping_orders' => ShopOrder::where('order_status', 'shipping')->count(),
            'delivered_orders' => ShopOrder::where('order_status', 'delivered')->count(),
            'completed_orders' => ShopOrder::where('order_status', 'completed')->count(),
            'cancelled_orders' => ShopOrder::where('order_status', 'cancelled')->count(),
            'total_sales' => ShopOrder::whereNotIn('order_status', ['cancelled'])->sum('total_amount'),
            'today_sales' => ShopOrder::whereDate('created_at', today())->whereNotIn('order_status', ['cancelled'])->sum('total_amount'),
            'monthly_sales' => ShopOrder::whereMonth('created_at', now()->month)->whereNotIn('order_status', ['cancelled'])->sum('total_amount'),
            'low_stock_count' => ShopProduct::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count(),
            'out_of_stock_count' => ShopProduct::where('stock_quantity', '<=', 0)->count(),
            'total_customers' => User::count(),
        ];

        $recentOrders = ShopOrder::with('items')->orderBy('id', 'desc')->take(8)->get();
        $lowStockProducts = ShopProduct::where('stock_quantity', '<=', 10)->orderBy('stock_quantity', 'asc')->take(6)->get();

        return view('admin.ecommerce.dashboard', compact('metrics', 'recentOrders', 'lowStockProducts'));
    }

    /**
     * 2. Slider Management
     */
    public function sliders()
    {
        $sliders = ShopSlider::orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.sliders.index', compact('sliders'));
    }

    public function sliderStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'title' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
        ]);

        $imagePath = $this->uploadFile($request->file('image'), 'uploads/slider');

        ShopSlider::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'image' => $imagePath,
            'button_text' => $request->button_text ?: 'এখনই অর্ডার করুন →',
            'button_link' => $request->button_link ?: '#',
            'hotline' => $request->hotline ?: '09617900200',
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'নতুন স্লাইডার সফলভাবে আপলোড করা হয়েছে!');
    }

    public function sliderUpdate(Request $request, $id)
    {
        $slider = ShopSlider::findOrFail($id);
        $data = [
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'button_text' => $request->button_text ?: 'এখনই অর্ডার করুন →',
            'button_link' => $request->button_link ?: '#',
            'hotline' => $request->hotline ?: '09617900200',
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteFile($slider->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/slider');
        }

        $slider->update($data);
        return back()->with('success', 'স্লাইডার সফলভাবে আপডেট করা হয়েছে!');
    }

    public function sliderDestroy($id)
    {
        $slider = ShopSlider::findOrFail($id);
        $this->deleteFile($slider->image);
        $slider->delete();
        return back()->with('success', 'স্লাইডার সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * 3. Banner Management
     */
    public function banners()
    {
        $banners = ShopBanner::orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.banners.index', compact('banners'));
    }

    public function bannerStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:3072',
            'title' => 'nullable|string|max:255',
        ]);

        $imagePath = $this->uploadFile($request->file('image'), 'uploads/banner');

        ShopBanner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link ?: '#',
            'position' => $request->position ?: 'homepage_middle',
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'ব্যানার সফলভাবে যোগ করা হয়েছে!');
    }

    public function bannerUpdate(Request $request, $id)
    {
        $banner = ShopBanner::findOrFail($id);
        $data = [
            'title' => $request->title,
            'link' => $request->link ?: '#',
            'position' => $request->position ?: 'homepage_middle',
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteFile($banner->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/banner');
        }

        $banner->update($data);
        return back()->with('success', 'ব্যানার সফলভাবে আপডেট করা হয়েছে!');
    }

    public function bannerDestroy($id)
    {
        $banner = ShopBanner::findOrFail($id);
        $this->deleteFile($banner->image);
        $banner->delete();
        return back()->with('success', 'ব্যানার সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * 4. Categories & Subcategories
     */
    public function categories()
    {
        $categories = ShopCategory::withCount(['products', 'subcategories'])->orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.categories.index', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:shop_categories,slug',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = $request->slug ? Str::slug($request->slug) : Str::slug($request->name);
        if (ShopCategory::where('slug', $slug)->exists()) {
            $slug .= '-' . rand(10, 99);
        }

        $imagePath = $request->hasFile('image') ? $this->uploadFile($request->file('image'), 'uploads/category') : null;

        ShopCategory::create([
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'slug' => $slug,
            'icon' => $request->icon ?: 'fa-shopping-basket',
            'image' => $imagePath,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে!');
    }

    public function categoryUpdate(Request $request, $id)
    {
        $cat = ShopCategory::findOrFail($id);
        $data = [
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'icon' => $request->icon ?: 'fa-shopping-basket',
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteFile($cat->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/category');
        }

        $cat->update($data);
        return back()->with('success', 'ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে!');
    }

    public function categoryDestroy($id)
    {
        $cat = ShopCategory::withCount('products')->findOrFail($id);
        if ($cat->products_count > 0) {
            return back()->with('error', 'এই ক্যাটাগরিতে পণ্য রয়েছে। ক্যাটাগরি ডিলিট করার পূর্বে পণ্য অন্য ক্যাটাগরিতে স্থানান্তর করুন অথবা নিষ্ক্রিয় করুন।');
        }
        $this->deleteFile($cat->image);
        $cat->delete();
        return back()->with('success', 'ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে!');
    }

    public function subcategories()
    {
        $categories = ShopCategory::orderBy('name', 'asc')->get();
        $subcategories = ShopSubcategory::with('category')->withCount('products')->orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.subcategories.index', compact('categories', 'subcategories'));
    }

    public function subcategoryStore(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:shop_categories,id',
            'name' => 'required|string|max:150',
        ]);

        $slug = Str::slug($request->name);
        if (ShopSubcategory::where('slug', $slug)->exists()) {
            $slug .= '-' . rand(10, 99);
        }

        ShopSubcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'slug' => $slug,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'সাব-ক্যাটাগরি সফলভাবে তৈরি করা হয়েছে!');
    }

    public function subcategoryUpdate(Request $request, $id)
    {
        $sub = ShopSubcategory::findOrFail($id);
        $sub->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'সাব-ক্যাটাগরি সফলভাবে আপডেট করা হয়েছে!');
    }

    public function subcategoryDestroy($id)
    {
        $sub = ShopSubcategory::withCount('products')->findOrFail($id);
        if ($sub->products_count > 0) {
            return back()->with('error', 'এই সাব-ক্যাটাগরিতে পণ্য রয়েছে। মুছে ফেলার আগে পণ্য অন্য সাব-ক্যাটাগরিতে স্থানান্তর করুন।');
        }
        $sub->delete();
        return back()->with('success', 'সাব-ক্যাটাগরি সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * 5. Brand Management
     */
    public function brands()
    {
        $brands = ShopBrand::withCount('products')->orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.brands.index', compact('brands'));
    }

    public function brandStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $slug = Str::slug($request->name);
        if (ShopBrand::where('slug', $slug)->exists()) {
            $slug .= '-' . rand(10, 99);
        }

        $logoPath = $request->hasFile('logo') ? $this->uploadFile($request->file('logo'), 'uploads/brand') : null;

        ShopBrand::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'logo' => $logoPath,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'ব্র্যান্ড সফলভাবে তৈরি করা হয়েছে!');
    }

    public function brandUpdate(Request $request, $id)
    {
        $brand = ShopBrand::findOrFail($id);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('logo')) {
            $this->deleteFile($brand->logo);
            $data['logo'] = $this->uploadFile($request->file('logo'), 'uploads/brand');
        }

        $brand->update($data);
        return back()->with('success', 'ব্র্যান্ড সফলভাবে আপডেট করা হয়েছে!');
    }

    public function brandDestroy($id)
    {
        $brand = ShopBrand::findOrFail($id);
        $this->deleteFile($brand->logo);
        $brand->delete();
        return back()->with('success', 'ব্র্যান্ড সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * 6. Attributes & Values (Size, Color, Weight, Capacity)
     */
    public function attributes()
    {
        $attributes = ShopAttribute::with('values')->get();
        return view('admin.ecommerce.attributes.index', compact('attributes'));
    }

    public function attributeStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100']);
        ShopAttribute::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'type' => $request->type ?: 'button',
            'is_active' => true,
        ]);
        return back()->with('success', 'নতুন অ্যাট্রিবিউট সফলভাবে যোগ করা হয়েছে!');
    }

    public function attributeValueStore(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:ecommerce_attributes,id',
            'value' => 'required|string|max:100',
        ]);
        ShopAttributeValue::create([
            'attribute_id' => $request->attribute_id,
            'value' => $request->value,
            'color_code' => $request->color_code,
            'order_num' => (int)$request->order_num,
        ]);
        return back()->with('success', 'অ্যাট্রিবিউট ভ্যালু সফলভাবে যোগ করা হয়েছে!');
    }

    public function attributeDestroy($id)
    {
        ShopAttribute::findOrFail($id)->delete();
        return back()->with('success', 'অ্যাট্রিবিউট সফলভাবে মুছে ফেলা হয়েছে!');
    }

    public function attributeValueDestroy($id)
    {
        ShopAttributeValue::findOrFail($id)->delete();
        return back()->with('success', 'ভ্যালু সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * 7. Product Management (CRUD & Variant Handling)
     */
    public function products(Request $request)
    {
        $query = ShopProduct::with(['category', 'subcategory', 'brand', 'variants']);

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('brand', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($request->stock_status === 'in') {
                $query->where('stock_quantity', '>', 10);
            }
        }

        $products = $query->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $categories = ShopCategory::orderBy('name', 'asc')->get();

        return view('admin.ecommerce.products.index', compact('products', 'categories'));
    }

    public function productCreate()
    {
        $categories = ShopCategory::with('subcategories')->orderBy('name', 'asc')->get();
        $brands = ShopBrand::where('is_active', true)->orderBy('name', 'asc')->get();
        $attributes = ShopAttribute::with('values')->where('is_active', true)->get();

        return view('admin.ecommerce.products.create', compact('categories', 'brands', 'attributes'));
    }

    public function productStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:shop_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $slug = Str::slug($request->name);
        if (ShopProduct::where('slug', $slug)->exists()) {
            $slug .= '-' . rand(100, 999);
        }

        $sku = $request->sku ?: 'SKU-' . strtoupper(Str::random(6));

        $mainImage = null;
        if ($request->hasFile('image')) {
            $mainImage = $this->uploadFile($request->file('image'), 'uploads/product/product-main-images');
        }

        DB::beginTransaction();
        try {
            $product = ShopProduct::create([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'brand_id' => $request->brand_id,
                'brand' => $request->brand_id ? optional(ShopBrand::find($request->brand_id))->name : $request->brand_name,
                'name' => $request->name,
                'name_bn' => $request->name_bn,
                'slug' => $slug,
                'sku' => $sku,
                'price' => (float)$request->price,
                'old_price' => $request->filled('old_price') ? (float)$request->old_price : null,
                'purchase_price' => $request->filled('purchase_price') ? (float)$request->purchase_price : null,
                'delivery_time' => $request->delivery_time ?: 'Delivery 1-2 hours',
                'unit' => $request->unit ?: '1 Pc',
                'weight' => $request->weight,
                'stock_quantity' => (int)($request->stock_quantity ?? 50),
                'min_order_qty' => (int)($request->min_order_qty ?? 1),
                'max_order_qty' => $request->filled('max_order_qty') ? (int)$request->max_order_qty : null,
                'image' => $mainImage,
                'description' => $request->description,
                'has_variants' => $request->has('has_variants'),
                'is_exclusive' => $request->has('is_exclusive'),
                'is_featured' => $request->has('is_featured'),
                'is_new_arrival' => $request->has('is_new_arrival'),
                'is_trending' => $request->has('is_trending'),
                'is_popular' => $request->has('is_popular'),
                'is_best_selling' => $request->has('is_best_selling'),
                'is_daily_offer' => $request->has('is_daily_offer'),
                'is_combo_offer' => $request->has('is_combo_offer'),
                'is_winter_essential' => $request->has('is_winter_essential'),
                'is_monthly_bazar' => $request->has('is_monthly_bazar'),
                'offer_end_date' => $request->offer_end_date,
                'is_active' => $request->has('is_active'),
            ]);

            // Multiple Gallery Images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $idx => $gImg) {
                    $gPath = $this->uploadFile($gImg, 'uploads/product/product-gallery');
                    ShopProductImage::create([
                        'product_id' => $product->id,
                        'image' => $gPath,
                        'order_num' => $idx + 1
                    ]);
                }
            }

            // Dynamic Variants
            if ($request->has('has_variants') && $request->filled('variants')) {
                $variantRows = $request->variants; // array of variants
                foreach ($variantRows as $vRow) {
                    if (!empty($vRow['sku']) || !empty($vRow['price'])) {
                        ShopProductVariant::create([
                            'product_id' => $product->id,
                            'sku' => $vRow['sku'] ?? ($product->sku . '-' . rand(10, 99)),
                            'price' => (float)($vRow['price'] ?? $product->price),
                            'old_price' => !empty($vRow['old_price']) ? (float)$vRow['old_price'] : null,
                            'stock_quantity' => (int)($vRow['stock_quantity'] ?? 20),
                            'attribute_values' => $vRow['attributes'] ?? [],
                            'is_active' => true,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.ecommerce.products')->with('success', 'নতুন পণ্য সফলভাবে তৈরি ও সংরক্ষণ করা হয়েছে!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'পণ্য তৈরি করতে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function productEdit($id)
    {
        $product = ShopProduct::with(['variants', 'images'])->findOrFail($id);
        $categories = ShopCategory::with('subcategories')->orderBy('name', 'asc')->get();
        $brands = ShopBrand::where('is_active', true)->orderBy('name', 'asc')->get();
        $attributes = ShopAttribute::with('values')->where('is_active', true)->get();

        return view('admin.ecommerce.products.edit', compact('product', 'categories', 'brands', 'attributes'));
    }

    public function productUpdate(Request $request, $id)
    {
        $product = ShopProduct::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:shop_categories,id',
        ]);

        $data = [
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'brand_id' => $request->brand_id,
            'brand' => $request->brand_id ? optional(ShopBrand::find($request->brand_id))->name : $request->brand_name,
            'name' => $request->name,
            'name_bn' => $request->name_bn,
            'sku' => $request->sku ?: $product->sku,
            'price' => (float)$request->price,
            'old_price' => $request->filled('old_price') ? (float)$request->old_price : null,
            'purchase_price' => $request->filled('purchase_price') ? (float)$request->purchase_price : null,
            'delivery_time' => $request->delivery_time ?: 'Delivery 1-2 hours',
            'unit' => $request->unit ?: '1 Pc',
            'weight' => $request->weight,
            'stock_quantity' => (int)($request->stock_quantity ?? $product->stock_quantity),
            'min_order_qty' => (int)($request->min_order_qty ?? 1),
            'max_order_qty' => $request->filled('max_order_qty') ? (int)$request->max_order_qty : null,
            'description' => $request->description,
            'has_variants' => $request->has('has_variants'),
            'is_exclusive' => $request->has('is_exclusive'),
            'is_featured' => $request->has('is_featured'),
            'is_new_arrival' => $request->has('is_new_arrival'),
            'is_trending' => $request->has('is_trending'),
            'is_popular' => $request->has('is_popular'),
            'is_best_selling' => $request->has('is_best_selling'),
            'is_daily_offer' => $request->has('is_daily_offer'),
            'is_combo_offer' => $request->has('is_combo_offer'),
            'is_winter_essential' => $request->has('is_winter_essential'),
            'is_monthly_bazar' => $request->has('is_monthly_bazar'),
            'offer_end_date' => $request->offer_end_date,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $this->deleteFile($product->image);
            $data['image'] = $this->uploadFile($request->file('image'), 'uploads/product/product-main-images');
        }

        $product->update($data);

        // Upload additional gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $idx => $gImg) {
                $gPath = $this->uploadFile($gImg, 'uploads/product/product-gallery');
                ShopProductImage::create([
                    'product_id' => $product->id,
                    'image' => $gPath,
                    'order_num' => $idx + 1
                ]);
            }
        }

        return redirect()->route('admin.ecommerce.products')->with('success', 'পণ্য সফলভাবে আপডেট করা হয়েছে!');
    }

    public function productDestroy($id)
    {
        $product = ShopProduct::with(['images', 'variants'])->findOrFail($id);
        $this->deleteFile($product->image);
        foreach ($product->images as $img) {
            $this->deleteFile($img->image);
        }
        $product->delete();
        return back()->with('success', 'পণ্য সফলভাবে মুছে ফেলা হয়েছে!');
    }

    public function productQuickStock(Request $request, $id)
    {
        $product = ShopProduct::findOrFail($id);
        $product->stock_quantity = max(0, (int)$request->stock);
        $product->save();
        return response()->json(['success' => true, 'new_stock' => $product->stock_quantity]);
    }

    /**
     * 8. Inventory & Stock Management
     */
    public function inventory(Request $request)
    {
        $query = ShopProduct::with('category');

        if ($request->filter === 'low') {
            $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
        } elseif ($request->filter === 'out') {
            $query->where('stock_quantity', '<=', 0);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where('name', 'like', "%{$s}%")->orWhere('sku', 'like', "%{$s}%");
        }

        $products = $query->orderBy('stock_quantity', 'asc')->paginate(20)->withQueryString();
        return view('admin.ecommerce.inventory.index', compact('products'));
    }

    /**
     * 9. Combo Offers
     */
    public function comboOffers()
    {
        $combos = ShopComboOffer::orderBy('id', 'desc')->get();
        $products = ShopProduct::where('is_active', true)->select('id', 'name', 'price')->get();
        return view('admin.ecommerce.combo_offers.index', compact('combos', 'products'));
    }

    public function comboOfferStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'regular_price' => 'required|numeric',
            'offer_price' => 'required|numeric',
        ]);

        $imagePath = $request->hasFile('image') ? $this->uploadFile($request->file('image'), 'uploads/combo-offer') : null;

        ShopComboOffer::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . rand(10, 99),
            'image' => $imagePath,
            'description' => $request->description,
            'regular_price' => (float)$request->regular_price,
            'offer_price' => (float)$request->offer_price,
            'stock_quantity' => (int)($request->stock_quantity ?? 50),
            'product_ids' => $request->product_ids ?? [],
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'কম্বো অফার সফলভাবে তৈরি করা হয়েছে!');
    }

    public function comboOfferDestroy($id)
    {
        $combo = ShopComboOffer::findOrFail($id);
        $this->deleteFile($combo->image);
        $combo->delete();
        return back()->with('success', 'কম্বো অফার মুছে ফেলা হয়েছে!');
    }

    /**
     * 10. Coupons & Discounts
     */
    public function coupons()
    {
        $coupons = ShopCoupon::orderBy('id', 'desc')->get();
        return view('admin.ecommerce.coupons.index', compact('coupons'));
    }

    public function couponStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:ecommerce_coupons,code',
            'discount_amount' => 'required|numeric|min:1',
        ]);

        ShopCoupon::create([
            'code' => strtoupper(trim($request->code)),
            'discount_type' => $request->discount_type ?: 'percentage',
            'discount_amount' => (float)$request->discount_amount,
            'min_order_amount' => (float)($request->min_order_amount ?? 0),
            'max_discount' => $request->filled('max_discount') ? (float)$request->max_discount : null,
            'usage_limit' => (int)($request->usage_limit ?? 100),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'কুপন ডিসকাউন্ট সফলভাবে তৈরি করা হয়েছে!');
    }

    public function couponDestroy($id)
    {
        ShopCoupon::findOrFail($id)->delete();
        return back()->with('success', 'কুপন মুছে ফেলা হয়েছে!');
    }

    /**
     * 11. Order Management
     */
    public function orders(Request $request)
    {
        $query = ShopOrder::with(['items.product', 'user']);

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'cancelled' || $request->status === 'canceled') {
                $query->whereIn('order_status', ['cancelled', 'canceled']);
            } elseif ($request->status === 'in_transport') {
                $query->whereIn('order_status', ['in_transport', 'shipping']);
            } elseif ($request->status === 'pending_payment') {
                $query->where('payment_status', 'pending');
            } else {
                $query->where('order_status', $request->status);
            }
        }

        if ($request->filled('platform') && $request->platform !== 'all') {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $query->where(function ($q) use ($s) {
                $q->where('order_number', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%")
                  ->orWhere('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('address', 'like', "%{$s}%")
                  ->orWhere('tracking_code', 'like', "%{$s}%")
                  ->orWhere('courier_name', 'like', "%{$s}%");
            });
        }

        $orders = $query->orderBy('id', 'desc')->paginate(20)->withQueryString();

        $counts = [
            'all' => ShopOrder::count(),
            'pending_entry' => ShopOrder::where('order_status', 'pending')->count(),
            'processing' => ShopOrder::where('order_status', 'processing')->count(),
            'in_transport' => ShopOrder::whereIn('order_status', ['in_transport', 'shipping'])->count(),
            'delivered' => ShopOrder::where('order_status', 'delivered')->count(),
            'pending_payment' => ShopOrder::where('payment_status', 'pending')->count(),
            'hold' => ShopOrder::where('order_status', 'hold')->count(),
            'canceled' => ShopOrder::whereIn('order_status', ['cancelled', 'canceled'])->count(),
        ];

        $products = ShopProduct::where('is_active', true)->select('id', 'name', 'price', 'sku')->get();

        return view('admin.ecommerce.orders.index', compact('orders', 'counts', 'products'));
    }

    public function orderShow($id)
    {
        $order = ShopOrder::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.ecommerce.orders.show', compact('order'));
    }

    public function orderStatusUpdate(Request $request, $id)
    {
        $order = ShopOrder::findOrFail($id);
        $oldStatus = $order->order_status;
        $newStatus = $request->order_status;

        $order->addStatusHistory($newStatus, $request->comment ?: "Status updated from {$oldStatus} to {$newStatus}");

        if ($request->filled('courier_name')) {
            $order->courier_name = $request->courier_name;
        }
        if ($request->filled('tracking_code')) {
            $order->tracking_code = $request->tracking_code;
        }
        $order->save();

        return back()->with('success', "অর্ডার স্ট্যাটাস সফলভাবে '{$newStatus}'-এ পরিবর্তন করা হয়েছে!");
    }

    public function orderPaymentStatusUpdate(Request $request, $id)
    {
        $order = ShopOrder::findOrFail($id);
        $order->payment_status = $request->payment_status;
        if ($request->filled('payment_transaction_id')) {
            $order->payment_transaction_id = $request->payment_transaction_id;
        }
        $order->save();

        return back()->with('success', 'পেমেন্ট স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে!');
    }

    public function orderInvoice($id)
    {
        $order = ShopOrder::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.ecommerce.orders.invoice', compact('order'));
    }

    /**
     * Send order to courier (Steadfast or Pathao)
     */
    public function orderSendCourier($id, Request $request)
    {
        $order = ShopOrder::findOrFail($id);
        $courier = $request->get('courier', 'Steadfast');

        $trackingCode = $request->filled('tracking_code')
            ? $request->tracking_code
            : (($courier === 'Pathao' ? 'PTH-' : 'STDF-') . strtoupper(Str::random(6)));

        $order->courier_name = $courier;
        $order->tracking_code = $trackingCode;
        $order->order_status = 'in_transport';
        $order->addStatusHistory('in_transport', "কুরিয়ার ({$courier}) এ হস্তান্তর করা হয়েছে। ট্র্যাকিং আইডি: {$trackingCode}");
        $order->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "অর্ডারটি সফলভাবে {$courier} এ পাঠানো হয়েছে! ট্র্যাকিং কোড: {$trackingCode}",
                'courier_name' => $courier,
                'tracking_code' => $trackingCode,
                'status' => 'in_transport'
            ]);
        }

        return back()->with('success', "অর্ডারটি সফলভাবে {$courier} এ পাঠানো হয়েছে! ট্র্যাকিং কোড: {$trackingCode}");
    }

    /**
     * Bulk action on orders (Delete, Change Status, Send to Courier)
     */
    public function ordersBulkAction(Request $request)
    {
        $action = $request->action;
        $orderIds = $request->order_ids;

        if (empty($orderIds) || !is_array($orderIds)) {
            return back()->with('error', 'অনুগ্রহ করে অন্তত একটি অর্ডার সিলেক্ট করুন!');
        }

        if ($action === 'delete') {
            ShopOrderItem::whereIn('order_id', $orderIds)->delete();
            ShopOrder::whereIn('id', $orderIds)->delete();
            return back()->with('success', count($orderIds) . ' টি অর্ডার সফলভাবে ডিলিট করা হয়েছে!');
        }

        if ($action === 'status' && $request->filled('bulk_status')) {
            $newStatus = $request->bulk_status;
            foreach ($orderIds as $oid) {
                $order = ShopOrder::find($oid);
                if ($order) {
                    $order->addStatusHistory($newStatus, "বাল্ক অ্যাকশন দ্বারা স্ট্যাটাস পরিবর্তন: {$newStatus}");
                    $order->order_status = $newStatus;
                    $order->save();
                }
            }
            return back()->with('success', count($orderIds) . " টি অর্ডারের স্ট্যাটাস '{$newStatus}'-এ পরিবর্তন করা হয়েছে!");
        }

        if ($action === 'send_steadfast') {
            $count = 0;
            foreach ($orderIds as $oid) {
                $order = ShopOrder::find($oid);
                if ($order) {
                    $order->courier_name = 'Steadfast';
                    if (empty($order->tracking_code)) {
                        $order->tracking_code = 'STDF-' . strtoupper(Str::random(6));
                    }
                    $order->order_status = 'in_transport';
                    $order->addStatusHistory('in_transport', "বাল্ক ডেলিভারি: Steadfast এ হস্তান্তর (ট্র্যাকিং: {$order->tracking_code})");
                    $order->save();
                    $count++;
                }
            }
            return back()->with('success', "{$count} টি অর্ডার সফলভাবে Steadfast Courier এ পাঠানো হয়েছে!");
        }

        if ($action === 'send_pathao') {
            $count = 0;
            foreach ($orderIds as $oid) {
                $order = ShopOrder::find($oid);
                if ($order) {
                    $order->courier_name = 'Pathao';
                    if (empty($order->tracking_code)) {
                        $order->tracking_code = 'PTH-' . strtoupper(Str::random(6));
                    }
                    $order->order_status = 'in_transport';
                    $order->addStatusHistory('in_transport', "বাল্ক ডেলিভারি: Pathao এ হস্তান্তর (ট্র্যাকিং: {$order->tracking_code})");
                    $order->save();
                    $count++;
                }
            }
            return back()->with('success', "{$count} টি অর্ডার সফলভাবে Pathao Courier এ পাঠানো হয়েছে!");
        }

        return back()->with('info', 'কোনো কার্যকর অ্যাকশন নির্বাচন করা হয়নি।');
    }

    /**
     * Delete a single order
     */
    public function orderDestroy($id)
    {
        $order = ShopOrder::findOrFail($id);
        ShopOrderItem::where('order_id', $order->id)->delete();
        $order->delete();
        return back()->with('success', 'অর্ডারটি সফলভাবে ডিলিট করা হয়েছে!');
    }

    /**
     * Create manual order from admin (+ Add Order)
     */
    public function orderCreateManual(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'product_id' => 'required|exists:shop_products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = ShopProduct::findOrFail($request->product_id);
        $qty = (int)$request->quantity;
        $subtotal = $product->price * $qty;
        $shipping = (float)($request->shipping_charge ?? 0);
        $total = $subtotal + $shipping;

        $orderNumber = 'ORD' . rand(44000, 49999);

        $order = ShopOrder::create([
            'order_number' => $orderNumber,
            'first_name' => $request->first_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city ?: 'Dhaka',
            'ip_address' => $request->ip(),
            'platform' => $request->platform ?: 'DIRECT WEB',
            'shipping_method' => $request->shipping_method ?: 'Regular Delivery',
            'shipping_charge' => $shipping,
            'subtotal' => $subtotal,
            'total_amount' => $total,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'order_status' => $request->order_status ?: 'pending',
            'notes' => $request->notes,
            'status_history' => [
                [
                    'status' => $request->order_status ?: 'pending',
                    'comment' => 'অ্যাডমিন প্যানেল থেকে ম্যানুয়াল অর্ডার তৈরি করা হয়েছে।',
                    'time' => now()->toDateTimeString()
                ]
            ]
        ]);

        ShopOrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'quantity' => $qty,
            'total' => $subtotal,
        ]);

        return back()->with('success', "ম্যানুয়াল অর্ডার #{$orderNumber} সফলভাবে তৈরি করা হয়েছে!");
    }

    /**
     * Check Customer Fraud & History by phone
     */
    public function checkCustomer($phone)
    {
        $orders = ShopOrder::where('phone', 'like', "%{$phone}%")->get();
        $total = $orders->count();
        $delivered = $orders->where('order_status', 'delivered')->count();
        $cancelled = $orders->whereIn('order_status', ['cancelled', 'canceled'])->count();
        $lastOrder = $orders->sortByDesc('id')->first();

        $riskLevel = 'Safe';
        $riskColor = 'success';
        if ($cancelled > 2 && $delivered == 0) {
            $riskLevel = 'High Risk (Cancel Rate 100%)';
            $riskColor = 'danger';
        } elseif ($cancelled > $delivered) {
            $riskLevel = 'Moderate Risk';
            $riskColor = 'warning';
        } elseif ($total > 1) {
            $riskLevel = 'Trusted Regular Customer';
            $riskColor = 'success';
        } else {
            $riskLevel = 'New Customer';
            $riskColor = 'info';
        }

        return response()->json([
            'phone' => $phone,
            'total_orders' => $total,
            'delivered_orders' => $delivered,
            'cancelled_orders' => $cancelled,
            'risk_level' => $riskLevel,
            'risk_color' => $riskColor,
            'last_order_date' => $lastOrder ? $lastOrder->created_at->format('d M Y, h:i A') : 'N/A'
        ]);
    }

    /**
     * Courier Steadfast Setup
     */
    public function courierSteadfast()
    {
        $settings = ShopSetting::all()->pluck('value', 'key');
        return view('admin.ecommerce.courier.steadfast', compact('settings'));
    }

    public function courierSteadfastUpdate(Request $request)
    {
        ShopSetting::set('steadfast_api_key', $request->api_key);
        ShopSetting::set('steadfast_secret_key', $request->secret_key);
        ShopSetting::set('steadfast_url', $request->url ?: 'https://portal.packzy.com/api/v1');
        ShopSetting::set('steadfast_status', $request->has('status') ? 'active' : 'inactive');

        return back()->with('success', 'Steadfast Courier সেটিংস সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    /**
     * Courier Pathao Setup
     */
    public function courierPathao()
    {
        $settings = ShopSetting::all()->pluck('value', 'key');
        return view('admin.ecommerce.courier.pathao', compact('settings'));
    }

    public function courierPathaoUpdate(Request $request)
    {
        ShopSetting::set('pathao_client_id', $request->client_id);
        ShopSetting::set('pathao_client_secret', $request->client_secret);
        ShopSetting::set('pathao_username', $request->username);
        ShopSetting::set('pathao_password', $request->password);
        ShopSetting::set('pathao_store_id', $request->store_id);
        ShopSetting::set('pathao_url', $request->url ?: 'https://api-hermes.pathao.com');
        ShopSetting::set('pathao_status', $request->has('status') ? 'active' : 'inactive');

        return back()->with('success', 'Pathao Courier সেটিংস সফলভাবে সংরক্ষণ করা হয়েছে!');
    }

    /**
     * 12. Customers
     */
    public function customers(Request $request)
    {
        $customers = User::withCount('shopOrders')
            ->withSum('shopOrders', 'total_amount')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('admin.ecommerce.customers.index', compact('customers'));
    }

    /**
     * 13. Shipping Management
     */
    public function shipping()
    {
        $zones = ShopShippingZone::orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.shipping.index', compact('zones'));
    }

    public function shippingStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:150', 'charge' => 'required|numeric|min:0']);
        ShopShippingZone::create([
            'name' => $request->name,
            'charge' => (float)$request->charge,
            'estimated_time' => $request->estimated_time ?: '১-২ কার্যদিবস',
            'min_order_amount' => $request->filled('min_order_amount') ? (float)$request->min_order_amount : null,
            'free_shipping_min_amount' => $request->filled('free_shipping_min_amount') ? (float)$request->free_shipping_min_amount : null,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'শিপিং জোন সফলভাবে তৈরি করা হয়েছে!');
    }

    public function shippingUpdate(Request $request, $id)
    {
        $zone = ShopShippingZone::findOrFail($id);
        $zone->update([
            'name' => $request->name,
            'charge' => (float)$request->charge,
            'estimated_time' => $request->estimated_time ?: '১-২ কার্যদিবস',
            'min_order_amount' => $request->filled('min_order_amount') ? (float)$request->min_order_amount : null,
            'free_shipping_min_amount' => $request->filled('free_shipping_min_amount') ? (float)$request->free_shipping_min_amount : null,
            'order_num' => (int)$request->order_num,
            'is_active' => $request->has('is_active'),
        ]);
        return back()->with('success', 'শিপিং জোন সফলভাবে আপডেট করা হয়েছে!');
    }

    public function shippingDestroy($id)
    {
        ShopShippingZone::findOrFail($id)->delete();
        return back()->with('success', 'শিপিং জোন মুছে ফেলা হয়েছে!');
    }

    /**
     * 14. Payment Methods Management
     */
    public function payments()
    {
        $payments = ShopPaymentMethod::orderBy('order_num', 'asc')->get();
        return view('admin.ecommerce.payments.index', compact('payments'));
    }

    public function paymentUpdate(Request $request, $id)
    {
        $payment = ShopPaymentMethod::findOrFail($id);
        $data = [
            'name' => $request->name,
            'merchant_number' => $request->merchant_number,
            'instructions' => $request->instructions,
            'is_active' => $request->has('is_active'),
            'api_key' => $request->api_key,
            'secret_key' => $request->secret_key,
        ];

        if ($request->hasFile('logo')) {
            $this->deleteFile($payment->logo);
            $data['logo'] = $this->uploadFile($request->file('logo'), 'uploads/payment');
        }

        $payment->update($data);
        return back()->with('success', 'পেমেন্ট মেথড কনফিগারেশন আপডেট করা হয়েছে!');
    }

    /**
     * 15. Reports & Analytics
     */
    public function reports(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $orders = ShopOrder::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('id', 'desc')
            ->get();

        $totalSales = $orders->whereNotIn('order_status', ['cancelled'])->sum('total_amount');
        $totalOrders = $orders->count();
        $completedOrders = $orders->where('order_status', 'completed')->count();
        $totalDiscounts = $orders->sum('discount_amount');
        $shippingRevenue = $orders->sum('shipping_charge');

        return view('admin.ecommerce.reports.index', compact('orders', 'startDate', 'endDate', 'totalSales', 'totalOrders', 'completedOrders', 'totalDiscounts', 'shippingRevenue'));
    }

    /**
     * 16. E-commerce General Settings
     */
    public function settings()
    {
        $settings = ShopSetting::all()->pluck('value', 'key');
        return view('admin.ecommerce.settings.index', compact('settings'));
    }

    public function settingsUpdate(Request $request)
    {
        foreach ($request->except('_token') as $key => $val) {
            ShopSetting::set($key, $val);
        }
        return back()->with('success', 'ই-কমার্স সেটিংস সফলভাবে আপডেট করা হয়েছে!');
    }

    /**
     * Helper to handle file uploads into public/uploads/*
     */
    protected function uploadFile($file, $folder)
    {
        if (!$file) return null;
        $folderPath = public_path($folder);
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($folderPath, $filename);
        return $folder . '/' . $filename;
    }

    /**
     * Helper to delete an old uploaded file safely
     */
    protected function deleteFile($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

