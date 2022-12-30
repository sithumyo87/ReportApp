<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Model
{
    use HasFactory,HasRoles;

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
