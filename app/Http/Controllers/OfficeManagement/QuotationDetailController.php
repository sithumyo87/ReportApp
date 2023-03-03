<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Dealer;
use App\Models\Currency;
use App\Models\Authorizer;

class QuotationDetailController extends Controller
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
    public function index(Request $request,$id)
    {
    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $quotation = Quotation::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.quotationDetail.create',compact('dealers','quotation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $this->validate($request, [
            
        ]);

        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        // $quoDetail = QuotationDetail::create($input);
        $input = QuotationDetail::create([
            'Quotation_Id'  => $request->Quotation_Id,
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
        return redirect()->route('OfficeManagement.quotationDetail.show',$request->Quotation_Id)
                        ->with('success','Quotation Detail created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $quotation  = Quotation::select('quotations.*', 't2.Serial_No as refer')->leftJoin('quotations as t2', 't2.id','quotations.Refer_No')->find($id);
        $currency   = Currency::where('id',$quotation->Currency_type)->first();
        $quoDetails = QuotationDetail::where('Quotation_Id',$id)->get();
        $quoNotes   = QuotationNote::where('QuotationId',$quotation->id)->where('Note','!=',"")->get();
        $quoFiles   = QuotationNote::where('QuotationId',$quotation->id)->where('list_file','!=',"")->get();
        $authorizers = Authorizer::get();
        return view('OfficeManagement.quotationDetail.index',compact('quotation','quoNotes','quoFiles','quoDetails','currency','authorizers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $quoDetail = QuotationDetail::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.quotationDetail.edit',compact('quoDetail','dealers'));
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
            
        ]);

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
        return redirect()->route('OfficeManagement.quotationDetail.show',$quotationDetail->Quotation_Id)
                        ->with('success','Quotation Detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quotationDetailOld = QuotationDetail::find($id);
        $quotationDetail = QuotationDetail::where('id', $id)->delete();
        return redirect()->route('OfficeManagement.quotationDetail.show',$quotationDetailOld->Quotation_Id)
        ->with('success','Quotation Detail deleted successfully');
    }

    public function getNote(Request $request, $quoDetailId,$quoNoteId ){
        $quotation = Quotation::find($quoDetailId);
        $quoNote = QuotationNote::where('id',$quoNoteId)->first();
        $quoNotes = QuotationNote::where('QuotationId',$quotation->id)->where('Note','!=',"")->get();
        $quoFiles = QuotationNote::where('QuotationId',$quotation->id)->where('list_file','!=',"")->get();
        return view('OfficeManagement.quotationDetail.index',compact('quotation','quoNotes','quoNote','quoFiles'));
    }

    //Quotation Sign Authorizer
    public function quotationAuthorizer(Request $request,$id){
        $quotation = Quotation::find($id);
        $authorizer = Authorizer::find($request->authorizer);
        $quotation->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return redirect()->route('OfficeManagement.quotationDetail.show',$id)
        ->with('success','Authorized Person Updated Successful!');
    }

    public function quotationConfirm($id){
        $quotation = Quotation::find($id);
        // dd($quotation);
        $quotation->update([
            'SubmitStatus' => 1,
        ]);
        return redirect()->route('OfficeManagement.quotationDetail.show',$id)
        ->with('success','Quotation Confirm Successful!');
        

    }


}
