<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Brands
        if (!Schema::hasTable('ecommerce_brands')) {
            Schema::create('ecommerce_brands', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('logo')->nullable();
                $table->text('description')->nullable();
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Sliders
        if (!Schema::hasTable('ecommerce_sliders')) {
            Schema::create('ecommerce_sliders', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->text('description')->nullable();
                $table->string('image');
                $table->string('mobile_image')->nullable();
                $table->string('button_text')->default('এখনই অর্ডার করুন →');
                $table->string('button_link')->default('#');
                $table->string('hotline')->default('09617900200');
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 3. Banners
        if (!Schema::hasTable('ecommerce_banners')) {
            Schema::create('ecommerce_banners', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('image');
                $table->string('link')->default('#');
                $table->string('position')->default('homepage_middle'); // top, middle, sidebar, bottom
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Attributes (Size, Color, Weight, Capacity, etc.)
        if (!Schema::hasTable('ecommerce_attributes')) {
            Schema::create('ecommerce_attributes', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Size, Color, Weight, Capacity
                $table->string('slug')->unique();
                $table->string('type')->default('select'); // select, color, button
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 5. Attribute Values (S, M, L, XL; Red, Blue; 1kg, 5kg; 500ml, 1L)
        if (!Schema::hasTable('ecommerce_attribute_values')) {
            Schema::create('ecommerce_attribute_values', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('attribute_id');
                $table->string('value'); // e.g. "XL", "Red", "1kg", "500ml"
                $table->string('color_code')->nullable(); // e.g. "#ff0000"
                $table->integer('order_num')->default(0);
                $table->timestamps();

                $table->foreign('attribute_id')->references('id')->on('ecommerce_attributes')->onDelete('cascade');
            });
        }

        // 6. Product Variants (Variant-wise SKU, price, stock, image, attributes JSON)
        if (!Schema::hasTable('ecommerce_product_variants')) {
            Schema::create('ecommerce_product_variants', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('sku')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('old_price', 10, 2)->nullable();
                $table->integer('stock_quantity')->default(50);
                $table->string('image')->nullable();
                $table->json('attribute_values')->nullable(); // e.g. {"Size":"XL", "Color":"Red"}
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
            });
        }

        // 7. Product Gallery Images
        if (!Schema::hasTable('ecommerce_product_images')) {
            Schema::create('ecommerce_product_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('image');
                $table->integer('order_num')->default(0);
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
            });
        }

        // 8. Shipping Zones (Inside Dhaka, Outside Dhaka, Sub-urban, etc.)
        if (!Schema::hasTable('ecommerce_shipping_zones')) {
            Schema::create('ecommerce_shipping_zones', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. "Inside Dhaka", "Outside Dhaka"
                $table->decimal('charge', 10, 2)->default(60);
                $table->string('estimated_time')->default('1-2 Business Days');
                $table->decimal('min_order_amount', 10, 2)->nullable();
                $table->decimal('free_shipping_min_amount', 10, 2)->nullable();
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 9. Payment Methods (COD, bKash, Nagad, Rocket, Bank Transfer)
        if (!Schema::hasTable('ecommerce_payment_methods')) {
            Schema::create('ecommerce_payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. "Cash on Delivery", "bKash (বিকাশ)"
                $table->string('code')->unique(); // cod, bkash, nagad, rocket, bank
                $table->string('merchant_number')->nullable();
                $table->text('instructions')->nullable();
                $table->string('logo')->nullable();
                $table->boolean('is_manual')->default(true); // manual vs API gateway
                $table->string('api_key')->nullable();
                $table->string('secret_key')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('order_num')->default(0);
                $table->timestamps();
            });
        }

        // 10. Coupons & Discounts
        if (!Schema::hasTable('ecommerce_coupons')) {
            Schema::create('ecommerce_coupons', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('discount_type')->default('percentage'); // percentage, fixed
                $table->decimal('discount_amount', 10, 2);
                $table->decimal('min_order_amount', 10, 2)->default(0);
                $table->decimal('max_discount', 10, 2)->nullable();
                $table->integer('usage_limit')->default(100);
                $table->integer('used_count')->default(0);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 11. Combo Offers
        if (!Schema::hasTable('ecommerce_combo_offers')) {
            Schema::create('ecommerce_combo_offers', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('image')->nullable();
                $table->text('description')->nullable();
                $table->decimal('regular_price', 10, 2);
                $table->decimal('offer_price', 10, 2);
                $table->integer('stock_quantity')->default(50);
                $table->json('product_ids')->nullable(); // array of product IDs and quantities
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 12. Product Reviews
        if (!Schema::hasTable('ecommerce_product_reviews')) {
            Schema::create('ecommerce_product_reviews', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('customer_name');
                $table->tinyInteger('rating')->default(5);
                $table->text('review')->nullable();
                $table->string('image')->nullable();
                $table->boolean('is_approved')->default(true);
                $table->timestamps();

                $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 13. E-commerce General Settings
        if (!Schema::hasTable('ecommerce_settings')) {
            Schema::create('ecommerce_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->longText('value')->nullable();
                $table->string('group')->default('general');
                $table->timestamps();
            });
        }

        // 14. Expand shop_products columns
        Schema::table('shop_products', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_products', 'brand_id')) {
                $table->unsignedBigInteger('brand_id')->nullable()->after('subcategory_id');
            }
            if (!Schema::hasColumn('shop_products', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('shop_products', 'min_order_qty')) {
                $table->integer('min_order_qty')->default(1)->after('stock_quantity');
            }
            if (!Schema::hasColumn('shop_products', 'max_order_qty')) {
                $table->integer('max_order_qty')->nullable()->after('min_order_qty');
            }
            if (!Schema::hasColumn('shop_products', 'weight')) {
                $table->string('weight')->nullable()->after('unit');
            }
            if (!Schema::hasColumn('shop_products', 'has_variants')) {
                $table->boolean('has_variants')->default(false)->after('weight');
            }
            if (!Schema::hasColumn('shop_products', 'is_new_arrival')) {
                $table->boolean('is_new_arrival')->default(true)->after('is_exclusive');
            }
            if (!Schema::hasColumn('shop_products', 'is_trending')) {
                $table->boolean('is_trending')->default(false)->after('is_new_arrival');
            }
            if (!Schema::hasColumn('shop_products', 'is_popular')) {
                $table->boolean('is_popular')->default(false)->after('is_trending');
            }
            if (!Schema::hasColumn('shop_products', 'is_best_selling')) {
                $table->boolean('is_best_selling')->default(false)->after('is_popular');
            }
            if (!Schema::hasColumn('shop_products', 'is_daily_offer')) {
                $table->boolean('is_daily_offer')->default(false)->after('is_best_selling');
            }
            if (!Schema::hasColumn('shop_products', 'is_combo_offer')) {
                $table->boolean('is_combo_offer')->default(false)->after('is_daily_offer');
            }
            if (!Schema::hasColumn('shop_products', 'is_winter_essential')) {
                $table->boolean('is_winter_essential')->default(false)->after('is_combo_offer');
            }
            if (!Schema::hasColumn('shop_products', 'is_monthly_bazar')) {
                $table->boolean('is_monthly_bazar')->default(false)->after('is_winter_essential');
            }
        });

        // 15. Expand shop_orders columns
        Schema::table('shop_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_orders', 'shipping_zone_id')) {
                $table->unsignedBigInteger('shipping_zone_id')->nullable()->after('city');
            }
            if (!Schema::hasColumn('shop_orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('subtotal');
            }
            if (!Schema::hasColumn('shop_orders', 'coupon_code')) {
                $table->string('coupon_code')->nullable()->after('discount_amount');
            }
            if (!Schema::hasColumn('shop_orders', 'payment_transaction_id')) {
                $table->string('payment_transaction_id')->nullable()->after('payment_method');
            }
            if (!Schema::hasColumn('shop_orders', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('order_status');
            }
            if (!Schema::hasColumn('shop_orders', 'tracking_code')) {
                $table->string('tracking_code')->nullable()->after('courier_name');
            }
            if (!Schema::hasColumn('shop_orders', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('shop_orders', 'status_history')) {
                $table->json('status_history')->nullable()->after('admin_notes');
            }
        });

        // 16. Expand shop_order_items columns
        Schema::table('shop_order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('shop_order_items', 'sku')) {
                $table->string('sku')->nullable()->after('product_name');
            }
            if (!Schema::hasColumn('shop_order_items', 'variant_details')) {
                $table->text('variant_details')->nullable()->after('sku');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce_product_reviews');
        Schema::dropIfExists('ecommerce_combo_offers');
        Schema::dropIfExists('ecommerce_coupons');
        Schema::dropIfExists('ecommerce_payment_methods');
        Schema::dropIfExists('ecommerce_shipping_zones');
        Schema::dropIfExists('ecommerce_product_images');
        Schema::dropIfExists('ecommerce_product_variants');
        Schema::dropIfExists('ecommerce_attribute_values');
        Schema::dropIfExists('ecommerce_attributes');
        Schema::dropIfExists('ecommerce_banners');
        Schema::dropIfExists('ecommerce_sliders');
        Schema::dropIfExists('ecommerce_brands');
        Schema::dropIfExists('ecommerce_settings');
    }
};

