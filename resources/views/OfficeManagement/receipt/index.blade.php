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
                    <a href="{{ route('OfficeManagement.receipt.create') }}" class="btn btn-success btn-sm d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
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
    <div class="bg-white p-20 m-t-20">
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
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Form::select('customer_name', $customer_names, @$search->customer_name, ['placeholder' => 'Customer Name', 'class' => 'form-control select2 input-sm']) !!}
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
                        <th style="min-width: 110px">Received</th>
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
                            <td class="text-center">
                                @can('receipt-edit')
                                    @if($row->Advance == '6')
                                        <?php $advs = $advances[$row->id];?>
                                        @foreach ($advs as $adv)
                                            @if($adv->received_date != '')
                                                <strong>{{ dateformat($adv->received_date)}}</strong>
                                            @else
                                                <a class="btn btn-sm btn-success receivedButton" data-bs-toggle="modal" data-bs-target="#receivedModel" 
                                                data-id="{{ $row->id }}"
                                                data-type="4">
                                                    <small>{{ advanceFormat($adv->nth_time) }}</small>
                                                    <i class="fa fa-get-pocket"></i>
                                                </a>
                                                <?php break;?>
                                            @endif
                                        @endforeach
                                    @elseif($row->Advance == '4' || $row->Advance == '5')
                                        @if($row->first_received_date != '')
                                            <strong>{{ dateformat($row->first_received_date)}}</strong>
                                        @else
                                            <a class="btn btn-sm btn-success receivedButton" data-bs-toggle="modal"
                                                data-bs-target="#receivedModel" 
                                                data-id="{{ $row->id }}"
                                                data-type="3"><i class="fa fa-get-pocket"></i></a>
                                        @endif
                                    @else
                                        <div class="">
                                        @if($row->first_received_date != '')
                                            <strong>{{ dateformat($row->first_received_date)}}</strong>
                                            <br>
                                            @if($row->second_received_date != '')
                                                <strong>{{ dateformat($row->second_received_date)}}</strong>
                                            @else
                                                <a class="btn btn-sm btn-success receivedButton flex-block" data-bs-toggle="modal" data-bs-target="#receivedModel" 
                                                data-id="{{ $row->id }}"
                                                data-type="2">
                                                    <small>2nd</small>
                                                    <i class="fa fa-get-pocket"></i>
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-sm btn-success receivedButton flex-block" data-bs-toggle="modal" data-bs-target="#receivedModel" 
                                            data-id="{{ $row->id }}"
                                            data-type="1">
                                                <small>1st</small>
                                                <i class="fa fa-get-pocket"></i>
                                            </a>
                                        @endif

                                       
                                        </div>
                                    @endif
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

<!-- Modal -->
    <div class="modal fade" id="receivedModel" tabindex="-1" aria-labelledby="receivedModelLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            {!! Form::open(['route' => ['OfficeManagement.receipt.receive'], 'method' => 'POST']) !!}
                <input type="hidden" value="{{ $data->currentPage() }}" name="page"/>
                <input type="hidden" value="" name="id" id="receivedId"/>
                <input type="hidden" value="" name="type" id="typeId"/>
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


