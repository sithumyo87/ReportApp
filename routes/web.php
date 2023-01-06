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
    Route::resource('quotationDetail.quotationNote', QuotationDetailController::class);

    Route::get('/quotationDetail/{id?}/note/{noteId?}','QuotationDetailController@getNote')->name('quotationDetail.getNote');
    Route::get('quotationDetail/quoId/{quoId}', 'QuotationDetailController@create')->name('quotationDetailCreate');
    Route::post('quotationDetail/quoId/{quoId}', 'QuotationDetailController@store')->name('quotationDetailCreate');



    Route::resource('quotationDetail', QuotationDetailController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('currency', CurrencyController::class);
    Route::resource('dealer', DealerController::class);
    Route::resource('quotationNote', QuotationNotesController::class);
    Route::resource('quotationNote.quotationDetail', QuotationNotesController::class);

    Route::resource('quotationFile',QuotationFileController::class);
});



Route::group(['middleware' => ['auth'], 'prefix' => 'setting', 'namespace' => 'App\Http\Controllers\Setting', 'as' => 'setting.'], function() {
    // Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
});
