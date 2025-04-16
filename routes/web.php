<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BasicController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\MasukController;
use App\Http\Controllers\KeluarController;
use App\Http\Controllers\ServiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');
Route::get('/basic/{id}/edit', [BasicController::class, 'edit'])->name('basic.edit');
Route::put('/basic/{id}', [BasicController::class, 'update'])->name('basic.update');
Route::put('/setting/{id_setting}', 'SettingController@update')->name('setting.update');
Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
// Route::get('/outlet', [OutletController::class, 'index'])->name('outlet.index');
Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');
Route::get('/satuan/{kode_satuan}/edit', [SatuanController::class, 'getSatuan']);
Route::get('satuan/{kode_satuan}/edit', [SatuanController::class, 'edit']);
Route::get('/satuan/invoice', [SatuanController::class, 'invoice'])->name('satuan.invoice');
Route::get('/alat', [AlatController::class, 'index'])->name('alat.index');
Route::get('/alat/{kode_alat}/edit', [AlatController::class, 'getAlat']);
Route::get('alat/{kode_alat}/edit', [AlatController::class, 'edit']);
Route::get('/alat/invoice', [AlatController::class, 'invoice'])->name('alat.invoice');
Route::get('/brand/invoice', [BrandController::class, 'invoice'])->name('brand.invoice');
Route::put('/brand/{kode_brand}', [BrandController::class, 'update'])->name('brand.update');
Route::get('/brand/{kode_brand}/edit', [BrandController::class, 'edit']);
Route::get('/brand/{kode_brand}/detail', [BrandController::class, 'detail'])->name('brand.detail');Route::get('/sparepart', [SparepartController::class, 'index'])->name('sparepart.index');
Route::get('/sparepart/{kode_sparepart}/edit', [SparepartController::class, 'getSparepart']);
Route::get('sparepart/{kode_sparepart}/edit', [SparepartController::class, 'edit']);
Route::get('/sparepart/invoice', [SparepartController::class, 'invoice'])->name('sparepart.invoice');
Route::get('/barang_masuk', [MasukController::class, 'index'])->name('barang_masuk.index');
Route::post('/barang_masuk', [MasukController::class, 'store'])->name('barang_masuk.store'); 
Route::get('/barang_masuk/{kode_masuk}/edit', [MasukController::class, 'edit']); 
Route::put('/barang_masuk/{kode_masuk}', [MasukController::class, 'update'])->name('barang_masuk.update');
Route::get('/barang_masuk/invoice', [MasukController::class, 'invoice'])->name('barang_masuk.invoice');
Route::get('/barang_keluar', [KeluarController::class, 'index'])->name('barang_keluar.index');
Route::post('/barang_keluar', [KeluarController::class, 'store'])->name('barang_keluar.store'); 
Route::get('/barang_keluar/{kode_keluar}/edit', [KeluarController::class, 'edit']); 
Route::put('/barang_keluar/{kode_keluar}', [KeluarController::class, 'update'])->name('barang_keluar.update');
Route::delete('/barang_keluar/{kode_keluar}', [KeluarController::class, 'destroy'])->name('barang_keluar.destroy');
Route::get('/barang_keluar/invoice', [KeluarController::class, 'invoice'])->name('barang_keluar.invoice');
Route::put('/shift/{kode_shift}', [ShiftController::class, 'update'])->name('shift.update');
Route::get('/shift/{kode_shift}/edit', [ShiftController::class, 'edit']);
Route::get('/shift/{kode_shift}/get', [ShiftController::class, 'getShift']);
Route::get('/shift/invoice', [ShiftController::class, 'invoice'])->name('shift.invoice');
Route::get('/service/{kode_service}/edit', [ServiceController::class, 'edit']);
Route::put('/service/{kode_service}', [ServiceController::class, 'update'])->name('service.update');
Route::get('/service/invoice', [ServiceController::class, 'invoice'])->name('service.invoice');Route::put('/service/{kode_service}', [ServiceController::class, 'update']);
Route::get('/riwayat-service', [ServiceController::class, 'riwayat'])->name('service.riwayat');


// Route::get('/shift', [ShiftController::class, 'index'])->name('shift.index');
// Route::get('/shift/create', [ShiftController::class, 'index'])->name('shift.create');
// Route::get('/shift/invoice', [ShiftController::class, 'invoice'])->name('shift.invoice');




Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/blank', function () {
    return view('blank');
})->name('blank');

Route::middleware('auth')->group(function() {
    Route::resource('basic', BasicController::class);
    Route::resource('satuan', SatuanController::class);
    Route::resource('layanan', LayananController::class);
    Route::resource('outlet', OutletController::class);
    Route::resource('admin', AdminController::class);
    Route::resource('supervisor', SupervisorController::class);
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('setting', SettingController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('sparepart', SparepartController::class);
    Route::resource('shift', ShiftController::class);
    Route::resource('barang_masuk', MasukController::class);
    Route::resource('barang_keluar', KeluarController::class);
    Route::resource('service', ServiceController::class);
    // Route::resource('shift', ShiftController::class);
});
