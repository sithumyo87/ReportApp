<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'Quotation_Id',
        'Attn',
        'Contact_phone',
        'Company_name',
        'Address',
        'sub',
        'do_code',
        'date',
        'quo_id',
        'inv_id',
        'po_no',
        'received_name',
        'received_sign',
        'delivered_name',
        'delivered_sign',
        'submit_status',
        'disable',
    ];
}
