@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Delivery Order</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('role-create')
                    <a href="{{ route('OfficeManagement.deliveryOrder.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
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
    <div class="bg-white p-30 m-t-30">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Date</th>
                    <th>D.O No:</th>
                    <th>Attn Name</th>
                    <th>Company Name</th>
                    <th>Sub</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>{{ $row->date }}</td>
                        <td><a href="{{route('OfficeManagement.deliveryOrder.show',$row->id)}}">{{ $row->do_code }}</a></td>
                        <td>{{ $row->Attn }}</td>
                        <td>{{ $row->Company_name }}</td>
                        <td>{{ $row->sub }}</td>
                        <td class="text-center">
                        @if($row->submit_status == '0')
                            @can('user-edit')
                                <a class="btn btn-sm btn-primary" href="{{ route('OfficeManagement.deliveryOrder.edit', $row->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('user-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.deliveryOrder.destroy', $row->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger btn-sm', 'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $data->render() !!}
    </div>
</div>
@endsection


