<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Currency;
use Carbon\Carbon;
use  App\Models\PaymentTerm;

class InvoiceController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:Invoice-index|Invoice-create|Invoice-edit|Invoice-delete', ['only' => ['index','store']]);
        // $this->middleware('permission:Invoice-show', ['only' => ['show']]);
        // $this->middleware('permission:Invoice-create', ['only' => ['create','store']]);
        // $this->middleware('permission:Invoice-edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:Invoice-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Invoice::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        $invoice = Invoice::where('id','>',0)->first();
        $quotation = Quotation::where('Id',$invoice['Quotation_Id'])->first();
        return view('OfficeManagement.invoice.index',compact('data','invoice','quotation'))->with('i', ($request->input('page', 1) - 1) * 5);
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
        $paymentTerms = PaymentTerm::get();
        return view('OfficeManagement.invoice.create',compact('customers','quotations','currency','paymentTerms'));
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
        dd($input);
        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|mimes:png,jpg,jpeg,pdf|max:10240'
              ]);
            $fileNameToStore = $request->file->getClientOriginalName();
            $request->file->move(public_path('attachments/officeManagement/'), $fileNameToStore);
            $storedFileName= 'attachments/officeManagement/'.$fileNameToStore;
        }else{
            $storedFileName = null;
        }

        $input['Invoice_No'] = 'IV-'.strtotime(Carbon::now()->format('H:i:s'));
        $input['file_name'] = $storedFileName;
        $input['submit_status'] = false;

        
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
        return redirect()->route('OfficeManagement.invoice.index')
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
        $Invoice = Invoice::findOrFail($id);
        $customers = Customer::where('action',true)->get(); 
        $currency = Currency::all();
        $Invoices = Invoice::all();
        return view('OfficeManagement.invoice.edit',compact('Invoice','customers','currency','Invoices'));
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
}
