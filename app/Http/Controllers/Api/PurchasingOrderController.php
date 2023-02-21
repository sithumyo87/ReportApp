<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchasingOrder;
use App\Models\PurchasingOrderDetail;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Quotation;
use App\Models\QuotationNote;
use App\Models\PurchasingOrderNote;
use App\Models\Authorizer;
use App\Models\Dealer;
use PDF;

class PurchasingOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:po-index|po-create|po-edit|po-delete', ['only' => ['index']]);
        $this->middleware('permission:po-show', ['only' => ['show']]);
        $this->middleware('permission:po-create', ['only' => ['create','store']]);
        $this->middleware('permission:po-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:po-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // echo "Hello";
        $data           = PurchasingOrder::searchDataPaginate($request);
        $attachs        = PurchasingOrder::getQuoAttachs($data);
        $po_codes       = PurchasingOrder::poNoDropDown();
        $company_names  = Customer::companyDropDown();
        $customer_names = Customer::customerDropDown();
        $search         = $request;
        $data = PurchasingOrder::searchDataPaginate($request);
        return response()->json([
            'status'    => true,
            'data'      => $data,
            'attachs'   => $attachs,
            'po_codes'  => $po_codes,
            'company_names' => $company_names,
            'customer_names' => $customer_names,
            'search' => $search
        ], 200);
    }
    public function create()
    {
        $customers  = Customer::where('action',true)->get(); 
        $currency   = Currency::all();
        $quotations = Quotation::where('SubmitStatus',true)->get();
        return response()->json([
            'status'            => true,
            'customers'         => $customers,
            'currency'          => $currency,
            'quotations'        => $quotations,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $count = PurchasingOrder::count();
        $input['po_code'] = 'PO-'.date('Ymd', strtotime($request->date)).sprintf('%04d', $count+1);
        $input['date'] = date('Y-m-d', strtotime($input['date']));
        $po = PurchasingOrder::create($input);
        return response()->json([
            'status'            => true,
            'po'         => $po,
        ], 200);  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $po = PurchasingOrder::findOrFail($id);
        $customers  = Customer::where('action',true)->get(); 
        $currency   = Currency::all();
        $quotations = Quotation::where('SubmitStatus',true)->get();
        return response()->json([
            'status'            => true,
            'po'                => $po,
            'customers'         => $customers,
            'currency'          => $currency,
            'quotations'        => $quotations,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $count = PurchasingOrder::count();
        $input['po_code'] = 'PO-'.date('Ymd', strtotime($request->date)).sprintf('%04d', $count+1);
        $input['date'] = date('Y-m-d',strtotime($request->date));
        $po = PurchasingOrder::findOrFail($id);
        $po->update($input);
        return response()->json([
            'status'            => true,
            'po'         =>  $po,
        ], 200); 
    }

    public function detail($id){
        $po = PurchasingOrder::select('purchasing_orders.*', 'quotations.Serial_No', 'quotations.Refer_No')->leftJoin('quotations', 'quotations.id','=', 'purchasing_orders.quo_id')->where('purchasing_orders.id', $id)->first();
        $poDetails = PurchasingOrderDetail::where('po_id', $id)->get();
        $currency   = Currency::findOrFail($po->currency);
        $notes = PurchasingOrderNote::where('po_id', $id)->get();
        $authorizers = Authorizer::get();
        return response()->json([
            'status'            => true,
            'po'         => $po,
            'po_details'         => $poDetails,
            'currency'          => $currency,
            'notes'        => $notes,
            'authorizers'       => $authorizers,
        ], 200);
    }

    public function detail_create($id)
    {
        $po = PurchasingOrder::findOrFail($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'            => true,
            'po'         => $po,
            'dealers'           => $dealers,
        ], 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detail_store(Request $request)
    {
        $input = $request->all();
        PurchasingOrderDetail::create($input);
        return response()->json([
            'status'         => true,
            'detail'         => $input,
        ], 200); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail_edit($id)
    {
        $poDetail = PurchasingOrderDetail::findOrFail($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'    => true,
            'quoDetail' => $poDetail,
            'dealers'   => $dealers,
        ], 200); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail_update(Request $request, $id)
    {
        $input = $request->all();
        $poDetail = PurchasingOrderDetail::findOrFail($id);
        $poDetail->update($input);
        return response()->json([
            'status'    => true,
            'poDetail' => $poDetail,
        ], 200);
    }

    public function detail_delete($id){
        $poDetail = PurchasingOrderDetail::findOrFail($id);
        $poDetail->delete();
        return response()->json([
            'status'    => true
        ], 200);
    }
    
}