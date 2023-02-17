<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PersonInvoice;
use App\Models\Currency;

class PersonInvoiceController extends Controller
{
    public function invoiceToCheck(Request $request){
        // if($request->ajax()){
            $personInvoices = PersonInvoice::all();

            $attn       = $request->attn;
            $company    = $request->company;

            if($attn != ''){
                $personInvoice = PersonInvoice::findOrFail($attn);
            }else if($company != ''){
                $personInvoice = PersonInvoice::where('company', $company)->first();
            }else{
                $personInvoice = new PersonInvoice();
            }

            $currency = Currency::all();

            return view('OfficeManagement.personInvoice.invoice_to_form',compact('personInvoices', 'personInvoice', 'currency'));
        // }
    }

    public function store(Request $request){
        $input = $request->all();
        PersonInvoice::create($input);
        return redirect()->route($request->route, $request->id)
                        ->with('success','Invoice To s created successfully');
    }
}
