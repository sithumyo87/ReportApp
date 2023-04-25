<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DeliveryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'Quotation_Id',
        'Attn',
        'Contact_phone',
        'Company_name',
        'Address',
        'sub',
        'do_code',
        'date',
        'quo_id',
        'inv_id',
        'po_no',
        'received_name',
        'received_sign',
        'delivered_name',
        'delivered_sign',
        'submit_status',
        'disable',
    ];

    protected function searchData(Request $request){
        $data = $this->where('id','>',0);
        if($request->do_code != ''){
            $data = $data->where('do_code', $request->do_code);
        } 
        if($request->company_name != ''){
            $data = $data->where('Company_name', $request->company_name);
        }
        if($request->customer_name != ''){
            $data = $data->where('Attn', $request->customer_name);
        }
        if($request->search != ''){
            $data = $data->where('do_code', 'LIKE', '%'.$request->search.'%')
            ->orWhere('Company_name', 'LIKE', '%'.$request->search.'%')
            ->orWhere('Attn', 'LIKE', '%'.$request->search.'%');
        }
        return $data->groupby('id')->distinct();
    }

    protected function searchDataPaginate(Request $request){
        $data = $this->searchData($request);
        return $data->orderBy('date','DESC')->paginate(pagination());
    }

    protected function searchDataCount(Request $request){
        $data = $this->searchData($request);
        return $data->get()->count();
    }

    protected function doNoDropDown(){
        return $this->where('do_code', '!=', '')->where('do_code','!=',null)->pluck('do_code', 'do_code');
    }
}
