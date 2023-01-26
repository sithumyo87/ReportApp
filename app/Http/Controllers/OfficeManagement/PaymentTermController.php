<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Models\PaymentTerm;

class PaymentTermController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = PaymentTerm::where('id','>',0)->paginate(10);
        return view('OfficeManagement.paymentTerm.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('OfficeManagement.PaymentTerm.create');
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
            'FirstPay' => 'required',
            'SecondPay' => 'required',
        ]);
        
        $input = $request->all();
        $PaymentTerm = PaymentTerm::create($input);
        return redirect()->route('OfficeManagement.paymentTerm.index')
                        ->with('success','PaymentTerm created successfully');
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
        $paymentTerm = PaymentTerm::find($id);
        return view('OfficeManagement.paymentTerm.edit',compact('paymentTerm'));
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
            'FirstPay' => 'required',
            'SecondPay' => 'required',
        ]);
    
        $input = $request->all();

        $PaymentTerm = PaymentTerm::find($id);
        $PaymentTerm->update($input);
    
        return redirect()->route('OfficeManagement.paymentTerm.index')
                        ->with('success','PaymentTerm updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $PaymentTerm = PaymentTerm::find($id);
        $PaymentTerm->destroy($id);
        return redirect()->route('OfficeManagement.paymentTerm.index')
                        ->with('success','PaymentTerm deleted successfully');
    }
}
