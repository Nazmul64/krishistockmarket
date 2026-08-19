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

Route::get('/', function () { return view('frontend.index');})->name('/');
Route::get('/about', function () {return view('frontend.about-us');})->name('about');
Route::get('/gallery', function () {return view('frontend.gallery');})->name('gallery');
Route::get('/contact', function () {return view('frontend.contact');})->name('contact');
Route::post('/contact/send', [ContactController::class, 'store'])->name('contact.send');
Route::get('/terms', function () {return view('frontend.terms');})->name('terms');
Route::get('/privacy-policy', function () { return view('frontend.privacy');})->name('privacy');

Auth::routes();
// Dedicated Role Login Routes
Route::get('/admin/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
Route::get('/supplier/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('supplier.login');
Route::get('/agent/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('agent.login');
Route::get('/employee/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('employee.login');
Route::get('/user/register', [RegisterController::class, 'showRegistrationForm'])->name('register.referlink');
Route::post('/user/register', [RegisterController::class, 'registerPost'])->name('register.post');

// Supplier Self Registration Routes
Route::get('/supplier/register', [RegisterController::class, 'showSupplierRegistrationForm'])->name('supplier.register');
Route::post('/supplier/register', [RegisterController::class, 'supplierRegisterPost'])->name('supplier.register.post');




// Common Route
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::resource('profile', 'App\Http\Controllers\ProfileController');
Route::post('/profile/change', [ProfileController::class, 'changePasswordPost'])->name('profile.password.change');


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














