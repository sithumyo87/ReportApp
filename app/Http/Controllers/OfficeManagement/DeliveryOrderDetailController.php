<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\DeliveryOrderDetailRecord;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Quotation;
use App\Models\QuotationDetail;

class DeliveryOrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $do = DeliveryOrder::findOrFail($request->do_id);
        return view('OfficeManagement.deliveryOrder.detail_create',compact('do'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $do     = DeliveryOrder::findOrFail($request->do_id);
        $input  = $request->all();
        DeliveryOrderDetail::create($input); 
        return redirect()->route('OfficeManagement.deliveryOrder.show', $do->id)
                    ->with('success','Delivery Order detail created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $detail         = DeliveryOrderDetail::findOrFail($id);
        $do             = DeliveryOrder::findOrFail($detail->do_id);
        $last_record    = DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $detail->id)->orderBy('id', 'desc')->first();
        return view('OfficeManagement.deliveryOrder.detail_edit',compact('do', 'detail', 'last_record'));
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
        $detail = DeliveryOrderDetail::findOrFail($id);
        $do = DeliveryOrder::findOrFail($detail->do_id);
        if($do->quo_id == '' && $do->inv_id == ''){
            $input = $request->all();
            $detail->update($input); 
        }else{
            $detail->name = $request->name;
            $detail->update();

            $record = DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $detail->id)->orderBy('id', 'desc')->first();

            $last_balance = isset($record) ? $record->balance : $detail->qty;

            $input['do_id']         = $do->id;
            $input['do_detail_id']  = $detail->id;
            $input['qty']           = $detail->qty;
            $input['amount']        = $request->qty;
            $input['balance']       = $last_balance - $request->qty;
            $input['date']          = date('Y-m-d H:i:s');
            DeliveryOrderDetailRecord::create($input);
        }
        return redirect()->route('OfficeManagement.deliveryOrder.show', $do->id)
                        ->with('success','Delivery Order detail edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detail = DeliveryOrderDetail::findOrFail($id);
        $do = DeliveryOrder::findOrFail($detail->do_id);
        if($do->quo_id == '' && $do->inv_id == ''){
            $detail->destroy($id); 
        }else{
            DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $id)->delete();
            $detail->destroy($id); 
        }
        return redirect()->route('OfficeManagement.deliveryOrder.show', $do->id)
                        ->with('success','Delivery Order detail deleted successfully');
    }
}
