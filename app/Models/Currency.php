<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'Currency_name', 'UnitPrice', 'symbol', 'detail', 'detail_print'
    ];
}
