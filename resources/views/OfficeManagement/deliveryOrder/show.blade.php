@extends('layouts.setting')
@section('content')

<div class="container-fluid">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $deliveryOrder->do_code}} 's Detail</h4>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end">
                @if($deliveryOrder->submit_status == true)
                    <a href="{{ route('OfficeManagement.deliveryOrder.create') }}"
                        class="btn btn-success d-none d-lg-block m-l-15 mr-3"><i class="fa fa-print"></i>
                        Print
                    </a>
                @endif
                <a href="{{ route('OfficeManagement.deliveryOrder.index') }}"
                    class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-back"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
    <hr>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
    @endif

    @if(count($histories) > 0)
    <div class="m-b-20">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#doHistory">
            View D.O History
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-md-7">
            <p><b>Attn: </b> {{ $deliveryOrder->Attn}}</p>
            <p><b>Company Name: </b> {{ $deliveryOrder->Company_name}}</p>
            <p><b>Contact Phone: </b> {{ $deliveryOrder->Contact_phone}}</p>
            <p><b>Address: </b> {{ $deliveryOrder->Address}}</p>
            <p><b>Sub: </b>
                {{ $deliveryOrder->sub}}
            </p>
        </div>
        <div class="col-md-5">
            <p><b>D.O No. </b> {{ $deliveryOrder->do_code}}</p>
            <p><b>Date: </b>  {{ date('d-m-Y', strtotime($deliveryOrder->date)) }}</p>
            @if($deliveryOrder->po_no != '')
                <p><b>PO No: </b> - {{ $deliveryOrder->po_no}}</p>
            @endif
        </div>
    </div>

    <!-- start detail -->
    @if($deliveryOrder->submit_status != '1' && $deliveryOrder->quo_id == '' && $deliveryOrder->inv_id == '')
        <div class="m-b-10">
            <a href="{{ route('OfficeManagement.deliveryOrderDetail.create', ['do_id' => $deliveryOrder->id]) }}"
                class="btn btn-success m-l-15"><i class="fa fa-plus-circle"></i>
                {{ __('Add Descrition') }}
            </a>
        </div>
    @endif
    <div class="table-responsive bg-white p-30">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th style="min-width:300px">Description</th>
                    <th width="50">Qty</th>

                    {{-- for quo/inv refer --}}
                    @if($deliveryOrder->quo_id != '' || $deliveryOrder->inv_id != '')
                        <th>Delivered Qty</th>
                        <th>Delivered Date</th>
                    @endif

                    @if($deliveryOrder->submit_status != '1')
                        <th width="100">{{ __('label.action') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(!empty($details))
                <?php 
                    $i = 1;
                    $refer_state = $deliveryOrder->quo_id != '' || $deliveryOrder->inv_id != '';
                    $confirmDeliverButton = false;
                ?>
                @foreach($details as $row)
                <?php 
                    $showDeliverButton      = $refer_state;
                    $editState          = true; 
                    if(isset($detail_records[$row->id])){
                        $last_record        = $detail_records[$row->id];
                        $delivered_amt      = $last_record->amount;
                        $delivered_date     = date('d-m-Y', strtotime($last_record->date));
                        $balance            = $last_record->balance;
                        $editState          = $balance > 0 ? true: false;
                        $showDeliverButton  = $last_record->submit_status == '1';
                        if($showDeliverButton == false){
                            $confirmDeliverButton = true;
                        }
                    }else{
                        $delivered_amt      = 0;
                        $delivered_date     = '-';
                    }
                ?>
                <tr>
                    <td width="50px">{{ $i++ }}</td>
                    <td>{!! $row->name !!}</td>
                    <td class="text-right">{{ $row->qty }}</td>

                    {{-- for quo/inv refer --}}
                    @if($refer_state)
                        <td class="text-right">{{ $delivered_amt }}</td>
                        <td class="text-center">{{ $delivered_date }}</td>
                    @endif

                    @if($deliveryOrder->submit_status != '1')
                        <td class="text-center">
                            @can('user-edit')
                                @if($editState)
                                    @if($refer_state)
                                        @if($showDeliverButton)
                                            <a class="btn btn-sm btn-primary"
                                            href="{{ route('OfficeManagement.deliveryOrderDetail.edit', $row->id) }}"><i class="fa fa-file-export"></i></a>
                                        @endif
                                    @else
                                        <a class="btn btn-sm btn-primary"
                                        href="{{ route('OfficeManagement.deliveryOrderDetail.edit', $row->id) }}"><i class="fa fa-edit"></i></a>
                                    @endif
                                @endif
                            @endcan

                            @can('user-delete')
                                @if($deliveryOrder->submit_status != '1')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.deliveryOrderDetail.destroy',
                                    $row->id], 'style' => 'display:inline']) !!}
                                        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-sm btn-danger', 'onclick' => 'return confirm("Are you sure to delete?")',
                                        'id' => 'delete']) !!}
                                    {!! Form::close() !!}
                                @endif
                            @endcan
                        </td>
                    @endif
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
    <!-- end detail -->

    <!-- authorized Person -->
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-center">
            @if($deliveryOrder->submit_status == '1')
                @if($deliveryOrder->received_sign != "")
                    <img src="{{ asset($deliveryOrder->received_sign)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
                @else
                    <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
                @endif
                <h6>Authorized Person</h6> 
                {!! Form::open(['route' => ['OfficeManagement.invoiceAuthorizer',$deliveryOrder->id],'method' => 'POST']) !!}
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary float-right" type="submit">Add</button>
                    </div>
                </div>
                {!! Form::close() !!}
            @endif
        </div>
    </div>

    @if($confirmDeliverButton)
        <div class="text-center ">
            <a href="{{route('OfficeManagement.deliveryOrderConfirmDelivery',$deliveryOrder->id)}}"><p><button class="btn btn-info btn-block w-80">Confirm Deliver</button></p></a> 
        </div>
    @endif
   
    @if($deliveryOrder->submit_status == 0)
        <div class="text-center ">
            <a href="{{route('OfficeManagement.deliveryOrderConfirm',$deliveryOrder->id)}}"><p><button class="btn btn-primary btn-block w-80">Confirm</button></p></a> 
        </div>
    @endif
</div>

<!-- The Modal -->
<div class="modal" id="doHistory">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Delivery Order</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div class="accordion" id="accordionExample">
                    <?php $heading = 1;?>
                    @foreach ($histories as $history)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{$heading}}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$heading}}" aria-expanded="false" aria-controls="collapse{{$heading}}">
                                    ({{ $heading }}) {{ dateformat($history['date']) }}
                                </button>
                            </h2>
                            <div id="collapse{{$heading}}" class="accordion-collapse collapse" aria-labelledby="heading{{$heading++}}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="50">No</th>
                                                    <th>Description</th>
                                                    <th width="50">Qty</th>
                                                    <th width="50">Delivered Qty</th>
                                                    <th width="50">Left Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $hCount = 1;
                                                    $historyData = $history['data'];
                                                ?>
                                                @foreach($historyData as $historyRow)
                                                <tr class="text-center">
                                                    <td>{{ $hCount++ }}</td>
                                                    <td width="300">{!! $historyRow->name !!}</td>
                                                    <td>{{ $historyRow->qty }}</td>
                                                    <td>{{ $historyRow->amount }}</td>
                                                    <td>{{ $historyRow->balance }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection