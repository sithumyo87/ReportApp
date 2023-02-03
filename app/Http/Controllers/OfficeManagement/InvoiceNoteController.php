<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuotationNote;

class InvoiceNoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invId = $request->invId;
        $noteId = $request->noteId;

        if($noteId != ''){
            $input = QuotationNote::find($noteId);
            $input = $input->update([
                'InvoiceId'     => $invId,
                'Note'          => $request->Note,
            ]);
            return redirect()->route('OfficeManagement.invoiceDetail.show',$invId)->with('success','Note Updated successfully');
        }else{
            $input = QuotationNote::create([
                'InvoiceId'     => $invId,
                'Note'          => $request->Note,
            ]);
            return redirect()->route('OfficeManagement.invoiceDetail.show',$invId)->with('success','Note Created successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $invId = $request->invId;
        $note = QuotationNote::find($id);
        $note->destroy($id);
        return redirect()->route('OfficeManagement.invoiceDetail.show',$invId)
                        ->with('success','Note deleted successfully');
    }

}
