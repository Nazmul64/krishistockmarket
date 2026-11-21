<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Design Templates (10 distinct UI templates)
        if (!Schema::hasTable('design_templates')) {
            Schema::create('design_templates', function (Blueprint $table) {
                $table->id();
                $table->string('slug')->unique();
                $table->string('name_en');
                $table->string('name_bn');
                $table->string('category')->default('General');
                $table->string('preview_thumbnail')->nullable();
                $table->text('description_en')->nullable();
                $table->text('description_bn')->nullable();
                $table->json('default_config')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. UI Configurations (Draft, Published & Centralized Design State)
        if (!Schema::hasTable('ui_configurations')) {
            Schema::create('ui_configurations', function (Blueprint $table) {
                $table->id();
                $table->string('version_code')->default('v1.0.0');
                $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
                $table->string('active_template_slug')->default('template_01_modern_agriculture');
                $table->json('global_colors')->nullable();
                $table->json('typography')->nullable();
                $table->json('header_config')->nullable();
                $table->json('footer_config')->nullable();
                $table->json('dark_mode_config')->nullable();
                $table->json('mobile_app_config')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamp('published_at')->nullable();
                $table->timestamps();
            });
        }

        // 3. Navigation Menus
        if (!Schema::hasTable('navigation_menus')) {
            Schema::create('navigation_menus', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique(); // header_menu, footer_quick_links, app_bottom_nav, app_drawer
                $table->string('title');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 4. Navigation Menu Items
        if (!Schema::hasTable('navigation_menu_items')) {
            Schema::create('navigation_menu_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('menu_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('title_bn');
                $table->string('title_en');
                $table->string('url')->nullable();
                $table->string('route_name')->nullable();
                $table->string('icon')->nullable();
                $table->string('badge_text')->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('show_on_web')->default(true);
                $table->boolean('show_on_app')->default(true);
                $table->boolean('is_active')->default(true);
                $table->string('target')->default('_self');
                $table->timestamps();

                $table->foreign('menu_id')->references('id')->on('navigation_menus')->onDelete('cascade');
                $table->foreign('parent_id')->references('id')->on('navigation_menu_items')->onDelete('cascade');
            });
        }

        // 5. Homepage Sections
        if (!Schema::hasTable('homepage_sections')) {
            Schema::create('homepage_sections', function (Blueprint $table) {
                $table->id();
                $table->string('section_key')->unique();
                $table->string('title_bn');
                $table->string('title_en');
                $table->string('subtitle_bn')->nullable();
                $table->string('subtitle_en')->nullable();
                $table->string('component_name');
                $table->integer('sort_order_web')->default(0);
                $table->integer('sort_order_app')->default(0);
                $table->boolean('is_enabled_web')->default(true);
                $table->boolean('is_enabled_app')->default(true);
                $table->json('section_settings')->nullable();
                $table->timestamps();
            });
        }

        // 6. Theme Version History & 1-Click Rollback
        if (!Schema::hasTable('theme_versions')) {
            Schema::create('theme_versions', function (Blueprint $table) {
                $table->id();
                $table->string('version_number')->unique();
                $table->string('template_slug');
                $table->json('full_config_snapshot');
                $table->string('note')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }

        // 7. Theme Change Logs
        if (!Schema::hasTable('theme_change_logs')) {
            Schema::create('theme_change_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('action');
                $table->string('module')->nullable();
                $table->json('diff_payload')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_change_logs');
        Schema::dropIfExists('theme_versions');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('navigation_menu_items');
        Schema::dropIfExists('navigation_menus');
        Schema::dropIfExists('ui_configurations');
        Schema::dropIfExists('design_templates');
    }
};
