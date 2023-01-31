@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Authorizer Create</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a href="{{ route('setting.user.index') }}">Authorizer</a></li>
                    @endcan
                    <li class="breadcrumb-item active">Create</li>
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
        <!-- <h3 class="text-center m-b-20"></h3> -->
        {!! Form::open(['route' => 'setting.authorizer.store', 'method' => 'POST','files' => true]) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8 mt-3">
                <div class="form-group">
                    <label for="">Authorizer Name</label>
                    {!! Form::text('authorized_name', null, ['placeholder' => 'Authorizer Name', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Sign</label>
                    <input type="file" name="file" class="form-control" accept="image/jpeg,image/png" required>
                </div>
                <div class="text-center">
                    <a href="{{ route('setting.authorizer.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection