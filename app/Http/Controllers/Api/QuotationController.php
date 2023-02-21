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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quotation      = Quotation::findOrFail($id);
        $customers      = Customer::where('action',true)->get(); 
        $currency       = Currency::all();
        $quotations     = Quotation::where('SubmitStatus', true)->where('id','!=',$id)->get();

        return response()->json([
            'status'            => true,
            'quotation'         => $quotation,
            'customers'         => $customers,
            'currency'          => $currency,
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

        return response()->json([
            'status'            => true,
            'quotation'         => $quotation,
        ], 200);   
    }

    public function detail($id){
        $quotation  = Quotation::find($id);
        $currency   = Currency::where('id',$quotation->Currency_type)->first();
        $quoDetails = QuotationDetail::where('Quotation_Id',$id)->get();
        $quoNotes   = QuotationNote::where('QuotationId',$quotation->id)->where('Note', '!=', "")->get();
        $quoFiles   = QuotationNote::where('QuotationId',$quotation->id)->where('list_file', '!=', "")->get();
        $authorizers = Authorizer::get();

        return response()->json([
            'status'            => true,
            'quotation'         => $quotation,
            'currency'          => $currency,
            'quoDetails'        => $quoDetails,
            'quoNotes'          => $quoNotes,
            'quoFiles'          => $quoFiles,
            'authorizers'       => $authorizers,
        ], 200);   
    }

    public function detail_create($id){
        $quotation = Quotation::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'            => true,
            'quotation'         => $quotation,
            'dealers'           => $dealers,
        ], 200);   
    }
    public function detail_store(Request $request, $id){
        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        // $quoDetail = QuotationDetail::create($input);
        $detail = QuotationDetail::create([
            'Quotation_Id'  => $id,
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
            'detail'         => $detail,
        ], 200);  
    }
    public function detail_edit($id){
        $quoDetail = QuotationDetail::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return response()->json([
            'status'    => true,
            'quoDetail' => $quoDetail,
            'dealers'   => $dealers,
        ], 200); 
    }
    public function detail_update(Request $request, $id){
        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        $quotationDetail = QuotationDetail::find($id);
        $quotationDetail->update([
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
            'quoDetail' => $quotationDetail,
        ], 200); 
    }
    public function detail_delete($id){
        $quotationDetail = QuotationDetail::find($id);
        $quotationDetail->delete();
        return response()->json([
            'status'    => true
        ], 200);
    }

    public function tax_check(Request $request, $id){
        $tax = $request->tax;
        $total = $request->total;

        $quotation = Quotation::find($id);
        $quotation->Tax = $tax;
        $quotation->save();

        $tax_amount     = ($quotation->Tax * $total)/100;
        $grand_total    = $total + $tax_amount;
        return response()->json([
            'status'    => true,
            'tax_amount' => number_format($tax_amount,2),
            'grand_total' => number_format($grand_total,2),
        ]);
    }

    public function note_store(Request $request, $id){
        $input = QuotationNote::create([
            'QuotationId'   => $id,
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
        $note = QuotationNote::find($id);
        $note->update([
            'Note'          => $request->Note,
        ]);
        return response()->json([
            'status'    => true,
            'note'      => $note,
        ], 200);
    }

    public function file_store(Request $request, $id){
        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|mimes:png,jpg,jpeg,pdf|max:10240'
            ]);
            $fileNameWithExtension = $request->file->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExtension, PATHINFO_FILENAME);
            $fileExtension = $request->file->getClientOriginalExtension();

            $datetime = strtotime(date('Y-m-d H:i:s'));

            $fileNameToStore = $fileName.$datetime.$fileExtension;
            $request->file->move(public_path('attachments/'), $fileNameToStore);
            $storedFileName= 'attachments/'.$fileNameToStore;
        }else{
            $storedFileName = null;
        }
        $input = QuotationNote::create([
            'QuotationId'   => $id,
            'list_name'     => $request->list_name,
            'list_file'     => $storedFileName,
        ]);
        return response()->json([
            'status'    => true,
            'note'      => $input,
        ], 200);
    }
    public function file_delete(Request $request,$id)
    {
        $quoNote = QuotationNote::find($id);
        $quoNote->destroy($id);
        return response()->json([
            'status'    => true,
        ], 200);
    }

    public function sign_store(Request $request, $id){
        $quotation = Quotation::find($id);
        $authorizer = Authorizer::find($request->authorizer);
        $quotation->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return response()->json([
            'status'    => true,
        ], 200);
    }

    public function confirm($id){
        $quotation = Quotation::find($id);
        // dd($quotation);
        $quotation->update([
            'SubmitStatus' => 1,
        ]);
        return response()->json([
            'status'    => true,
        ], 200);
        

    }
}
