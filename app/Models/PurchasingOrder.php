<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'quo_id',
        'customer_id',
        'Attn',
        'Contact_phone',
        'Company_name',
        'Address',
        'sub',
        'po_code',
        'currency',
        'tax',
        'date',
        'sign_name',
        'file_name',
        'refer_no',
        'submit_status',
        'action'
    ];
}
