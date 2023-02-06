@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $bankInfo->name }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a href="{{ route('OfficeManagement.bankInfo.index') }}">Bank Info</a></li>
                    @endcan
                    <li class="breadcrumb-item active">{{ $bankInfo->name }}</li>
                </ol>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
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
        
    <div class="bg-white p-30 m-t-30">
        <div class="">
            @can('role-create')
                <a href="{{ route('OfficeManagement.bankInfoDetail.create', $bankInfo->id) }}" class="btn btn-success"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
                </a>
            @endcan
        </div>
        <table class="table table-bordered m-t-10">
            <thead>
                <tr class="text-center">
                    <th width="50">{{ __('label.no') }}</th>
                    <th>Label</th>
                    <th>Value</th>
                    <th width="120">{{ __('label.action') }}</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0;?>
                @foreach ($bankInfoDetails as $detail)
                    <tr>
                        <td class="text-right">{{ ++$i }}</td>
                        <td>{{$detail->label_name }}</td>
                        <td>{{$detail->value_name }}</td>
                        <td class="text-center">
                            @can('user-edit')
                                <a class="btn btn-primary" href="{{ route('OfficeManagement.bankInfoDetail.edit', $detail->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('user-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.bankInfoDetail.destroy', $detail->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger', 'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
