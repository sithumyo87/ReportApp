<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'Quotation_Id',
        'Invoice_Id',
        'Description',
        'Unit_Price',
        'Qty',
        'percent',
        'dealer_id',
        'form31_no',
        'invoice_no',
        'tax',
        'tax_amount',
    ];
}
