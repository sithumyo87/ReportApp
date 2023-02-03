<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankInfoDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'bank_info_id','label_name', 'value_name'
    ];
}
