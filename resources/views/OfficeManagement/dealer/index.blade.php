@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Dealer</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('dealer-create')
                    <a href="{{ route('OfficeManagement.dealer.create') }}" class="btn btn-success d-none d-lg-block m-l-15 btn-sm"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-t-30">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="bg-white p-30 m-t-30">
        <div class="search">
            {!! Form::open(['method' => 'GET', 'route' => ['OfficeManagement.dealer.index']]) !!}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('company_name', $company_names, @$search->company_name, ['placeholder' => 'Company Name', 'class' => 'form-control select2 input-sm']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('customer_name', $customer_names, @$search->customer_name, ['placeholder' => 'Customer Name', 'class' => 'form-control select2 input-sm']) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-sm">Search</button>
                        <a href="{{ route('OfficeManagement.dealer.index') }}" class="btn btn-warning btn-sm">Clear</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th width="50">{{ __('label.no') }}</th>
                        <th>Attn</th>
                        <th>Position</th>
                        <th>Company</th>
                        <th>Phone No</th>
                        <th>Email</th>
                        <th width="200">Address</th>
                        <th width="120">{{ __('label.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $row)
                        <tr>
                            <td class="text-right">{{ ++$i }}</td>
                            <td style="min-width: 110px;">{{$row->name }}</td>
                            <td style="min-width: 110px;">{{$row->position }}</td>
                            <td style="min-width: 150px;">{{$row->company }}</td>
                            <td style="min-width: 110px;">{{$row->phone }} / {{$row->phone_other }} </td>
                            <td style="min-width: 110px;">{{$row->email }}</td>
                            <td style="min-width: 180px;">{{$row->address }}</td>

                            <td class="text-center"  style="min-width: 110px;">
                                @can('dealer-edit')
                                    <a class="btn btn-primary btn-sm" href="{{ route('OfficeManagement.dealer.edit',$row->id) }}"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('dealer-delete')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.dealer.destroy',$row->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger btn-sm', 'id' => 'delete']) !!}
                                    {!! Form::close() !!}
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $data->render() !!}
    </div>
</div>
@endsection
