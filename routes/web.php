<?php

use App\Http\Controllers\Admin\AdminCardNumberController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminMonthlyBazaarController;
use App\Http\Controllers\Admin\AdminPaymentSystemController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminStockPresetController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Users\UserCartController;
use App\Http\Controllers\Users\UserMonthlyBazaarController;
use App\Http\Controllers\Users\UserPaymentSystemController;
use App\Http\Controllers\Users\UserStockController;
use App\Http\Controllers\Users\WithdrawController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('frontend.index');
})->name('/');

Route::get('/about', function () {
    return view('frontend.about-us');
})->name('about');

Route::get('/gallery', function () {
    return view('frontend.gallery');
})->name('gallery');

Route::get('/contact', function () {
    return view('frontend.contact');
})->name('contact');

Route::get('/terms', function () {
    return view('frontend.terms');
})->name('terms');

Route::get('/privacy-policy', function () {
    return view('frontend.privacy');
})->name('privacy');







Auth::routes();
Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('register.referlink');
Route::post('/user/register', [RegisterController::class, 'registerPost'])->name('register.post');




// Common Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::resource('profile', 'App\Http\Controllers\ProfileController');
Route::post('/profile/change', [ProfileController::class, 'changePasswordPost'])->name('profile.password.change');


// Admin Route
Route::group(['prefix' => 'admin', 'middleware' => 'AdminCheck'], function () {
    Route::get('/users', [AdminUserController::class, 'alluser'])->name('alluser');
    Route::get('/user/destroy/{id}', [AdminEmployeeController::class, 'destroy'])->name('admin.user.destroy');

    // 12-Digit Number Generator Routes
    Route::get('/card-numbers', [AdminCardNumberController::class, 'index'])->name('admin.card_numbers.index');
    Route::post('/card-numbers/store', [AdminCardNumberController::class, 'store'])->name('admin.card_numbers.store');
    Route::post('/card-numbers/update/{id}', [AdminCardNumberController::class, 'update'])->name('admin.card_numbers.update');
    Route::get('/card-numbers/destroy/{id}', [AdminCardNumberController::class, 'destroy'])->name('admin.card_numbers.destroy');

    Route::get('/create-employee/', [AdminEmployeeController::class, 'index'])->name('admin.employee.index');
    Route::post('/create-employee/post', [AdminEmployeeController::class, 'store'])->name('admin.employee.post');
    Route::post('/create-employee/destroy/{id}', [AdminEmployeeController::class, 'destroy'])->name('admin.employee.destroy');
    Route::get('/employee/edit/{id}', [AdminEmployeeController::class, 'Edit'])->name('admin.employee.edit');
    Route::post('/employee/edit/', [AdminEmployeeController::class, 'EditPost'])->name('admin.employee.edit.post');
    Route::get('/employee/view/{id}', [AdminEmployeeController::class, 'ViewEmployee'])->name('admin.employee.view');
    Route::get('/employee/referal/view/{id}', [AdminEmployeeController::class, 'ViewReferalUser'])->name('admin.employee.referaluser');



    Route::get('/setting', [SiteSettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/update', [SiteSettingController::class, 'update'])->name('setting.update');
    Route::get('/setting/slider', [SiteSettingController::class, 'SliderSetting'])->name('setting.slider');
    Route::post('/setting/slider/post', [SiteSettingController::class, 'SliderSettingPost'])->name('setting.slider.post');

    Route::get('/features', [AdminFeatureController::class, 'index'])->name('admin.feature.index');
    Route::post('/features/store', [AdminFeatureController::class, 'store'])->name('admin.feature.store');
    Route::get('/features/edit/{id}', [AdminFeatureController::class, 'edit'])->name('admin.feature.edit');
    Route::post('/features/update/{id}', [AdminFeatureController::class, 'update'])->name('admin.feature.update');
    Route::get('/features/delete/{id}', [AdminFeatureController::class, 'destroy'])->name('admin.feature.delete');

    Route::get('/payment-system', [AdminPaymentSystemController::class, 'index'])->name('admin.payment.index');
    Route::post('/payment-system/post', [AdminPaymentSystemController::class, 'store'])->name('admin.payment.post');
    Route::get('/payment-system/destroy/{id}', [AdminPaymentSystemController::class, 'destroy'])->name('admin.payment.destroy');




    Route::get('/all/withdraw', [AdminWithdrawController::class, 'index'])->name('admin.all.withdraw');
    Route::get('/all/withdraw/request', [AdminWithdrawController::class, 'WithdrawRequset'])->name('admin.all.withdraw.request');
    Route::get('/withdraw/aprove/{id}', [AdminWithdrawController::class, 'AprovedWithdraw'])->name('admin.all.withdraw.aprove');
    Route::get('/withdraw/reject/{id}', [AdminWithdrawController::class, 'RejectedWithdraw'])->name('admin.all.withdraw.reject');






    Route::get('/stocks', [AdminStockController::class, 'index'])->name('admin.stock.index');
    Route::post('/stock/post', [AdminStockController::class, 'store'])->name('admin.stock.post');
    Route::get('/stock-presets', [AdminStockPresetController::class, 'index'])->name('admin.stock_preset.index');
    Route::post('/stock-presets/store', [AdminStockPresetController::class, 'store'])->name('admin.stock_preset.store');
    Route::get('/stock-presets/edit/{id}', [AdminStockPresetController::class, 'edit'])->name('admin.stock_preset.edit');
    Route::post('/stock-presets/update/{id}', [AdminStockPresetController::class, 'update'])->name('admin.stock_preset.update');
    Route::get('/stock-presets/destroy/{id}', [AdminStockPresetController::class, 'destroy'])->name('admin.stock_preset.destroy');
    Route::get('/stock/pricing-list', [AdminStockController::class, 'list'])->name('admin.stock.list');
    Route::get('/stock/pricing/{id}', [AdminStockController::class, 'pricing'])->name('admin.stock.add.price');
    Route::post('/stock/pricing/post', [AdminStockController::class, 'pricingPost'])->name('admin.stock.pricing.post');

    Route::get('/stock/image/delete/{id}', [AdminStockController::class, 'imageDelete'])->name('admin.stock.image.delete');



    Route::get('/stock/allStock', [AdminStockController::class, 'allStock'])->name('admin.stock.allStock');
    Route::get('/stock/detials/{id}', [AdminStockController::class, 'adminStockDetials'])->name('admin.stock.detials');
    Route::get('/stock/edit/{id}', [AdminStockController::class, 'adminStockEdit'])->name('admin.stock.edit');
    Route::post('/stock/edit/post/{id}', [AdminStockController::class, 'adminStockEditPost'])->name('admin.stock.edit.post');
    Route::get('/stock/delete/{id}', [AdminStockController::class, 'stockDelete'])->name('admin.stock.delete');


    Route::get('/buy-request-stock/list', [AdminStockController::class, 'ByuRequestList'])->name('admin.stock.buyrequest.list');
    Route::get('/buy-request-stock/aproved/{id}', [AdminStockController::class, 'ByuRequestAproved'])->name('admin.stock.buyrequest.aproved');
    Route::get('/buy-request-stock/rejected/{id}', [AdminStockController::class, 'ByuRequestrejected'])->name('admin.stock.buyrequest.rejected');
    Route::get('/buy-stock/list', [AdminStockController::class, 'ByuList'])->name('admin.buy.stock.list');

    Route::get('/sell-request-stock/list', [AdminStockController::class, 'SellRequestList'])->name('admin.stock.sellrequest.list');
    Route::get('/sell-request-stock/aproved/{id}', [AdminStockController::class, 'SellRequestAproved'])->name('admin.stock.sellrequest.aproved');
    Route::get('/sell-request-stock/rejected/{id}', [AdminStockController::class, 'SellRequestRejected'])->name('admin.stock.sellrequest.rejected');
    Route::get('/sell-stock/list', [AdminStockController::class, 'SellList'])->name('admin.sell.stock.list');

    // Monthly Bazaar Admin Routes
    Route::get('/monthly-bazaar', [AdminMonthlyBazaarController::class, 'index'])->name('admin.monthly_bazaar.index');
    Route::post('/monthly-bazaar/store', [AdminMonthlyBazaarController::class, 'store'])->name('admin.monthly_bazaar.store');
    Route::get('/monthly-bazaar/edit/{id}', [AdminMonthlyBazaarController::class, 'edit'])->name('admin.monthly_bazaar.edit');
    Route::post('/monthly-bazaar/update/{id}', [AdminMonthlyBazaarController::class, 'update'])->name('admin.monthly_bazaar.update');
    Route::get('/monthly-bazaar/destroy/{id}', [AdminMonthlyBazaarController::class, 'destroy'])->name('admin.monthly_bazaar.destroy');

    Route::get('/monthly-bazaar/orders', [AdminMonthlyBazaarController::class, 'orders'])->name('admin.monthly_bazaar.orders');
    Route::get('/monthly-bazaar/order/approve/{id}', [AdminMonthlyBazaarController::class, 'approveOrder'])->name('admin.monthly_bazaar.order.approve');
    Route::get('/monthly-bazaar/order/reject/{id}', [AdminMonthlyBazaarController::class, 'rejectOrder'])->name('admin.monthly_bazaar.order.reject');

});






// employee Route
Route::group(['prefix' => 'employee', 'middleware' => 'EmployeeChacker'], function () {
    Route::get('/referal', [EmployeeController::class, 'Referal'])->name('my.referal');
    Route::get('/profile/business', [EmployeeController::class, 'profileBusiness'])->name('profile.business');
    Route::post('/profile/business/submit', [EmployeeController::class, 'profileBusinessSubmit'])->name('profile.business.submit');
});





// user Route
Route::group(['prefix' => 'user', 'middleware' => 'UserChacker'], function () {
    Route::get('/stock', [UserStockController::class, 'index'])->name('stock.index');
    Route::get('/show/stock/{id}', [UserStockController::class, 'show'])->name('stock.detials');
    Route::get('/stock/details/{id}', [UserStockController::class, 'show'])->name('stock.details');
    Route::get('/sell-stock-list', [UserStockController::class, 'userSellStockList'])->name('usersellstocklist');
    Route::get('/buy-stock-list', [UserStockController::class, 'userBuyStockList'])->name('userbuystocklist');
    Route::get('/stock/sell-request/{id}', [UserStockController::class, 'SellRequest'])->name('user.stock.sell.request');

    Route::get('/payment-system', [UserPaymentSystemController::class, 'index'])->name('payment.index');
    Route::post('/payment-system/post', [UserPaymentSystemController::class, 'store'])->name('payment.post');
    Route::get('/payment-system/destroy/{id}', [UserPaymentSystemController::class, 'destroy'])->name('payment.destroy');

    Route::get('/cart', [UserCartController::class, 'index'])->name('my.cart');
    Route::get('/add/cart/{id}', [UserCartController::class, 'AddCart'])->name('my.cart.add');
    Route::get('/remove/cart/{id}', [UserCartController::class, 'removeCartItem'])->name('my.cart.remove');

    Route::post('/cart/post', [UserCartController::class, 'CartPost'])->name('my.cart.post');


    Route::get('/withdraw', [WithdrawController::class, 'index'])->name('withdraw.index');
    Route::get('/withdraw/request', [WithdrawController::class, 'create'])->name('withdraw.form');
    Route::post('/withdraw', [WithdrawController::class, 'store'])->name('withdraw.post');
    Route::get('/withdraw/destroy/{id}', [WithdrawController::class, 'destroy'])->name('user.withdraw.destroy');

    // Monthly Bazaar User Routes
    Route::get('/monthly-bazaar', [UserMonthlyBazaarController::class, 'index'])->name('user.monthly_bazaar.index');
    Route::post('/monthly-bazaar/order', [UserMonthlyBazaarController::class, 'storeOrder'])->name('user.monthly_bazaar.order.post');
    Route::get('/monthly-bazaar/my-orders', [UserMonthlyBazaarController::class, 'myOrders'])->name('user.monthly_bazaar.my_orders');

});














