@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Delivery Order Edit</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.deliveryOrder.index') }}">Delivery Order</a></li>
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
        {!! Form::model($deliveryOrder, ['route' => ['OfficeManagement.deliveryOrder.update', $deliveryOrder->id], 'method' => 'PATCH', 'files' => true]) !!}
        <div class="row justify-content-center p-3">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="control-label" for="date">Date</label>
                    {!! Form::text('date', null, ['placeholder' => 'DD-MM-YYYY', 'class' => 'form-control date-picker', 'id' => 'date']) !!}
                </div>
                <div class="inv-quo-group">
                    <div class="form-group">
                        {{ Form::label('sub', 'Subject') }}
                        {!! Form::text('sub', null, ['placeholder' => 'Subject', 'class' => 'form-control', 'required']) !!}
                    </div>
                    <div class="form-group">
                        <label for="">ATT Name</label>
                        {!! Form::text('Attn', null, ['placeholder' => 'ATT Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="company">Company Name</label>
                        {!! Form::text('Company_name', null, ['placeholder' => 'Company Name', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="">Contact phone</label>
                        {!! Form::text('Contact_phone', null, ['placeholder' => 'Contact phone', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        {!! Form::textarea('Address', null, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5]) !!}
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="quo_no">
                        <input type="checkbox" id="quo-cb" class="form-check-input" value="quo_no" @if($deliveryOrder->quo_id != '') checked @endif> <label class="form-check-label" for="quo-cb">Quotation</label>
                    </label>
                    <select name="quo_id" class="form-control select2" id="quo_no" @if($deliveryOrder->quo_id == '') disabled @endif>
                        <option value="">Quotation No:</option>
                        @foreach($quotations as $row)
                        <option value="{{ $row->id }}" @if($deliveryOrder->quo_id == $row->id) selected @endif>{{ $row->Serial_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="inv_no">
                        <input type="checkbox" id="inv-cb" class="form-check-input" value="inv_no" @if($deliveryOrder->inv_id != '') checked @endif> <label class="form-check-label" for="inv-cb">Invoice</label>
                    </label>
                    <select name="inv_id" class="form-control select2" id="inv_no" @if($deliveryOrder->inv_id == '') disabled @endif>
                        <option value="">Invoice No:</option>
                        @foreach($invoices as $inv)
                        <option value="{{ $inv->id }}" @if($deliveryOrder->inv_id == $inv->id) selected @endif>{{ $inv->Invoice_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="po_no">
                        <input type="checkbox" id="po-cb" class="form-check-input" value="po_no" @if($deliveryOrder->po_no != '') checked @endif> <label class="form-check-label" for="po-cb">P.O No.</label>
                    </label>
                    {!! Form::text('po_no', null, ['placeholder' => 'P.O Number', 'class' => 'form-control', 'id' => 'po_no', ($deliveryOrder->po_no != '') ? '' : 'readonly']) !!}
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('OfficeManagement.deliveryOrder.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection