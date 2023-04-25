<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'Quotation_Id',
        'Attn',
        'Sub',
        'Invoice_No',
        'Date',
        'Discount',
        'Refer_status',
        'Advance',
        'FirstInvoice',
        'SecondInvoice',
        'finv_date',
        'sinv_date',
        'Company_name',
        'Contact_phone',
        'Address',
        'sinv_date',
        'First_payment_amount',
        'Second_payment_amount',
        'sign_name',
        'file_name',
        'file_name',
        'Date_INT',
        'tax_id',
        'bank_info',
        'submit_status',
        'customer_tax_id',
        'Currency_type',
        'po_no',
        'vender_id',
        'form31_no',
        'form31_issue_date',
        'form31_files'
    ];  

    protected function searchData(Request $request){
        $data = $this->select('invoices.*')->where('invoices.id','>',0);
        if($request->inv_code != ''){
            $data = $data->where('invoices.Invoice_No', $request->inv_code);
        } 
        if($request->company_name != ''){
            $data = $data->where('invoices.Company_name', $request->company_name);
        }
        if($request->customer_name != ''){
            $data = $data->where('invoices.Attn', $request->customer_name);
        }
        if($request->show != ''){
            if($request->show == 'received'){
                $data = $data->leftJoin('receipts', 'receipts.Invoice_Id','=','invoices.id')->where('receipts.first_received_date', '!=', null)->where('receipts.first_received_date', '!=', '');
            }elseif($request->show == 'unreceived'){
                $data = $data->leftJoin('receipts', 'receipts.Invoice_Id','=','invoices.id')->where('receipts.second_received_date', null);
            }
        }
        if($request->search != ''){
            $data = $data->where('invoices.Invoice_No', 'LIKE', '%'.$request->search.'%')
            ->orWhere('invoices.Company_name', 'LIKE', '%'.$request->search.'%')
            ->orWhere('invoices.Attn', 'LIKE', '%'.$request->search.'%');
        }
        return $data->groupby('invoices.Invoice_No')->distinct();
    }

    protected function searchDataPaginate(Request $request){
        $data = $this->searchData($request);
        return $data->orderBy('invoices.Date','DESC')->paginate(pagination());
    }

    protected function searchDataCount(Request $request){
        $data = $this->searchData($request);
        return $data->get()->count();
    }

    protected function invoiceNoDropDown(){
        return $this->where('Invoice_No', '!=', '')->where('Invoice_No','!=',null)->pluck('Invoice_No', 'Invoice_No');
    }
}
