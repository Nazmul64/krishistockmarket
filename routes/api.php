<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Centralized UI Customization System API (Web + Mobile App Sync)
Route::prefix('v1/ui')->group(function () {
    Route::get('/config', [\App\Http\Controllers\Api\UiConfigApiController::class, 'getFullConfig'])->name('api.ui.config');
    Route::get('/templates', [\App\Http\Controllers\Api\UiConfigApiController::class, 'getTemplates'])->name('api.ui.templates');
    Route::get('/home-sections', [\App\Http\Controllers\Api\UiConfigApiController::class, 'getHomeSections'])->name('api.ui.home_sections');
    Route::get('/component-settings', [\App\Http\Controllers\Admin\UiComponentSettingController::class, 'getApiConfig'])->name('api.ui.component_settings');
    Route::post('/component-settings', [\App\Http\Controllers\Admin\UiComponentSettingController::class, 'updateApiConfig'])->name('api.ui.component_settings.update');
    Route::get('/preloader-types', function () {
        return response()->json([
            'success' => true,
            'types' => [
                ['id' => 'cinematic_orbital', 'name' => 'Cinematic Orbital Ring', 'name_bn' => 'অরবিটাল গ্লোয়িং রিং'],
                ['id' => 'spinner_circular', 'name' => 'Circular Dual Spinner', 'name_bn' => 'গোল সার্কুলার স্পিনার'],
                ['id' => 'pulse_logo', 'name' => 'Pulse Logo Wave', 'name_bn' => 'পালস লোগো রিপল'],
                ['id' => 'leaf_agriculture', 'name' => 'Eco Leaf & Growth', 'name_bn' => 'কৃষি পাতা ও গ্রোথ লোডার'],
                ['id' => 'progress_bar', 'name' => 'Minimal Top Bar', 'name_bn' => 'মিনিমাল টপ প্রোগ্রেস লাইন'],
            ],
        ]);
    })->name('api.ui.preloader_types');
});

// Mobile App REST API Endpoints
Route::get('/home-data', [\App\Http\Controllers\FrontendController::class, 'apiHomepageData'])->name('api.mobile.home_data');
Route::get('/live-search', [\App\Http\Controllers\FrontendController::class, 'apiLiveSearch'])->name('api.mobile.live_search');
Route::get('/about', [\App\Http\Controllers\FrontendController::class, 'apiAbout'])->name('api.mobile.about');
Route::post('/contact/send', [\App\Http\Controllers\ContactController::class, 'store'])->name('api.mobile.contact_send');
Route::get('/career/info', [\App\Http\Controllers\CareerController::class, 'apiCareerInfo'])->name('api.mobile.career_info');
Route::post('/career/apply', [\App\Http\Controllers\CareerController::class, 'apiApply'])->name('api.mobile.career_apply');
Route::get('/our-videos', [\App\Http\Controllers\FrontendController::class, 'apiOurVideos'])->name('api.mobile.our_videos');

// Land, Plots & Properties Real Estate Showcase API
Route::get('/properties', [\App\Http\Controllers\PropertyFrontendController::class, 'apiIndex'])->name('api.mobile.properties.index');
Route::get('/properties/{id}', [\App\Http\Controllers\PropertyFrontendController::class, 'apiShow'])->name('api.mobile.properties.show');
Route::post('/properties/inquiry', [\App\Http\Controllers\PropertyFrontendController::class, 'apiSubmitInquiry'])->name('api.mobile.properties.inquiry');

