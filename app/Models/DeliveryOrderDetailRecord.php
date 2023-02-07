<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetailRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'do_id',
        'do_detail_id',
        'qty',
        'amount',
        'balance',
        'date',
        'submit_status',
    ];
}
