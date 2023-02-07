<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryOrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'do_id',
        'quo_id',
        'inv_id',
        'name',
        'qty',
        'disable',
    ];
}
