<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'po_id',
        'dealer_id',
        'description',
        'price',
        'qty',
        'form31_no',
        'invoice_no',
        'tax'
    ];
}
