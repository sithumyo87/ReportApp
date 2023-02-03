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

class PurchasingOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PurchasingOrder::select('purchasing_orders.*', 'quotations.Serial_No', 'quotations.Refer_No')->leftJoin('quotations', 'quotations.id','=', 'purchasing_orders.quo_id')->paginate(10);
        $attachs = [];
        foreach($data as $row){
            $quoAttfile = QuotationNote::getAttFiles($row->quo_id);
            if(count($quoAttfile) > 0){
                $attachs[$row->id] = $quoAttfile;
            }
        }
        return view('OfficeManagement.purchasingOrder.index',compact('data','attachs'))->with('i', ($request->input('page', 1) - 1) * 5);
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
        PurchasingOrder::create($input);
        return redirect()->route('OfficeManagement.purchasingOrder.index')
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
}
