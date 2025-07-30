<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\AsetController;
use App\Http\Controllers\Frontend\ProfileController;

use App\Http\Controllers\Backend\LoginController;
use Illuminate\Support\Facades\Artisan;


Route::get('/c', function () {
    Artisan::call('optimize:clear');
    return view('Backend.cache');
})->name('clear.cache');

//Login and Logout
Route::get('/login', [LoginController::class, 'showForm'])->name('login');
Route::post('/login', [LoginController::class, 'loginApi'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth.session'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('frontend.dashboard');
    Route::get('/identifikasiaset', [AsetController::class, 'index'])->name('frontend.identifikasiaset');
    Route::get('/profile', [ProfileController::class, 'index'])->name('frontend.profile');
});



