
@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Permission</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('permission-create')
                    <a href="{{ route('setting.permission.create') }}" class="btn btn-success btn-sm d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success m-t-30">
            {{ $message }}
        </div>
    @endif

    <div class="bg-white p-30 m-t-30">
        <div class="search">
            {!! Form::open(['method' => 'GET', 'route' => ['setting.permission.index']]) !!}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::text('name', @$search['name'], ['placeholder' => 'Name', 'class' => 'form-control input-sm']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        <a href="{{ route('setting.permission.index') }}" class="btn btn-warning btn-sm">Clear</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="text-center">
                    <tr>
                        <th width="50">No</th>
                        <th>Name</th>
                        <th class="text-center" width="200">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $key => $permission)
                        <tr>
                            <td class="text-right">{{ ++$page }}</td>
                            <td>{{ $permission->name }}</td>
                            <td class="text-center">
                                @can('permission-edit')
                                    <a class="btn btn-primary btn-sm" href="{{ route('setting.permission.edit', $permission->id) }}"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('permission-delete')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['setting.permission.destroy', $permission->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger btn-sm', 'id' => 'delete']) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-right">
            {!! $permissions->render() !!}
        </div>
    </div>
</div>
@endsection