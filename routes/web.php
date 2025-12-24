<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\RuangController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\AsetsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LoginauthController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

// AUTH
// Proses Login
Route::get('/', [LoginauthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/loginPros', [LoginauthController::class, 'loginPros'])->name('loginPros');
Route::post('/logout', [LoginauthController::class, 'logout'])->name('logout');

// GET RUANG
Route::get('/get-ruang/{unit_id}', [AsetsController::class, 'getRuang'])->name('get.ruang');

// DOWNLOAD QR
Route::get('/asets/{id}/download-qr-multiple', [AsetsController::class, 'downloadQRMultiple'])
    ->name('downloadQRMultiple');
// Route::get('/asets/{id}/download-qr', [AsetsController::class, 'downloadQR'])->name('downloadQR');

Route::group(['middleware' => ['auth', 'checkRole:admin']], function(){
    Route::prefix('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Route Unit
        Route::get('deleteUnit/{id}', [UnitController::class, 'delete'])->name('delete');
        Route::resource('unit', UnitController::class);

        // Route Ruang
        Route::get('deleteRuang/{id}', [RuangController::class, 'delete'])->name('delete');
        Route::resource('ruang', RuangController::class);
    
        // Route Type
        Route::get('deleteType/{id}', [TypeController::class, 'delete'])->name('delete');
        Route::resource('type', TypeController::class);
    
        // Route Asets
        // Route::get('/get-ruang/{unit_id}', [AsetsController::class, 'getRuang'])->name('get.ruang');
        // Route::get('/asets/{id}/download-qr', [AsetsController::class, 'downloadQR'])->name('downloadQR');
        Route::get('deleteAset/{id}', [AsetsController::class, 'delete'])->name('delete');
        Route::resource('asets', AsetsController::class);

        // Route User
        Route::get('deleteUser/{id}', [UserController::class, 'delete'])->name('delete');
        Route::resource('user', UserController::class);
    });
});

Route::group(['middleware' => ['auth', 'checkRole:sarpra,admin,ks']], function(){
    Route::prefix('assets')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('sarpra.dashboard');

        // Route Unit
        // Route::get('deleteUnit/{id}', [UnitController::class, 'delete'])->name('delete');
        // Route::resource('unit', UnitController::class);
    
        // Route Type
        // Route::get('deleteType/{id}', [TypeController::class, 'delete'])->name('delete');
        // Route::resource('type', TypeController::class);
    
        // Route Asets
        Route::get('deleteAset/{id}', [AsetsController::class, 'delete'])->name('delete');
        Route::resource('asets', AsetsController::class);

        // Route User
        // Route::get('deleteUser/{id}', [UserController::class, 'delete'])->name('delete');
        // Route::resource('user', UserController::class);
    });
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
