<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

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
    if(auth()->user() == null) {
        return redirect()->route('login');
    } else {
        return redirect()->route('table');
    }
});
Route::controller(AuthController::class)->group(function() {
    Route::middleware('guest')->group(function() {
        Route::get('/login', 'create')->name('login');
        Route::post('/login', 'store');
    });
    Route::middleware('auth')->group(function() {
        Route::post('/logout', 'logout');
    });
});
Route::middleware('auth','can:check-worker')->group(function() {
    Route::get('/table', [HomeController::class, 'getTable'])->name('table');
    Route::post('/update-data', [HomeController::class, 'updateData']);
});


