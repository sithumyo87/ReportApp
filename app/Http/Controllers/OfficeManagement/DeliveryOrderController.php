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
use DB;

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = DeliveryOrder::where('id','>',0)->orderBy('id','DESC')->paginate(15);
        return view('OfficeManagement.deliveryOrder.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 15);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $invoices = Invoice::where('submit_status',1)->get();
        $quotations = Quotation::where('SubmitStatus',1)->get();
        return view('OfficeManagement.deliveryOrder.create',compact('invoices', 'quotations'));
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
        $input['date'] = date('Y-m-d', strtotime($input['date']));

        // generate do_code
        $date = strtotime($input['date']);
        $count = DeliveryOrder::count();
        $input['do_code'] = 'DO-'.date('Ymd', $date).sprintf('%05d',$count+1);

        $do = DeliveryOrder::create($input);

        // insert do detail from quo/inv detail
        if($request->quo_id != '' || $request->inv_id != ''){
            if($request->quo_id != ''){
                $details = QuotationDetail::where('Quotation_Id', $request->quo_id)->get();
            }else{
                $details = QuotationDetail::where('Invoice_Id', $request->inv_id)->get();
            }
            foreach($details as $detail){
                DeliveryOrderDetail::create([
                    'do_id'     => $do->id,
                    'quo_id'    => $do->quo_id,
                    'inv_id'    => $do->inv_id,
                    'name'      => $detail->Description,
                    'qty'       => $detail->Qty,
                ]);
            }
        }

        return redirect()->route('OfficeManagement.deliveryOrder.show', $do->id)
                        ->with('success','Delivery Order created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);

        $details = DeliveryOrderDetail::where('do_id', $id)->get();
        $detail_records = [];
        foreach($details as $detail){
            $last = DeliveryOrderDetailRecord::where('do_id', $id)->where('do_detail_id', $detail->id)->orderBy('id', 'desc')->first();
            $detail_records[$detail->id] = $last;
        }

        $histories = [];
        $recordDates = DeliveryOrderDetailRecord::select('date', 'do_id')->where('do_id', $id)->orderBy('id', 'asc')->orderBy('date', 'asc')->groupBy('date')->get();
        foreach($recordDates as $row){
            $data = DeliveryOrderDetailRecord::join('delivery_order_details', 
            'delivery_order_details.id', '=', 'delivery_order_detail_records.do_detail_id')->where('delivery_order_detail_records.do_id', $id)
            ->where('delivery_order_detail_records.date', $row->date)
            ->orderBy('delivery_order_detail_records.id', 'asc')
            ->select('delivery_order_detail_records.*', 'delivery_order_details.name')
            ->get();
            $result['date'] = $row->date;
            $result['data'] = $data;
            array_push($histories, $result);
        }
        return view('OfficeManagement.deliveryOrder.show',compact('deliveryOrder', 'details', 'detail_records', 'histories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $deliveryOrder = DeliveryOrder::findOrFail($id);
        $invoices = Invoice::where('submit_status',1)->get();
        $quotations = Quotation::where('SubmitStatus',1)->get();
        return view('OfficeManagement.deliveryOrder.edit',compact('deliveryOrder', 'invoices', 'quotations'));
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
        $input['date'] = date('Y-m-d', strtotime($input['date']));
        $input['quo_id'] = isset($input['quo_id']) ? $input['quo_id'] : null;
        $input['inv_id'] = isset($input['inv_id']) ? $input['inv_id'] : null;

        $do = DeliveryOrder::findOrFail($id);
        
        // clear all do detail by quo_id
        if($request->quo_id != $do->quo_id){

            // return $do->quo_id;
            
            $details = DeliveryOrderDetail::where('do_id', $do->id)->where('quo_id', $do->quo_id)->get();
            // return $details;
            // clear all do detail record
            foreach($details as $detail){
                DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $detail->id)->delete();
                // $detail->delete();
            }

            DeliveryOrderDetail::where('do_id', $do->id)->where('quo_id', $do->quo_id)->delete();
        }

        // clear all do detail by inv_id
        if($request->inv_id != $do->inv_id){
            $details = DeliveryOrderDetail::where('do_id', $do->id)->where('inv_id', $do->inv_id)->get();
            // clear all do detail record
            foreach($details as $detail){
                DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $detail->id)->delete();
                // $detail->delete();
            }
            DeliveryOrderDetail::where('do_id', $do->id)->where('inv_id', $do->inv_id)->delete();
        }
        
        // insert new do detail from quo/inv detail
        if(($request->quo_id != $do->quo_id) || ($request->inv_id != $do->inv_id)){
            $details = [];

            if($request->quo_id != $do->quo_id){
                $details = QuotationDetail::where('Quotation_Id', $request->quo_id)->get();
            }
            if($request->inv_id != $do->inv_id){
                $details = QuotationDetail::where('Invoice_Id', $request->inv_id)->get();
            }
            foreach($details as $detail){
                DeliveryOrderDetail::create([
                    'do_id'     => $do->id,
                    'quo_id'    => $request->quo_id,
                    'inv_id'    => $request->inv_id,
                    'name'      => $detail->Description,
                    'qty'       => $detail->Qty,
                ]);
            }
        }

        $do->update($input);
        return redirect()->route('OfficeManagement.deliveryOrder.index')
                        ->with('success','Delivery Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $do = DeliveryOrder::findOrFail($id);
        DeliveryOrderDetailRecord::where('do_id', $do->id)->delete();
        DeliveryOrderDetail::where('do_id', $do->id)->delete();
        $do->destroy($id);
        return redirect()->route('OfficeManagement.deliveryOrder.index')
                        ->with('success','Delivery Order deleted successfully');
    }

    public function deliveryOrderQuoInvCheck(Request $request){
        $quo_no = $request->quo_no;
        $inv_no = $request->inv_no;
        if($quo_no != ''){
            $data = Quotation::findOrFail($quo_no);
        }elseif($inv_no != ''){
            $data = Invoice::findOrFail($inv_no);
        }else{
            $data = null;
        }
        return view('OfficeManagement.deliveryOrder.inv_quo_group',compact('data'));
    }

    public function deliveryOrderConfirm($id){
        $do = DeliveryOrder::findOrFail($id);
        $do->update(['submit_status' => 1]);
        return redirect()->route('OfficeManagement.deliveryOrder.show', $do->id)
                        ->with('success','DO confirmed successfully');
    }

    public function deliveryOrderConfirmDelivery($id){
        $do = DeliveryOrder::findOrFail($id);
        DeliveryOrderDetailRecord::where('do_id', $do->id)->update(['submit_status' => 1]);
        return redirect()->route('OfficeManagement.deliveryOrder.show', $do->id)
                        ->with('success','Delivery confirmed successfully');
    }
}
