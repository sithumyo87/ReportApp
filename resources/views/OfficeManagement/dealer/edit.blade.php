@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Dealer Edit</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                        <li class="breadcrumb-item"><a href="{{ route('OfficeManagement.dealer.index') }}">Dealer</a></li>
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
        {!! Form::model($dealer, ['method' => 'PATCH', 'route' => ['OfficeManagement.dealer.update', $dealer->id]]) !!}
        <div class="row justify-content-center mt-3">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="form-group">
                    <label for="">Name</label>
                    {!! Form::text('name', null, ['placeholder' => 'Dealer Name', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Position</label>
                    {!! Form::text('position', null, ['placeholder' => 'Position', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Phone Number</label>
                    {!! Form::text('phone', null, ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Phone Number(Other)</label>
                    {!! Form::text('phone_other', null, ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Company</label>
                    {!! Form::text('company', null, ['placeholder' => 'company name', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">E-mail</label>
                    {!! Form::text('email', null, ['placeholder' => 'Email Address', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Address</label>
                    {!! Form::text('address', null, ['placeholder' => 'Address', 'class' => 'form-control' , 'cols'=>5,'rows'=>5]) !!}
                </div>
                <div class="text-center">
                    <a href="{{ route('OfficeManagement.dealer.index') }}" class="btn btn-warning btn-sm">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('button.update') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
