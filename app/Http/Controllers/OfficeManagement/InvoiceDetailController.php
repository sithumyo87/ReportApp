<?php

namespace App\Http\Controllers\OfficeManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\QuotationDetail;
use App\Models\QuotationNote;
use App\Models\Dealer;
use App\Models\Currency;
use App\Models\Authorizer;
use App\Models\Advance;
use App\Models\BankInfo;
use App\Models\BankInfoDetail;

class InvoiceDetailController extends Controller
{
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceDetailCreate($id)
    {
        $inv = Invoice::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.invoiceDetail.create', compact('dealers', 'inv'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invId = $request->invId;
        $inv = Invoice::findOrFail($invId);

        $this->validate($request, [
        ]);

        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }

        $input = QuotationDetail::create([
            'Quotation_Id'  => $inv->Quotation_Id,
            'Invoice_Id'    => $inv->id,
            'Description'   => $request->Description,
	        'Unit_Price'    => $request->Unit_Price,
	        'Qty'           => $request->Qty,
	        'percent'       => $request->percent,
	        'dealer_id'     => $request->dealer_id,
	        'form31_no'     => $request->form31_no,
            'invoice_no'    => $request->invoice_no,
	        'tax'           => $request->tax,
	        'tax_amount'    => $tax_amount,
        ]);
        return redirect()->route('OfficeManagement.invoiceDetail.show',$invId)
                        ->with('success','Invoice detail created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $type=null)
    {
        $invoice    = Invoice::find($id);
        $currency   = Currency::where('id',$invoice->Currency_type)->first();
        $invDetails = QuotationDetail::where('Invoice_Id',$id)->get();
        $invNotes       = QuotationNote::where('InvoiceId', $id)->where('Note','!=',"")->get();
        $authorizers    = Authorizer::get();
        $bankInfos = BankInfo::get();

        $bankInfoDetails = [];
        if($invoice->submit_status == 1 && $invoice->bank_info != ''){
            $banks = explode(',', $invoice->bank_info);
            foreach($banks as $bank){
                $banInfo = BankInfo::find($bank);
                $bInfo['name'] = $banInfo->name;
                $bInfo['details'] = BankInfoDetail::where('bank_info_id', $bank)->get();
                array_push($bankInfoDetails, $bInfo);
            }
        }

        // data for other payment
        if(is_numeric($type)){
            $advance_data = Advance::where('Invoice_Id', $invoice->id)->where('nth_time', $type)->first();
        }else{
            $advance_data = null;
        }
        $advance_last = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();
        $advances = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'asc')->get();

        return view('OfficeManagement.invoiceDetail.show',compact('invoice','invNotes','invDetails','currency','authorizers', 'bankInfos', 'bankInfoDetails', 'type', 'advance_last', 'advances', 'advance_data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invDetail = QuotationDetail::find($id);
        $dealers = Dealer::where('action',true)->get(); 
        return view('OfficeManagement.invoiceDetail.edit',compact('invDetail','dealers'));
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
            
        ]);

        if($request->tax == "1"){
            $tax_amount = 5;
        }else{
            $tax_amount = 0;
        }
        $detail = QuotationDetail::find($id);
        $detail->update([
            'Description'   => $request->Description,
	        'Unit_Price'    => $request->Unit_Price,
	        'Qty'           => $request->Qty,
	        'percent'       => $request->percent,
	        'dealer_id'     => $request->dealer_id,
	        'form31_no'     => $request->form31_no,
            'invoice_no'    => $request->invoice_no,
	        'tax'           => $request->tax,
	        'tax_amount'    => $tax_amount,
        ]);
        return redirect()->route('OfficeManagement.invoiceDetail.show', $detail->Invoice_Id)
                        ->with('success','Invoice Detail updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $detailOld = QuotationDetail::find($id);
        $detail = QuotationDetail::find($id);
        $detail->Invoice_Id = null;
        $detail->save();
        return redirect()->route('OfficeManagement.invoiceDetail.show', $detailOld->Invoice_Id)
        ->with('success','Invoice Detail deleted successfully');
    }

    public function invTaxCheck(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $tax = $request->tax;
            $total = $request->total;

            $inv = Invoice::find($id);
            $inv->tax_id = $tax;
            $inv->save();

            $tax_amount     = ($inv->tax_id * $total)/100;
            $grand_total    = $total + $tax_amount;
            return ([
                'tax_amount' => number_format($tax_amount, 2),
                'grand_total' => number_format($grand_total, 2),
            ]);
        }
    }

