<?php

namespace App\Http\Controllers\OfficeManagement;

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
        $data           = PurchasingOrder::searchDataPaginate($request);
        $attachs        = PurchasingOrder::getQuoAttachs($data);
        $po_codes       = PurchasingOrder::poNoDropDown();
        $company_names  = Customer::companyDropDown();
        $customer_names = Customer::customerDropDown();
        $search         = $request;
        return view('OfficeManagement.purchasingOrder.index',compact('data','attachs', 'po_codes', 'company_names', 'customer_names', 'search'))->with('i', pageNumber($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers  = Customer::where('action',true)->get(); 
        $currency   = Currency::all();
        $quotations = Quotation::where('SubmitStatus',true)->get();
        return view('OfficeManagement.purchasingOrder.create', compact('customers','quotations', 'currency'));
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
        return redirect()->route('OfficeManagement.purchasingOrder.show', $po->id)
                        ->with('success','Purchasing Order created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $po = PurchasingOrder::select('purchasing_orders.*', 'quotations.Serial_No', 'quotations.Refer_No')->leftJoin('quotations', 'quotations.id','=', 'purchasing_orders.quo_id')->where('purchasing_orders.id', $id)->first();
        $poDetails = PurchasingOrderDetail::where('po_id', $id)->get();
        $currency   = Currency::findOrFail($po->currency);
        $notes = PurchasingOrderNote::where('po_id', $id)->get();
        $authorizers = Authorizer::get();
        return view('OfficeManagement.purchasingOrder.show', compact('po', 'poDetails', 'currency', 'notes', 'authorizers'));
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
        return view('OfficeManagement.purchasingOrder.edit', compact('customers','quotations', 'currency', 'po'));
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
        $input['date'] = date('Y-m-d', strtotime($input['date']));
        $po = PurchasingOrder::findOrFail($id);
        $po->update($input);
        return redirect()->route('OfficeManagement.purchasingOrder.index')
                        ->with('success','Purchasing Order created successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function poTaxCheck(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $tax = $request->tax;
            $total = $request->total;

            $po = PurchasingOrder::find($id);
            $po->tax = $tax;
            $po->save();

            $tax_amount     = ($po->tax * $total)/100;
            $grand_total    = $total + $tax_amount;
            return ([
                'tax_amount' => number_format($tax_amount, 2),
                'grand_total' => number_format($grand_total, 2),
            ]);
        }
    }

    public function poAuthorizer(Request $request, $id){
        $authorizer = Authorizer::find($request->authorizer);
        $po = PurchasingOrder::find($id);
        $po->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return redirect()->route('OfficeManagement.purchasingOrder.show',$id)
                        ->with('success','Authorized Person Updated Successful!');
    }

    public function poConfirm($id){
        $po = PurchasingOrder::find($id);
        $po->update([
            'submit_status' => 1,
        ]);
        return redirect()->route('OfficeManagement.purchasingOrder.show',$id)
        ->with('success','Purchasing Order Confirmed Successful!');
    }

    public function poPrint($id){
        $po = PurchasingOrder::select('purchasing_orders.*', 'quotations.Serial_No', 'quotations.Refer_No')->leftJoin('quotations', 'quotations.id','=', 'purchasing_orders.quo_id')->where('purchasing_orders.id', $id)->first();
        $poDetails = PurchasingOrderDetail::where('po_id', $id)->get();
        $currency   = Currency::findOrFail($po->currency);
        $notes = PurchasingOrderNote::where('po_id', $id)->get();
        $authorizers = Authorizer::get();
        
        $data = [
            'po'                => $po,
            'poDetails'         => $poDetails,
            'currency'          => $currency,
            'notes'             => $notes,
            'authorizers'       => $authorizers,
        ]; 

        $pdf = PDF::loadView('OfficeManagement.purchasingOrder.print', $data);
        return $pdf->stream($po->po_code.'.pdf');
    }


    public function poReceive(Request $request){
        $po = PurchasingOrder::find($request->id);
        $po->update([
            'received_date' => date('Y-m-d', strtotime($request->received_date)),
        ]);
        return redirect()->route('OfficeManagement.purchasingOrder.index',['page' => $request->page])
        ->with('success','Purchasing Order\'s Received Successfully!');
    }
}
