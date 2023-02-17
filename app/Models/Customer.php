<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;

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

    protected function searchDataPaginate(Request $request){
        $data = $this->where('action',true);
        if($request->company_name != ''){
            $data = $data->where('company', $request->company_name);
        }
        if($request->customer_name != ''){
            $data = $data->where('name', $request->customer_name);
        }
        return $data->orderBy('id','DESC')->paginate(pagination());
    }

    protected function companyDropDown(){
        return $this->where('company','!=', '')->where('company','!=', null)->where('company','!=', '-')->pluck('company', 'company');
    }

    protected function customerDropDown(){
        return $this->where('name','!=', '')->where('name','!=', null)->where('name','!=', '-')->pluck('name', 'name');
    }
}
