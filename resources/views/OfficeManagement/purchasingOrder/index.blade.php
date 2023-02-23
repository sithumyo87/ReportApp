@extends('layouts.setting')
@section('content')
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h4 class="text-themecolor">Purchasing Order</h4>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <div class="d-flex justify-content-end align-items-center">
                    @can('po-create')
                        <a href="{{ route('OfficeManagement.purchasingOrder.create') }}"
                            class="btn btn-success btn-sm d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>
                            {{ __('button.create') }}
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
        <div class="table-responsive bg-white p-30 m-t-30">
            <div class="search">
                {!! Form::open(['method' => 'GET', 'route' => ['OfficeManagement.purchasingOrder.index']]) !!}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::select('po_code', $po_codes, @$search['po_code'], [
                                'placeholder' => 'PO Number',
                                'class' => 'form-control select2 input-sm',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::select('company_name', $company_names, @$search['company_name'], [
                                'placeholder' => 'Company Name',
                                'class' => 'form-control select2 input-sm',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::select('customer_name', $customer_names, @$search->customer_name, [
                                'placeholder' => 'Customer Name',
                                'class' => 'form-control select2 input-sm',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::select('show', ['received'=>'received','unreceived'=>'unreceived'], @$search->show, [
                                'placeholder' => 'Show',
                                'class' => 'form-control select2 input-sm',
                            ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            <a href="{{ route('OfficeManagement.purchasingOrder.index') }}"
                                class="btn btn-warning btn-sm">Clear</a>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th style="min-width: 110px">{{ __('label.date') }}</th>
                        <th style="min-width: 170px">PO No:</th>
                        <th style="min-width: 180px">Attn Name</th>
                        <th style="min-width: 200px">Company Name</th>
                        <th style="min-width: 200px">Address</th>
                        <th style="min-width: 200px">Sub</th>
                        <th style="min-width: 180px">Quotation No:</th>
                        <th style="min-width: 200px">Refer No:</th>
                        <th>{{ __('label.action') }}</th>
                        <th style="min-width: 110px">Received</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <td class="text-right">{{ ++$i }}</td>
                            <td class="text-center">{{ date('d-m-Y', strtotime($row->date)) }}</td>
                            <td><a href="{{ route('OfficeManagement.purchasingOrder.show', $row->id) }}">{{ $row->po_code }}</a>
                            </td>
                            <td>{{ $row->Attn }}</td>
                            <td>{{ $row->Company_name }}</td>
                            <td>{{ $row->Address }}</td>
                            <td>{{ $row->sub }}</td>
                            <td>{{ $row->Serial_No ?? '-' }}</td>
                            <td>
                                {{ $row->Refer_No ?? '-' }}
                                @if (isset($attachs[$row->id]))
                                    @foreach ($attachs[$row->id] as $attach)
                                        <a href="{{ asset($attach['list_file']) }}" target="_blank"
                                            class="refer-attach">{{ $attach['list_name'] }}</a>,
                                        &nbsp;
                                    @endforeach
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($row->submit_status != '1')
                                    @can('po-edit')
                                        <a class="btn btn-sm btn-primary"
                                            href="{{ route('OfficeManagement.purchasingOrder.edit', $row->id) }}"><i
                                                class="fa fa-edit"></i></a>
                                    @endcan
                                @endif
                            </td>
                            <td class="text-center">
                                @can('po-edit')
                                @if($row->submit_status == 1)
                                        @if($row->received_date != '')
                                            <strong>{{ date('d-m-Y', strtotime($row->received_date))}}</strong>
                                        @else
                                            <a class="btn btn-sm btn-success receivedButton" data-bs-toggle="modal"
                                                data-bs-target="#receivedModel" data-id="{{ $row->id }}"><i class="fa fa-get-pocket"></i></a>
                                        @endif
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $data->withQueryString()->links() !!}
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="receivedModel" tabindex="-1" aria-labelledby="receivedModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            {!! Form::open(['route' => ['OfficeManagement.purchasingOrder.poReceive'], 'method' => 'POST']) !!}
                <input type="hidden" value="{{ $data->currentPage() }}" name="page"/>
                <input type="hidden" value="" name="id" id="receivedId"/>
                <div class="modal-header">
                    <h5 class="modal-title" id="receivedModelLabel">Confirm Receive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                      <label for="rdate">Received Date</label>
                      <input type="text" name="received_date" id="rdate" class="form-control input-sm date-picker" placeholder="" aria-describedby="helpId" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Confirm</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
