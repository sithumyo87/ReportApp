<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Dealer;
use App\Models\Authorizer;
use PDF;
use Carbon\Carbon;

class QuotationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:quotation-index|quotation-create|quotation-edit|quotation-delete', ['only' => ['index']]);
        $this->middleware('permission:quotation-show', ['only' => ['show']]);
        $this->middleware('permission:quotation-create', ['only' => ['create','store']]);
        $this->middleware('permission:quotation-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:quotation-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data           = Quotation::searchDataPaginate($request);
        $quo_nos        = Quotation::quoNoDropDown();
        $company_names  = Customer::companyDropDown();
        $customer_names = Customer::customerDropDown();
        $search         = $request;
        return view('OfficeManagement.quotation.index',compact('data', 'quo_nos', 'company_names', 'customer_names', 'search'))->with('i', pageNumber($request));
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
        $quotations = Quotation::where('SubmitStatus', true)->get();
        return view('OfficeManagement.quotation.create',compact('customers','quotations','currency'));
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

        //get count from the quotation
        $quoCount = Quotation::where('Refer_status',false)->count();

        $Serial = 'QN-'.Carbon::now()->format('Ymd') . sprintf('%05d',$quoCount+1);

        $refer_no = $request->refer_no;
        $Refer = true;
		if ($refer_no == '' || $refer_no == 'Refer No:') {
			$refer_no = '';
			$Refer = false;
		};
        $Date = date('Y-m-d',strtotime($request->date));
        
        $customerName = Customer::find($request->customer_id);

        $input = Quotation::create([
            'customer_id'=>$request->customer_id,
            'Attn'=>$customerName->name,
	        'Company_name' => $request->Company_name,
	        'Contact_phone' => $request->Contact_phone,
	        'Address' => $request->Address,
	        'Sub'=>$request->Sub,
	        'Date'=>$Date,
	        'Serial_No'=> $Serial,
	        'Refer_No'=>$refer_no,
	        'Refer_status' => false,
	        'Currency_type' => $request->Currency_type,
	        'SubmitStatus' => false
        ]);
        return redirect()->route('OfficeManagement.quotation.index')
                        ->with('success','Quotation created successfully');
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
        $quotation = Quotation::findOrFail($id);
        $customers = Customer::where('action',true)->get(); 
        $currency = Currency::all();
        $quotations = Quotation::where('SubmitStatus', true)->where('id','!=',$id)->get();
        return view('OfficeManagement.quotation.edit',compact('quotation','customers','currency','quotations'));
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

        $Date = date('Y-m-d',strtotime($request->date));

        $customerName = Customer::find($request->customer_id);

        $quotation = Quotation::find($id);
        $quotation->update([
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
    
        return redirect()->route('OfficeManagement.quotation.index')
                        ->with('success','Quotation updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quotation = Quotation::find($id);
        $quotation->delete();
        return redirect()->route('OfficeManagement.quotation.index')
                        ->with('success','Quotation deleted successfully');
    }


    public function quoTaxCheck(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $tax = $request->tax;
            $total = $request->total;

            $quotation = Quotation::find($id);
            $quotation->Tax = $tax;
            $quotation->save();

            $tax_amount     = ($quotation->Tax * $total)/100;
            $grand_total    = $total + $tax_amount;
            return ([
                'tax_amount' => number_format($tax_amount,2),
                'grand_total' => number_format($grand_total,2),
            ]);
        }
    }

    public function print($id){
        $quotation = Quotation::find($id);
        $currency = Currency::where('id',$quotation->Currency_type)->first();
        $quoDetails = QuotationDetail::where('Quotation_Id',$id)->get();
        $quoNotes = QuotationNote::where('QuotationId',$quotation->id)->where('Note','!=',"")->get();
        $authorizers = Authorizer::get();
  
        $data = [
            'quotation'     => $quotation,
            'currency'      => $currency,
            'quoDetails'    => $quoDetails,
            'quoNotes'      => $quoNotes,
            'authorizers'   => $authorizers,
        ]; 

        // return view('OfficeManagement.quotation.print')->with($data);
            
        $pdf = PDF::loadView('OfficeManagement.quotation.print', $data);
        return $pdf->stream($quotation->Serial_No.'.pdf');

        // return view('OfficeManagement.quotation.print')->with($data);
    }

}
