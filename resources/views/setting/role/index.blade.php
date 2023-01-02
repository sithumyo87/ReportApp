@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Roles</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
                @can('role-create')
                    <a href="{{ route('setting.role.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New
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
                    <th width="200">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td class="text-right">{{ ++$i }}</td>
                        <td>{{ $role->name }}</td>
                        <td class="text-center">
                            @can('role-show')
                                <a class="btn btn-info" href="{{ route('setting.role.show', $role->id) }}"><i class="fa fa-eye"></i></a>
                            @endcan
                            @can('role-edit')
                                <a class="btn btn-warning" href="{{ route('setting.role.edit', $role->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('role-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['setting.role.destroy', $role->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger', 'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $roles->render() !!}
    </div>
</div>
@endsection
