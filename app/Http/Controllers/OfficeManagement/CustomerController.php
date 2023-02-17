<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:customer-index|customer-create|customer-edit|customer-delete', ['only' => ['index','store']]);
        $this->middleware('permission:customer-show', ['only' => ['show']]);
        $this->middleware('permission:customer-create', ['only' => ['create','store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data               = Customer::searchDataPaginate($request);
        $company_names      = Customer::companyDropDown();
        $customer_names     = Customer::customerDropDown();
        $search             = $request;
        return view('OfficeManagement.customer.index', compact('data', 'company_names', 'customer_names', 'search'))->with('i', pageNumber($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('OfficeManagement.customer.create');
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
        $customer = Customer::create($input);
        return redirect()->route('OfficeManagement.customer.index')
                        ->with('success','Customer created successfully');
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
        $customer = Customer::findOrFail($id);
        return view('OfficeManagement.customer.edit',compact('customer'));
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

        $customer = Customer::findOrFail($id);
        $customer->update($input);
    
        return redirect()->route('OfficeManagement.customer.index')
                        ->with('success','Customer updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update(array('action'=> false));
        return redirect()->route('OfficeManagement.customer.index')
                        ->with('success','customer deleted successfully');
    }

    public function attnOnChange(Request $request){
        if($request->ajax()){
            $attn       = $request->attn;
            $company    = $request->company;

            if($attn != ''){
                $customer = Customer::findOrFail($attn);
            }else if($company != ''){
                $customer = Customer::where('company', $company)->first();
            }else{
                $customer = new Customer();
            }

            $customers = Customer::where('action',true)->get(); 

            return view('OfficeManagement.customer.attn_form',compact('customers', 'customer'));
        }
    }
}
