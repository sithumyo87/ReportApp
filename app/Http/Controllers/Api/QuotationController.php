<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Dealer;
use App\Models\Authorizer;
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
        $data = Quotation::searchDataPaginate($request);
        return response()->json([
            'status'    => true,
            'data'      => $data
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
        $quotations = Quotation::where('SubmitStatus', true)->get(); 
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
        //get count from the quotation
        $quoCount   = Quotation::where('Refer_status',false)->count();
        $Serial     = 'QN-'.Carbon::now()->format('Ymd') . sprintf('%05d',$quoCount+1);

        $refer_no = $request->refer_no;
        $Refer = true;
        if ($refer_no == '' || $refer_no == 'Refer No:') {
            $refer_no = '';
            $Refer = false;
        };
        $Date = date('Y-m-d', strtotime($request->date));
        
        $customerName = Customer::find($request->customer_id);
        $quotation = Quotation::create([
            'customer_id'   => $request->customer_id,
            'Attn'          => $customerName->name,
            'Company_name'  => $request->Company_name,
            'Contact_phone' => $request->Contact_phone,
            'Address'       => $request->Address,
            'Sub'           => $request->Sub,
            'Date'          => $Date,
            'Serial_No'     => $Serial,
            'Refer_No'      => $refer_no,
            'Refer_status'  => false,
            'Currency_type' => $request->Currency_type,
            'SubmitStatus'  => false
        ]);

        return response()->json([
            'status'            => true,
            'quotation'         => $quotation,
        ], 200);   
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
