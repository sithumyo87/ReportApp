<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'Invoice_Id',
        'Quotation_Id',
        'Attn',
        'Company_name',
        'Contact_phone',
        'Address',
        'Sub',
        'Receipt_No',
        'Date',
        'frec_date',
        'srec_date',
        'Discount',
        'Tax',
        'Advance',
        'First_Receipt',
        'Second_Receipt',
        'Refer_status',
        'sign_name',
        'file_name',
        'Currency_type',
        'first_received_date',
        'second_received_date'
    ];

    protected function searchDataPaginate(Request $request){
        $data = $this->where('id','>',0);
        if($request->rec_code != ''){
            $data = $data->where('Receipt_No', $request->rec_code);
        } 
        if($request->company_name != ''){
            $data = $data->where('Company_name', $request->company_name);
        }
        if($request->customer_name != ''){
            $data = $data->where('Attn', $request->customer_name);
        }
        if($request->show != ''){
            if($request->show == 'received'){
                $data = $data->where('first_received_date', '!=', null)->where('first_received_date', '!=', '');
            }elseif($request->show == 'unreceived'){
                $data = $data->where('first_received_date', null);
            }
        }
        return $data->orderBy('Date','DESC')->paginate(pagination());
    }

    protected function receiptNoDropDown(){
        return $this->where('Receipt_No', '!=', '')->where('Receipt_No','!=',null)->pluck('Receipt_No', 'Receipt_No');
    }
}
