<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:quotation-index|quotation-create|quotation-edit|quotation-delete', ['only' => ['index','store']]);
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
        $data = Quotation::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('OfficeManagement.quotation.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
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
        $quotations = Quotation::all();
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
            'company' => 'required',
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
        $Date = date('Y-m-d', strtotime($request->Date));

        $customerName = Customer::find($request->customer_id);

        $input = Quotation::create([
            'customer_id'=>$request->customer_id,
            'Attn'=>$customerName->name,
	        'Company_name' => $request->company,
	        'Contact_phone' => $request->phone,
	        'Address' => $request->address,
	        'Sub'=>$request->sub,
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
        $quotation = Quotation::find($id);
        return view('OfficeManagement.quotation.edit',compact('quotation'));
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
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'phone' => 'required',
            'company' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);
    
        $input = $request->all();

        $quotation = Customer::find($id);
        $quotation->update([
            'customer_id'=>$request->customer_id,
            'Attn'=>"Hello",
	        'Company_name' => $request->company,
	        'Contact_phone' => $request->phone,
	        'Address' => $request->address,
	        'Sub'=>$request->sub,
	        'Date'=>$Date,
	        'Serial_No'=> $Serial,
	        'Refer_No'=>$refer_no,
	        'Refer_status' => false,
	        'Currency_type' => $request->currency,
	        'SubmitStatus' => false
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
        //
    }
}
