<?php

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    
Route::group(['middleware' => ['auth'], 'prefix' => 'OfficeManagement', 'namespace' => 'App\Http\Controllers\OfficeManagement', 'as' => 'OfficeManagement.'], function() {
    Route::resource('quotation', QuotationController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('currency', CurrencyController::class);
});



Route::group(['middleware' => ['auth'], 'prefix' => 'setting', 'namespace' => 'App\Http\Controllers\Setting', 'as' => 'setting.'], function() {
    // Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
});
