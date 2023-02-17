<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonInvoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'name',
        'position',
        'phone',
        'phone_other',
        'company',
        'email',
        'address',
        'action',
    ];
}
