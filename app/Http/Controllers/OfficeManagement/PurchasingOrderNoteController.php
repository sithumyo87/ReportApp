<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchasingOrderNote;

class PurchasingOrderNoteController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $poId   = $request->poId;
        $noteId = $request->noteId;

        if($noteId != ''){
            $input = PurchasingOrderNote::find($noteId);
            $input = $input->update([
                'po_id'     => $poId,
                'note'      => $request->Note,
            ]);
            return redirect()->route('OfficeManagement.purchasingOrder.show',$poId)->with('success','Note Updated successfully');
        }else{
            $input = PurchasingOrderNote::create([
                'po_id'     => $poId,
                'note'      => $request->Note,
            ]);
            return redirect()->route('OfficeManagement.purchasingOrder.show',$poId)->with('success','Note Created successfully');
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
        $poId = $request->poId;
        $note = PurchasingOrderNote::find($id);
        $note->destroy($id);
        return redirect()->route('OfficeManagement.purchasingOrder.show', $poId)
                        ->with('success','Note deleted successfully');
    }
}
