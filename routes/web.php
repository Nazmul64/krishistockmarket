<?php

use App\Http\Controllers\Admin\AdminAgentPointController;
use App\Http\Controllers\Admin\AdminAgentLedgerController;
use App\Http\Controllers\Admin\AdminCardBenefitController;
use App\Http\Controllers\Admin\AdminCardNumberController;
use App\Http\Controllers\Admin\AdminContactMessageController;
use App\Http\Controllers\Admin\AdminDepositController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\AdminFeatureController;
use App\Http\Controllers\Admin\AdminOurPackageController;
use App\Http\Controllers\Admin\AdminMonthlyBazaarController;
use App\Http\Controllers\Admin\AdminPaymentSystemController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminStockController;
use App\Http\Controllers\Admin\AdminStockPresetController;
use App\Http\Controllers\Admin\AdminSupplierController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminWithdrawController;
use App\Http\Controllers\Admin\HRM\HrmAnnouncementController;
use App\Http\Controllers\Admin\HRM\HrmAssetController;
use App\Http\Controllers\Admin\HRM\HrmAttendanceController;
use App\Http\Controllers\Admin\HRM\HrmBranchController;
use App\Http\Controllers\Admin\HRM\HrmDashboardController;
use App\Http\Controllers\Admin\HRM\HrmDepartmentController;
use App\Http\Controllers\Admin\HRM\HrmDesignationController;
use App\Http\Controllers\Admin\HRM\HrmEmployeeManagementController;
use App\Http\Controllers\Admin\HRM\HrmHolidayController;
use App\Http\Controllers\Admin\HRM\HrmLeaveController;
use App\Http\Controllers\Admin\HRM\HrmLoanAdvanceController;
use App\Http\Controllers\Admin\HRM\HrmPayrollController;
use App\Http\Controllers\Admin\HRM\HrmPerformanceController;
use App\Http\Controllers\Admin\HRM\HrmRecruitmentController;
use App\Http\Controllers\Admin\HRM\HrmReportController;
use App\Http\Controllers\Admin\HRM\HrmShiftController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Employee\EmployeeStockLedgerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Supplier\SupplierSupplyController;
use App\Http\Controllers\Users\UserCartController;
use App\Http\Controllers\Users\UserDepositController;
use App\Http\Controllers\Users\UserMonthlyBazaarController;
use App\Http\Controllers\Users\UserPaymentSystemController;
use App\Http\Controllers\Users\UserStockController;
use App\Http\Controllers\Users\UserWalletController;
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

