<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JenisPotonganGajiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggajianController;
use App\Http\Controllers\PotonganGajiController;
use App\Http\Controllers\UserController;
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
    return redirect('/login');
});

Auth::routes();


Route::middleware('auth')->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('karyawan', KaryawanController::class);
    Route::get('get-karyawan', [KaryawanController::class, 'getKaryawan'])->name('getKaryawan');
    Route::resource('jabatans', JabatanController::class);
    Route::get('get-jabatans', [JabatanController::class, 'getJabatans'])->name('getJabatans');
    Route::resource('absensi', AbsensiController::class);
    Route::resource('potongan-gaji', PotonganGajiController::class);
    Route::get('get-potongan', [PotonganGajiController::class, 'getPotongan'])->name('getPotongan');
    Route::resource('jenis-potongan-gaji', JenisPotonganGajiController::class);
    Route::get('get-jenis', [JenisPotonganGajiController::class, 'getJenis'])->name('getJenis');
    Route::resource('penggajian', PenggajianController::class);
    Route::resource('laporan', LaporanController::class);
    Route::resource('users', UserController::class);
    Route::get('get-users', [UserController::class, 'getUsers'])->name('getUsers');
    
});
