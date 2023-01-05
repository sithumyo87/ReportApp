<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationNote;
use App\Models\Quotation;

class QuotationNotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quoId = $request->quoId;
        $input = QuotationNote::create([
            'QuotationId'=>$quoId,
            'Note'=>$request->Note,
        ]);
        return redirect()->route('OfficeManagement.quotationDetail.show',$quoId);
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
    public function edit(Request $request,$id)
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
    public function update(Request $request, $id,$quoDetailId)
    {
        $quoId = Quotation::find($quoDetailId);
        $quoNote = QuotationNote::where('id',$id)->first();
        if($quoId){
            $quotation = QuotationNote::findorfail($id);
            $input =  $quoNote->update([
                'Note'=>$request->Note,
            ]);
            // dd($input . 'Updated');
            return redirect()->route('OfficeManagement.quotationDetail.show',$quoId); 
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $quoDetailId = $request->id;
        $quoNote = QuotationNote::find($id);
        dd($quoNote);
        $quoNote->destroy();
        return redirect()->route('OfficeManagement.quotationDetail.index'.$quoDetailId)
                        ->with('success','quoNote deleted successfully');
    }
}
