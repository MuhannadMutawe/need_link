<?php

use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\dashboard\users\requests\RequestsController;
use App\Http\Controllers\main\MainController;
use Illuminate\Support\Facades\Route;



Route::prefix('main')->name('main.')->controller(MainController::class)->group(function (){
    Route::get('landing-page' , 'showLanding')->name('showLanding');
});

Route::prefix('auth')->name('auth.')->controller(AuthController::class)->group(function () {
    Route::get('/login' , 'indexlogin')->name('login');
    Route::post('/login' , 'login')->name('login.submit');
    Route::get('/register' , 'indexRegister')->name('register');
    Route::post('/register' , 'register')->name('register.submit');
});

Route::prefix('dashbaord')->name('dashbaord.')->group(function(){
    Route::get('/main' , function(){
        return view('dashboard.users.main');
    })->name('main');

    Route::prefix('requests')->name('requests.')->controller(RequestsController::class)->group(function(){
            Route::get('/index' , 'index')->name('index');
    });

});