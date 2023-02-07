<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PurchasingOrder;
use App\Models\PurchasingOrderDetail;
use App\Models\Dealer;

class PurchasingOrderDetailController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $po = PurchasingOrder::findOrFail($request->po_id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.purchasingOrder.detail_create',compact('po', 'dealers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        PurchasingOrderDetail::create($input);
        return redirect()->route('OfficeManagement.purchasingOrder.show',$request->po_id)
        ->with('success','Purchasing order detail created successfully'); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $poDetail = PurchasingOrderDetail::findOrFail($id);
        $po = PurchasingOrder::findOrFail($poDetail->po_id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.purchasingOrder.detail_edit',compact('poDetail','po', 'dealers'));
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
        $input = $request->all();
        $poDetail = PurchasingOrderDetail::findOrFail($id);
        $poDetail->update($input);
        return redirect()->route('OfficeManagement.purchasingOrder.show',$request->po_id)
        ->with('success','Purchasing order detail updated successfully'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $poDetail = PurchasingOrderDetail::findOrFail($id);
        $poId = $poDetail->po_id;
        $poDetail->destroy($id);
        return redirect()->route('OfficeManagement.purchasingOrder.show', $poId)
                        ->with('success','Purchasing order detail deleted successfully');
    }
}
