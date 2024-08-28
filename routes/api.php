<?php

use App\Http\Controllers\Api\BookingCongtroller;
use App\Http\Controllers\Api\ClientLoginController;
use App\Http\Controllers\Api\CommonController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\ProfessionalController;
use App\Http\Controllers\Api\ProfessionalLoginController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\UssdApiController;
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

// API for USSD
Route::get('/skill/category', [UssdApiController::class, 'skill_category'])->name('skill.category');
Route::get('/professionals', [UssdApiController::class, 'professionals'])->name('professionals');
Route::get('/service/area', [UssdApiController::class, 'service_area'])->name('service.area');
Route::get('/professional', [UssdApiController::class, 'available'])->name('professional.available');
Route::get('/booking', [UssdApiController::class, 'booking'])->name('professional.booking');

// API for Clients
Route::prefix('client/')->name('client.')->group(function () {

    Route::post('configuration', [ConfigurationController::class, 'client_config'])->name('configuration');

    Route::post('login', [ClientLoginController::class, 'login'])->name('login');
    Route::post('signup', [ClientLoginController::class, 'signup'])->name('signup');

    Route::post('customer-support', [CommonController::class, 'customer_support'])->name('customer.support');

    Route::middleware(['auth:sanctum', 'client'])->group(function () {

        Route::post('details', [ClientLoginController::class, 'details'])->name('details');
        Route::post('logout', [ClientLoginController::class, 'logout'])->name('logout');
        Route::post('change-password', [ClientLoginController::class, 'change_password'])->name('change.password');
        Route::post('edit-profile', [ClientLoginController::class, 'edit_profile'])->name('edit.profile');

        Route::post('cancel-reason', [CommonController::class, 'cancel_reason'])->name('cancel.reason');
        Route::post('cms-page', [CommonController::class, 'cms_page'])->name('cms.page');
        Route::post('refer', [CommonController::class, 'refer'])->name('refer');

        Route::prefix('booking/')->name('booking.')->group(function () {
            Route::post('search', [BookingCongtroller::class, 'search'])->name('search');
            Route::post('checkout', [BookingCongtroller::class, 'checkout'])->name('checkout');
            Route::post('confirm', [BookingCongtroller::class, 'confirm'])->name('confirm');
            Route::post('auto/cancel', [BookingCongtroller::class, 'auto_cancel'])->name('auto.cancel');
            Route::post('status', [BookingCongtroller::class, 'status'])->name('status');
            Route::post('active', [BookingCongtroller::class, 'active'])->name('active');
            Route::post('details', [BookingCongtroller::class, 'details'])->name('details');
            Route::post('cancel', [BookingCongtroller::class, 'cancel'])->name('cancel');
            Route::post('tracking', [BookingCongtroller::class, 'tracking'])->name('tracking');
            Route::post('history', [BookingCongtroller::class, 'history'])->name('history');
            Route::post('rating', [BookingCongtroller::class, 'rating'])->name('rating');
            Route::post('complete', [BookingCongtroller::class, 'complete'])->name('complete');
        });

    });

});

// API for Professionals
Route::prefix('professional/')->name('professional.')->group(function () {

    Route::post('configuration', [ConfigurationController::class, 'professional_config'])->name('configuration');

    Route::post('login', [ProfessionalLoginController::class, 'login'])->name('login');
    Route::post('signup', [ProfessionalLoginController::class, 'signup'])->name('signup');

    Route::middleware(['auth:sanctum', 'professional'])->group(function () {

        Route::post('details', [ProfessionalLoginController::class, 'details'])->name('details');
        Route::post('logout', [ProfessionalLoginController::class, 'logout'])->name('logout');
        Route::post('change-password', [ProfessionalLoginController::class, 'change_password'])->name('change.password');
        Route::post('edit-profile', [ProfessionalLoginController::class, 'edit_profile'])->name('edit.profile');

        Route::post('cancel-reason', [CommonController::class, 'cancel_reason'])->name('cancel.reason');
        Route::post('cms-page', [CommonController::class, 'cms_page'])->name('cms.page');
        Route::post('refer', [CommonController::class, 'refer'])->name('refer');

        Route::prefix('subscription/')->name('subscription.')->group(function () {
            Route::post('packages', [SubscriptionController::class, 'packages'])->name('packages');
            Route::post('add', [SubscriptionController::class, 'add'])->name('add');
            Route::post('history', [SubscriptionController::class, 'history'])->name('history');
        });

        Route::post('location', [ProfessionalController::class, 'location'])->name('location');
        Route::post('online/offline', [ProfessionalController::class, 'online_offline'])->name('online.offline');
        Route::post('topup', [ProfessionalController::class, 'topup'])->name('topup');
        Route::post('wallet/transaction', [ProfessionalController::class, 'wallet_transaction'])->name('wallet.transaction');
        Route::post('earnings/revised', [ProfessionalController::class, 'earnings_revised'])->name('earnings.revised');

        Route::prefix('booking/')->name('booking.')->group(function () {
            Route::prefix('history/')->name('history.')->group(function () {
                Route::post('active', [BookingCongtroller::class, 'history_active'])->name('active');
                Route::post('past', [BookingCongtroller::class, 'history_past'])->name('past');
            });
            Route::post('accept', [BookingCongtroller::class, 'accept'])->name('accept');
            Route::post('auto/cancel', [ProfessionalController::class, 'auto_cancel'])->name('auto.cancel');
            Route::post('cancel', [ProfessionalController::class, 'cancel'])->name('cancel');
            Route::post('cancel-reason', [CommonController::class, 'cancel_reason'])->name('cancel.reason');
            Route::post('start', [BookingCongtroller::class, 'start'])->name('start');
            Route::post('end', [BookingCongtroller::class, 'end'])->name('end');
            Route::post('rating', [BookingCongtroller::class, 'rating'])->name('rating');
            Route::post('complete', [BookingCongtroller::class, 'complete'])->name('complete');
        });

    });

});
