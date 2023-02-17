<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dealer;

class DealerController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:dealer-index|dealer-create|dealer-edit|dealer-delete', ['only' => ['index']]);
        $this->middleware('permission:dealer-create', ['only' => ['create','store']]);
        $this->middleware('permission:dealer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:dealer-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data               = Dealer::searchDataPaginate($request);
        $company_names      = Dealer::companyDropDown();
        $customer_names     = Dealer::customerDropDown();
        $search             = $request;
        return view('OfficeManagement.dealer.index', compact('data', 'company_names', 'customer_names', 'search'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('OfficeManagement.dealer.create');
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
            'email' => 'required',
            'phone' => 'required',
            'phone' => 'required',
            'company' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);
        
        $input = $request->all();
        $dealer = Dealer::create($input);
        return redirect()->route('OfficeManagement.dealer.index')
                        ->with('success','Dealer created successfully');
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
        $dealer = Dealer::find($id);
        return view('OfficeManagement.dealer.edit',compact('dealer'));
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
            'email' => 'required',
            'phone' => 'required',
            'phone' => 'required',
            'company' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);
    
        $input = $request->all();

        $dealer = dealer::find($id);
        $dealer->update($input);
    
        return redirect()->route('OfficeManagement.dealer.index')
                        ->with('success','Dealer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dealer = Dealer::find($id);
        $dealer->update(array('action'=> false));
        return redirect()->route('OfficeManagement.dealer.index')
                        ->with('success','Dealer deleted successfully');
    }
}
