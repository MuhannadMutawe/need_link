<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\dashboard\DashboardController;
use App\Http\Controllers\dashboard\users\requests\RequestsController;
use App\Http\Controllers\dashboard\users\offers\OffersController;
use App\Http\Controllers\dashboard\admin\CategoryController;
use App\Http\Controllers\main\MainController;
use Illuminate\Support\Facades\Route;

// Main Routes
Route::prefix('main')->name('main.')->controller(MainController::class)->group(function () {
    Route::get('landing-page', 'index')->name('showLanding');
    Route::get('requests', 'browseRequests')->name('requests');
});

// Auth Routes
Route::prefix('auth')->name('auth.')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('login', 'index')->name('login');
        Route::post('login', 'login')->name('login.submit');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('register', 'index')->name('register');
        Route::post('register', 'register')->name('register.submit');
    });

    Route::POST('logout', [LoginController::class, 'logout'])
        ->name('logout');
});

// Dashboard Routes
Route::prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('main', [DashboardController::class, 'index'])->name('main');

    // User Requests Routes
    Route::prefix('requests')->name('requests.')->controller(RequestsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('{serviceRequest}', 'show')->name('show');
        Route::post('/', 'store')->name('store');
        Route::put('{serviceRequest}', 'update')->name('update');
        Route::delete('{serviceRequest}', 'destroy')->name('destroy');
    });

    // Admin Categories Routes
    Route::prefix('categories')->name('categories.')->controller(CategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{category}', 'update')->name('update');
        Route::delete('{category}', 'destroy')->name('destroy');
    });

    // User Offers Routes
    Route::prefix('offers')->name('offers.')->controller(OffersController::class)->group(function () {
        Route::get('my-offers', 'myOffers')->name('myOffers');
        Route::get('requests/{serviceRequest}', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('{offer}', 'update')->name('update');
        Route::delete('{offer}', 'destroy')->name('destroy');
    });
});