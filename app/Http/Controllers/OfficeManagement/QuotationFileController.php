<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationNote;
use App\Models\Quotation;

class QuotationFileController extends Controller
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
        //
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
        // dd($request);
        if($request->hasFile('file')){
            $request->validate([
                'file' => 'required|mimes:png,jpg,jpeg,pdf|max:10240'
              ]);
            $fileNameToStore = $request->file->getClientOriginalName();
            $request->file->move(public_path('attachments/'), $fileNameToStore);
            $storedFileName= 'attachments/'.$fileNameToStore;
        }else{
            $storedFileName = null;
        }
        $input = QuotationNote::create([
                'QuotationId'=>$quoId,
                'list_name'=>$request->list_name,
                'list_file'=>$storedFileName,
            ]);
        return redirect()->route('OfficeManagement.quotationDetail.show',$quoId)->with('success','quotation File Created successfully'); 
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $quoDetailId = $request->Quotation_Id;
        $quoNote = QuotationNote::find($id);
        $quoNote->destroy($id);
        return redirect()->route('OfficeManagement.quotationDetail.show',$quoDetailId)
                        ->with('success','quoNote deleted successfully');
    }
}
