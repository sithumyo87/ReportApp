<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankInfo;
use App\Models\BankInfoDetail;

class BankInfoDetailController extends Controller
{
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($bankInfoId)
    {
        $bankInfo = BankInfo::find($bankInfoId);
        return view('OfficeManagement.bankInfo.detail_create', compact('bankInfo'));
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
            'label_name' => 'required',
            'value_name' => 'required',
        ]);
        $input = $request->all();
        $detail = BankInfoDetail::create($input);
        return redirect()->route('OfficeManagement.bankInfo.show', $input['bank_info_id'])
                        ->with('success','Bank Info Detail created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankInfoDetails = BankInfoDetail::findOrFail($id);
        $bankInfo = BankInfo::find($bankInfoDetails->bank_info_id);
        return view('OfficeManagement.bankInfo.detail_edit',compact('bankInfoDetails', 'bankInfo'));
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

        $bankInfoDetails = BankInfoDetail::findOrFail($id);
        $bankInfoDetails->update($input);
    
        return redirect()->route('OfficeManagement.bankInfo.show', $input['bank_info_id'])->with('success','Bank Info detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $old = BankInfoDetail::findOrFail($id);
        $bankInfoDetails = BankInfoDetail::findOrFail($id);
        $bankInfoDetails->destroy($id);
        return redirect()->route('OfficeManagement.bankInfo.show', $old->bank_info_id)->with('success','Bank Info detail deleted successfully');
    }
}
