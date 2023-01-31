@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">PaymentTerm Edit</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                        <li class="breadcrumb-item"><a href="{{ route('OfficeManagement.paymentTerm.index') }}">PaymentTerm</a></li>
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
        {!! Form::model($paymentTerm, ['method' => 'PATCH', 'route' => ['OfficeManagement.paymentTerm.update', $paymentTerm->id]]) !!}
        <div class="row justify-content-center mt-3">
            <div class="col-xs-12 col-sm-12 col-md-8">
            <div class="form-group">
                    <label for="">First Pay</label>
                    {!! Form::text('FirstPay', null, ['placeholder' => 'First Pay', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Second Pay</label>
                    {!! Form::text('SecondPay', null, ['placeholder' => 'Second Pay', 'class' => 'form-control']) !!}
                </div>
                <div class="text-center">
                    <a href="{{ route('OfficeManagement.paymentTerm.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.update') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
