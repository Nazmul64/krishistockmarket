<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories
        if (!Schema::hasTable('shop_categories')) {
            Schema::create('shop_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('slug')->unique();
                $table->string('icon')->default('fa-shopping-basket');
                $table->string('image')->nullable();
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Subcategories
        if (!Schema::hasTable('shop_subcategories')) {
            Schema::create('shop_subcategories', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('slug');
                $table->integer('order_num')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('shop_categories')->onDelete('cascade');
            });
        }

        // 3. Products
        if (!Schema::hasTable('shop_products')) {
            Schema::create('shop_products', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id')->nullable();
                $table->unsignedBigInteger('subcategory_id')->nullable();
                $table->string('name');
                $table->string('name_bn')->nullable();
                $table->string('slug')->unique();
                $table->string('sku')->nullable();
                $table->string('brand')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('old_price', 10, 2)->nullable();
                $table->string('delivery_time')->default('Delivery 1-2 hours');
                $table->string('unit')->default('1 Pc');
                $table->integer('stock_quantity')->default(100);
                $table->string('image')->nullable();
                $table->text('gallery')->nullable();
                $table->longText('description')->nullable();
                $table->boolean('is_exclusive')->default(false);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->foreign('category_id')->references('id')->on('shop_categories')->onDelete('set null');
                $table->foreign('subcategory_id')->references('id')->on('shop_subcategories')->onDelete('set null');
            });
        }

        // 4. Orders
        if (!Schema::hasTable('shop_orders')) {
            Schema::create('shop_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('first_name');
                $table->string('last_name')->nullable();
                $table->string('phone');
                $table->string('email')->nullable();
                $table->text('address');
                $table->string('city')->default('Dhaka');
                $table->string('shipping_method')->default('dhaka_free'); // dhaka_free, inside_dhaka, outside_dhaka
                $table->decimal('shipping_charge', 10, 2)->default(0);
                $table->decimal('subtotal', 10, 2);
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_method')->default('cod'); // cod, bkash, nagad, wallet
                $table->string('payment_status')->default('pending');
                $table->string('order_status')->default('processing');
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        // 5. Order Items
        if (!Schema::hasTable('shop_order_items')) {
            Schema::create('shop_order_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('product_id')->nullable();
                $table->string('product_name');
                $table->decimal('price', 10, 2);
                $table->integer('quantity')->default(1);
                $table->decimal('total', 10, 2);
                $table->timestamps();

                $table->foreign('order_id')->references('id')->on('shop_orders')->onDelete('cascade');
                $table->foreign('product_id')->references('id')->on('shop_products')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_order_items');
        Schema::dropIfExists('shop_orders');
        Schema::dropIfExists('shop_products');
        Schema::dropIfExists('shop_subcategories');
        Schema::dropIfExists('shop_categories');
    }
};

