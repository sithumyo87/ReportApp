@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Purchasing Order Create</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a href="{{ route('OfficeManagement.purchasingOrder.index') }}">Purchasing Order</a></li>
                    @endcan
                    <li class="breadcrumb-item active">{{ __('label.create') }}</li>
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
        {!! Form::open(['route' => 'OfficeManagement.purchasingOrder.store', 'method' => 'POST', 'files' => true]) !!}
        <div class="row justify-content-center p-3">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="">Quotation No:</label>
                    <select name="quo_id" class="form-control select2">
                        <option value="">Quotation No:</option>
                        @foreach($quotations as $row)
                        <option value="{{ $row->id }}">{{ $row->Serial_No}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="attn-form">
                    <div class="form-group">
                        <label for="">ATT Name</label>
                        <select name="customer_id" class="form-control attn-customer select2" required>
                            <option value="">Choose Customer</option>
                            @foreach($customers as $row)
                            <option value="{{ $row->id }}">{{ $row->name}}</option>
                            @endforeach
                        </select>
                        {!! Form::hidden('attn', null, ['id' => 'attn-name']) !!}
                    </div>
                    <div class="form-group">
                        <label for="company">Company Name</label>
                        <select name="Company_name" class="form-control attn-company select2" required>
                            <option value="">Choose Company Name</option>
                            @foreach($customers as $row)
                            <option value="{{ $row->company }}">{{ $row->company}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Phone No</label>
                        {!! Form::text('Contact_phone', null, ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        <label for="">Address</label>
                        {!! Form::textarea('Address', null, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5]) !!}
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::label('sub', 'Subject') }}
                    {!! Form::text('sub', null, ['placeholder' => 'Subject', 'class' => 'form-control', 'sub' => null, 'required']) !!} 
                </div>

                <div class="form-group">
                    {{ Form::label('currency', 'Currency') }}
                    <div class="form-inline">
                        @foreach($currency as $row)
                            <div class="form-check mr-4">
                                <input type="radio" id="{{$row->Currency_name}}"name="currency" class="form-check-input" value="{{$row->id}}" required> <label class="form-check-label" for="{{$row->Currency_name}}">{{$row->Currency_name}}</label>
                            </div>
                        @endforeach
                    </div>    
                </div>

                <div class="form-group">
                    <label class="control-label" for="Date">Date</label>
                    <input class="form-control date-picker" id="Date" name="date" placeholder="DD-MM-YYYY" type="text" required/>
                </div>

                <div class="text-end ">
                    <a href="{{ route('OfficeManagement.purchasingOrder.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection