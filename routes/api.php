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

Route::group(['middleware' => ['cors', 'auth:sanctum']], function () {
    Route::post('/user', function(Request $request){
        return $request->user();
    }); 
});

Route::group(['middleware' =>  ['cors', 'auth:sanctum'], 'prefix' => 'office', 'as' => 'office'], function() {
    Route::get('/quotation', [App\Http\Controllers\Api\QuotationController::class, 'index'])->middleware('permission:quotation-index');
    Route::get('/quotation/create', [App\Http\Controllers\Api\QuotationController::class, 'create'])->middleware('permission:quotation-create');
    Route::post('/quotation/store', [App\Http\Controllers\Api\QuotationController::class, 'store'])->middleware('permission:quotation-create');
});

Route::group(['middleware' => ['cors']], function () {
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'loginUser']);
});


