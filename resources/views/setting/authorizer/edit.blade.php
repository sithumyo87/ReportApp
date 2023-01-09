@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Currency Edit</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                        <li class="breadcrumb-item"><a href="{{ route('OfficeManagement.currency.index') }}">currency</a></li>
                    @endcan
                    <li class="breadcrumb-item active">Edit</li>
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
    <div class="bg-white p-30 ">
        <!-- <h3 class="text-center m-b-20">{{ __('label.user') }}{{ __('label.edit') }}</h3> -->
        {!! Form::model($currency, ['method' => 'PATCH', 'route' => ['OfficeManagement.currency.update', $currency->id]]) !!}
        <div class="row justify-content-center mt-3">
            <div class="col-xs-12 col-sm-12 col-md-8">
            <div class="form-group">
                    <label for="">Currency Name</label>
                    {!! Form::text('Currency_name', null, ['placeholder' => 'Currency Name', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Unit Price</label>
                    {!! Form::text('UnitPrice', null, ['placeholder' => 'UnitPrice', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Symbol</label>
                    {!! Form::text('symbol', null, ['placeholder' => 'Symbol', 'class' => 'form-control']) !!}
                </div>
                <div class="text-center">
                    <a href="{{ route('setting.user.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.update') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