Route::get('/', [\App\Http\Controllers\FrontendController::class, 'index'])->name('/');
Route::get('/properties', [\App\Http\Controllers\PropertyFrontendController::class, 'index'])->name('properties.index');
Route::get('/properties/{id}', [\App\Http\Controllers\PropertyFrontendController::class, 'show'])->name('properties.show');
Route::post('/properties/inquiry', [\App\Http\Controllers\PropertyFrontendController::class, 'submitInquiry'])->name('properties.inquiry');
Route::get('/api/home-data', [\App\Http\Controllers\FrontendController::class, 'apiHomepageData'])->name('api.home_data');
Route::get('/api/live-search', [\App\Http\Controllers\FrontendController::class, 'apiLiveSearch'])->name('api.live_search');
Route::get('/about', function () {return view('frontend.about-us');})->name('about');
Route::get('/gallery', function () {return view('frontend.gallery');})->name('gallery');
Route::get('/contact', function () {return view('frontend.contact');})->name('contact');
Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.send');
Route::get('/career', [\App\Http\Controllers\CareerController::class, 'index'])->name('career');
Route::post('/career/apply', [\App\Http\Controllers\CareerController::class, 'apply'])->name('career.apply');
Route::get('/terms', function () {return view('frontend.terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('frontend.privacy');})->name('privacy');

// E-commerce Superstore / Our Products System
Route::get('/our-products', [\App\Http\Controllers\EcommerceShopController::class, 'index'])->name('products.index');
Route::get('/our-products/{slug}', [\App\Http\Controllers\EcommerceShopController::class, 'show'])->name('products.show');
Route::get('/shop/checkout', [\App\Http\Controllers\EcommerceShopController::class, 'checkout'])->name('shop.checkout');
Route::post('/shop/checkout', [\App\Http\Controllers\EcommerceShopController::class, 'placeOrder'])->name('shop.checkout.submit');
Route::post('/shop/apply-coupon', [\App\Http\Controllers\EcommerceShopController::class, 'applyCoupon'])->name('shop.apply_coupon');
Route::get('/shop/order-success/{orderNumber}', [\App\Http\Controllers\EcommerceShopController::class, 'orderSuccess'])->name('shop.order_success');
Route::get('/api/shop/search', [\App\Http\Controllers\EcommerceShopController::class, 'apiSearch'])->name('shop.api.search');
Route::get('/track-order', [\App\Http\Controllers\EcommerceShopController::class, 'trackOrder'])->name('shop.track_order');
Route::post('/track-order', [\App\Http\Controllers\EcommerceShopController::class, 'trackOrderPost'])->name('shop.track_order.post');
Route::get('/order-track', [\App\Http\Controllers\EcommerceShopController::class, 'trackOrder']);

Route::get('/notices/{id}', [\App\Http\Controllers\CorporateProfileController::class, 'noticeDetail'])->name('corporate.notice.show');
Route::get('/online-services', [\App\Http\Controllers\CorporateProfileController::class, 'onlineServices'])->name('corporate.online_services');
Route::get('/investment-policy', [\App\Http\Controllers\CorporateProfileController::class, 'investmentPolicy'])->name('corporate.investment_policy');
Route::get('/policy', [\App\Http\Controllers\CorporateProfileController::class, 'investmentPolicy'])->name('corporate.policy');
Route::get('/apply-card', [\App\Http\Controllers\CorporateProfileController::class, 'applyCard'])->name('apply.card');
Route::post('/apply-card/submit', [\App\Http\Controllers\CorporateProfileController::class, 'storeCardApplication'])->name('apply.card.submit');
Route::get('/verify-card/{card_number?}', [\App\Http\Controllers\CorporateProfileController::class, 'verifyCard'])->name('verify.card');
Route::get('/grievance', [\App\Http\Controllers\CorporateProfileController::class, 'grievanceForm'])->name('grievance.form');
Route::get('/corporate-grievance', [\App\Http\Controllers\CorporateProfileController::class, 'grievanceForm'])->name('corporate.grievance');
Route::post('/grievance/submit', [\App\Http\Controllers\CorporateProfileController::class, 'submitGrievance'])->name('grievance.submit');
Route::get('/grievance/track', [\App\Http\Controllers\CorporateProfileController::class, 'trackGrievance'])->name('grievance.track');



// Dedicated Role Login & Auth Routes (Section 2 - Isolated Login Mappings)
Route::get('/admin/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'admin')->name('admin.login');
Route::post('/admin/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'admin')->name('admin.login.post');
Route::post('/admin/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'admin')->name('admin.logout');

Route::get('/manager/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'manager')->name('manager.login');
Route::post('/manager/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'manager')->name('manager.login.post');
Route::post('/manager/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'manager')->name('manager.logout');

Route::get('/employee/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'employee')->name('employee.login');
Route::post('/employee/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'employee')->name('employee.login.post');
Route::post('/employee/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'employee')->name('employee.logout');

Route::get('/staff/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'staff')->name('staff.login');
Route::post('/staff/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'staff')->name('staff.login.post');
Route::post('/staff/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'staff')->name('staff.logout');

Route::get('/support/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'support')->name('support.login');
Route::post('/support/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'support')->name('support.login.post');
Route::post('/support/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'support')->name('support.logout');

Route::get('/security/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'security')->name('security.login');
Route::post('/security/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'security')->name('security.login.post');
Route::post('/security/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'security')->name('security.logout');

Route::get('/audit/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'audit')->name('audit.login');
Route::post('/audit/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'audit')->name('audit.login.post');
Route::post('/audit/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'audit')->name('audit.logout');

Route::get('/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'customer')->name('login');
Route::post('/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'customer')->name('login.post');
Route::post('/logout', [App\Http\Controllers\Auth\PortalLoginController::class, 'logout'])->defaults('portal', 'customer')->name('logout');

// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Registration routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'registerPost'])->name('register.submit');
Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('register.referlink');
Route::post('/user/register', [RegisterController::class, 'registerPost'])->name('register.post');

// Supplier Registration Routes
Route::get('/supplier/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'showLoginForm'])->defaults('portal', 'supplier')->name('supplier.login');
Route::post('/supplier/login', [App\Http\Controllers\Auth\PortalLoginController::class, 'login'])->defaults('portal', 'supplier')->name('supplier.login.post');
Route::get('/supplier/register', [RegisterController::class, 'showSupplierRegistrationForm'])->name('supplier.register');
Route::post('/supplier/register', [RegisterController::class, 'supplierRegisterPost'])->name('supplier.register.post');

// Common Profiles & Settings
Route::resource('profile', 'App\Http\Controllers\ProfileController');
Route::post('/profile/change', [ProfileController::class, 'changePasswordPost'])->name('profile.password.change');

// Dedicated Role Dashboards & Isolated Portals (Section 2)
// 1. Super Admin / Admin Portal
Route::get('/admin/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard')->middleware(['auth', 'portal:/admin']);

// 2. Manager Portal
Route::group(['prefix' => 'manager', 'middleware' => ['auth', 'portal:/manager']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Manager\ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('/leave/approve/{id}', [\App\Http\Controllers\Manager\ManagerDashboardController::class, 'approveLeave'])->name('manager.leave.approve');
});

// 3. Employee Portal
Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'portal:/employee']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Employee\EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
    Route::get('/packages', [\App\Http\Controllers\Employee\EmployeeDashboardController::class, 'packages'])->name('employee.packages');
    Route::post('/packages/store', [\App\Http\Controllers\Employee\EmployeeDashboardController::class, 'storePackage'])->name('employee.packages.store');
});

// 4. Operations Staff Portal
Route::group(['prefix' => 'staff', 'middleware' => ['auth', 'portal:/staff']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\StaffDashboardController::class, 'index'])->name('staff.dashboard');
    Route::post('/task/escalate/{id}', [\App\Http\Controllers\Staff\StaffDashboardController::class, 'escalateTask'])->name('staff.task.escalate');
});

// 5. Customer Support Hub (Support Lead & Agent Dashboards)
Route::group(['prefix' => 'support', 'middleware' => ['auth', 'portal:/support']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Support\SupportDashboardController::class, 'index'])->name('support.dashboard');
    Route::get('/lead/dashboard', [\App\Http\Controllers\Support\SupportDashboardController::class, 'leadDashboard'])->name('support.lead.dashboard');
    Route::get('/agent/dashboard', [\App\Http\Controllers\Support\SupportDashboardController::class, 'agentDashboard'])->name('support.agent.dashboard');
    Route::post('/ticket/reply/{id}', [\App\Http\Controllers\Support\SupportDashboardController::class, 'reply'])->name('support.ticket.reply');
    Route::post('/ticket/assign/{id}', [\App\Http\Controllers\Support\SupportDashboardController::class, 'assignTicket'])->name('support.ticket.assign');
    Route::post('/ticket/status/{id}', [\App\Http\Controllers\Support\SupportDashboardController::class, 'updateStatus'])->name('support.ticket.status');
});

// 6. Cyber Security SOC Portal
Route::group(['prefix' => 'security', 'middleware' => ['auth', 'portal:/security']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Security\SecurityDashboardController::class, 'index'])->name('security.dashboard');
    Route::post('/block/add', [\App\Http\Controllers\Security\SecurityDashboardController::class, 'addBlock'])->name('security.block.add');
    Route::get('/block/remove/{id}', [\App\Http\Controllers\Security\SecurityDashboardController::class, 'removeBlock'])->name('security.block.remove');
    Route::get('/alert/ack/{id}', [\App\Http\Controllers\Security\SecurityDashboardController::class, 'acknowledgeAlert'])->name('security.alert.ack');
    Route::post('/fraud/update/{id}', [\App\Http\Controllers\Security\SecurityDashboardController::class, 'updateFraud'])->name('security.fraud.update');
});

// 7. Compliance & Audit Portal (Read-only)
Route::group(['prefix' => 'audit', 'middleware' => ['auth', 'portal:/audit']], function () {
    Route::get('/dashboard', [\App\Http\Controllers\Auditor\AuditorDashboardController::class, 'index'])->name('audit.dashboard');
});

// 8. Customer Member Portal
Route::group(['prefix' => 'portal', 'middleware' => ['auth', 'portal:/portal']], function () {
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('portal.dashboard');
});

// Customer Dashboard Aliases
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::get('/user/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('user.dashboard');

// Admin RBAC Management Routes
Route::group(['prefix' => 'admin/rbac', 'middleware' => ['auth', 'role:super_admin,admin']], function () {
    Route::get('/users', [\App\Http\Controllers\Admin\RbacManagementController::class, 'users'])->name('admin.rbac.users');
    Route::post('/users/store', [\App\Http\Controllers\Admin\RbacManagementController::class, 'storeUser'])->name('admin.rbac.users.store');
    Route::post('/users/update/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'updateUser'])->name('admin.rbac.users.update');
    Route::get('/users/{id}/permissions', [\App\Http\Controllers\Admin\RbacManagementController::class, 'manageDirectPermissions'])->name('admin.rbac.users.permissions');
    Route::post('/users/{id}/permissions/update', [\App\Http\Controllers\Admin\RbacManagementController::class, 'updateDirectPermissions'])->name('admin.rbac.users.permissions.update');
    Route::post('/users/toggle-status/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'toggleStatus'])->name('admin.rbac.users.toggle_status');
    Route::post('/users/force-logout/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'forceLogout'])->name('admin.rbac.users.force_logout');

    Route::get('/roles', [\App\Http\Controllers\Admin\RbacManagementController::class, 'roles'])->name('admin.rbac.roles');
    Route::post('/roles/store', [\App\Http\Controllers\Admin\RbacManagementController::class, 'storeRole'])->name('admin.rbac.roles.store');
    Route::post('/roles/update/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'updateRole'])->name('admin.rbac.roles.update');
    Route::post('/roles/clone/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'cloneRole'])->name('admin.rbac.roles.clone');
    Route::post('/roles/archive/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'archiveRole'])->name('admin.rbac.roles.archive');

    Route::get('/permissions', [\App\Http\Controllers\Admin\RbacManagementController::class, 'permissions'])->name('admin.rbac.permissions');
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\RbacManagementController::class, 'auditLogs'])->name('admin.rbac.audit_logs');

    Route::get('/security-hub', [\App\Http\Controllers\Admin\RbacManagementController::class, 'securityHub'])->name('admin.rbac.security_hub');
    Route::post('/blocklist/store', [\App\Http\Controllers\Admin\RbacManagementController::class, 'storeBlocklist'])->name('admin.rbac.blocklist.store');
    Route::get('/blocklist/remove/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'removeBlocklist'])->name('admin.rbac.blocklist.remove');
    Route::get('/alert/ack/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'acknowledgeAlert'])->name('admin.rbac.alert.ack');
    Route::post('/fraud/update/{id}', [\App\Http\Controllers\Admin\RbacManagementController::class, 'updateFraudCase'])->name('admin.rbac.fraud.update');
});

// Admin Real Estate & Property Management (LandShare ERP)
Route::group(['prefix' => 'admin/real-estate', 'middleware' => ['auth', 'role:super_admin,admin']], function () {
    Route::get('/hub', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'index'])->name('admin.landshare.hub');
    Route::get('/', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'index'])->name('admin.real_estate.hub');
    Route::get('/properties', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'properties'])->name('admin.properties.index');
    Route::post('/properties/store', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'storeProperty'])->name('admin.properties.store');
    Route::get('/units', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'units'])->name('admin.properties.units');
    Route::get('/projects', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'projects'])->name('admin.projects.index');
    Route::get('/investments/offers', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'investments'])->name('admin.investments.offers');
    Route::get('/investors', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'investors'])->name('admin.investors.index');
    Route::get('/sales-bookings', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'salesBookings'])->name('admin.sales.bookings');
    Route::get('/profit-reconciliation', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'profit'])->name('admin.profit.reconciliation');
    Route::get('/accounting', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'accounting'])->name('admin.property.accounting');
    Route::get('/payments', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'payments'])->name('admin.property.payments');
    Route::get('/rentals', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'rentals'])->name('admin.rentals.index');
    Route::get('/land-owners', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'landOwners'])->name('admin.land_owners.index');
    Route::get('/brokers', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'brokers'])->name('admin.brokers.index');
    Route::get('/legal-documents', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'legalDocuments'])->name('admin.legal.documents');
});


// Admin Route
Route::group(['prefix' => 'admin', 'middleware' => 'AdminCheck'], function () {
    Route::get('/users', [AdminUserController::class, 'alluser'])->name('alluser');
    Route::post('/user/unlock-balance/{id}', [AdminUserController::class, 'unlockBalance'])->name('admin.user.unlock_balance');
    Route::post('/user/lock-balance/{id}', [AdminUserController::class, 'lockBalance'])->name('admin.user.lock_balance');
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
    Route::get('/employee/permissions/{id}', [AdminEmployeeController::class, 'managePermissions'])->name('admin.employee.permissions');
    Route::post('/employee/permissions/update/{id}', [AdminEmployeeController::class, 'updatePermissions'])->name('admin.employee.permissions.update');

    // Marketing Agent Live Stock & Ledger Routes
    Route::get('/agent-ledger', [AdminAgentLedgerController::class, 'index'])->name('admin.agent_ledger.index');
    Route::get('/agent-ledger/view/{agent_id}', [AdminAgentLedgerController::class, 'show'])->name('admin.agent_ledger.show');
    Route::post('/agent-ledger/allocate', [AdminAgentLedgerController::class, 'allocateStock'])->name('admin.agent_ledger.allocate');



    Route::get('/setting', [SiteSettingController::class, 'index'])->name('setting.index');
    Route::post('/setting/update', [SiteSettingController::class, 'update'])->name('setting.update');
    Route::get('/setting/slider', [SiteSettingController::class, 'SliderSetting'])->name('setting.slider');
    Route::post('/setting/slider/post', [SiteSettingController::class, 'SliderSettingPost'])->name('setting.slider.post');
    Route::get('/setting/slider/delete/{num}', [SiteSettingController::class, 'SliderDelete'])->name('setting.slider.delete');
    Route::get('/setting/notice', [SiteSettingController::class, 'NoticeSetting'])->name('setting.notice');
    Route::post('/setting/notice/post', [SiteSettingController::class, 'NoticeSettingPost'])->name('setting.notice.post');
    Route::get('/setting/notice/delete', [SiteSettingController::class, 'NoticeDelete'])->name('setting.notice.delete');
    Route::get('/setting/offer-banner', [SiteSettingController::class, 'OfferBannerSetting'])->name('setting.offer_banner');
    Route::post('/setting/offer-banner/post', [SiteSettingController::class, 'OfferBannerPost'])->name('setting.offer_banner.post');
    Route::get('/setting/offer-banner/delete', [SiteSettingController::class, 'OfferBannerDelete'])->name('setting.offer_banner.delete');

    Route::get('/features', [AdminFeatureController::class, 'index'])->name('admin.feature.index');
    Route::post('/features/store', [AdminFeatureController::class, 'store'])->name('admin.feature.store');
    Route::get('/features/edit/{id}', [AdminFeatureController::class, 'edit'])->name('admin.feature.edit');
    Route::post('/features/update/{id}', [AdminFeatureController::class, 'update'])->name('admin.feature.update');
    Route::get('/features/delete/{id}', [AdminFeatureController::class, 'destroy'])->name('admin.feature.delete');

    Route::get('/payment-system', [AdminPaymentSystemController::class, 'index'])->name('admin.payment.index');
    Route::post('/payment-system/post', [AdminPaymentSystemController::class, 'store'])->name('admin.payment.post');
    Route::get('/payment-system/destroy/{id}', [AdminPaymentSystemController::class, 'destroy'])->name('admin.payment.destroy');

    // Agent Points (সংগ্রহ এজেন্ট পয়েন্টসমূহ) Routes
    Route::get('/agent-points', [AdminAgentPointController::class, 'index'])->name('admin.agent_points.index');
    Route::post('/agent-points/store', [AdminAgentPointController::class, 'store'])->name('admin.agent_points.store');
    Route::get('/agent-points/destroy/{id}', [AdminAgentPointController::class, 'destroy'])->name('admin.agent_points.destroy');




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
    Route::post('/monthly-bazaar/order/update-allocation/{id}', [AdminMonthlyBazaarController::class, 'updateAllocation'])->name('admin.monthly_bazaar.order.update_allocation');
    Route::get('/monthly-bazaar/distribution-reports', [AdminMonthlyBazaarController::class, 'distributionReports'])->name('admin.monthly_bazaar.distribution_reports');

    // Deposit System Admin Routes
    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('admin.deposit.index');
    Route::get('/deposit/approve/{id}', [AdminDepositController::class, 'approve'])->name('admin.deposit.approve');
    Route::get('/deposit/reject/{id}', [AdminDepositController::class, 'reject'])->name('admin.deposit.reject');

    // Admin Reports & Analytics Routes
    Route::get('/reports', [AdminReportController::class, 'index'])->name('admin.reports.index');

    // Supplier Management Admin Routes
    Route::get('/suppliers', [AdminSupplierController::class, 'index'])->name('admin.suppliers.index');
    Route::get('/suppliers/create', [AdminSupplierController::class, 'create'])->name('admin.suppliers.create');
    Route::post('/suppliers/store', [AdminSupplierController::class, 'store'])->name('admin.suppliers.store');
    Route::get('/suppliers/show/{id}', [AdminSupplierController::class, 'show'])->name('admin.suppliers.show');
    Route::get('/suppliers/verify-supplies', [AdminSupplierController::class, 'pendingSupplies'])->name('admin.suppliers.pending_supplies');
    Route::get('/suppliers/supply/approve/{id}', [AdminSupplierController::class, 'approveSupply'])->name('admin.suppliers.supply.approve');
    Route::get('/suppliers/supply/reject/{id}', [AdminSupplierController::class, 'rejectSupply'])->name('admin.suppliers.supply.reject');
    Route::post('/suppliers/payment/store', [AdminSupplierController::class, 'storePayment'])->name('admin.suppliers.payment.store');
    Route::get('/suppliers/reports', [AdminSupplierController::class, 'reports'])->name('admin.suppliers.reports');
    Route::get('/suppliers/statement/print/{id}', [AdminSupplierController::class, 'printStatement'])->name('admin.suppliers.statement.print');
    Route::get('/suppliers/invoice/print/{id}', [AdminSupplierController::class, 'printInvoice'])->name('admin.suppliers.invoice.print');

    // Our Packages Admin Routes
    Route::get('/our-packages', [AdminOurPackageController::class, 'index'])->name('admin.our_packages.index');
    Route::post('/our-packages/store', [AdminOurPackageController::class, 'store'])->name('admin.our_packages.store');
    Route::get('/our-packages/edit/{id}', [AdminOurPackageController::class, 'edit'])->name('admin.our_packages.edit');
    Route::post('/our-packages/update/{id}', [AdminOurPackageController::class, 'update'])->name('admin.our_packages.update');
    Route::get('/our-packages/destroy/{id}', [AdminOurPackageController::class, 'destroy'])->name('admin.our_packages.destroy');

    // Card Benefits Admin Routes
    Route::get('/card-benefits', [AdminCardBenefitController::class, 'index'])->name('admin.card_benefits.index');
    Route::post('/card-benefits/store', [AdminCardBenefitController::class, 'store'])->name('admin.card_benefits.store');
    Route::get('/card-benefits/edit/{id}', [AdminCardBenefitController::class, 'edit'])->name('admin.card_benefits.edit');
    Route::post('/card-benefits/update/{id}', [AdminCardBenefitController::class, 'update'])->name('admin.card_benefits.update');
    Route::get('/card-benefits/destroy/{id}', [AdminCardBenefitController::class, 'destroy'])->name('admin.card_benefits.delete');
    Route::post('/card-benefits/toggle-status/{id}', [AdminCardBenefitController::class, 'toggleStatus'])->name('admin.card_benefits.toggle_status');

    // Admin Live Chat Support Hub Routes
    Route::get('/chat-support', [\App\Http\Controllers\ChatController::class, 'adminIndex'])->name('admin.chat.index');
    Route::get('/chat-support/sessions-json', [\App\Http\Controllers\ChatController::class, 'adminFetchSessions'])->name('admin.chat.sessions_json');
    Route::get('/chat-support/session/{sessionId}', [\App\Http\Controllers\ChatController::class, 'adminFetchSession'])->name('admin.chat.session');
    Route::post('/chat-support/reply', [\App\Http\Controllers\ChatController::class, 'adminSendReply'])->name('admin.chat.reply');

    // Admin Contact Messages Routes
    Route::get('/contact-messages', [AdminContactMessageController::class, 'index'])->name('admin.contact_messages.index');
    Route::get('/contact-messages/show/{id}', [AdminContactMessageController::class, 'show'])->name('admin.contact_messages.show');
    Route::post('/contact-messages/update/{id}', [AdminContactMessageController::class, 'update'])->name('admin.contact_messages.update');
    Route::get('/contact-messages/destroy/{id}', [AdminContactMessageController::class, 'destroy'])->name('admin.contact_messages.destroy');

    // Admin Full HRM (Human Resource Management) System Routes
    Route::group(['prefix' => 'hrm'], function () {
        Route::get('/dashboard', [HrmDashboardController::class, 'index'])->name('admin.hrm.dashboard');

        // Departments
        Route::get('/departments', [HrmDepartmentController::class, 'index'])->name('admin.hrm.departments.index');
        Route::post('/departments/store', [HrmDepartmentController::class, 'store'])->name('admin.hrm.departments.store');
        Route::put('/departments/update/{id}', [HrmDepartmentController::class, 'update'])->name('admin.hrm.departments.update');
        Route::delete('/departments/destroy/{id}', [HrmDepartmentController::class, 'destroy'])->name('admin.hrm.departments.destroy');

        // Designations
        Route::get('/designations', [HrmDesignationController::class, 'index'])->name('admin.hrm.designations.index');
        Route::post('/designations/store', [HrmDesignationController::class, 'store'])->name('admin.hrm.designations.store');
        Route::put('/designations/update/{id}', [HrmDesignationController::class, 'update'])->name('admin.hrm.designations.update');
        Route::delete('/designations/destroy/{id}', [HrmDesignationController::class, 'destroy'])->name('admin.hrm.designations.destroy');

        // Branches
        Route::get('/branches', [HrmBranchController::class, 'index'])->name('admin.hrm.branches.index');
        Route::post('/branches/store', [HrmBranchController::class, 'store'])->name('admin.hrm.branches.store');
        Route::put('/branches/update/{id}', [HrmBranchController::class, 'update'])->name('admin.hrm.branches.update');
        Route::delete('/branches/destroy/{id}', [HrmBranchController::class, 'destroy'])->name('admin.hrm.branches.destroy');

        // Shifts
        Route::get('/shifts', [HrmShiftController::class, 'index'])->name('admin.hrm.shifts.index');
        Route::post('/shifts/store', [HrmShiftController::class, 'store'])->name('admin.hrm.shifts.store');
        Route::put('/shifts/update/{id}', [HrmShiftController::class, 'update'])->name('admin.hrm.shifts.update');
        Route::delete('/shifts/destroy/{id}', [HrmShiftController::class, 'destroy'])->name('admin.hrm.shifts.destroy');

        // Holidays
        Route::get('/holidays', [HrmHolidayController::class, 'index'])->name('admin.hrm.holidays.index');
        Route::post('/holidays/store', [HrmHolidayController::class, 'store'])->name('admin.hrm.holidays.store');
        Route::put('/holidays/update/{id}', [HrmHolidayController::class, 'update'])->name('admin.hrm.holidays.update');
        Route::delete('/holidays/destroy/{id}', [HrmHolidayController::class, 'destroy'])->name('admin.hrm.holidays.destroy');

        // Employees
        Route::get('/employees', [HrmEmployeeManagementController::class, 'index'])->name('admin.hrm.employees.index');
        Route::post('/employees/store', [HrmEmployeeManagementController::class, 'store'])->name('admin.hrm.employees.store');
        Route::get('/employees/show/{id}', [HrmEmployeeManagementController::class, 'show'])->name('admin.hrm.employees.show');
        Route::put('/employees/update-profile/{id}', [HrmEmployeeManagementController::class, 'updateProfile'])->name('admin.hrm.employees.update_profile');

        // Attendance
        Route::get('/attendance', [HrmAttendanceController::class, 'index'])->name('admin.hrm.attendance.index');
        Route::post('/attendance/store', [HrmAttendanceController::class, 'store'])->name('admin.hrm.attendance.store');

        // Leave
        Route::get('/leave', [HrmLeaveController::class, 'index'])->name('admin.hrm.leave.index');
        Route::post('/leave/store-type', [HrmLeaveController::class, 'storeType'])->name('admin.hrm.leave.store_type');
        Route::put('/leave/update-status/{id}', [HrmLeaveController::class, 'updateStatus'])->name('admin.hrm.leave.update_status');

        // Payroll
        Route::get('/payroll', [HrmPayrollController::class, 'index'])->name('admin.hrm.payroll.index');
        Route::post('/payroll/generate', [HrmPayrollController::class, 'generatePayroll'])->name('admin.hrm.payroll.generate');
        Route::put('/payroll/mark-paid/{itemId}', [HrmPayrollController::class, 'markPaid'])->name('admin.hrm.payroll.mark_paid');
        Route::get('/payroll/payslip/{itemId}', [HrmPayrollController::class, 'showPayslip'])->name('admin.hrm.payroll.payslip');

        // Loans & Advance
        Route::get('/loans', [HrmLoanAdvanceController::class, 'index'])->name('admin.hrm.loans.index');
        Route::post('/loans/store-loan', [HrmLoanAdvanceController::class, 'storeLoan'])->name('admin.hrm.loans.store_loan');
        Route::post('/loans/store-advance', [HrmLoanAdvanceController::class, 'storeAdvance'])->name('admin.hrm.loans.store_advance');

        // Recruitment
        Route::get('/recruitment', [HrmRecruitmentController::class, 'index'])->name('admin.hrm.recruitment.index');
        Route::post('/recruitment/store-job', [HrmRecruitmentController::class, 'storeJob'])->name('admin.hrm.recruitment.store_job');
        Route::get('/recruitment/applicants/{jobId}', [HrmRecruitmentController::class, 'applicants'])->name('admin.hrm.recruitment.applicants');
        Route::put('/recruitment/update-applicant-status/{applicantId}', [HrmRecruitmentController::class, 'updateApplicantStatus'])->name('admin.hrm.recruitment.update_applicant_status');

        // Performance
        Route::get('/performance', [HrmPerformanceController::class, 'index'])->name('admin.hrm.performance.index');
        Route::post('/performance/store', [HrmPerformanceController::class, 'store'])->name('admin.hrm.performance.store');

        // Company Assets
        Route::get('/assets', [HrmAssetController::class, 'index'])->name('admin.hrm.assets.index');
        Route::post('/assets/store', [HrmAssetController::class, 'store'])->name('admin.hrm.assets.store');
        Route::put('/assets/assign/{id}', [HrmAssetController::class, 'assign'])->name('admin.hrm.assets.assign');

        // Announcements
        Route::get('/announcements', [HrmAnnouncementController::class, 'index'])->name('admin.hrm.announcements.index');
        Route::post('/announcements/store', [HrmAnnouncementController::class, 'store'])->name('admin.hrm.announcements.store');

        // Reports
        Route::get('/reports', [HrmReportController::class, 'index'])->name('admin.hrm.reports.index');
    });

    // Admin Career Applications Management
    Route::get('/career-applications', [\App\Http\Controllers\Admin\AdminCareerApplicationController::class, 'index'])->name('admin.career.index');
    Route::get('/career-applications/{id}', [\App\Http\Controllers\Admin\AdminCareerApplicationController::class, 'show'])->name('admin.career.show');
    Route::post('/career-applications/status/{id}', [\App\Http\Controllers\Admin\AdminCareerApplicationController::class, 'updateStatus'])->name('admin.career.status');
    Route::delete('/career-applications/destroy/{id}', [\App\Http\Controllers\Admin\AdminCareerApplicationController::class, 'destroy'])->name('admin.career.destroy');

});

// Public Live Chat & AI Bot API Routes
Route::post('/chat/fetch', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->name('chat.fetch');
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');

// Supplier Portal Routes
Route::group(['prefix' => 'supplier', 'middleware' => 'SupplierChecker'], function () {
    Route::get('/dashboard', [SupplierSupplyController::class, 'dashboard'])->name('supplier.dashboard');
    Route::get('/supplies', [SupplierSupplyController::class, 'index'])->name('supplier.supplies.index');
    Route::get('/supplies/create', [SupplierSupplyController::class, 'create'])->name('supplier.supplies.create');
    Route::post('/supplies/store', [SupplierSupplyController::class, 'store'])->name('supplier.supplies.store');
    Route::get('/statement', [SupplierSupplyController::class, 'statement'])->name('supplier.statement');
    Route::get('/invoice/print/{id}', [SupplierSupplyController::class, 'printInvoice'])->name('supplier.invoice.print');
});






// employee Route
Route::group(['prefix' => 'employee', 'middleware' => 'EmployeeChacker'], function () {
    Route::get('/referal', [EmployeeController::class, 'Referal'])->name('my.referal');
    Route::get('/profile/business', [EmployeeController::class, 'profileBusiness'])->name('profile.business');
    Route::post('/profile/business/submit', [EmployeeController::class, 'profileBusinessSubmit'])->name('profile.business.submit');

    // Marketing Agent Live Stock & Ledger Employee Routes
    Route::get('/stock-ledger', [EmployeeStockLedgerController::class, 'index'])->name('employee.stock_ledger.index');
    Route::post('/stock-ledger/sell', [EmployeeStockLedgerController::class, 'sellStock'])->name('employee.stock_ledger.sell');
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
    Route::get('/cart/count', [UserCartController::class, 'getCartCount'])->name('my.cart.count');
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

    // Customer Deposit & Wallet Ledger Routes
    Route::get('/deposit', [UserDepositController::class, 'index'])->name('user.deposit.index');
    Route::post('/deposit/post', [UserDepositController::class, 'store'])->name('user.deposit.post');
    Route::get('/wallet/ledger', [UserWalletController::class, 'ledger'])->name('user.wallet.ledger');

    // Customer Land & Property Routes
    Route::prefix('properties')->name('user.properties.')->group(function() {
        Route::get('/my-bookings', [\App\Http\Controllers\Users\UserPropertyController::class, 'myBookings'])->name('my_bookings');
        Route::get('/investments', [\App\Http\Controllers\Users\UserPropertyController::class, 'investments'])->name('investments');
        Route::post('/cancel-inquiry/{id}', [\App\Http\Controllers\Users\UserPropertyController::class, 'cancelInquiry'])->name('cancel_inquiry');
    });

    // User Corporate Membership, Cards, Investments & Community Routes
    Route::prefix('corporate')->name('user.corporate.')->group(function() {
        Route::get('/my-card', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myCard'])->name('card');
        Route::get('/investments', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myInvestments'])->name('investments');
        Route::post('/investments/withdraw', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'requestWithdrawal'])->name('investments.withdraw');
        Route::get('/shareholder-profile', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myShareholderProfile'])->name('shareholder');
        Route::get('/partnership-profile', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myPartnershipProfile'])->name('partnership');
        Route::get('/community', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'communityForum'])->name('community');
        Route::post('/community/post', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'storeCommunityPost'])->name('community.post');        Route::post('/community/comment/{postId}', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'storeCommunityComment'])->name('community.comment');
        Route::get('/agent-inventory', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'agentInventory'])->name('agent_inventory');
        Route::get('/provident-fund', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myProvidentFund'])->name('provident_fund');
    });

    // Direct User Shortcuts
    Route::get('/my-card', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myCard'])->name('user.my_card');
    Route::get('/my-investments', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'myInvestments'])->name('user.investments.my');
    Route::get('/community-forum', [\App\Http\Controllers\Users\UserCorporatePortalController::class, 'communityForum'])->name('user.community.forum');



});

Route::group(['prefix' => 'admin/corporate', 'middleware' => 'AdminCheck'], function () {
    Route::get('/board-members', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'boardMembersIndex'])->name('admin.corporate.board_members');
    Route::post('/board-members/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeBoardMember'])->name('admin.corporate.board_members.store');
    Route::post('/board-members/update/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateBoardMember'])->name('admin.corporate.board_members.update');
    Route::get('/board-members/destroy/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'destroyBoardMember'])->name('admin.corporate.board_members.destroy');

    Route::get('/profile-cms', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'corporateProfileCms'])->name('admin.corporate.profile_cms');
    Route::post('/profile-cms/update/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateProfileSection'])->name('admin.corporate.profile_cms.update');

    Route::get('/activities', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'activitiesIndex'])->name('admin.corporate.activities');
    Route::post('/activities/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeActivity'])->name('admin.corporate.activities.store');
    Route::post('/activities/update/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateActivity'])->name('admin.corporate.activities.update');
    Route::get('/activities/destroy/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'destroyActivity'])->name('admin.corporate.activities.destroy');

    Route::get('/sectors', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'sectorsIndex'])->name('admin.corporate.sectors');
    Route::post('/sectors/update/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateSector'])->name('admin.corporate.sectors.update');
    Route::get('/investment-projects', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'projectsIndex'])->name('admin.corporate.projects');
    Route::post('/investment-projects/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeProject'])->name('admin.corporate.projects.store');
    Route::post('/investment-projects/update/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateProject'])->name('admin.corporate.projects.update');
    Route::get('/investment-projects/destroy/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'destroyProject'])->name('admin.corporate.projects.destroy');
    Route::get('/investments', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'investmentsIndex'])->name('admin.corporate.investments');
    Route::post('/investments/status/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateInvestmentStatus'])->name('admin.corporate.investments.status');
    Route::post('/investments/dividend/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'declareDividend'])->name('admin.corporate.investments.dividend');
    Route::post('/investments/dividend-paid/{dividendId}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'markDividendPaid'])->name('admin.corporate.investments.dividend_paid');
    Route::get('/withdrawals', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'withdrawalsIndex'])->name('admin.corporate.withdrawals');
    Route::post('/withdrawals/status/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateWithdrawalStatus'])->name('admin.corporate.withdrawals.status');
    Route::get('/shareholders', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'shareholdersIndex'])->name('admin.corporate.shareholders');
    Route::post('/shareholders/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeShareholder'])->name('admin.corporate.shareholders.store');
    Route::post('/shareholders/dividend/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'declareShareDividend'])->name('admin.corporate.shareholders.dividend');
    Route::get('/partnerships', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'partnershipsIndex'])->name('admin.corporate.partnerships');
    Route::post('/partnerships/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storePartnership'])->name('admin.corporate.partnerships.store');
    Route::get('/finance-dashboard', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'financeDashboard'])->name('admin.corporate.finance');
    Route::post('/finance-dashboard/entry', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeFinanceEntry'])->name('admin.corporate.finance.entry');
    Route::get('/agent-inventory', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'agentInventoryIndex'])->name('admin.corporate.agent_inventory');
    Route::post('/agent-inventory/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeAgentInventory'])->name('admin.corporate.agent_inventory.store');
    Route::get('/provident-fund', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'providentFundIndex'])->name('admin.corporate.provident_fund');
    Route::post('/provident-fund/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeProvidentFund'])->name('admin.corporate.provident_fund.store');
    Route::get('/notices', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'noticesIndex'])->name('admin.corporate.notices');    Route::post('/notices/store', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'storeNotice'])->name('admin.corporate.notices.store');
    Route::post('/notices/update/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'updateNotice'])->name('admin.corporate.notices.update');
    Route::get('/notices/destroy/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'destroyNotice'])->name('admin.corporate.notices.destroy');
    Route::get('/grievances', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'grievancesIndex'])->name('admin.corporate.grievances');
    Route::post('/grievances/respond/{id}', [\App\Http\Controllers\Admin\AdminCorporateManagementController::class, 'respondGrievance'])->name('admin.corporate.grievances.respond');
});
// =========================================================================
// Corporate ERP System Routes (Store, Inventory, Asset, Maintenance & Finance)
// =========================================================================
Route::group(['prefix' => 'admin/erp', 'middleware' => ['auth', 'role:super_admin,admin,managing_director,finance_admin']], function () {
    // 1. Dashboard & Company Profile
    Route::get('/dashboard', [\App\Http\Controllers\Admin\ERP\ErpDashboardController::class, 'index'])->name('admin.erp.dashboard');
    Route::get('/company-profile', [\App\Http\Controllers\Admin\ERP\ErpDashboardController::class, 'companyProfile'])->name('admin.erp.company_profile');
    Route::post('/company-profile/update', [\App\Http\Controllers\Admin\ERP\ErpDashboardController::class, 'updateCompanyProfile'])->name('admin.erp.company_profile.update');
    Route::post('/documents/store', [\App\Http\Controllers\Admin\ERP\ErpDashboardController::class, 'storeDocument'])->name('admin.erp.documents.store');

    // 2. Store & Inventory Management
    Route::get('/store/items', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'itemsIndex'])->name('admin.erp.store.items');
    Route::post('/store/items/store', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'storeItem'])->name('admin.erp.store.items.store');
    Route::post('/store/items/update/{id}', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'updateItem'])->name('admin.erp.store.items.update');
    Route::get('/store/requisitions', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'requisitionsIndex'])->name('admin.erp.store.requisitions');
    Route::post('/store/requisitions/store', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'storeRequisition'])->name('admin.erp.store.requisitions.store');
    Route::post('/store/requisitions/status/{id}', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'updateRequisitionStatus'])->name('admin.erp.store.requisitions.status');
    Route::get('/store/stock-in-out', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'stockInOutIndex'])->name('admin.erp.store.stock_in_out');
    Route::post('/store/stock-in-out/store', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'storeStockTransaction'])->name('admin.erp.store.stock_in_out.store');
    Route::get('/store/transfers-adjustments', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'transfersAdjustmentsIndex'])->name('admin.erp.store.transfers_adjustments');
    Route::get('/store/transfers', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'transfersAdjustmentsIndex'])->name('admin.erp.store.transfers');
    Route::post('/store/transfers/store', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'storeTransfer'])->name('admin.erp.store.transfers.store');
    Route::post('/store/adjustments/store', [\App\Http\Controllers\Admin\ERP\ErpStoreController::class, 'storeAdjustment'])->name('admin.erp.store.adjustments.store');

    // 3. Procurement & Purchase Orders
    Route::get('/procurement', [\App\Http\Controllers\Admin\ERP\ErpProcurementController::class, 'index'])->name('admin.erp.procurement');
    Route::get('/procurement/index', [\App\Http\Controllers\Admin\ERP\ErpProcurementController::class, 'index'])->name('admin.erp.procurement.index');
    Route::post('/procurement/supplier/store', [\App\Http\Controllers\Admin\ERP\ErpProcurementController::class, 'storeSupplier'])->name('admin.erp.procurement.supplier.store');
    Route::post('/procurement/po/store', [\App\Http\Controllers\Admin\ERP\ErpProcurementController::class, 'storePurchaseOrder'])->name('admin.erp.procurement.po.store');
    Route::post('/procurement/grn/store', [\App\Http\Controllers\Admin\ERP\ErpProcurementController::class, 'storeGrn'])->name('admin.erp.procurement.grn.store');

    // 4. Fixed Asset Management
    Route::get('/assets', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'assetsIndex'])->name('admin.erp.assets');
    Route::get('/assets/index', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'assetsIndex'])->name('admin.erp.assets.index');
    Route::post('/assets/store', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'storeAsset'])->name('admin.erp.assets.store');
    Route::post('/assets/update/{id}', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'updateAsset'])->name('admin.erp.assets.update');
    Route::get('/assets/assignments', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'assignmentsIndex'])->name('admin.erp.assets.assignments');
    Route::post('/assets/assignments/store', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'storeAssignment'])->name('admin.erp.assets.assignments.store');
    Route::post('/assets/assignments/return/{id}', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'returnAssignment'])->name('admin.erp.assets.assignments.return');
    Route::get('/assets/depreciation', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'depreciationIndex'])->name('admin.erp.assets.depreciation');
    Route::post('/assets/depreciation/calculate', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'calculateDepreciation'])->name('admin.erp.assets.depreciation.calculate');
    Route::post('/assets/disposal/store', [\App\Http\Controllers\Admin\ERP\ErpAssetController::class, 'storeDisposal'])->name('admin.erp.assets.disposal.store');

    // 5. Vehicle & Fleet Management
    Route::get('/vehicles', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'index'])->name('admin.erp.vehicles');
    Route::get('/vehicles/index', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'index'])->name('admin.erp.vehicles.index');
    Route::post('/vehicles/store', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'storeVehicle'])->name('admin.erp.vehicles.store');
    Route::post('/vehicles/documents/store', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'storeDocument'])->name('admin.erp.vehicles.documents.store');
    Route::get('/vehicles/trips', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'tripsIndex'])->name('admin.erp.vehicles.trips');
    Route::post('/vehicles/trips/store', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'storeTrip'])->name('admin.erp.vehicles.trips.store');
    Route::post('/vehicles/fuel/store', [\App\Http\Controllers\Admin\ERP\ErpVehicleController::class, 'storeFuel'])->name('admin.erp.vehicles.fuel.store');
    // 6. Maintenance & Repairs
    Route::get('/maintenance', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'index'])->name('admin.erp.maintenance');
    Route::get('/maintenance/index', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'index'])->name('admin.erp.maintenance.index');
    Route::post('/maintenance/request/store', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'storeRequest'])->name('admin.erp.maintenance.request.store');
    Route::post('/maintenance/requests/store', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'storeRequest'])->name('admin.erp.maintenance.requests.store');
    Route::post('/maintenance/request/status/{id}', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'updateRequestStatus'])->name('admin.erp.maintenance.request.status');
    Route::post('/maintenance/requests/status/{id}', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'updateRequestStatus'])->name('admin.erp.maintenance.requests.status');
    Route::post('/maintenance/schedule/store', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'storeSchedule'])->name('admin.erp.maintenance.schedule.store');
    Route::post('/maintenance/schedules/store', [\App\Http\Controllers\Admin\ERP\ErpMaintenanceController::class, 'storeSchedule'])->name('admin.erp.maintenance.schedules.store');

    // 7. Bills & Invoices
    Route::get('/bills', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'index'])->name('admin.erp.bills');
    Route::get('/bills/index', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'index'])->name('admin.erp.bills.index');
    Route::get('/billing/bills', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'index'])->name('admin.erp.billing.bills');
    Route::post('/bills/store', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'storeBill'])->name('admin.erp.bills.store');
    Route::post('/billing/bills/store', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'storeBill'])->name('admin.erp.billing.bills.store');
    Route::post('/bills/approve/{id}', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'approveBill'])->name('admin.erp.bills.approve');
    Route::post('/billing/bills/approve/{id}', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'approveBill'])->name('admin.erp.billing.bills.approve');
    Route::post('/bills/pay/{id}', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'payBill'])->name('admin.erp.bills.pay');
    Route::post('/billing/bills/pay/{id}', [\App\Http\Controllers\Admin\ERP\ErpBillingController::class, 'payBill'])->name('admin.erp.billing.bills.pay');

    // 8. Vouchers (Double Entry)
    Route::get('/vouchers', [\App\Http\Controllers\Admin\ERP\ErpVoucherController::class, 'index'])->name('admin.erp.vouchers');
    Route::get('/vouchers/index', [\App\Http\Controllers\Admin\ERP\ErpVoucherController::class, 'index'])->name('admin.erp.vouchers.index');
    Route::post('/vouchers/store', [\App\Http\Controllers\Admin\ERP\ErpVoucherController::class, 'storeVoucher'])->name('admin.erp.vouchers.store');
    Route::get('/vouchers/print/{id}', [\App\Http\Controllers\Admin\ERP\ErpVoucherController::class, 'printVoucher'])->name('admin.erp.vouchers.print');

    // 9. Money Receipts & Cash Memos
    Route::get('/money-receipts', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'moneyReceiptsIndex'])->name('admin.erp.money_receipts');
    Route::get('/money-receipts/index', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'moneyReceiptsIndex'])->name('admin.erp.money_receipts.index');
    Route::get('/billing/money-receipts', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'moneyReceiptsIndex'])->name('admin.erp.billing.money_receipts');
    Route::post('/money-receipts/store', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'storeMoneyReceipt'])->name('admin.erp.money_receipts.store');
    Route::post('/billing/money-receipts/store', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'storeMoneyReceipt'])->name('admin.erp.billing.money_receipts.store');
    Route::get('/money-receipts/print/{id}', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'printMoneyReceipt'])->name('admin.erp.money_receipts.print');
    Route::get('/billing/money-receipts/print/{id}', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'printMoneyReceipt'])->name('admin.erp.billing.money_receipts.print');

    Route::get('/cash-memos', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'cashMemosIndex'])->name('admin.erp.cash_memos');
    Route::get('/cash-memos/index', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'cashMemosIndex'])->name('admin.erp.cash_memos.index');
    Route::get('/billing/cash-memos', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'cashMemosIndex'])->name('admin.erp.billing.cash_memos');
    Route::post('/cash-memos/store', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'storeCashMemo'])->name('admin.erp.cash_memos.store');
    Route::post('/billing/cash-memos/store', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'storeCashMemo'])->name('admin.erp.billing.cash_memos.store');
    Route::get('/cash-memos/print/{id}', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'printCashMemo'])->name('admin.erp.cash_memos.print');
    Route::get('/billing/cash-memos/print/{id}', [\App\Http\Controllers\Admin\ERP\ErpReceiptMemoController::class, 'printCashMemo'])->name('admin.erp.billing.cash_memos.print');

    // 10. Accounts & Finance
    Route::get('/accounts/chart-of-accounts', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'chartOfAccounts'])->name('admin.erp.accounts.chart_of_accounts');
    Route::post('/accounts/chart-of-accounts/store', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'storeAccount'])->name('admin.erp.accounts.chart_of_accounts.store');
    Route::get('/accounts/general-ledger', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'generalLedger'])->name('admin.erp.accounts.general_ledger');
    Route::get('/accounts/cash-bank', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'cashBankIndex'])->name('admin.erp.accounts.cash_bank');
    Route::post('/accounts/cash-bank/store', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'storeCashBankAccount'])->name('admin.erp.accounts.cash_bank.store');
    Route::get('/accounts/trial-balance', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'trialBalance'])->name('admin.erp.accounts.trial_balance');
    Route::get('/accounts/profit-and-loss', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'profitAndLoss'])->name('admin.erp.accounts.profit_and_loss');
    Route::get('/accounts/balance-sheet', [\App\Http\Controllers\Admin\ERP\ErpAccountsController::class, 'balanceSheet'])->name('admin.erp.accounts.balance_sheet');

    // 11. Expenses & Budgets
    Route::get('/expenses', [\App\Http\Controllers\Admin\ERP\ErpExpenseBudgetController::class, 'expensesIndex'])->name('admin.erp.expenses');
    Route::get('/expenses/index', [\App\Http\Controllers\Admin\ERP\ErpExpenseBudgetController::class, 'expensesIndex'])->name('admin.erp.expenses.index');
    Route::post('/expenses/store', [\App\Http\Controllers\Admin\ERP\ErpExpenseBudgetController::class, 'storeExpense'])->name('admin.erp.expenses.store');
    Route::get('/budgets', [\App\Http\Controllers\Admin\ERP\ErpExpenseBudgetController::class, 'budgetsIndex'])->name('admin.erp.budgets');
    Route::get('/budgets/index', [\App\Http\Controllers\Admin\ERP\ErpExpenseBudgetController::class, 'budgetsIndex'])->name('admin.erp.budgets.index');
    Route::post('/budgets/store', [\App\Http\Controllers\Admin\ERP\ErpExpenseBudgetController::class, 'storeBudget'])->name('admin.erp.budgets.store');

    // 12. Reports & Analytics
    Route::get('/reports', [\App\Http\Controllers\Admin\ERP\ErpReportsController::class, 'index'])->name('admin.erp.reports');
    Route::get('/reports/index', [\App\Http\Controllers\Admin\ERP\ErpReportsController::class, 'index'])->name('admin.erp.reports.index');
});
// =========================================================================
// Complete Independent E-commerce Management Module Routes
// =========================================================================
Route::group(['prefix' => 'admin/ecommerce', 'middleware' => ['auth', 'role:super_admin,admin']], function () {
    // 1. Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'dashboard'])->name('admin.ecommerce.dashboard');

    // 2. Sliders
    Route::get('/sliders', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'sliders'])->name('admin.ecommerce.sliders');
    Route::post('/sliders/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'sliderStore'])->name('admin.ecommerce.sliders.store');
    Route::post('/sliders/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'sliderUpdate'])->name('admin.ecommerce.sliders.update');
    Route::get('/sliders/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'sliderDestroy'])->name('admin.ecommerce.sliders.destroy');

    // 3. Banners
    Route::get('/banners', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'banners'])->name('admin.ecommerce.banners');
    Route::post('/banners/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'bannerStore'])->name('admin.ecommerce.banners.store');
    Route::post('/banners/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'bannerUpdate'])->name('admin.ecommerce.banners.update');
    Route::get('/banners/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'bannerDestroy'])->name('admin.ecommerce.banners.destroy');

    // 4. Categories & Subcategories
    Route::get('/categories', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'categories'])->name('admin.ecommerce.categories');
    Route::post('/categories/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'categoryStore'])->name('admin.ecommerce.categories.store');
    Route::post('/categories/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'categoryUpdate'])->name('admin.ecommerce.categories.update');
    Route::get('/categories/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'categoryDestroy'])->name('admin.ecommerce.categories.destroy');

    Route::get('/subcategories', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'subcategories'])->name('admin.ecommerce.subcategories');
    Route::post('/subcategories/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'subcategoryStore'])->name('admin.ecommerce.subcategories.store');
    Route::post('/subcategories/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'subcategoryUpdate'])->name('admin.ecommerce.subcategories.update');
    Route::get('/subcategories/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'subcategoryDestroy'])->name('admin.ecommerce.subcategories.destroy');

    // 5. Brands
    Route::get('/brands', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'brands'])->name('admin.ecommerce.brands');
    Route::post('/brands/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'brandStore'])->name('admin.ecommerce.brands.store');
    Route::post('/brands/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'brandUpdate'])->name('admin.ecommerce.brands.update');
    Route::get('/brands/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'brandDestroy'])->name('admin.ecommerce.brands.destroy');

    // 6. Attributes & Values
    Route::get('/attributes', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'attributes'])->name('admin.ecommerce.attributes');
    Route::post('/attributes/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'attributeStore'])->name('admin.ecommerce.attributes.store');
    Route::post('/attributes/values/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'attributeValueStore'])->name('admin.ecommerce.attributes.values.store');
    Route::get('/attributes/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'attributeDestroy'])->name('admin.ecommerce.attributes.destroy');
    Route::get('/attributes/values/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'attributeValueDestroy'])->name('admin.ecommerce.attributes.values.destroy');

    // 7. Products
    Route::get('/products', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'products'])->name('admin.ecommerce.products');
    Route::get('/products/create', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'productCreate'])->name('admin.ecommerce.products.create');
    Route::post('/products/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'productStore'])->name('admin.ecommerce.products.store');
    Route::get('/products/edit/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'productEdit'])->name('admin.ecommerce.products.edit');
    Route::post('/products/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'productUpdate'])->name('admin.ecommerce.products.update');
    Route::get('/products/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'productDestroy'])->name('admin.ecommerce.products.destroy');
    Route::post('/products/quick-stock/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'productQuickStock'])->name('admin.ecommerce.products.quick_stock');

    // 8. Inventory
    Route::get('/inventory', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'inventory'])->name('admin.ecommerce.inventory');

    // 9. Combo Offers
    Route::get('/combo-offers', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'comboOffers'])->name('admin.ecommerce.combo_offers');
    Route::post('/combo-offers/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'comboOfferStore'])->name('admin.ecommerce.combo_offers.store');
    Route::get('/combo-offers/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'comboOfferDestroy'])->name('admin.ecommerce.combo_offers.destroy');

    // 10. Coupons
    Route::get('/coupons', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'coupons'])->name('admin.ecommerce.coupons');
    Route::post('/coupons/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'couponStore'])->name('admin.ecommerce.coupons.store');
    Route::get('/coupons/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'couponDestroy'])->name('admin.ecommerce.coupons.destroy');

    // 11. Orders
    Route::get('/orders', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orders'])->name('admin.ecommerce.orders');
    Route::get('/orders/show/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderShow'])->name('admin.ecommerce.orders.show');
    Route::match(['post', 'put'], '/orders/status/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderStatusUpdate'])->name('admin.ecommerce.orders.status');
    Route::match(['post', 'put'], '/orders/payment-status/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderPaymentStatusUpdate'])->name('admin.ecommerce.orders.payment_status');
    Route::get('/orders/invoice/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderInvoice'])->name('admin.ecommerce.orders.invoice');
    Route::post('/orders/{id}/send-courier', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderSendCourier'])->name('admin.ecommerce.orders.send_courier');
    Route::post('/orders/bulk-action', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'ordersBulkAction'])->name('admin.ecommerce.orders.bulk_action');
    Route::match(['get', 'delete'], '/orders/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderDestroy'])->name('admin.ecommerce.orders.destroy');
    Route::post('/orders/create-manual', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'orderCreateManual'])->name('admin.ecommerce.orders.create_manual');
    Route::get('/orders/check-customer/{phone}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'checkCustomer'])->name('admin.ecommerce.orders.check_customer');

    // Courier Settings
    Route::get('/courier/steadfast', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'courierSteadfast'])->name('admin.ecommerce.courier.steadfast');
    Route::post('/courier/steadfast', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'courierSteadfastUpdate'])->name('admin.ecommerce.courier.steadfast.update');
    Route::get('/courier/pathao', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'courierPathao'])->name('admin.ecommerce.courier.pathao');
    Route::post('/courier/pathao', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'courierPathaoUpdate'])->name('admin.ecommerce.courier.pathao.update');

    // 12. Customers
    Route::get('/customers', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'customers'])->name('admin.ecommerce.customers');

    // 13. Shipping Areas
    Route::get('/shipping', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'shipping'])->name('admin.ecommerce.shipping');
    Route::post('/shipping/store', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'shippingStore'])->name('admin.ecommerce.shipping.store');
    Route::post('/shipping/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'shippingUpdate'])->name('admin.ecommerce.shipping.update');
    Route::get('/shipping/destroy/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'shippingDestroy'])->name('admin.ecommerce.shipping.destroy');

    // 14. Payment Methods
    Route::get('/payments', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'payments'])->name('admin.ecommerce.payments');
    Route::post('/payments/update/{id}', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'paymentUpdate'])->name('admin.ecommerce.payments.update');

    // 15. Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'reports'])->name('admin.ecommerce.reports');

    // 16. Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'settings'])->name('admin.ecommerce.settings');
    Route::post('/settings/update', [\App\Http\Controllers\Admin\Ecommerce\EcommerceAdminController::class, 'settingsUpdate'])->name('admin.ecommerce.settings.update');
});

// =========================================================================
// Super Shop & Multi-Branch Management Enterprise Module Routes
// =========================================================================
Route::group(['prefix' => 'admin/supershop', 'middleware' => ['auth', 'role:super_admin,admin']], function () {
    // 1. Central & Branch Executive Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\SuperShop\SuperShopDashboardController::class, 'index'])->name('admin.supershop.dashboard');

    // 2. Branch Management
    Route::get('/branches', [\App\Http\Controllers\Admin\SuperShop\BranchManagementController::class, 'index'])->name('admin.supershop.branches');
    Route::post('/branches/store', [\App\Http\Controllers\Admin\SuperShop\BranchManagementController::class, 'store'])->name('admin.supershop.branches.store');
    Route::match(['post', 'put'], '/branches/update/{id}', [\App\Http\Controllers\Admin\SuperShop\BranchManagementController::class, 'update'])->name('admin.supershop.branches.update');
    Route::match(['get', 'delete'], '/branches/destroy/{id}', [\App\Http\Controllers\Admin\SuperShop\BranchManagementController::class, 'destroy'])->name('admin.supershop.branches.destroy');

    // 3. Branch Inventory & Stock Movement
    Route::get('/inventory', [\App\Http\Controllers\Admin\SuperShop\BranchInventoryController::class, 'index'])->name('admin.supershop.inventory');
    Route::post('/inventory/adjust/{id}', [\App\Http\Controllers\Admin\SuperShop\BranchInventoryController::class, 'adjustStock'])->name('admin.supershop.inventory.adjust');
    Route::post('/inventory/assign-product', [\App\Http\Controllers\Admin\SuperShop\BranchInventoryController::class, 'assignProduct'])->name('admin.supershop.inventory.assign');
    Route::get('/inventory/movements', [\App\Http\Controllers\Admin\SuperShop\BranchInventoryController::class, 'movements'])->name('admin.supershop.inventory.movements');

    // 4. POS Terminal & Fast Billing
    Route::get('/pos', [\App\Http\Controllers\Admin\SuperShop\PosTerminalController::class, 'terminal'])->name('admin.supershop.pos');
    Route::get('/pos/barcode', [\App\Http\Controllers\Admin\SuperShop\PosTerminalController::class, 'barcodeSearch'])->name('admin.supershop.pos.barcode');
    Route::get('/pos/search', [\App\Http\Controllers\Admin\SuperShop\PosTerminalController::class, 'searchProducts'])->name('admin.supershop.pos.search');
    Route::get('/pos/customer-lookup', [\App\Http\Controllers\Admin\SuperShop\PosTerminalController::class, 'customerLookup'])->name('admin.supershop.pos.customer_lookup');
    Route::post('/pos/checkout', [\App\Http\Controllers\Admin\SuperShop\PosTerminalController::class, 'checkout'])->name('admin.supershop.pos.checkout');
    Route::get('/pos/receipt/{id}', [\App\Http\Controllers\Admin\SuperShop\PosTerminalController::class, 'receipt'])->name('admin.supershop.pos.receipt');

    // 5. Cashier Shift & Cash Drawer
    Route::get('/shifts', [\App\Http\Controllers\Admin\SuperShop\ShiftManagementController::class, 'index'])->name('admin.supershop.shifts');
    Route::post('/shifts/open', [\App\Http\Controllers\Admin\SuperShop\ShiftManagementController::class, 'openShift'])->name('admin.supershop.shifts.open');
    Route::post('/shifts/close/{id}', [\App\Http\Controllers\Admin\SuperShop\ShiftManagementController::class, 'closeShift'])->name('admin.supershop.shifts.close');
    Route::post('/shifts/drawer-transaction', [\App\Http\Controllers\Admin\SuperShop\ShiftManagementController::class, 'drawerTransaction'])->name('admin.supershop.shifts.drawer_transaction');

    // 6. Central Procurement & Suppliers (PO & GRN)
    Route::get('/procurement', [\App\Http\Controllers\Admin\SuperShop\ProcurementController::class, 'index'])->name('admin.supershop.procurement');
    Route::post('/procurement/suppliers/store', [\App\Http\Controllers\Admin\SuperShop\ProcurementController::class, 'supplierStore'])->name('admin.supershop.procurement.suppliers.store');
    Route::post('/procurement/po/store', [\App\Http\Controllers\Admin\SuperShop\ProcurementController::class, 'poStore'])->name('admin.supershop.procurement.po.store');
    Route::post('/procurement/grn/store', [\App\Http\Controllers\Admin\SuperShop\ProcurementController::class, 'grnStore'])->name('admin.supershop.procurement.grn.store');

    // 7. Branch-to-Branch Stock Transfers
    Route::get('/transfers', [\App\Http\Controllers\Admin\SuperShop\StockTransferController::class, 'index'])->name('admin.supershop.transfers');
    Route::post('/transfers/store', [\App\Http\Controllers\Admin\SuperShop\StockTransferController::class, 'store'])->name('admin.supershop.transfers.store');
    Route::post('/transfers/dispatch/{id}', [\App\Http\Controllers\Admin\SuperShop\StockTransferController::class, 'dispatchTransfer'])->name('admin.supershop.transfers.dispatch');
    Route::post('/transfers/receive/{id}', [\App\Http\Controllers\Admin\SuperShop\StockTransferController::class, 'receiveTransfer'])->name('admin.supershop.transfers.receive');

    // 8. POS Sales & Returns
    Route::get('/sales', [\App\Http\Controllers\Admin\SuperShop\SalesManagementController::class, 'index'])->name('admin.supershop.sales');
    Route::get('/sales/show/{id}', [\App\Http\Controllers\Admin\SuperShop\SalesManagementController::class, 'show'])->name('admin.supershop.sales.show');
    Route::post('/sales/return', [\App\Http\Controllers\Admin\SuperShop\SalesManagementController::class, 'returnStore'])->name('admin.supershop.sales.return');

    // 9. Branch Expenses
    Route::get('/expenses', [\App\Http\Controllers\Admin\SuperShop\ExpenseManagementController::class, 'index'])->name('admin.supershop.expenses');
    Route::post('/expenses/store', [\App\Http\Controllers\Admin\SuperShop\ExpenseManagementController::class, 'store'])->name('admin.supershop.expenses.store');
    Route::match(['get', 'delete'], '/expenses/destroy/{id}', [\App\Http\Controllers\Admin\SuperShop\ExpenseManagementController::class, 'destroy'])->name('admin.supershop.expenses.destroy');

    // 10. Reports & Business Intelligence
    Route::get('/reports', [\App\Http\Controllers\Admin\SuperShop\ReportsController::class, 'index'])->name('admin.supershop.reports');
});
