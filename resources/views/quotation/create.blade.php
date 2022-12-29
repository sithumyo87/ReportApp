@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ __('navbar.quotations') }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a href="{{ route('admin.user.index') }}">{{ __('navbar.login_accounts') }}</a></li>
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
        <h3 class="text-center m-b-20">{{ __('label.user') }}{{ __('label.create') }}</h3>
        {!! Form::open(['route' => 'admin.user.store', 'method' => 'POST']) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="form-group">
                    <label for="">Refer No</label>
                    {!! Form::select('roles[]', $roles, [], ['class' => 'form-control','placeholder' => 'Please Select' ]) !!}
                </div>
                <div class="form-group">
                    <label for="">ATT Name</label>
                    {!! Form::select('roles[]', $roles, [], ['class' => 'form-control','placeholder' => 'Please Select' ]) !!}
                </div>
                <div class="form-group">
                    <label for="">Company Name</label>
                    {!! Form::select('roles[]', $roles, [], ['class' => 'form-control','placeholder' => 'Please Select' ]) !!}
                </div>
                <div class="form-group">
                    <label for="">Phone No</label>
                    {!! Form::password('confirm-password', ['placeholder' => '', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Subject</label>
                    {!! Form::text('name', null, ['placeholder' => '', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Date</label>
                    {!! Form::text('name', null, ['placeholder' => '', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {{ Form::label('currency', 'Currency') }}
                    <div class="form-inline">
                        <div class="radio">
                            {{ Form::label('usd', 'USD') }}
                            {{ Form::radio('currency', 'true', true) }}
                        </div>
                        <div class="radio pl-2">
                            {{ Form::label('mmk', 'MMK') }}
                            {{ Form::radio('currency', 'false') }}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="text-center">
                    <a href="{{ route('admin.user.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection