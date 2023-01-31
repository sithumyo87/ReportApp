@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ __('navbar.login_accounts') }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                        <li class="breadcrumb-item"><a href="{{ route('setting.user.index') }}">{{ __('navbar.login_accounts') }}</a></li>
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
        <h3 class="text-center m-b-20">Quotation Edit</h3>
        {!! Form::model($quotation, ['method' => 'PATCH', 'route' => ['OfficeManagement.quotation.update', $quotation->id]]) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8">
            <div class="form-group">
                    <label for="">Refer No:</label>
                    <select name="refer_no" class="form-control">
                        <option value="">Refer No:</option>
                        @foreach($quotations as $row)
                        <option value="{{ $row->id }}" <?php if($row->id == $quotation->Refer_No) echo "selected" ?>>{{ $row->Serial_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">ATT Name</label>
                    <select name="customer_id" class="form-control" required>
                        <option value="">choose Customer</option>
                        @foreach($customers as $row)
                        <option value="{{ $row->id }}" <?php if($row->id == $quotation->customer_id) echo "selected" ?>>{{ $row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="company">Company Name</label>
                    <select name="Company_name" class="form-control" required>
                        <option value="">Company Name</option>
                        @foreach($customers as $row)
                        <option value="{{ $row->company }}" <?php if($row->company == $quotation->Company_name) echo "selected" ?>>{{ $row->company}}</option>
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
                    <label class="control-label" for="Date">Date</label>
                    <input class="form-control" id="Date" name="date" placeholder="YYY-MM-DD" type="text" value="{{$quotation->Date}}"/>
                </div>
                <div class="form-group">
                    {{ Form::label('currency', 'Currency') }}
                    <div class="form-inline">
                    @foreach($currency as $row)
                        <div class="radio mr-4">
                        <input type="radio" name="Currency_type" value="{{$row->id}}" <?php if($row->id == $quotation->Currency_type) echo "checked"?>> {{$row->Currency_name}}
                        </div>
                        @endforeach
                    </div>
                    
                </div>
                <hr>
                <div class="text-center">
                    <a href="{{ route('OfficeManagement.quotation.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.update') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
