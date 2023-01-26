<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    'bank_info_mmk',
    'submit_status',
    'customer_tax_id',
    'Currency_type',
    'po_no',
    'vender_id',
    'form31_no',
    'form31_issue_date',
   'form31_files'
    ];


    
}
