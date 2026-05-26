<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\dashboard\users\requests\RequestsController;
use App\Http\Controllers\dashboard\users\offers\OffersController;
use App\Http\Controllers\dashboard\admin\CategoryController;
use App\Http\Controllers\main\MainController;
use Illuminate\Support\Facades\Route;



Route::prefix('main')->name('main.')->controller(MainController::class)->group(function (){
    Route::get('landing-page' , 'showLanding')->name('showLanding');
});

Route::prefix('auth')->name('auth.')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login' , 'indexlogin')->name('login');
        Route::post('/login' , 'login')->name('login.submit');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register' , 'indexRegister')->name('register');
        Route::post('/register' , 'register')->name('register.submit');
    });
});

Route::prefix('dashboard')->name('dashboard.')->group(function(){
    Route::get('/main' , function(){
        return view('dashboard.users.main');
    })->name('main');

    // User Requests Routes
    Route::get('requests', [RequestsController::class, 'index'])
        ->name('requests.index');
    Route::post('requests', [RequestsController::class, 'store'])
        ->name('requests.store');
    Route::put('requests/{serviceRequest}', [RequestsController::class, 'update'])
        ->name('requests.update');
    Route::delete('requests/{serviceRequest}', [RequestsController::class, 'destroy'])
        ->name('requests.destroy');

        
        // Admin Categories Routes
    Route::get('categories', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

});
// User Offers Routes
Route::get('requests/{serviceRequest}/offers', [OffersController::class, 'index'])
    ->name('offers.index');
Route::get('users/{user}/offers', [OffersController::class, 'userOffers'])
    ->name('offers.user');
Route::post('offers', [OffersController::class, 'store'])
    ->name('offers.store');
Route::put('offers/{offer}', [OffersController::class, 'update'])
    ->name('offers.update');
Route::delete('offers/{offer}', [OffersController::class, 'destroy'])
    ->name('offers.destroy');