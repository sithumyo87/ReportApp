<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Dealer;

class QuotationDetailController extends Controller
{
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
    public function create(Request $request,$id)
    {
        $quoId = Quotation::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.quotationDetail.create',compact('dealers','quoId'));
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

        if($request->tax == "on"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        // $quoDetail = QuotationDetail::create($input);
        $input = QuotationDetail::create([
            'Quotation_Id'=>$request->Quotation_Id,
            'Description'=>$request->Description,
	        'Unit_Price' => $request->Unit_Price,
	        'Qty' => $request->Qty,
	        'percent' => $request->percent,
	        'dealer_id'=>$request->dealer_id,
	        'form31_no'=>$request->form31_no,
	        'tax'=> $request->tax,
	        'tax_amount'=>$tax_amount,
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
        $quotation = Quotation::find($id);
        $quoDetails = QuotationDetail::where('Quotation_Id',$id)->get();
        $quoNotes = QuotationNote::where('QuotationId',$quotation->id)->where('Note','!=',"")->get();
        $quoFiles = QuotationNote::where('QuotationId',$quotation->id)->where('list_file','!=',"")->get();
        return view('OfficeManagement.quotationDetail.index',compact('quotation','quoNotes','quoFiles','quoDetails'));
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
        // dd($quoDetail);
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

    public function getNote(Request $request, $quoDetailId,$quoNoteId ){
        $quotation = Quotation::find($quoDetailId);
        $quoNote = QuotationNote::where('id',$quoNoteId)->first();
        $quoNotes = QuotationNote::where('QuotationId',$quotation->id)->where('Note','!=',"")->get();
        $quoFiles = QuotationNote::where('QuotationId',$quotation->id)->where('list_file','!=',"")->get();
        return view('OfficeManagement.quotationDetail.index',compact('quotation','quoNotes','quoNote','quoFiles'));
    }
}
