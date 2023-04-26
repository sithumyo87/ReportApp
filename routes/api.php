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

    // dashboard
    Route::get('/dashboard', [App\Http\Controllers\Api\DashboardController::class, 'index'])->middleware('permission:dashboard-index');

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
    Route::post('/quotation/note/delete/{id}', [App\Http\Controllers\Api\QuotationController::class, 'note_delete'])->middleware('permission:quotation-edit');
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
    Route::post('/po/tax_check/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'tax_check'])->middleware('permission:quotation-edit');
    // note
    Route::post('/po/note/store/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'note_store'])->middleware('permission:po-create');
    Route::get('/po/note/edit/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'note_edit'])->middleware('permission:po-edit');
    Route::post('/po/note/update/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'note_update'])->middleware('permission:po-edit');
    Route::post('/po/note/delete/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'note_delete'])->middleware('permission:po-edit');
    // signature
    Route::post('/po/sign/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'sign_store'])->middleware('permission:po-edit');
    // confirm
    Route::post('/po/confirm/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'confirm'])->middleware('permission:po-edit');
    ///end po------------------------------------

    //DO start----------------------------------------------
    Route::get('/do', [App\Http\Controllers\Api\DeliveryOrderController::class, 'index'])->middleware('permission:do-index');
    Route::get('/do/create', [App\Http\Controllers\Api\DeliveryOrderController::class, 'create'])->middleware('permission:do-create');
    Route::post('/do/store', [App\Http\Controllers\Api\DeliveryOrderController::class, 'store'])->middleware('permission:do-create');
    Route::get('/do/edit/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'edit'])->middleware('permission:do-edit');
    Route::post('/do/update/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'update'])->middleware('permission:po-edit');
    Route::post('/do/delete/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'delete'])->middleware('permission:do-edit');
    // detail
    Route::get('/do/detail/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail'])->middleware('permission:do-show');
    Route::get('/do/detail/create/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail_create'])->middleware('permission:do-create');
    Route::post('/do/detail/store/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail_store'])->middleware('permission:do-create');
    Route::get('/do/detail/edit/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail_edit'])->middleware('permission:do-edit');
    Route::post('/do/detail/update/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail_update'])->middleware('permission:do-edit');
    Route::post('/do/detail/cancel/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail_cancel'])->middleware('permission:do-delete');
    Route::post('/do/detail/delete/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'detail_delete'])->middleware('permission:do-delete');
    //DO Invoice Quotation Check
    Route::post('/do/quo_inv_check', [App\Http\Controllers\Api\DeliveryOrderController::class, 'quo_inv_check'])->middleware('permission:do-edit');
    //DO Confirm
    Route::post('/do/do_confirm/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'do_confirm'])->middleware('permission:do-edit');
    //DO Delivery Confirm
    Route::post('/do/do_confirm_delivery/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'do_confirm_delivery'])->middleware('permission:do-edit');
    //DO SIgn
    Route::post('/do/do_sign/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'do_sign'])->middleware('permission:do-edit');
    //DO End-------------------------------------------------

    //Invoice Start-----------------------------------------------------
    Route::get('/invoice', [App\Http\Controllers\Api\InvoiceController::class, 'index'])->middleware('permission:invoice-index');
    Route::get('/invoice/create', [App\Http\Controllers\Api\InvoiceController::class, 'create'])->middleware('permission:invoice-create');
    
    Route::get('/invoice/get_quo_data/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'getQuoData'])->middleware('permission:invoice-create');
    Route::post('/invoice/store', [App\Http\Controllers\Api\InvoiceController::class, 'store'])->middleware('permission:invoice-create');
    // detail
    Route::get('/invoice/detail/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'detail'])->middleware('permission:invoice-show');
    Route::get('/invoice/detail/create/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'detail_create'])->middleware('permission:invoice-create');
    Route::post('/invoice/detail/store/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'detail_store'])->middleware('permission:invoice-create');
    Route::get('/invoice/detail/edit/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'detail_edit'])->middleware('permission:invoice-edit');
    Route::post('/invoice/detail/update/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'detail_update'])->middleware('permission:invoice-edit');
    Route::post('/invoice/detail/delete/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'detail_delete'])->middleware('permission:invoice-delete');
    // note
    Route::post('/invoice/note/store/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'note_store'])->middleware('permission:invoice-create');
    Route::get('/invoice/note/edit/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'note_edit'])->middleware('permission:invoice-edit');
    Route::post('/invoice/note/update/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'note_update'])->middleware('permission:invoice-edit');
    Route::post('/invoice/note/delete/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'note_delete'])->middleware('permission:invoice-edit');
    //Tax Check
    Route::post('/invoice/tax_check/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'tax_check'])->middleware('permission:invoice-edit');
    //Bank Check
    Route::post('/invoice/bank_check/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'bank_check'])->middleware('permission:invoice-edit');
    //Invoice Discount
    Route::post('/invoice/discount/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'discount'])->middleware('permission:invoice-edit');
    // signature
    Route::post('/invoice/sign/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'sign_store'])->middleware('permission:invoice-edit');
    // confirm
    Route::post('/invoice/confirm/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'confirm'])->middleware('permission:invoice-edit');
    // quotation Attn On change
    Route::post('/invoice/quoAttnChg/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'quo_attn_on_change'])->middleware('permission:invoice-edit');
    //getInvoice
    Route::post('/invoice/getInvoice/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'get_invoice'])->middleware('permission:invoice-edit');
    //Invoice End---------------------------------------------------------

    //Receipt Start--------------------------------------------------------
    Route::get('/receipt', [App\Http\Controllers\Api\ReceiptController::class, 'index'])->middleware('permission:receipt-index');
    Route::get('/receipt/create', [App\Http\Controllers\Api\ReceiptController::class, 'create'])->middleware('permission:receipt-create');
    Route::post('/receipt/store', [App\Http\Controllers\Api\ReceiptController::class, 'store'])->middleware('permission:receipt-create');
    Route::get('/receipt/edit/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'edit'])->middleware('permission:receipt-edit');
    Route::post('/receipt/update/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'update'])->middleware('permission:receipt-edit');
    //detail
    Route::get('/receipt/detail/{id}/{type?}', [App\Http\Controllers\Api\ReceiptController::class, 'detail'])->middleware('permission:receipt-show');
     // get_data_from_quo_name
     Route::get('/receipt/getDataFromQuoName/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'get_data_from_quo_name'])->middleware('permission:receipt-edit');
     // signature
    Route::post('/receipt/sign/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'sign_store'])->middleware('permission:receipt-edit');
     // Invoice Attn On change
     Route::post('/receipt/invAttnChg/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'inv_attn_on_chg'])->middleware('permission:receipt-edit');
     //Receive
     Route::post('/receipt/receive/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'receive'])->middleware('permission:receipt-edit');
     //getReceipt
    Route::post('/receipt/getReceipt/{id}', [App\Http\Controllers\Api\ReceiptController::class, 'get_receipt'])->middleware('permission:receipt-edit');
    //Receipt End----------------------------------------------------------
});

Route::group(['middleware' => ['cors']], function () {
    Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'loginUser']);
});

// print
Route::get('/office/quotation/print/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'print']);
Route::get('/office/po/print/{id}', [App\Http\Controllers\Api\PurchasingOrderController::class, 'print']);
Route::get('/office/invoice/print/{id}', [App\Http\Controllers\Api\InvoiceController::class, 'print']);
Route::get('/office/receipt/print/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'print']);
Route::get('/office/do/print/{id}', [App\Http\Controllers\Api\DeliveryOrderController::class, 'print']);


