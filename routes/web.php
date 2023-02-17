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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth'], 'prefix' => 'OfficeManagement', 'namespace' => 'App\Http\Controllers\OfficeManagement', 'as' => 'OfficeManagement.'], function() {
    // customers ----------------------------------------
    Route::resource('customer', CustomerController::class);
    Route::get('/attnOnChange','CustomerController@attnOnChange');

    // currency ----------------------------------------
    Route::resource('currency', CurrencyController::class);

    // bank info ----------------------------------------
    Route::resource('bankInfo', BankInfoController::class);
    Route::get('bankInfoDetailCreate/{bankInfo}', 'BankInfoDetailController@create')->name('bankInfoDetail.create');
    Route::resource('bankInfoDetail', BankInfoDetailController::class, ['only' => ['store', 'edit', 'update', 'destroy']]);

    // dealer ----------------------------------------
    Route::resource('dealer', DealerController::class);

    // quotation ----------------------------------------
    Route::resource('quotation', QuotationController::class);
    Route::get('/quoTaxCheck','QuotationController@quoTaxCheck');
    // quotation detail
    Route::resource('quotationDetail', QuotationDetailController::class);
    Route::get('quotationDetailCreate/{quoId}', 'QuotationDetailController@create')->name('quotationDetailCreate');
    Route::post('quotationDetailCreate/{quoId}', 'QuotationDetailController@store')->name('quotationDetailCreate');
    // quotation note
    Route::resource('quotationNote', QuotationNotesController::class);
    Route::resource('quotationNote.quotationDetail', QuotationNotesController::class);
    Route::resource('quotationDetail.quotationNote', QuotationDetailController::class);
    Route::get('/quotationDetail/{id?}/note/{noteId?}','QuotationDetailController@getNote')->name('quotationDetail.getNote');
    // quotation file
    Route::resource('quotationFile', QuotationFileController::class);
    // quotation sign
    Route::post('/quotationAuthorizer/{id}','QuotationDetailController@quotationAuthorizer')->name('quotationAuthorizer');
    // quotation confirm
    Route::get('/quotationConfirm/{id}','QuotationDetailController@quotationConfirm')->name('quotationConfirm');
    // quotation print
    Route::get('/quotationPrint/{id}','QuotationController@print')->name('quotationPrint');
    
    // Invoice ----------------------------------------
    Route::resource('invoice', InvoiceController::class);
    Route::get('/quoAttnOnChange', 'InvoiceController@quoAttnOnChange');
    
    // Invoice Detail
    Route::resource('invoiceDetail', InvoiceDetailController::class,  ['only' => ['store', 'save', 'edit', 'update', 'destroy']]);
    Route::get('invoiceDetail/{invoiceDetail}/{type?}', 'InvoiceDetailController@show')->name('invoiceDetail.show');
    Route::get('invoiceDetailCreate/{invId}', 'InvoiceDetailController@invoiceDetailCreate')->name('invoiceDetailCreate');
    
    Route::get('invTaxCheck', 'InvoiceDetailController@invTaxCheck');
    Route::get('invBankCheck', 'InvoiceDetailController@invBankCheck');
    Route::post('invoiceDiscount/{id}', 'InvoiceDetailController@invoiceDiscount')->name('invoiceDiscount');
    // Invoice Note
    Route::resource('invoiceNote', InvoiceNoteController::class, ['only' => ['store', 'destroy']]);
    // Invoice Sign
    Route::post('invoiceAuthorizer/{id}', 'InvoiceDetailController@invoiceAuthorizer')->name('invoiceAuthorizer');
    // Invoice confirm
    Route::get('/invoiceConfirm/{id}','InvoiceDetailController@invoiceConfirm')->name('invoiceConfirm');
    // Invoice getInv
    Route::post('/getInvoice/{id}', 'InvoiceDetailController@getInvoice')->name('invoiceDetail.getInvoice');
    Route::get('/invoicePrint/{id}/{type?}', 'InvoiceController@invoicePrint')->name('invoicePrint');

    //Receipt ----------------------------------------
    Route::resource('receipt', ReceiptController::class);
    Route::get('receiptDetail/{receiptDetail}/{type?}', 'ReceiptDetailController@show')->name('receiptDetail.show');
    Route::resource('receiptDetail', ReceiptDetailController::class,['only' => ['store', 'save', 'edit', 'update', 'destroy']]);
    Route::post('receiptAuthorizer/{id}', 'ReceiptController@receiptAuthorizer')->name('receiptAuthorizer');
    Route::post('/getReceipt/{id}', 'ReceiptDetailController@getReceipt')->name('receiptDetail.getReceipt');
    Route::get('invAttnOnChange', 'ReceiptController@invAttnOnChange');
    Route::get('/receiptPrint/{id}/{type?}', 'ReceiptController@receiptPrint')->name('receiptPrint');

    // Purchasing Order
    Route::resource('purchasingOrder', PurchasingOrderController::class);
    Route::resource('purchasingOrderDetail', PurchasingOrderDetailController::class, ['except' => 'index']);
    Route::get('poTaxCheck', 'PurchasingOrderController@poTaxCheck');
    Route::post('poAuthorizer/{id}', 'PurchasingOrderController@poAuthorizer')->name('poAuthorizer');
    Route::resource('purchasingOrderNote', PurchasingOrderNoteController::class, ['only' => ['store', 'destroy']]);
    Route::get('/poConfirm/{id}','PurchasingOrderController@poConfirm')->name('poConfirm');
    Route::get('/poPrint/{id}', 'PurchasingOrderController@poPrint')->name('poPrint');


    // Delivery Order
    Route::resource('deliveryOrder', DeliveryOrderController::class);
    Route::resource('deliveryOrderDetail', DeliveryOrderDetailController::class);
    Route::get('/deliveryOrderQuoInvCheck', 'DeliveryOrderController@deliveryOrderQuoInvCheck');
    Route::get('/deliveryOrderConfirmDelivery/{id}','DeliveryOrderController@deliveryOrderConfirmDelivery')->name('deliveryOrderConfirmDelivery');
    Route::get('/deliveryOrderConfirm/{id}','DeliveryOrderController@deliveryOrderConfirm')->name('deliveryOrderConfirm');
    // do sign
    Route::post('/deliveryOrderSign/{id}', 'DeliveryOrderController@deliveryOrderSign')->name('deliveryOrderSign');
    Route::get('/deliveryOrderSignRemove/{id}/{sign}', 'DeliveryOrderController@deliveryOrderSignRemove')->name('deliveryOrderSignRemove');
    Route::get('/doPrint/{id}/{date?}', 'DeliveryOrderController@doPrint')->name('doPrint');

    // Person Invoice Controller
    Route::get('/invoiceToCheck', 'PersonInvoiceController@invoiceToCheck');
    Route::resource('personInvoice', PersonInvoiceController::class, ['only' => 'store']);

    //PaymentTerm
    Route::resource('paymentTerm', PaymentTermController::class);

});

Route::group(['middleware' => ['auth'], 'prefix' => 'setting', 'namespace' => 'App\Http\Controllers\Setting', 'as' => 'setting.'], function() {
    Route::resource('permission', PermissionController::class);
    Route::resource('role', RoleController::class);
    Route::resource('user', UserController::class);
    Route::resource('authorizer', AuthorizedController::class);
});
