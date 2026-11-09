<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Property Types
        if (!Schema::hasTable('property_types')) {
            Schema::create('property_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('category')->default('land'); // land, building, flat, commercial
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Real Estate Properties
        if (!Schema::hasTable('properties')) {
            Schema::create('properties', function (Blueprint $table) {
                $table->id();
                $table->string('property_name');
                $table->string('property_code')->unique();
                $table->unsignedBigInteger('property_type_id')->nullable();
                $table->string('property_type_name')->default('জমি / প্লট'); // জমি, প্লট, ফ্ল্যাট, বাণিজ্যিক ভবন, অফিস স্পেস

                // Land Record & Administrative Identifications (Bangladesh Context)
                $table->string('division')->default('ঢাকা');
                $table->string('district')->default('ঢাকা');
                $table->string('upazila')->default('উত্তরা');
                $table->string('mouza')->nullable();
                $table->string('village')->nullable();
                $table->string('dag_number')->nullable();
                $table->string('khatian_number')->nullable();
                $table->string('jl_number')->nullable();

                // Dimensions & Measurements
                $table->decimal('total_area', 14, 2)->default(0);
                $table->string('measurement_unit')->default('Decimal'); // Decimal, Katha, Bigha, SqFt, Shotok

                // Pricing & Financials (BDT)
                $table->decimal('purchase_price', 16, 2)->default(0);
                $table->decimal('selling_price', 16, 2)->default(0);
                $table->decimal('booking_amount', 16, 2)->default(0);
                $table->decimal('current_valuation', 16, 2)->default(0);

                // Media & Description
                $table->string('featured_image')->nullable();
                $table->text('description')->nullable();
                $table->text('features')->nullable(); // JSON or comma-separated amenities (রাস্তা, বিদ্যুৎ, পানি, গ্যাস, সীমানা প্রাচীর ইত্যাদি)
                $table->string('owner_name')->nullable();
                $table->string('owner_phone')->nullable();

                // Status & Visibility
                $table->string('status')->default('available'); // available, reserved, sold, draft, pending_verification
                $table->boolean('is_featured')->default(false);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        // 3. Property Units (Flats, Floors, Shops)
        if (!Schema::hasTable('property_units')) {
            Schema::create('property_units', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('property_id')->nullable();
                $table->string('unit_number'); // Flat 4B, Shop 102
                $table->string('floor_number')->nullable();
                $table->integer('bedroom')->default(0);
                $table->integer('bathroom')->default(0);
                $table->integer('balcony')->default(0);
                $table->boolean('has_parking')->default(false);
                $table->decimal('total_area_sqft', 10, 2)->default(0);
                $table->decimal('selling_price', 16, 2)->default(0);
                $table->decimal('booking_amount', 16, 2)->default(0);
                $table->string('status')->default('available'); // available, booked, sold
                $table->timestamps();
            });
        }

        // 4. Real Estate Projects
        if (!Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('project_type')->default('residential_building');
                $table->string('location')->nullable();
                $table->decimal('total_budget', 16, 2)->default(0);
                $table->decimal('investment_target', 16, 2)->default(0);
                $table->decimal('investment_received', 16, 2)->default(0);
                $table->date('start_date')->nullable();
                $table->date('expected_completion_date')->nullable();
                $table->integer('progress_percentage')->default(0);
                $table->string('status')->default('active'); // active, planning, completed
                $table->string('featured_image')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        // 5. Customer Inquiries & Bookings (Direct from Frontend)
        if (!Schema::hasTable('property_inquiries')) {
            Schema::create('property_inquiries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('property_id')->nullable();
                $table->string('inquiry_code')->unique();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->string('customer_email')->nullable();
                $table->string('inquiry_type')->default('site_visit'); // site_visit, booking_request, price_inquiry, invest_inquiry
                $table->date('preferred_date')->nullable();
                $table->text('message')->nullable();
                $table->string('status')->default('pending'); // pending, contacted, scheduled, confirmed, cancelled
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('property_inquiries');
        Schema::dropIfExists('property_units');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('property_types');
        Schema::dropIfExists('projects');
    }
};
