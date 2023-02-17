@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Receipt</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('receipt-create')
                    <a href="{{ route('OfficeManagement.receipt.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
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
            {!! Form::open(['method' => 'GET', 'route' => ['OfficeManagement.receipt.index']]) !!}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('rec_code', $rec_codes, @$search->rec_code, ['placeholder' => 'Receipt Number', 'class' => 'form-control select2 input-sm']) !!}
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
                        <a href="{{ route('OfficeManagement.receipt.index') }}" class="btn btn-warning btn-sm">Clear</a>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th style="min-width: 110px">Date</th>
                        <th style="min-width: 170px">Receipt No:</th>
                        <th style="min-width: 170px">Attn Name</th>
                        <th style="min-width: 200px">Company Name</th>
                        <th style="min-width: 200px">Sub</th>
                        <th style="min-width: 110px">Refer No:</th>   
                        <th style="min-width: 110px">Payment Term</th>   
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $row)
                        <tr>
                            <td>{{ $row->Date }}</td>
                            <td class="text-center"><a href="{{route('OfficeManagement.receiptDetail.show',$row->id)}}">{{ $row->Receipt_No }}</a></td>
                            <td>{{ $row->Attn }}</td>
                            <td>{{ $row->Company_name }}</td>
                            <td>{{ $row->Sub }}</td>
                            <td></td>
                            <td>{{ get_pay_term($row->Advance)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {!! $data->render() !!}
    </div>
</div>
@endsection


