<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\dashboard\users\requests\RequestsController;
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

    Route::prefix('requests')->name('requests.')->controller(RequestsController::class)->group(function(){
            Route::get('/index' , 'index')->name('index');
    });

    // Admin Categories Routes
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);

});