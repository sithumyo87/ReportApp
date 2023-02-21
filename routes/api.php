<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' =>  ['cors', 'auth:sanctum'], 'prefix' => 'office', 'as' => 'office'], function() {

    // quotation start ---------------------------
    Route::get('/quotation', [App\Http\Controllers\Api\QuotationController::class, 'index'])->middleware('permission:quotation-index');
    Route::get('/quotation/create', [App\Http\Controllers\Api\QuotationController::class, 'create'])->middleware('permission:quotation-create');
    Route::post('/quotation/store', [App\Http\Controllers\Api\QuotationController::class, 'store'])->middleware('permission:quotation-create');
    Route::get('/quotation/edit/{id}', [App\Http\Controllers\Api\QuotationController::class, 'edit'])->middleware('permission:quotation-edit');
    Route::post('/quotation/update/{id}', [App\Http\Controllers\Api\QuotationController::class, 'update'])->middleware('permission:quotation-edit');
    // detail
    Route::get('/quotation/detail/{id}', [App\Http\Controllers\Api\QuotationController::class, 'detail'])->middleware('permission:quotation-show');
    Route::get('/quotation/detail/create/{id}', [App\Http\Controllers\Api\QuotationController::class, 'detail_create'])->middleware('permission:quotation-create');
    Route::post('/quotation/detail/store/{id}', [App\Http\Controllers\Api\QuotationController::class, 'detail_store'])->middleware('permission:quotation-create');
    Route::get('/quotation/detail/edit/{id}', [App\Http\Controllers\Api\QuotationController::class, 'detail_edit'])->middleware('permission:quotation-edit');
    Route::post('/quotation/detail/update/{id}', [App\Http\Controllers\Api\QuotationController::class, 'detail_update'])->middleware('permission:quotation-edit');
    Route::post('/quotation/detail/delete/{id}', [App\Http\Controllers\Api\QuotationController::class, 'detail_delete'])->middleware('permission:quotation-delete');
    Route::post('/quotation/tax_check/{id}', [App\Http\Controllers\Api\QuotationController::class, 'tax_check'])->middleware('permission:quotation-edit');
    // note
    Route::post('/quotation/note/store/{id}', [App\Http\Controllers\Api\QuotationController::class, 'note_store'])->middleware('permission:quotation-create');
    Route::get('/quotation/note/edit/{id}', [App\Http\Controllers\Api\QuotationController::class, 'note_edit'])->middleware('permission:quotation-edit');
    Route::post('/quotation/note/update/{id}', [App\Http\Controllers\Api\QuotationController::class, 'note_update'])->middleware('permission:quotation-edit');
    // file
    Route::post('/quotation/file/{id}', [App\Http\Controllers\Api\QuotationController::class, 'file_store'])->middleware('permission:quotation-edit');
    Route::post('/quotation/file/delete/{id}', [App\Http\Controllers\Api\QuotationController::class, 'file_delete'])->middleware('permission:quotation-edit');
    // signature
    Route::post('/quotation/sign/{id}', [App\Http\Controllers\Api\QuotationController::class, 'sign_store'])->middleware('permission:quotation-edit');
    // confirm
    Route::post('/quotation/confirm/{id}', [App\Http\Controllers\Api\QuotationController::class, 'confirm'])->middleware('permission:quotation-edit');
    // quotation end ---------------------------


    //po start -----------------------------------------
    Route::get('/po', [App\Http\Controllers\Api\PurchasingOrderController::class, 'index'])->middleware('permission:po-index');
    Route::get('/po/create', [App\Http\Controllers\Api\PurchasingOrderController::class, 'create'])->middleware('permission:po-create');
    Route::post('/po/store', [App\Http\Controllers\Api\PurchasingOrderController::class, 'store'])->middleware('permission:po-create');
    Route::get('/po/edit/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'edit'])->middleware('permission:po-edit');
    Route::post('/po/update/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'update'])->middleware('permission:po-edit');
    // detail
    Route::get('/po/detail/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'detail'])->middleware('permission:po-show');
    Route::get('/po/detail/create/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'detail_create'])->middleware('permission:po-create');
    Route::post('/po/detail/store/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'detail_store'])->middleware('permission:po-create');
    Route::get('/po/detail/edit/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'detail_edit'])->middleware('permission:po-edit');
    Route::post('/po/detail/update/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'detail_update'])->middleware('permission:po-edit');
    Route::post('/po/detail/delete/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'detail_delete'])->middleware('permission:po-delete');
});

Route::group(['middleware' => ['cors']], function () {
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'loginUser']);
});


