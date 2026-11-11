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

// Mobile App REST API Endpoints
Route::get('/home-data', [\App\Http\Controllers\FrontendController::class, 'apiHomepageData'])->name('api.mobile.home_data');
Route::get('/live-search', [\App\Http\Controllers\FrontendController::class, 'apiLiveSearch'])->name('api.mobile.live_search');
Route::post('/contact/send', [\App\Http\Controllers\ContactController::class, 'store'])->name('api.mobile.contact_send');
Route::get('/career/info', [\App\Http\Controllers\CareerController::class, 'apiCareerInfo'])->name('api.mobile.career_info');
Route::post('/career/apply', [\App\Http\Controllers\CareerController::class, 'apiApply'])->name('api.mobile.career_apply');


