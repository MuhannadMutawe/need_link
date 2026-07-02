<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\RequestsController;
use App\Http\Controllers\dashboard\OffersController;
use App\Http\Controllers\dashboard\AdminCategoryController;
use App\Http\Controllers\dashboard\AdminDisputesController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

// Main Routes
Route::get('/', [MainController::class, 'browseRequests'])->name('main.requests');
Route::get('landing-page', [MainController::class, 'index'])->name('main.showLanding');

// Auth Routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'login')->name('login.submit');
        Route::post('logout', 'logout')->name('logout');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'index')->name('register');
        Route::post('register', 'register')->name('register.submit');
    });
});

// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('main', [DashboardController::class, 'index'])->name('main');

    // User Requests Routes
    Route::prefix('requests')->name('requests.')->controller(RequestsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{serviceRequest}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::put('{serviceRequest}', 'update')->name('update');
        Route::delete('{serviceRequest}', 'destroy')->name('destroy');
    });

    // Admin Categories Routes
    Route::prefix('categories')->name('categories.')->controller(AdminCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{category}', 'update')->name('update');
        Route::delete('{category}', 'destroy')->name('destroy');
    });

    // Admin Disputes Routes
    Route::prefix('disputes')->name('disputes.')->controller(AdminDisputesController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('{dispute}/resolve', 'resolve')->name('resolve');
    });

    // User Offers Routes
    Route::prefix('offers')->name('offers.')->controller(OffersController::class)->group(function () {
        Route::get('my-offers', 'myOffers')->name('myOffers');
        Route::get('requests/{serviceRequest}', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{offer}', 'update')->name('update');
        Route::delete('{offer}', 'destroy')->name('destroy');
    });

    // User Orders Routes
    Route::prefix('orders')->name('orders.')->controller(\App\Http\Controllers\dashboard\OrdersController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{order}', 'show')->name('show');
    });

    // User Order Actions
    Route::prefix('orders/{order}')->name('orders.actions.')->controller(\App\Http\Controllers\dashboard\OrderActionController::class)->group(function () {
        // Service order actions
        Route::post('delivery', 'submitDelivery')->name('delivery');
        Route::post('request-completion', 'requestCompletion')->name('requestCompletion');
        Route::post('request-completion/{completionRequest}/respond', 'respondCompletion')->name('requestCompletion.respond');
        Route::post('request-revision', 'requestRevision')->name('requestRevision');

        // Escape hatches (both order types)
        Route::post('cancellation', 'requestCancellation')->name('cancellation');
        Route::post('cancellation/{cancellationRequest}/respond', 'respondCancellation')->name('cancellation.respond');
        Route::post('dispute', 'openDispute')->name('dispute');
        Route::post('dispute/respond', 'respondDispute')->name('dispute.respond');
    });

    // Accept, Reject, Reset Offers (POST)
    Route::post('service-requests/{serviceRequest}/offers/{offer}/accept', [OffersController::class, 'accept'])->name('requests.offers.accept');
    Route::post('service-requests/{serviceRequest}/offers/{offer}/reject', [OffersController::class, 'reject'])->name('requests.offers.reject');
    Route::post('service-requests/{serviceRequest}/offers/{offer}/reset', [OffersController::class, 'reset'])->name('requests.offers.reset');
});