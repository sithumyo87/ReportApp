
@extends('layouts.admin')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Permission</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Permission</li>
                </ol>
                @can('permission-create')
                    <a href="{{ route('admin.permission.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="bg-white p-30">
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
                                <a class="btn btn-primary" href="{{ route('admin.permission.edit', $permission->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('permission-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['admin.permission.destroy', $permission->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger', 'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $permissions->render() !!}
    </div>
</div>
@endsection