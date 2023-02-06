<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'Invoice_Id',
        'Quotation_Id',
        'Attn',
        'Company_name',
        'Contact_phone',
        'Address',
        'Sub',
        'Receipt_No',
        'Date',
        'frec_date',
        'srec_date',
        'Discount',
        'Tax',
        'Advance',
        'First_Receipt',
        'Second_Receipt',
        'Refer_status',
        'sign_name',
        'Currency_type',
    ];
}
