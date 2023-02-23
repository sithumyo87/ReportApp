<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\PaymentTerm;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Authorizer;
use App\Models\Advance;
use PDF;

class ReceiptController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:receipt-index|receipt-create|receipt-edit|receipt-delete', ['only' => ['index']]);
        $this->middleware('permission:receipt-show', ['only' => ['show']]);
        $this->middleware('permission:receipt-create', ['only' => ['create','store']]);
        $this->middleware('permission:receipt-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:receipt-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $data = Receipt::searchDataPaginate($request);
        $rec_codes          = Receipt::receiptNoDropDown();
        $company_names      = Customer::companyDropDown();
        $customer_names     = Customer::customerDropDown();
        $search             = $request;
        return response()->json([
            'status'     => true,
            'data'      => $data,
            'company_names' => $company_names,
            'customer_names' => $customer_names,
            'search' => $request,
        ], 200);
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
        $invoices   = Invoice::where('submit_status', true)->get();
        $payments   = payments();
        return response()->json([
            'status'            => true,
            'customers'         => $customers,
            'currency'          => $currency,
            'invoices'        => $invoices,
            'payments'         => $payments
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
       $this->validate($request, [
            // 'name' => 'required',
            // 'company' => 'required',
            // 'Attn' => 'required',
            // 'Company_name' => 'required',
            // 'Contact_phone' => 'required',
            // 'Sub' => 'required',
            // 'Serial_No' => 'required',
            // 'Date' => 'required',
        ]);
        $input =$request->all();
        
        $input['Receipt_No'] = 'RN-'.strtotime($input['Date'].' '.date('H:i:s'));
        $input['Date'] =date('Y-m-d',strtotime(str_replace('/', '-', $input['Date'])));
        
        $receipt = Receipt::create($input);

        return response()->json([
            'status'            => true,
            'receipt'         => $receipt,
        ], 200); 
    }

    public function detail($id,$type=null){
        $receipt    = Receipt::findOrFail($id);
        $invoice    = Invoice::findOrFail($receipt->Invoice_Id);
        $currency   = Currency::where('id', $receipt->Currency_type)->first();
        $invDetails = QuotationDetail::where('Invoice_Id', $receipt->Invoice_Id)->get();
        $invNotes       = QuotationNote::where('InvoiceId', $receipt->Invoice_Id)->where('Note','!=',"")->get();
        $authorizers    = Authorizer::get();

        // data for other payment
        if(is_numeric($type)){
            $advance_data = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('nth_time', $type)->where('receipt_date', '!=', null)->first();
        }else{
            $advance_data = null;
        }
        $advance_last = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('receipt_date', '!=', null)->orderBy('id', 'desc')->first();
        $advances = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('receipt_date', '!=', null)->orderBy('id', 'asc')->get();
        $inv_advances = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('receipt_date', null)->orderBy('id', 'asc')->get();

        return response()->json([
            'status'            => true,
            'receipt'           => $receipt,
            'invoice'           => $invoice,
            'currency'          => $currency,
            'invDetails'        => $invDetails,
            'invNotes'          => $invNotes,
            'authorizers'       => $authorizers,
            'type'              => $type,
            'advance_last'      => $advance_last,
            'advances'          => $advances,
            'advance_data'      => $advance_data,
            'inv_advances'      => $inv_advances,
        ], 200);   
    }

    public function get_data_from_quo_name($id) {
		$data = Customer::where('action',1)->where('id',$id)->get();
		// echo '<input type="hidden" name="name" value="'.$data['name'].'">
		// <div class="form-group">
		// 		<label class="col-sm-3 control-label text-right text-uppercase">company name</label>
		// 		<div class="col-sm-9" id="quo-company-data">';
		// 			echo form_input('company', $data['company'], 'class="form-control" readonly=""');
		// 		echo '</div>
		// 	</div>
		// 	<div class="form-group">
		// 		<label class="col-sm-3 control-label text-right text-uppercase">phone no:</label>
		// 		<div class="col-sm-9">';
		// 		if ($data['phone_other'] == "") {
		// 			echo form_input('phone', $data['phone'], 'class="form-control" readonly=""');
		// 		} else {
		// 			echo '<select name="phone" class="form-control">
		// 				<option value="'.$data['phone'].'">'.$data['phone'].'</option>
		// 				<option value="'.$data['phone_other'].'">'.$data['phone_other'].'</option>
		// 			</select>';
		// 		}
		// 		echo '</div>
		// 	</div>
		// 	<div class="form-group">
		// 		<label class="col-sm-3 control-label text-right text-uppercase">address</label>
		// 		<div class="col-sm-9">';
		// 			echo form_textarea('address', $data['address'], 'class="form-control" readonly=""');
		// 		echo '</div>
		// 	</div>';
            return response()->json([
                'status'            => true,
                'data'              => $data
            ], 200); 
	}

    public function sign_store(Request $request, $id){
        $authorizer = Authorizer::find($request->authorizer);
        $rec = Receipt::find($id);
        $rec->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return response()->json([
            'status'    => true,
            'receipt'   => $rec,
        ], 200);
    }

    public function inv_attn_on_chg(Request $request){
        $invId = $request->invId;
        if($invId != ''){
            $invoice = Invoice::findOrFail($invId);
        }else{
            $invoice = new Invoice();
        }
        $currency = Currency::all();
        $payments = payments();
        return response()->json([
            'status'    => true,
            'invoice'   => $invoice,
            'currency'  => $currency,
            'payments'  => $payments
        ], 200);
    }

    public function receive(Request $request,$id){
        $po = Receipt::find($id);
        $po->update([
            'received_date' => date('Y-m-d', strtotime($request->received_date)),
        ]);
        return response()->json([
            'status'    => true,
            'po'        => $po,
        ], 200);
    }


    public function get_receipt(Request $request, $id){
        $type = $request->type;
        // first   = 1; 50/50, 60/40, 80/20
        // second  = 2; 50/50, 60/40, 80/20
        // cash/credit = 3
        // other => 4

        $receipt = Receipt::find($id);
        // return $type;
        $invoice = Invoice::findOrFail($receipt->Invoice_Id);

        if ($type == 1){ // first payment 50/50 60/40 80/20
			$receipt->update([
				'First_Receipt' => true,
				'frec_date'     => date("Y-m-d"),
            ]);
            // return $receipt;
		} elseif ($type == 2) { // second payment
			$receipt->update([
				'Second_Receipt'    => true,
				'srec_date'         => date("Y-m-d"),
			]);
		} elseif ($type == 3) {
            $receipt->update([
				'First_Receipt'     => true,
				'frec_date'         => date("Y-m-d"),
                'Second_Receipt'    => true,
				'srec_date'         => date("Y-m-d"),
			]);
		} elseif ($type == 4) {
            $invoice->update([
				'First_Receipt'     => true,
                'Second_Receipt'    => true,
			]);

			$advan = Advance::where('Invoice_Id', $invoice->id)->where('receipt_date', null)->orderBy('id', 'asc')->first();

            $advan->update([
				'receipt_date' => date('Y-m-d h:i:s')
			]);
		}
        return response()->json([
            'status'    => true,
            'invoice' => $invoice,
            'receipt' => $receipt,
        ], 200);
    }
    
}
