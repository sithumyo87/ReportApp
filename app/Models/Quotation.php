<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Quotation extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'Attn',
        'Company_name',
        'Contact_phone',
        'Address',
        'Sub',
        'Serial_No',
        'Date',
        'Discount',
        'Refer_No',
        'Refer_status',
        'Currency_type',
        'SubmitStatus',
        'Currency_type',
        'sign_name',
        'file_name',
        'Date_INT',
    ];

    protected function searchDataPaginate(Request $request){
        $data = $this->where('id','>',0);
        if($request->quo_no != ''){
            $data = $data->where('Serial_No', $request->quo_no);
        } 
        if($request->company_name != ''){
            $data = $data->where('Company_name', $request->company_name);
        }
        if($request->customer_name != ''){
            $data = $data->where('Attn', $request->customer_name);
        }
        return $data->orderBy('Date','DESC')->paginate(pagination());
    }

    protected function quoNoDropDown(){
        return $this->where('Serial_No', '!=', '')->where('Serial_No','!=',null)->pluck('Serial_No', 'Serial_No');
    }
   
}
