<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\Customer;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Authorizer;
use App\Models\Advance;

class ReceiptDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $type=null)
    {
        $receipt    = Receipt::findOrFail($id);
        $invoice    = Invoice::findOrFail($receipt->Invoice_Id);
        $currency   = Currency::where('id', $receipt->Currency_type)->first();
        $invDetails = QuotationDetail::where('Invoice_Id', $receipt->Invoice_Id)->get();
        $invNotes       = QuotationNote::where('InvoiceId', $receipt->Invoice_Id)->where('Note','!=',"")->get();
        $authorizers    = Authorizer::get();

        // data for other payment
        if(is_numeric($type)){
            $advance_data = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('nth_time', $type)->where('receipt_date', '!=', null)->first();
        }else{
            $advance_data = null;
        }
        $advance_last = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('receipt_date', '!=', null)->orderBy('id', 'desc')->first();
        $advances = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('receipt_date', '!=', null)->orderBy('id', 'asc')->get();
        $inv_advances = Advance::where('Invoice_Id', $receipt->Invoice_Id)->where('receipt_date', null)->orderBy('id', 'asc')->get();

        // return $receipt;

        return view('OfficeManagement.receiptDetail.show',compact('receipt', 'invoice', 'invNotes','invDetails','currency','authorizers', 'type', 'advance_last', 'advances', 'advance_data','inv_advances'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getReceipt(Request $request, $id){
        $type = $request->type;
        // first   = 1; 50/50, 60/40, 80/20
        // second  = 2; 50/50, 60/40, 80/20
        // cash/credit = 3
        // other => 4

        $receipt = Receipt::find($id);
        // return $type;
        $invoice = Invoice::findOrFail($receipt->Invoice_Id);

        if ($type == 1){ // first payment 50/50 60/40 80/20
			$receipt->update([
				'First_Receipt' => true,
				'frec_date'     => date("Y-m-d"),
            ]);
            // return $receipt;
		} elseif ($type == 2) { // second payment
			$receipt->update([
				'Second_Receipt'    => true,
				'srec_date'         => date("Y-m-d"),
			]);
		} elseif ($type == 3) {
            $receipt->update([
				'First_Receipt'     => true,
				'frec_date'         => date("Y-m-d"),
                'Second_Receipt'    => true,
				'srec_date'         => date("Y-m-d"),
			]);
		} elseif ($type == 4) {
            $invoice->update([
				'First_Receipt'     => true,
                'Second_Receipt'    => true,
			]);

			$advan = Advance::where('Invoice_Id', $invoice->id)->where('receipt_date', null)->orderBy('id', 'asc')->first();

            $advan->update([
				'receipt_date' => date('Y-m-d h:i:s')
			]);
		}

        return redirect()->route('OfficeManagement.receiptDetail.show',$id)
                        ->with('success','Get receipt done Successful!');
    }
}
