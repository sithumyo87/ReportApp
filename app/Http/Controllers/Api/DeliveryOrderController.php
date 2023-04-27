<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderDetail;
use App\Models\DeliveryOrderDetailRecord;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Customer;
use DB;
use PDF;
use Laravel\Sanctum\PersonalAccessToken; 
use Laravel\Sanctum\Sanctum;


class DeliveryOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:do-index|do-create|do-edit|do-delete', ['only' => ['index']]);
        $this->middleware('permission:do-show', ['only' => ['show']]);
        $this->middleware('permission:do-create', ['only' => ['create','store']]);
        $this->middleware('permission:do-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:do-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data               = DeliveryOrder::searchDataPaginate($request);
        $total              = $data->total();
        $limit              = pagination();
        $do_codes           = DeliveryOrder::doNoDropDown();
        $company_names      = Customer::companyDropDown();
        $customer_names     = Customer::customerDropDown();
        $search             = $request;
        $data = DeliveryOrder::searchDataPaginate($request);
        return response()->json([
            'status'            => true,
            'total'             => $total,
            'limit'             => $limit,
            'do'                => $data,
            'company_names'     => $company_names,
            'customer_names'    => $customer_names,
            'search'            => $search
        ], 200);
    }
    public function create()
    {
        $invoices = Invoice::where('submit_status',1)->get();
        $quotations = Quotation::where('SubmitStatus',1)->get();
        $customers  = Customer::where('action',true)->get(); 
        return response()->json([
            'status'            => true,
            'invoices'          => $invoices,
            'customers'         => $customers,
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
                $do_detail = DeliveryOrderDetail::create([
                    'do_id'     => $do->id,
                    'quo_id'    => $do->quo_id,
                    'inv_id'    => $do->inv_id,
                    'name'      => $detail->Description,
                    'qty'       => $detail->Qty,
                ]);
            }
        }
        return response()->json([
            'status'     => true,
            'do'         => $do,
            'do_detail'  => $do_detail ?? null
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
        $deliveryOrder = DeliveryOrder::findOrFail($id);
        $invoices = Invoice::where('submit_status',1)->get();
        $quotations = Quotation::where('SubmitStatus',1)->get();
        return response()->json([
            'status'            => true,
            'deliveryOrder'     => $deliveryOrder,
            'invoices'          => $invoices,
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
                $do_detail = DeliveryOrderDetail::create([
                    'do_id'     => $do->id,
                    'quo_id'    => $request->quo_id,
                    'inv_id'    => $request->inv_id,
                    'name'      => $detail->Description,
                    'qty'       => $detail->Qty,
                ]);
            }
        }

        $do->update($input);
        return response()->json([
            'status'     => true,
            'do'         =>  $do,
            'do_detail'  =>  $do_detail ?? null,
        ], 200); 
    }

    public function delete($id){
        $do = DeliveryOrder::findOrFail($id);
        DeliveryOrderDetailRecord::where('do_id', $do->id)->delete();
        DeliveryOrderDetail::where('do_id', $do->id)->delete();
        $do->destroy($id);
        return response()->json([
            'status'    => true,
            'msg'       => 'successfully Deleted'
        ], 200);
    }

    public function detail($id){
        
        $deliveryOrder = DeliveryOrder::findOrFail($id);

        $details = DeliveryOrderDetail::where('do_id', $id)->get();
        $detail_records = null;
        foreach($details as $detail){
            $last = DeliveryOrderDetailRecord::where('do_id', $id)->where('do_detail_id', $detail->id)->orderBy('date', 'desc')->orderBy('id', 'desc')->first();
            if(isset($last)){
                $amount_sum = DeliveryOrderDetailRecord::where('date', $last->date)->where('do_id', $id)->where('do_detail_id', $detail->id)->sum('amount');
                $last->amount = $amount_sum != '' ? $amount_sum : 0;
            }
            $detail_records[$detail->id] = $last;
        }

        $histories = [];
        $recordDates = DeliveryOrderDetailRecord::select('date', 'do_id')->where('do_id', $id)->where('submit_status', true)->orderBy('id', 'asc')->orderBy('date', 'asc')->groupBy('date')->get();
        foreach($recordDates as $row){
            $ds = DeliveryOrderDetail::where('do_id', $id)->get();
            $d_records = [];
            foreach($ds as $d){
                $record = DeliveryOrderDetailRecord::where('do_id', $id)->where('do_detail_id', $d->id)->where('date', $row->date)->orderBy('id', 'desc')->where('submit_status', true)->first();
                $amount_sum = DeliveryOrderDetailRecord::where('do_id', $id)->where('do_detail_id', $d->id)->where('submit_status', true)->where('date', $row->date)->sum('amount');
                if(isset($record)){
                    $record->amount = $amount_sum != '' ? $amount_sum : 0;
                }
                array_push($d_records, $record);
            }
            $result['date'] = $row->date;
            $result['data'] = $d_records;

            array_push($histories, $result);
        }

        return response()->json([
            'status'           => true,
            'deliveryOrder'    => $deliveryOrder,
            'details'          => $details,
            'detail_records'   => $detail_records,
            'histories'        => $histories,
        ], 200);
    }

    public function detail_create($id)
    {
        $do = DeliveryOrder::findOrFail($id);
        return response()->json([
            'status'     => true,
            'do'         => $do,
        ], 200); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detail_store(Request $request,$id)
    {
        $do     = DeliveryOrder::findOrFail($id);
        $input  = $request->all();
        $input['do_id'] = $id;
        DeliveryOrderDetail::create($input); 
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
        $detail         = DeliveryOrderDetail::findOrFail($id);
        $do             = DeliveryOrder::findOrFail($detail->do_id);
        $last_record    = DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $detail->id)->orderBy('id', 'desc')->first();
        return response()->json([
            'status'        => true,
            'do_detail'     => $detail,
            'do'            => $do,
            'last_record'   => $last_record,
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
        return response()->json([
            'status'    => true,
            'do_detail' => $input,
            'do' => $do
        ], 200);
    }

    public function detail_cancel(Request $request, $id){
        $date = date('Y-m-d', strtotime($request->dDate));
        DeliveryOrderDetailRecord::where('date', $date)->where('do_detail_id', $id)->delete();
        return response()->json([
            'status'    => true,
            'msg'       => 'successsfully Cancel'
        ], 200);
    }

    public function detail_delete($id){
        $detail = DeliveryOrderDetail::findOrFail($id);
        $do = DeliveryOrder::findOrFail($detail->do_id);
        if($do->quo_id == '' && $do->inv_id == ''){
            $detail->destroy($id); 
        }else{
            DeliveryOrderDetailRecord::where('do_id', $do->id)->where('do_detail_id', $id)->delete();
            $detail->destroy($id); 
        }
        return response()->json([
            'status'    => true,
            'msg'  => 'successsfully Deleted'
        ], 200);
    }

    public function quo_inv_check(Request $request){
        $quo_no = $request->quo_no;
        $inv_no = $request->inv_no;
        if($quo_no != ''){
            $data = Quotation::findOrFail($quo_no);
        }elseif($inv_no != ''){
            $data = Invoice::findOrFail($inv_no);
        }else{
            $data = null;
        }
        return response()->json([
            'status'    => true,
            'data' => $data,
        ], 200);
    }
    
    public function do_confirm($id){
        $do = DeliveryOrder::findOrFail($id);
        $do->update(['submit_status' => 1]);
        return response()->json([
            'status'    => true,
            'do' => $do,
        ], 200);
    }

    public function do_confirm_delivery($id){
        $do = DeliveryOrder::findOrFail($id);
        DeliveryOrderDetailRecord::where('do_id', $do->id)->update(['submit_status' => 1]);
        return response()->json([
            'status'    => true,
            'do' => $do,
        ], 200);
    }

    public function do_sign(Request $request,$id){
        $do = DeliveryOrder::findOrFail($id);
        $input = $request->all();
        // dd($input);

        if($request->received_name != ''){
            $saveSignature = saveSignatureApi($request->received_sign);
            if($saveSignature['status']){
                $input['received_sign'] = $saveSignature['file'];
                // dd($input);
                $do->update($input);
            }
        }
        if($request->delivered_name != ''){
                $saveSignature = saveSignatureApi($request->delivered_sign);
                if($saveSignature['status']){
                    $input['delivered_sign'] = $saveSignature['file'];
                    $do->update($input);
            }
        }
        return response()->json([
            'status'    => true,
            'do'        => $do,
            'sign'      => $input,
        ], 200);
    }

    public function print(Request $request, $id, $date=null){
        $token = $request->token;

        $user = PersonalAccessToken::findToken($request->token);

        if(!(isset($user))){
            return response()->json([
                'status'    => false,
                'error'     => 'The login user is invalid!',
            ], 200);
        }
        
        $deliveryOrder = DeliveryOrder::findOrFail($id);

        $details = DeliveryOrderDetail::where('do_id', $id)->get();
        $detail_records = [];
        foreach($details as $detail){
            $record = DeliveryOrderDetailRecord::where('do_id', $id)->where('do_detail_id', $detail->id)->where('date', $date)->orderBy('id', 'desc')->first();
            $amount_sum = DeliveryOrderDetailRecord::where('do_id', $id)->where('do_detail_id', $detail->id)->where('date', $date)->sum('amount');
            if(isset($record)){
                $record->amount = $amount_sum != '' ? $amount_sum : 0;
            }
            $detail_records[$detail->id] = $record;
        }
        
        $data = [
            'deliveryOrder'  => $deliveryOrder,
            'details'        => $details,
            'detail_records' => $detail_records,
            'date'           => $date
        ]; 

        // if($request->pdf == 'kinzi'){
            $data['layout'] = 'layouts.kinzi_print';
            return view('OfficeManagement.deliveryOrder.print')->with($data);
        // }else{
        //     $data['layout'] = 'layouts.mpdf';
        //     $pdf = PDF::loadView('OfficeManagement.deliveryOrder.print', $data);
        //     return $pdf->stream($deliveryOrder->do_code.'.pdf');
        // }

        
    }
    
    
}
