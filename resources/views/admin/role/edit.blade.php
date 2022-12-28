@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Roles</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('role-index')
                        <li class="breadcrumb-item"><a href="{{ route('admin.role.index') }}">Roles</a></li>
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
        <h3 class="text-center m-b-20">Edit Role</h3>
        {!! Form::model($role, ['method' => 'PATCH', 'route' => ['admin.role.update', $role->id]]) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="form-group">
                    <label for=""><strong>Name:</strong></label>
                    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for=""><strong>Permission:</strong></label>
                    <br />
                    @foreach ($permission as $value)
                        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, ['class' => 'name']) }}
                            {{ $value->name }}</label> &nbsp;&nbsp;&nbsp;&nbsp;
                    @endforeach
                </div>
                <div class="text-center">
                    <a href="{{ route('admin.role.index') }}" class="btn btn-warning">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection
