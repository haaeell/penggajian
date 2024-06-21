<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisPotonganGajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PotonganGajiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('karyawan', KaryawanController::class);
    Route::resource('jabatan', JabatanController::class);
    Route::resource('absensi', AbsensiController::class);
    Route::resource('potongan-gaji', PotonganGajiController::class);
    Route::resource('jenis-potongan-gaji', JenisPotonganGajiController::class);
    Route::resource('penggajian', PenggajianController::class);
    Route::resource('laporan', LaporanController::class);

});

