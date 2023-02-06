<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingOrderNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'po_id',
        'note',
        'list_file',
        'list_name'
    ];
}
