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
Route::get('/api/home-data', [\App\Http\Controllers\FrontendController::class, 'apiHomepageData'])->name('api.home_data');
Route::get('/api/live-search', [\App\Http\Controllers\FrontendController::class, 'apiLiveSearch'])->name('api.live_search');
Route::get('/about', function () {return view('frontend.about-us');})->name('about');
Route::get('/gallery', function () {return view('frontend.gallery');})->name('gallery');
Route::get('/contact', function () {return view('frontend.contact');})->name('contact');
Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.send');
Route::get('/terms', function () {return view('frontend.terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('frontend.privacy');})->name('privacy');

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
    Route::get('/properties', [\App\Http\Controllers\Admin\AdminPropertyManagementController::class, 'properties'])->name('admin.properties.index');
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

});














