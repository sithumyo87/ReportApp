<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'Attn',
        'Company_name',
        'Contact_phone',
        'Address',
        'Sub',
        'Serial_No',
        'Date',
        'Discount',
        'Refer_No',
        'Refer_status',
        'Currency_type',
        'SubmitStatus',
        'Currency_type',
        'sign_name',
        'file_name',
        'Date_INT',
    ];
}
