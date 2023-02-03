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

    protected function getAttFiles($id){
        $attachs = $this->where('QuotationId', $id)->where('quotation_notes.list_file', '!=', null)->get();
        $result = [];
        foreach($attachs as $attach){
            $array = [
                'list_name' => $attach->list_name != '' ? $attach->list_name : str_replace('attachments/', '', $attach->list_file),
                'list_file' => $attach->list_file
            ];
            array_push($result, $array);
        }
        return $result;
    }
}
