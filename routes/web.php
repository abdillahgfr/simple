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
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('frontend.profile');

    // Form Aset and Table Aset
    Route::get('/identifikasiaset', [AsetController::class, 'index'])->name('frontend.identifikasiaset');
    Route::get('/formaset', [AsetController::class, 'form'])->name('frontend.formaset');

    Route::get('/asetform', [AsetController::class, 'tambah'])->name('frontend.asetform');

    // Menampilkan halaman validasi
    Route::get('/validasi', [AsetController::class, 'validasi'])->name('frontend.validasiaset');

    // Menampilkan halaman validasi
    Route::get('/identifikasi/{guid_aset}', [AsetController::class, 'identifikasiDetail'])->name('frontend.identikasiasetdetail');

    // Submit validasi via POST
    Route::post('/validasikepala', [AsetController::class, 'validasiKepala'])->name('frontend.asetvalidasi');

    // Dsiplay Aset Idle
    Route::get('/asetidle', [AsetController::class, 'show'])->name('frontend.asetidle');

    // Rincian Aset
    Route::get('/rincian-aset/{guid_aset}', [AsetController::class, 'detail'])->name('frontend.rincianaset');

    // Rincian Aset Submit
    Route::post('/rincian-aset', [AsetController::class, 'permohonan'])->name('frontend.rincianasetsubmit');

    // Proses simpan data aset (POST)
    Route::post('/form-aset', [AsetController::class, 'store'])->name('frontend.formasetstore');

    // Display Permohonan Aset
    Route::get('/permohonan-aset', [AsetController::class, 'permohonanDetail'])->name('frontend.permohonan');
    Route::get('/permohonan-aset/detail/{guid_aset}', [AsetController::class, 'permohonanAset'])->name('frontend.permohonandetail');
    
    Route::post('/permohonan-aset/validasi', [AsetController::class, 'validasiPermohonan'])->name('frontend.permohonanasetvalidasi');

    // Display BMD Dimohon
     Route::get('/permohonan-bmd', [AsetController::class, 'cetakBast'])->name('frontend.bmddimohon');

     // Cetak BAST
     Route::get('/cetak-bast/{kolok}', [AsetController::class, 'cetak'])->name('cetak.bast');

});



