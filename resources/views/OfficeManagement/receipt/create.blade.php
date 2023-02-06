@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Receipt</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.receipt.index') }}">Receipt</a></li>
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
        <h3 class="text-center m-b-20">Receipt Create</h3>
        {!! Form::open(['route' => 'OfficeManagement.receipt.store', 'method' => 'POST']) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8">
            <div class="form-group">
                    <label for="inv-select">Invoice No:</label>
                    <select name="Invoice_Id" class="form-control select2" id="inv-select">
                        <option value="">Invoice Number</option>
                        @foreach($invoices as $row)
                            <option value="{{ $row->id }}">{{ $row->Invoice_No}}</option>
                        @endforeach
                    </select>
                </div>

                <div id="inv-data-form">
                    <div class="attn-form">
                        <div class="form-group">
                            <label for="">ATT Name</label>
                            <select name="customer_id" class="form-control attn-customer select2" required>
                                <option value="">Choose Customer</option>
                                @foreach($customers as $row)
                                <option value="{{ $row->id }}">{{ $row->name}}</option>
                                @endforeach
                            </select>
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
                        <label for="">Subject</label>
                        {!! Form::text('Sub', null, ['placeholder' => '', 'class' => 'form-control', 'required', 'id' => 'sub']) !!}
                    </div>
                    <div class="form-group">
                        {{ Form::label('currency', 'Currency') }}
                        <div class="form-inline">
                            @foreach($currency as $row)
                                <div class="form-check mr-4">
                                    <input type="radio" id="{{$row->Currency_name}}" name="Currency_type" class="form-check-input currency" value="{{$row->id}}"> <label class="form-check-label currency" for="{{$row->Currency_name}}">{{$row->Currency_name}}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Payment Term</label>
                        <select name="Advance" class="form-control form-select" required>
                            <option value="">Choose Payment</option>
                            @foreach($payments as $key => $value)
                                <option value="{{ $key }}">{{ ($value)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label" for="Date">Date</label>
                    <input class="form-control date-picker" id="Date" name="Date" placeholder="DD-MM-YYYY" type="text" required/>
                </div>

                <hr>
                <div class="text-center">
                    <a href="{{ route('OfficeManagement.receipt.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection