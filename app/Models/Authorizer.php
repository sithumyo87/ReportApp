<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Authorizer extends Model
{
    use HasFactory;
    protected $fillable = [
        'Authorized_name','file_name'
    ];
}
