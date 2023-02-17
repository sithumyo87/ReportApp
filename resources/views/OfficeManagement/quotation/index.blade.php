@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Quotation</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('quotation-create')
                    <a href="{{ route('OfficeManagement.quotation.create') }}" class="btn btn-success btn-sm d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
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
            {!! Form::open(['method' => 'GET', 'route' => ['OfficeManagement.quotation.index']]) !!}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('quo_no', $quo_nos, @$search->quo_no, ['placeholder' => 'Quotation Number', 'class' => 'form-control select2 input-sm']) !!}
                    </div>
                </div>
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
                        <a href="{{ route('OfficeManagement.quotation.index') }}" class="btn btn-warning btn-sm">Clear</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th style="min-width: 110px">Date</th>
                        <th style="min-width: 170px">Quotation No:</th>
                        <th style="min-width: 180px">Attn Name</th>
                        <th style="min-width: 200px">Company Name</th>
                        <th style="min-width: 200px">Sub</th>
                        <th style="min-width: 170px">Refer No:</th>   
                        <th width="50">{{ __('label.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $row)
                        <tr>
                            <td class="text-right">{{ ++$i }}</td>
                            <td>{{ date('d-m-Y', strtotime($row->Date)) }}</td>
                            <td><a href="{{route('OfficeManagement.quotationDetail.show',$row->id)}}">{{ $row->Serial_No }}</a></td>
                            <td>{{ $row->Attn }}</td>
                            <td>{{ $row->Company_name }}</td>
                            <td>{{ $row->Sub }}</td>
                            <td>{{ $row->Refer_No }}</td>
                            <td class="text-center">
                            @if($row->SubmitStatus != '1')
                                @can('quotation-edit')
                                    <a class="btn btn-sm btn-primary" href="{{ route('OfficeManagement.quotation.edit', $row->id) }}"><i class="fa fa-edit"></i></a>
                                @endcan
                                {{-- @can('user-delete')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotation.destroy', $row->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger btn-sm', 'id' => 'delete', 'onclick' => 'return confirm("Are you sure to delete?")']) !!}
                                    {!! Form::close() !!}
                                @endcan --}}
                            @endif
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