    public function invBankCheck(Request $request){
        if($request->ajax()){
            $id = $request->id;
            $bankId = $request->bankId;
            $check = $request->check;

            $bankInfo = [];

            $inv = Invoice::find($id);
            if($inv->bank_info != ''){
                $bankInfo = explode(',', $inv->bank_info);
            }
            if($check == '1'){ // add 
                if (array_search($bankId, $bankInfo) === false) {
                    array_push($bankInfo, $bankId);
                }
            }else if($check == '0'){ // remove
                if (($key = array_search($bankId, $bankInfo)) !== false) {
                    unset($bankInfo[$key]);
                }
            }

            $inv->bank_info = implode(',', $bankInfo);
            $inv->save();

            return $inv->bank_info;
        }
    }

    public function invoiceDiscount(Request $request, $id){
        $inv = Invoice::find($id);
        $inv->update([
            'Discount' => $request->discount,
        ]);
        return redirect()->route('OfficeManagement.invoiceDetail.show',$id)
                        ->with('success','Discount Updated Successful!');
    }

    public function invoiceAuthorizer(Request $request, $id){
        $authorizer = Authorizer::find($request->authorizer);
        $inv = Invoice::find($id);
        $inv->update([
            'sign_name' =>  $authorizer->authorized_name,
            'file_name' =>  $authorizer->file_name,
        ]);
        return redirect()->route('OfficeManagement.invoiceDetail.show',$id)
                        ->with('success','Authorized Person Updated Successful!');
    }

    public function invoiceConfirm($id){
        $invoice = Invoice::find($id);
        // dd($invoice);
        $invoice->update([
            'submit_status' => 1,
        ]);
        return redirect()->route('OfficeManagement.invoiceDetail.show',$id)
        ->with('success','Invoice Confirm Successful!');
    }

    public function getInvoice(Request $request, $id){
        $type = $request->type;
        // first invoice  = 1; 50/50, 60/40, 80/20
        // second invoice = 2; 50/50, 60/40, 80/20
        // cash/credit = 3
        // other => 4

        // used for other payment
        $amount     = $request->amount; // total amount
		$other_amt  = $request->other_amt; // apply amount
        if($other_amt == ''){
            $other_amt = 0;
        }

        $invoice = Invoice::find($id);
        $details = QuotationDetail::where('Invoice_Id', $id)->get();

        $advances = Advance::where('Invoice_Id', $id)->get();

        $total = 0;
        foreach ($details as $value) {
			$total += percent_price($value->Unit_Price, $value->percent) * $value->Qty;
		}

        $taxAmount = ($invoice->tax_id * ($total - $invoice->Discount))/100;
        $grandTotal = $total - $invoice->Discount + $taxAmount;

        // $tax_amount = ($total - $invoice->Discount) * ($invoice->tax_id/100);

        if ($type == 1){ // first payment 50/50 60/40 80/20
			if ($invoice->Advance == 1) { // 50/50
				$multiply = 50/100;
			} elseif ($invoice->Advance == 2) { // 60/40
				$multiply = 60/100;
			} else { // 80/20
				$multiply = 80/100; 
			}
			$invoice->update([
				'FirstInvoice'          => true,
				'finv_date'             => date("Y-m-d"),
				'First_payment_amount'  => round($grandTotal * $multiply, 2)
            ]);
		} elseif ($type == 2) { // second payment
			$invoice->update([
				'SecondInvoice'         => true,
				'sinv_date'             => date("Y-m-d"),
				'Second_payment_amount' => round($grandTotal - $invoice->First_payment_amount, 2),
			]);
		} elseif ($type == 3) {
            $invoice->update([
				'FirstInvoice'  => true, 
                'SecondInvoice' => true,
                'finv_date'     => date("Y-m-d"),
                'sinv_date'     => date("Y-m-d"),
			]);
		} elseif ($type == 4) {
            $invoice->update([
				'FirstInvoice'  => true, 
                'SecondInvoice' => true
			]);

			$last_row = Advance::where('Invoice_Id', $invoice->id)->orderBy('id', 'desc')->first();

            $remain_amt = isset($last_row) ? $last_row->Balance : $amount;
			$balance    = $remain_amt - $other_amt;

			$last_row_count = isset($last_row) ? $last_row->nth_time : 0;
			$last_row_count = $last_row_count + 1;
            
            $advan = Advance::create([
				'Invoice_Id'    => $invoice->id,
				'Advance_value' => $other_amt,
				'Balance'       => $balance,
				'Date'          => date('Y-m-d h:i:s'),
				'nth_time'      => $last_row_count
			]);
		}

        return redirect()->route('OfficeManagement.invoiceDetail.show',$id)
                        ->with('success','Get Invoice done Successful!');

    }
}
