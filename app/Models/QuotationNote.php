<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'Note',
        'QuotationId',
        'InvoiceId',
        'list_file',
        'list_name',
    ];
}
