<?php

namespace App\Http\Controllers\OfficeManagement;

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
        $advances = [];
        foreach($data as $row){
            $advances[$row->id] = Advance::where('Invoice_Id', $row->Invoice_Id)->orderBy('nth_time', 'asc')->get();
        }
        return view('OfficeManagement.receipt.index', compact('data', 'rec_codes', 'company_names', 'customer_names', 'search', 'advances'))->with('i', pageNumber($request));
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
        return view('OfficeManagement.receipt.create',compact('customers','currency','invoices','payments'));
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

        $receipt_old = Receipt::where('Invoice_Id', $input['Invoice_Id'])->get();
        if(count($receipt_old) > 0){
            Receipt::where('Invoice_Id', $input['Invoice_Id'])->update(['Invoice_Id'=> null]);
        }
        $receipt = Receipt::create($input);

        return redirect()->route('OfficeManagement.receiptDetail.show',$receipt->id)
                        ->with('success','Receipt created successfully');
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
    public function edit($id)
    {
        $Receipt = Receipt::findOrFail($id);
        $customers = Customer::where('action',true)->get(); 
        $currency = Currency::all();
        $quotations = Receipt::all();
        return view('OfficeManagement.receipt.edit',compact('Receipt','customers','currency','quotations'));
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
        $this->validate($request, [
            // 'name' => 'required',
            // 'email' => 'required',
            // 'phone' => 'required',
            // 'phone' => 'required',
            // 'company' => 'required',
            // 'address' => 'required',
            // 'phone' => 'required',
        ]);
        
        $refer_no = $request->refer_no;
        $Refer = true;
		if ($refer_no == '' || $refer_no == 'Refer No:') {
			$refer_no = '';
			$Refer = false;
		};

        $Date = date('Y-m-d',strtotime(str_replace('/', '-', $request->date)));

        $customerName = Customer::find($request->customer_id);

        $Receipt = Receipt::find($id);
        $Receipt->update([
            'customer_id'   => $request->customer_id,
            'Attn'          => $customerName->name,
	        'Company_name'  => $request->Company_name,
	        'Contact_phone' => $request->Contact_phone,
	        'Address'       => $request->Address,
	        'Sub'           => $request->Sub,
	        'Date'          => $Date,
	        'Refer_No'      => $refer_no,
	        'Refer_status'  => false,
	        'Currency_type' => $request->Currency_type,
        ]);
    
        return redirect()->route('OfficeManagement.receipt.index')
                        ->with('success','Receipt updated successfully');
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

    //JS function
	public function get_data_from_quo_name() {
		$id = $this->uri->segment(3);
		$data = $this->db->get_where('customers_tbl', array('action' => true, 'id' => $id))->row_array();
		echo '<input type="hidden" name="name" value="'.$data['name'].'">
		<div class="form-group">
				<label class="col-sm-3 control-label text-right text-uppercase">company name</label>
				<div class="col-sm-9" id="quo-company-data">';
					echo form_input('company', $data['company'], 'class="form-control" readonly=""');
				echo '</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label text-right text-uppercase">phone no:</label>
				<div class="col-sm-9">';
				if ($data['phone_other'] == "") {
					echo form_input('phone', $data['phone'], 'class="form-control" readonly=""');
				} else {
					echo '<select name="phone" class="form-control">
						<option value="'.$data['phone'].'">'.$data['phone'].'</option>
						<option value="'.$data['phone_other'].'">'.$data['phone_other'].'</option>
					</select>';
				}
				echo '</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label text-right text-uppercase">address</label>
				<div class="col-sm-9">';
					echo form_textarea('address', $data['address'], 'class="form-control" readonly=""');
				echo '</div>
			</div>';
	}

    public function receiptAuthorizer(Request $request, $id){
        $authorizer = Authorizer::find($request->authorizer);
        $rec = Receipt::find($id);
        $rec->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return redirect()->route('OfficeManagement.receiptDetail.show', $id)
                        ->with('success','Authorized Person Updated Successful!');
    }


    public function invAttnOnChange(Request $request){
        $invId = $request->invId;
        if($invId != ''){
            $invoice = Invoice::findOrFail($invId);
        }else{
            $invoice = new Invoice();
        }
        $currency = Currency::all();
        $payments = payments();
        return view('OfficeManagement.receipt.attn_form',compact('invoice','currency', 'payments'));
    }

    public function receiptPrint($id, $type=null) {
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

        $data = [
            'receipt'           => $receipt,
            'invoice'           => $invoice,
            'invNotes'          => $invNotes,
            'invDetails'        => $invDetails,
            'currency'          => $currency,
            'authorizers'       => $authorizers,
            'type'              => $type,
            'advance_last'      => $advance_last,
            'advances'          => $advances,
            'advance_data'      => $advance_data,
            'inv_advances'      => $inv_advances,
        ]; 

        $pdf = PDF::loadView('OfficeManagement.receipt.print', $data);
        return $pdf->stream($receipt->Receipt_No.'.pdf');
    }

    public function receive(Request $request){
        $type           = $request->type;
        $receipt        = Receipt::find($request->id);
        $invoice        = Invoice::findOrFail($receipt->Invoice_Id);
        $received_date  = date('Y-m-d', strtotime($request->received_date));

        if ($type == 1){ // first payment 50/50 60/40 80/20
			$receipt->update([
				'first_received_date' => $received_date,
            ]);
            // return $receipt;
		} elseif ($type == 2) { // second payment
			$receipt->update([
				'second_received_date' => $received_date,
			]);
		} elseif ($type == 3) {
            $receipt->update([
				'first_received_date' => $received_date,
                'second_received_date' => $received_date,
			]);
		} elseif ($type == 4) {

			$advan = Advance::where('Invoice_Id', $invoice->id)->where('received_date', null)->orderBy('id', 'asc')->first();

            $advan_last = Advance::where('Invoice_Id', $invoice->id)->where('received_date', null)->orderBy('id', 'desc')->first();

            if($advan->id == $advan_last->id){
                $receipt->update([
                    'first_received_date' => $received_date,
                    'second_received_date' => $received_date,
                ]);
            }

            $advan->update([
				'received_date' => $received_date,
			]);
		}

        return redirect()->route('OfficeManagement.receipt.index',['page' => $request->page])
        ->with('success','Receipt\'s Received Successfully!');
    }
}
