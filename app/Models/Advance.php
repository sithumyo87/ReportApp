<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advance extends Model
{
    use HasFactory;
    protected $fillable = [
        'Invoice_Id',
        'Advance_value',
        'Balance',
        'Date',
        'receipt_date',
        'received_date',
        'nth_time'
    ];
}
