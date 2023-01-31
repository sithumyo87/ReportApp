<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'Invoice_Id',
        'Attn',
        'Company_name',
        'Contact_phone',
        'Address',
        'Sub',
        'Receipt_No',
        'Date',
        'Advance',
        'Discount',
        'Tax',
        'Currency_type',
        'Refer_status',
    ];
}
