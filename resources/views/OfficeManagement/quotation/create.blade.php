@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ __('navbar.quotations') }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right font-13">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.quotation.index') }}">{{ __('Quotation') }}</a></li>
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
        <h4 class="text-center m-b-20">Quotation Create</h4>
        {!! Form::open(['route' => 'OfficeManagement.quotation.store', 'method' => 'POST']) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="form-group">
                    <label for="">Refer No:</label>
                    <select name="refer_no" class="form-control">
                        <option value="">Refer No:</option>
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
                    {!! Form::text('Sub', null, ['placeholder' => '', 'class' => 'form-control', 'required']) !!}
                </div>

                <div class="form-group">
                    <label class="control-label" for="Date">Date</label>
                    <input class="form-control date-picker" name="date" placeholder="DD-MM-YYYY" type="text" required/>
                </div>

                <div class="form-group">
                    {{ Form::label('currency', 'Currency') }}
                    <div class="form-inline">
                    @foreach($currency as $row)
                        <div class="radio mr-4">
                            <label>
                                <input type="radio" name="Currency_type" value="{{$row->id}}" required> &nbsp; {{$row->Currency_name}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <hr>

                <div class="text-center">
                    <a href="{{ route('OfficeManagement.quotation.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection