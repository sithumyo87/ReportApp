<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\QuotationNote;

class PurchasingOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'quo_id',
        'customer_id',
        'Attn',
        'Contact_phone',
        'Company_name',
        'Address',
        'sub',
        'po_code',
        'currency',
        'tax',
        'date',
        'sign_name',
        'file_name',
        'refer_no',
        'submit_status',
        'received_date',
        'action'
    ];

    protected function searchData(Request $request){
        $data = $this->select('purchasing_orders.*', 'quotations.Serial_No', 'quotations.Refer_No as rno')->leftJoin('quotations', 'quotations.id','=', 'purchasing_orders.quo_id');
        if($request->po_code != ''){
            $data = $data->where('purchasing_orders.po_code', $request->po_code);
        }
        if($request->company_name != ''){
            $data = $data->where('purchasing_orders.Company_name', $request->company_name);
        }
        if($request->customer_name != ''){
            $data = $data->where('purchasing_orders.Attn', $request->customer_name);
        }
        if($request->show != ''){
            if($request->show == 'received'){
                $data = $data->where('purchasing_orders.received_date', '!=', null)->where('purchasing_orders.received_date', '!=', '');
            }elseif($request->show == 'unreceived'){
                $data = $data->where('purchasing_orders.received_date', null)->where('purchasing_orders.submit_status', 1);
            }
        }

        if($request->search != ''){
            $data = $data->where('purchasing_orders.po_code', 'LIKE', '%'.$request->search.'%')
            ->orWhere('purchasing_orders.Company_name', 'LIKE', '%'.$request->search.'%')
            ->orWhere('purchasing_orders.Attn', 'LIKE', '%'.$request->search.'%');
        }

        return $data->groupby('purchasing_orders.id')->distinct();
    }

    protected function searchDataPaginate(Request $request){
        $data = $this->searchData($request);
        return $data->orderBy('purchasing_orders.date', 'desc')->paginate(pagination());
    }

    protected function searchDataCount(Request $request){
        $data = $this->searchData($request);
        return $data->get()->count();
    }

    protected function getQuoAttachs($data){
        $attachs = [];
        foreach($data as $row){
            $quoAttfile = QuotationNote::getAttFiles($row->quo_id);
            if(count($quoAttfile) > 0){
                $attachs[$row->id] = $quoAttfile;
            }
        }
        return $attachs;
    }

    protected function poNoDropDown(){
        return $this->where('po_code', '!=', '')->where('po_code', '!=', null)->pluck('po_code', 'po_code');
    }
}
