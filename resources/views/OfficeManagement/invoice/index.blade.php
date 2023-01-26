@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Invoice</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('role-create')
                    <a href="{{ route('OfficeManagement.invoice.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
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
            <thead>
                <tr class="text-center">
                    <th>Date</th>
                    <th>Invoice No:</th>
                    <th>Attn Name</th>
                    <th>Company Name</th>
                    <th>Sub</th>
                    <th>Payment Term</th>   
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $row)
                    <tr>
                        <td>{{ $row->Date }}</td>
                        <td><a href="{{route('OfficeManagement.quotationDetail.show',$row->id)}}">{{ $row->Invoice_No }}</a></td>
                        <td>{{ $quotation->Attn }}</td>
                        <td>{{ $row->Company_name }}</td>
                        <td>{{ $row->Sub }}</td>
                        <td>{{ $row->Refer_No }}</td>
                        <td class="text-center">
                            @can('user-edit')
                                <a class="btn btn-primary" href="{{ route('OfficeManagement.invoice.edit', $row->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('user-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.invoice.destroy', $row->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger', 'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $data->render() !!}
    </div>
</div>
@endsection


