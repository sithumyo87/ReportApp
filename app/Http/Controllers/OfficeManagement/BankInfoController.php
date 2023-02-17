<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankInfo;
use App\Models\BankInfoDetail;

class BankInfoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:bank-index|bank-create|bank-edit|bank-delete', ['only' => ['index','store']]);
        $this->middleware('permission:bank-show', ['only' => ['show']]);
        $this->middleware('permission:bank-create', ['only' => ['create','store']]);
        $this->middleware('permission:bank-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:bank-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = BankInfo::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('OfficeManagement.bankInfo.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('OfficeManagement.bankInfo.create');
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
            'name' => 'required',
        ]);
        
        $input = $request->all();
        $bankInfo = BankInfo::create($input);
        return redirect()->route('OfficeManagement.bankInfo.index')
                        ->with('success','Bank Info created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bankInfo = BankInfo::findOrFail($id);
        $bankInfoDetails = BankInfoDetail::where('bank_info_id', $bankInfo->id)->get();
        return view('OfficeManagement.bankInfo.show',compact('bankInfo', 'bankInfoDetails'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bankInfo = BankInfo::findOrFail($id);
        return view('OfficeManagement.bankInfo.edit',compact('bankInfo'));
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
            'name' => 'required',
        ]);
    
        $input = $request->all();

        $bankInfo = BankInfo::findOrFail($id);
        $bankInfo->update($input);
    
        return redirect()->route('OfficeManagement.bankInfo.index')
                        ->with('success','Bank Info updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bankInfo = BankInfo::findOrFail($id);
        $bankInfo->destroy($id);
        return redirect()->route('OfficeManagement.bankInfo.index')
                        ->with('success','Bank Info deleted successfully');
    }
}
