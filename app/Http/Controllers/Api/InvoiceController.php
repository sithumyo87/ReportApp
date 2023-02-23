<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Currency;
use App\Models\PaymentTerm;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Customer;
use App\Models\PersonInvoice;
use App\Models\Dealer;
use App\Models\Authorizer;
use App\Models\Advance;
use App\Models\BankInfo;
use App\Models\BankInfoDetail;
use Carbon\Carbon;
use DB;
use PDF;

class InvoiceController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:invoice-index|invoice-create|invoice-edit|invoice-delete', ['only' => ['index']]);
        $this->middleware('permission:invoice-show', ['only' => ['show']]);
        $this->middleware('permission:invoice-create', ['only' => ['create','store']]);
        $this->middleware('permission:invoice-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:invoice-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data               = Invoice::searchDataPaginate($request);
        $inv_codes          = Invoice::invoiceNoDropDown();
        $company_names      = Customer::companyDropDown();
        $customer_names     = Customer::customerDropDown();
        $search             = $request;
        return response()->json([
            'status'     => true,
            'data'      => $data,
            'inv_codes' => $inv_codes,
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
        $customers = Customer::where('action',true)->get(); 
        $currency = Currency::all();
        $quotations = Quotation::where('SubmitStatus',true)->get();
        $payments = payments();
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
        $quo_Id = $request->Quotation_Id;

        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|mimes:png,jpg,jpeg,pdf|max:10240'
            ]);
            $fileNameWithExtension = $request->file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $fileExtension = $request->file->getClientOriginalExtension();

            $datetime = strtotime(date('Y-m-d H:i:s'));

            $fileNameToStore = $fileName.$datetime.$fileExtension;
            $request->file->move(public_path('attachments/officeManagement/'), $fileNameToStore);
            $storedFileName= 'attachments/officeManagement/'.$fileNameToStore;
        }else{
            $storedFileName = null;
        }

        $input['Invoice_No'] = 'IV-'.strtotime($input['Date'].' '.date('H:i:s'));
        $input['Date'] =date('Y-m-d',strtotime(str_replace('/', '-', $input['Date'])));
        
        $input['form31_files'] = $storedFileName;
        $input['submit_status'] = false;

        // dd($input);
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
        $invoice = Invoice::create($input);

        if ($quo_Id != "") {
            DB::table('quotation_details')->where('Quotation_Id',$quo_Id)->update(['Invoice_Id' => $invoice->id]);
        }
        return response()->json([
            'status'            => true,
            'invoice'         => $invoice,
        ], 200);  
    }

    public function detail($id,$type=null){
        $invoice    = Invoice::find($id);
        $currency   = Currency::where('id',$invoice->Currency_type)->first();
        $invDetails = QuotationDetail::where('Invoice_Id',$id)->get();
        $invNotes       = QuotationNote::where('InvoiceId', $id)->where('Note','!=',"")->get();
        $authorizers    = Authorizer::get();
        $bankInfos = BankInfo::get();

        $bankInfoDetails = [];
        if($invoice->submit_status == 1 && $invoice->bank_info != ''){
            $banks = explode(',', $invoice->bank_info);
            foreach($banks as $bank){
                $banInfo = BankInfo::find($bank);
                $bInfo['name'] = $banInfo->name;
                $bInfo['details'] = BankInfoDetail::where('bank_info_id', $bank)->get();
                array_push($bankInfoDetails, $bInfo);
            }
        }

        // data for other payment
        if(is_numeric($type)){
            $advance_data = Advance::where('Invoice_Id', $invoice->id)->where('nth_time', $type)->first();
        }else{
            $advance_data = null;
        }
        $advance_last = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();
        $advances = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'asc')->get();

        return response()->json([
            'status'            => true,
            'invoice'         => $invoice,
            'currency'          => $currency,
            'invDetails'        => $invDetails,
            'invNotes'          => $invNotes,
            'bankInfos'          => $bankInfos,
            'authorizers'       => $authorizers,
            'bankInfoDetails'       => $bankInfoDetails,
            'advance_last'       => $advance_last,
            'advances'       => $advances,
            'advance_data'       => $advance_data,
            'type'       => $type,
        ], 200);   
    }

    public function detail_create($id){
        $invoice = Invoice::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'            => true,
            'invoice'         => $invoice,
            'dealers'           => $dealers,
        ], 200);   
    }
    public function detail_store(Request $request, $id){
        $inv = Invoice::findOrFail($id);

        $this->validate($request, [
        ]);

        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }

        $input = QuotationDetail::create([
            'Quotation_Id'  => $inv->Quotation_Id,
            'Invoice_Id'    => $inv->id,
            'Description'   => $request->Description,
	        'Unit_Price'    => $request->Unit_Price,
	        'Qty'           => $request->Qty,
	        'percent'       => $request->percent,
	        'dealer_id'     => $request->dealer_id,
	        'form31_no'     => $request->form31_no,
            'invoice_no'    => $request->invoice_no,
	        'tax'           => $request->tax,
	        'tax_amount'    => $tax_amount,
        ]);
        return response()->json([
            'status'         => true,
            'detail'         => $input,
        ], 200);  
    }
    public function detail_edit($id){
        $invDetail = QuotationDetail::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'    => true,
            'invDetail' => $invDetail,
            'dealers'   => $dealers,
        ], 200); 
    }
    public function detail_update(Request $request, $id){
        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        $detail = QuotationDetail::find($id);
        $detail->update([
            'Description'   => $request->Description,
	        'Unit_Price'    => $request->Unit_Price,
	        'Qty'           => $request->Qty,
	        'percent'       => $request->percent,
	        'dealer_id'     => $request->dealer_id,
	        'form31_no'     => $request->form31_no,
            'invoice_no'    => $request->invoice_no,
	        'tax'           => $request->tax,
	        'tax_amount'    => $tax_amount,
        ]);
        return response()->json([
            'status'    => true,
            'invDetail' => $detail,
        ], 200); 
    }
    public function detail_delete($id){
        $detail = QuotationDetail::find($id);
        $detail->Invoice_Id = null;
        $detail->save();
        return response()->json([
            'status'    => true,
        ], 200);
    }

    public function tax_check(Request $request, $id){
            $tax = $request->tax;
            $total = $request->total;

            $inv = Invoice::find($id);
            $inv->tax_id = $tax;
            $inv->save();

            $tax_amount     = ($inv->tax_id * $total)/100;
            $grand_total    = $total + $tax_amount;
            return response()->json([
                'status'    => true,
                'tax_amount' => number_format($tax_amount,2),
                'grand_total' => number_format($grand_total,2),
            ]);
    }

    public function bank_check(Request $request, $id){
            $bankId = $request->bankId;
            $check = $request->check;

            $bankInfo = [];

            $inv = Invoice::find($id);
            if($inv->bank_info != ''){
                $bankInfo = explode(',', $inv->bank_info);
            }
            if($check == '1'){ // add 
                if (array_search($bankId, $bankInfo) === false) {
                    array_push($bankInfo, $bankId);
                }
            }else if($check == '0'){ // remove
                if (($key = array_search($bankId, $bankInfo)) !== false) {
                    unset($bankInfo[$key]);
                }
            }

            $inv->bank_info = implode(',', $bankInfo);
            $inv->save();
             return response()->json([
            'status'    => true,
            'invoice'  => $inv,
        ]);
    }

    public function discount(Request $request, $id){
            $inv = Invoice::find($id);
            $inv->update([
                'Discount' => $request->discount,
            ]);
            return response()->json([
            'status'    => true,
            'invoice'  => $inv,
        ]);
    }

    public function sign_store(Request $request, $id){
        $authorizer = Authorizer::find($request->authorizer);
        $inv = Invoice::find($id);
        $inv->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return response()->json([
        'status'    => true,
        'invoice'  => $inv,
        ]);
    }

    public function confirm(Request $request, $id){
        $invoice = Invoice::find($id);
        // dd($invoice);
        $invoice->update([
            'submit_status' => 1,
        ]);
        return response()->json([
        'status'    => true,
        'invoice'  => $invoice,
        ]);
    }
    
    public function note_store(Request $request, $id){
        $invId = $request->invId;
        $input = QuotationNote::create([
            'InvoiceId'     => $invId,
            'Note'          => $request->Note,
        ]);
        return response()->json([
            'status'    => true,
            'note'      => $input
        ], 200);
    }
    public function note_edit($id){
        $note = QuotationNote::findorfail($id);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }
    public function note_update(Request $request,$id){
        $invId = $request->invId;
        $note = QuotationNote::find($id);
        $input = $note->update([
            'InvoiceId'     => $invId,
            'Note'          => $request->Note,
        ]);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }

    public function note_delete(Request $request,$id){
        $invId = $request->invId;
        $note = QuotationNote::find($id);
        $note->destroy($id);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }
    public function quo_attn_on_change(Request $request){
            $quoId = $request->quoId;
            if($quoId != ''){
                $quotation = Quotation::findOrFail($quoId);
            }
            $currency = Currency::all();
            return response()->json([
                'status'    => true,
                'currency'  => $currency,
                'quotation' => $quotation
            ], 200);
    }

    public function get_invoice(Request $request, $id){
        $type = $request->type;
        // first invoice  = 1; 50/50, 60/40, 80/20
        // second invoice = 2; 50/50, 60/40, 80/20
        // cash/credit = 3
        // other => 4

        // used for other payment
        $amount     = $request->amount; // total amount
		$other_amt  = $request->other_amt; // apply amount
        if($other_amt == ''){
            $other_amt = 0;
        }

        $invoice = Invoice::find($id);
        $details = QuotationDetail::where('Invoice_Id', $id)->get();

        $advances = Advance::where('Invoice_Id', $id)->get();

        $total = 0;
        foreach ($details as $value) {
			$total += percent_price($value->Unit_Price, $value->percent) * $value->Qty;
		}

        $taxAmount = ($invoice->tax_id * ($total - $invoice->Discount))/100;
        $grandTotal = $total - $invoice->Discount + $taxAmount;
        $grandTotalWithoutTax = $total - $invoice->Discount;

        // $tax_amount = ($total - $invoice->Discount) * ($invoice->tax_id/100);

        if ($type == 1){ // first payment 50/50 60/40 80/20
			if ($invoice->Advance == 1) { // 50/50
				$multiply = 50/100;
			} elseif ($invoice->Advance == 2) { // 60/40
				$multiply = 60/100;
			} else { // 80/20
				$multiply = 80/100; 
			}
			$invoice->update([
				'FirstInvoice'          => true,
				'finv_date'             => date("Y-m-d"),
				'First_payment_amount'  => round($grandTotalWithoutTax * $multiply, 2)
            ]);
		} elseif ($type == 2) { // second payment
			$invoice->update([
				'SecondInvoice'         => true,
				'sinv_date'             => date("Y-m-d"),
				'Second_payment_amount' => round($grandTotal - $invoice->First_payment_amount, 2),
			]);
		} elseif ($type == 3) {
            $invoice->update([
				'FirstInvoice'  => true, 
                'SecondInvoice' => true,
                'finv_date'     => date("Y-m-d"),
                'sinv_date'     => date("Y-m-d"),
			]);
		} elseif ($type == 4) {
            $invoice->update([
				'FirstInvoice'  => true, 
                'SecondInvoice' => true
			]);

			$last_row = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();

            $remain_amt = isset($last_row) ? $last_row->Balance : $amount;
			$balance    = $remain_amt - $other_amt;

			$last_row_count = isset($last_row) ? $last_row->nth_time : 0;
			$last_row_count = $last_row_count + 1;
            
            $advan = Advance::create([
				'Invoice_Id'    => $invoice->id,
				'Advance_value' => $other_amt,
				'Balance'       => $balance,
				'Date'          => date('Y-m-d h:i:s'),
				'nth_time'      => $last_row_count
			]);
		}
        return response()->json([
            'status'    => true,
            'invoice' => $invoice,
            'quo_detail' => $details,
        ], 200);
    }
}