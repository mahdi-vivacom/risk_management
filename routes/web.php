<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CancelReasonController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CmsPageController;
use App\Http\Controllers\CommissionController;
use App\Http\Controllers\CountryAreaController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LikelihoodImpactVulnerabilityController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\ProfessionalController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\RiskLevelController;
use App\Http\Controllers\ScrapeTargetController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SosController;
use App\Http\Controllers\SosRequestController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ThreatScenarioController;
use App\Http\Controllers\TopUpController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserAccessControl\PermissionController;
use App\Http\Controllers\UserAccessControl\ProfileController;
use App\Http\Controllers\UserAccessControl\RoleController;
use App\Http\Controllers\UserAccessControl\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';

Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return redirect()->back()->with('success', "Cache is cleared");
});

Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : view('frontend.auth.login');
});

Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware(['auth', 'ensure_password_reset', 'can:dashboard']);

// .............. Profile ...............
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware(['auth', 'can:profile.edit']);
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware(['auth', 'can:profile.update']);

// .............. Phone Validation ...............
Route::get('/phone-validation', [HomeController::class, 'phone_validation'])->name('phone.validation');
Route::get('/get-country-info/{id}', [CountryController::class, 'getCountryInfo'])->name('get.country.info');
Route::get('/get-country-area/{id}', [CountryAreaController::class, 'getCountryArea'])->name('get.country.area');

// .............. Multi Language Translation ...............
Route::get('/changeLanguage', [LanguageController::class, 'changeLanguage'])->name('lang.change');

Route::middleware(['auth', 'ensure_password_reset', 'permission'])->group(function () {

    // .............. User Access Control ...............
    Route::resource('users', UserController::class);
    Route::get('users-status', [UserController::class, 'status'])->name('users.status');
    Route::prefix('users/')->name('users.permission.')->group(function () {
        Route::get('permission/{id}', [UserController::class, 'edit_permission'])->name('edit');
        Route::post('permission/{id}', [UserController::class, 'update_permission'])->name('update');
    });
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);

    // .............. Menu ...............
    Route::resource('menus', MenuController::class);
    Route::get('menus-status', [MenuController::class, 'status'])->name('menus.status');

    // .............. Country ...............
    Route::resource('countries', CountryController::class);
    // .............. Country Area ...............
    Route::resource('country-areas', CountryAreaController::class);

    // Route::resource('skills', SkillController::class);
    // Route::resource('documents', DocumentController::class);
    // Route::resource('top-ups', TopUpController::class);
    // Route::resource('commissions', CommissionController::class);
    // Route::resource('subscriptions', SubscriptionController::class);
    // Route::get('subscriptions-history', [SubscriptionController::class, 'history'])->name('subscriptions.history');
    // Route::resource('transactions', TransactionController::class);
    // Route::resource('ratings', RatingController::class);
    // Route::resource('bookings', BookingController::class);
    // Route::resource('clients', ClientController::class);
    // Route::get('clients-status', [ClientController::class, 'status'])->name('clients.status');
    // Route::resource('professionals', ProfessionalController::class);
    // Route::get('professionals-wallet-recharge', [ProfessionalController::class, 'wallet_recharge'])->name('professionals.wallet.recharge');
    // Route::get('professionals-status', [ProfessionalController::class, 'status'])->name('professionals.status');
    // Route::resource('navigations', NavigationController::class);
    // Route::resource('cancel-reasons', CancelReasonController::class);
    // Route::resource('customer-supports', CustomerSupportController::class);
    Route::get('professional-map', [MapController::class, 'map'])->name('map');
    Route::get('heat-map', [MapController::class, 'heat_map'])->name('heat.map');
    // Route::resource('cms-pages', CmsPageController::class);
    // Route::resource('sos-numbers', SosController::class);
    // Route::resource('sos-requests', SosRequestController::class);
    Route::resource('scrape-targets', ScrapeTargetController::class);
    Route::resource('risk-levels', RiskLevelController::class);
    Route::resource('threat-scenarios', ThreatScenarioController::class);
    Route::resource('likelihoods', LikelihoodImpactVulnerabilityController::class);
    Route::resource('impacts', LikelihoodImpactVulnerabilityController::class);
    Route::resource('vulnerabilities', LikelihoodImpactVulnerabilityController::class);


});
