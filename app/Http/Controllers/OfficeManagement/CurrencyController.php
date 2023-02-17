<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Currency;

class CurrencyController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:currency-index|currency-create|currency-edit|currency-delete', ['only' => ['index','store']]);
        $this->middleware('permission:currency-create', ['only' => ['create','store']]);
        $this->middleware('permission:currency-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:currency-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Currency::where('id','>',0)->orderBy('id','DESC')->paginate(5);
        return view('OfficeManagement.currency.index',compact('data'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('OfficeManagement.currency.create');
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
            'Currency_name' => 'required',
            'symbol' => 'required',
        ]);
        
        $input = $request->all();
        $currency = Currency::create($input);
        return redirect()->route('OfficeManagement.currency.index')
                        ->with('success','Currency created successfully');
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
        $currency = Currency::findOrFail($id);
        return view('OfficeManagement.currency.edit',compact('currency'));
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
            'Currency_name' => 'required',
            'symbol' => 'required',
        ]);
    
        $input = $request->all();

        $currency = Currency::findOrFail($id);
        $currency->update($input);
    
        return redirect()->route('OfficeManagement.currency.index')
                        ->with('success','Currency updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        $currency->destroy($id);
        return redirect()->route('OfficeManagement.currency.index')
                        ->with('success','currency deleted successfully');
    }
}
