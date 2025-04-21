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
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PosManagementController;

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
Route::get('/user', [UserController::class, 'index'])->name('user.dashboard')->middleware('auth');


Route::get('/profile', 'ProfileController@index')->name('profile');
Route::put('/profile', 'ProfileController@update')->name('profile.update');
Route::get('/basic/{id}/edit', [BasicController::class, 'edit'])->name('basic.edit');
Route::put('/basic/{id}', [BasicController::class, 'update'])->name('basic.update');
Route::put('/setting/{id_setting}', 'SettingController@update')->name('setting.update');
Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan.index');
Route::post('/member/payment', [MemberController::class, 'createPayment'])->name('member.payment');
Route::get('/member/success', [MemberController::class, 'paymentSuccess'])->name('member.success');
Route::post('/midtrans/callback', [MemberController::class, 'handleNotification']);
Route::get('/outlet/{id}', [UserController::class, 'showOutlet'])->name('outlet.show');
Route::get('/pos', [PosManagementController::class, 'index'])->name('pos.index');
Route::post('/pos', [PosManagementController::class, 'store'])->name('pos.store');
Route::get('/pos/{id}/edit', [PosManagementController::class, 'edit'])->name('pos.edit');
Route::put('/pos/{id}', [PosManagementController::class, 'update'])->name('pos.update');
Route::delete('/pos/{id}', [PosManagementController::class, 'destroy'])->name('pos.destroy');

// Route::get('/outlet', [OutletController::class, 'index'])->name('outlet.index');

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
    Route::resource('layanan', LayananController::class);
    Route::resource('outlet', OutletController::class);
    Route::resource('admin', AdminController::class);
    Route::resource('supervisor', SupervisorController::class);
    Route::resource('pegawai', PegawaiController::class);
    Route::resource('setting', SettingController::class);
    Route::resource('member', MemberController::class);
    Route::resource('pos', PosManagementController::class);
});