// E-Commerce Superstore Mobile App REST API Routes
Route::prefix('')->group(function () {
    Route::get('/shop/home', [\App\Http\Controllers\Api\EcommerceApiController::class, 'home'])->name('api.shop.home');
    Route::get('/banners', [\App\Http\Controllers\Api\EcommerceApiController::class, 'banners'])->name('api.shop.banners');
    Route::get('/categories', [\App\Http\Controllers\Api\EcommerceApiController::class, 'categories'])->name('api.shop.categories');
    Route::get('/products', [\App\Http\Controllers\Api\EcommerceApiController::class, 'products'])->name('api.shop.products');
    Route::get('/products/{id}', [\App\Http\Controllers\Api\EcommerceApiController::class, 'productDetail'])->name('api.shop.product_detail');
    Route::get('/products/{id}/related', [\App\Http\Controllers\Api\EcommerceApiController::class, 'relatedProducts'])->name('api.shop.related_products');
    Route::get('/shipping-methods', [\App\Http\Controllers\Api\EcommerceApiController::class, 'shippingMethods'])->name('api.shop.shipping_methods');
    Route::get('/payment-methods', [\App\Http\Controllers\Api\EcommerceApiController::class, 'paymentMethods'])->name('api.shop.payment_methods');
    Route::post('/coupon/apply', [\App\Http\Controllers\Api\EcommerceApiController::class, 'applyCoupon'])->name('api.shop.apply_coupon');
    Route::post('/checkout', [\App\Http\Controllers\Api\EcommerceApiController::class, 'checkout'])->name('api.shop.checkout');
    Route::get('/orders/track', [\App\Http\Controllers\Api\EcommerceApiController::class, 'trackOrder'])->name('api.shop.orders.track');
    Route::get('/orders/{id}', [\App\Http\Controllers\Api\EcommerceApiController::class, 'trackOrder'])->name('api.shop.orders.detail');

    // Live Chat Synchronized API
    Route::get('/chat/messages', [\App\Http\Controllers\Api\EcommerceApiController::class, 'getChatMessages'])->name('api.chat.messages');
    Route::post('/chat/send', [\App\Http\Controllers\Api\EcommerceApiController::class, 'sendChatMessage'])->name('api.chat.send');

    // Biometric Fingerprint Machine Push & Cloud Webhook API (Section 8.2)
    Route::post('/attendance/device-push', [\App\Http\Controllers\Admin\SuperShop\Employee\BranchBiometricDeviceController::class, 'pushWebhook'])->name('api.attendance.device_push');
    Route::post('/attendance/device-sync', [\App\Http\Controllers\Admin\SuperShop\Employee\BranchBiometricDeviceController::class, 'pushWebhook'])->name('api.attendance.device_sync');
});

// Customer Member Portal & Authentication REST API
Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'login'])->name('api.auth.login');
    Route::post('/logout', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'logout'])->name('api.auth.logout');
    Route::post('/forgot-password', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'forgotPassword'])->name('api.auth.forgot_password');
    Route::post('/reset-password', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'resetPassword'])->name('api.auth.reset_password');
});

// Top-level aliases for direct mobile & web client compatibility
Route::post('/login', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'login'])->name('api.direct.login');
Route::post('/logout', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'logout'])->name('api.direct.logout');
Route::post('/forgot-password', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'forgotPassword'])->name('api.direct.forgot_password');
Route::post('/reset-password', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'resetPassword'])->name('api.direct.reset_password');

Route::prefix('customer')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'dashboard'])->name('api.customer.dashboard');
    Route::get('/deposit-methods', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'depositMethods'])->name('api.customer.deposit_methods');
    Route::post('/deposit-submit', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'submitDeposit'])->name('api.customer.deposit_submit');
    Route::post('/withdraw-submit', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'submitWithdraw'])->name('api.customer.withdraw_submit');
    Route::get('/wallet-ledger', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'walletLedger'])->name('api.customer.wallet_ledger');
    Route::get('/my-stocks', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'myStocks'])->name('api.customer.my_stocks');
    Route::get('/my-orders', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'myOrders'])->name('api.customer.my_orders');
    Route::get('/card-info', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'cardInfo'])->name('api.customer.card_info');
    Route::post('/card-apply', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'submitCardApplication'])->name('api.customer.card_apply');
    Route::get('/corporate-data', [\App\Http\Controllers\Api\CustomerPortalApiController::class, 'corporateData'])->name('api.customer.corporate_data');
});




