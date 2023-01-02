<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Quotation;
use Carbon\Carbon;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Quotation::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('quotation.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = Customer::where('action',true)->get(); 
        $quotations = Quotation::all();
        // dd($quotation);
        return view('quotation.create',compact('customers','quotations'));
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
            'name' => 'required',
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

        $input = Quotation::create([
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
        return redirect()->route('quotation.index')
                        ->with('success','Customer created successfully');
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
        //
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
        //
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