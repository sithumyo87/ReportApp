@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Delivery Order Detail Edit</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.deliveryOrder.show', $do->id) }}">Delivery Order</a></li>
                    @endcan
                    <li class="breadcrumb-item active">{{ __('label.edit') }}</li>
                </ol>
            </div>
        </div>
    </div>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="bg-white p-30">
        {!! Form::open(['route' => ['OfficeManagement.deliveryOrderDetail.update', $detail->id], 'method' => 'PATCH', 'files' => true]) !!}
        <div class="row justify-content-center p-3">
            <div class="col-xs-8 col-sm-8 col-md-8">
                <div class="inv-quo-group">
                    <div class="form-group">
                        <label for="">Description</label>
                        {!! Form::textarea('name', $detail->name, ['placeholder' => 'Description', 'class' => 'form-control tinymce-editor','cols'=>5,'rows'=>5, 'required']) !!}
                    </div>
                    <div class="form-group">
                        <?php 
                            if($do->submit_status != '1' && $do->quo_id == '' && $do->inv_id == ''){
                                $label = 'Qty';
                                $max = null;
                                $oldQty = $detail->qty;
                            }else{
                                if(isset($last_record)){
                                    $max = $last_record->balance;
                                }else{
                                    $max = $detail->qty;
                                }
                                $label = 'Delivered Qty ( Max Number '. $max .')';
                                $oldQty =  null;
                            }
                        ?>
                        <label for="">{{ $label }}</label>
                        {!! Form::number('qty', $oldQty, ['placeholder' => 'Qty', 'class' => 'form-control', 'min' => '1', 'max' => $max]) !!}
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('OfficeManagement.deliveryOrder.show', $do->id) }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection