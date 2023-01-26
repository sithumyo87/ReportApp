@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Invoice Create</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.quotation.index') }}">Invoice</a></li>
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
        <!-- <h3 class="text-center m-b-20">Invoice Create</h3> -->
        {!! Form::open(['route' => 'OfficeManagement.invoice.store', 'method' => 'POST', 'files' => true]) !!}
        <div class="row justify-content-center p-3">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="">Quotation No:</label>
                    <select name="Quotation_Id" class="form-control form-select">
                        <option value="">Quotation No:</option>
                        @foreach($quotations as $row)
                        <option value="{{ $row->id }}">{{ $row->Serial_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">ATT Name</label>
                    <select name="Attn" class="form-control form-select" required>
                        <option value="">choose Customer</option>
                        @foreach($customers as $row)
                        <option value="{{ $row->name }}">{{ $row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="company">Company Name</label>
                    <select name="Company_name" class="form-control form-select" required>
                        <option value="">Company Name</option>
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
                <div class="form-group">
                    <label for="">Subject</label>
                    {!! Form::text('Sub', null, ['placeholder' => '', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {{ Form::label('currency', 'Currency') }}
                    <div class="form-inline">
                    @foreach($currency as $row)
                        <div class="radio mr-4">
                        <input type="radio" name="Currency_type" value="{{$row->id}}"> {{$row->Currency_name}}
                        </div>
                        @endforeach
                    </div>    
                </div>
                <div class="form-group">
                    <label class="control-label" for="Date">Date</label>
                    <input class="form-control" id="Date" name="Date" placeholder="YYY-MM-DD" type="text" />
                </div>
                <div class="form-group">
                    <label for="">Payment Term</label>
                    <select name="Advance" class="form-control form-select" required>
                        <option value="" >choose Payment</option>
                        @foreach($paymentTerms as $row)
                        <option value="{{ $row->id }}">{{ get_payment($row->FirstPay)}}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="">Form31 No</label>
                    {!! Form::text('form31_no', null, ['placeholder' => 'form31 No', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Form31 Issue Date</label>
                    {!! Form::text('form31_issue_date', null, ['placeholder' => 'Form31 Issue Date', 'class' => 'form-control','id'=>'Date','cols'=>5,'rows'=>5]) !!}
                </div>
                <div class="form-group">
                <label for="">FILE UPLOAD</label>
                <input type="file" name="file" class="form-control" accept="image/jpeg,image/png,application/pdf," required>
                </div>

                <br><br>

                <h4>Purchasing Order</h4>
                <hr>
                <label for="chkPo">
                    <input type="checkbox" id="chkPo" />
                    PO :
                </label>
                <div id="dvPo" style="display: none">
                {!! Form::text('po_no', null, ['placeholder' => 'PO:NO', 'class' => 'form-control ']) !!}
                </div>
                <br>
                <label for="chkVender">
                    <input type="checkbox" id="chkVender" />
                    VENDER ID
                </label>
                <div id="dvVender" style="display: none">
                {!! Form::text('vender_id', null, ['placeholder' => 'VENDER ID', 'class' => 'form-control box']) !!}
                </div>
                <br><br><br>
            </div>
            <div class="text-end ">
                    <a href="{{ route('OfficeManagement.quotation.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection