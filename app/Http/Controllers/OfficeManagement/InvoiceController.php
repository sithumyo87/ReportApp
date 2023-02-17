<?php

namespace App\Http\Controllers\OfficeManagement;

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
        return view('OfficeManagement.invoice.index',compact('data', 'inv_codes', 'company_names', 'customer_names', 'search'))->with('i', pageNumber($request));
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
        return view('OfficeManagement.invoice.create',compact('customers','quotations','currency','payments'));
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

        return redirect()->route('OfficeManagement.invoiceDetail.show', $invoice->id)
                        ->with('success','Invoice created successfully');
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

        $Invoice = Invoice::find($id);
        $Invoice->update([
            'customer_id'=>$request->customer_id,
            'Attn'=>$customerName->name,
	        'Company_name' => $request->Company_name,
	        'Contact_phone' => $request->Contact_phone,
	        'Address' => $request->Address,
	        'Sub'=>$request->Sub,
	        'Date'=>$Date,
	        'Refer_No'=>$refer_no,
	        'Refer_status' => false,
	        'Currency_type' => $request->Currency_type,
        ]);
    
        return redirect()->route('OfficeManagement.invoice.index')
                        ->with('success','Invoice updated successfully');
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


    public function quoAttnOnChange(Request $request){
        if($request->ajax()){
            $quoId = $request->quoId;
            if($quoId != ''){
                $quotation = Quotation::findOrFail($quoId);
            }else{
                $quotation = Quotation();
            }
            $currency = Currency::all();
            return view('OfficeManagement.invoice.attn_form',compact('quotation','currency'));
        }
    }
    
    public function invoicePrint($id, $type=null){
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

        $data = [
            'invoice'           => $invoice,
            'invNotes'          => $invNotes,
            'invDetails'        => $invDetails,
            'currency'          => $currency,
            'authorizers'       => $authorizers,
            'bankInfos'         => $bankInfos,
            'bankInfoDetails'   => $bankInfoDetails,
            'type'              => $type,
            'advance_last'      => $advance_last,
            'advances'          => $advances,
            'advance_data'      => $advance_data,
        ]; 

        $pdf = PDF::loadView('OfficeManagement.invoice.print', $data);
        return $pdf->stream($invoice->Invoice_No.'.pdf');
    }
}
