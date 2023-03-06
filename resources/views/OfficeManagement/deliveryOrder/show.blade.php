@extends('layouts.setting')
@section('content')

<div class="container-fluid detail-table">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $deliveryOrder->do_code}} 's Detail</h4>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end">
                @if($deliveryOrder->submit_status == true || count($histories) > 0)
                    {{-- <a href="{{ route('OfficeManagement.doPrint', $deliveryOrder->id) }}"
                        class="btn btn-success btn-sm d-none d-lg-block m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                        Print
                    </a> --}}
                    <a href="{{ route('OfficeManagement.doPrint', ['id' =>$deliveryOrder->id, 'pdf' =>'kinzi']) }}"
                        class="btn btn-success btn-sm d-none d-lg-block m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                        Print
                    </a>
                @endif
                <a href="{{ route('OfficeManagement.deliveryOrder.index') }}"
                    class="btn btn-info d-none btn-sm d-lg-block m-l-15"><i class="fa fa-back"></i>
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
         <table class="table table-no-border">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Attn</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Attn}}</td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Company_name}}</td>
                    </tr>
                    <tr>
                        <td>Contact Phone</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Contact_phone}}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Address}}</td>
                    </tr>
                    <tr>
                        <td>Sub</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->sub}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <table class="table table-no-border">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">D.O No.</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->do_code}}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td>{{ date('d-m-Y', strtotime($deliveryOrder->date)) }}</td>
                    </tr>
                    @if($deliveryOrder->quo_id != '')
                    <tr>
                        <td>Ref Quotation No.</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->quo_id}}</td>
                    </tr>
                    @endif
                    @if($deliveryOrder->inv_id != '')
                    <tr>
                        <td>Ref Invoice No.</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->inv_id}}</td>
                    </tr>
                    @endif
                    @if($deliveryOrder->po_no != '')
                    <tr>
                        <td>PO No.</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->po_no}}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- start detail -->
    @if($deliveryOrder->submit_status != '1' && $deliveryOrder->quo_id == '' && $deliveryOrder->inv_id == '')
        @can('do-create')
            <div class="m-b-10">
                <a href="{{ route('OfficeManagement.deliveryOrderDetail.create', ['do_id' => $deliveryOrder->id]) }}"
                    class="btn btn-success btn-sm m-l-15"><i class="fa fa-plus-circle"></i>
                    {{ __('Add Descrition') }}
                </a>
            </div>
        @endcan
    @endif
    <div class="table-responsive bg-white p-10">
        <table class="table table-bordered m-0">
            <thead>
                <tr class="text-center">
                    <th width="50">No</th>
                    <th style="min-width:300px">Description</th>
                    <th width="50">Qty</th>

                    {{-- for quo/inv refer --}}
                    @if($deliveryOrder->quo_id != '' || $deliveryOrder->inv_id != '')
                        <th width="50">Delivered Qty</th>
                        <th width="120">Delivered Date</th>
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
                    <td>{{ $i++ }}</td>
                    <td>{!! $row->name !!}</td>
                    <td class="text-right">{{ $row->qty }}</td>

                    {{-- for quo/inv refer --}}
                    @if($refer_state)
                        <td class="text-right">{{ $delivered_amt }}</td>
                        <td class="text-center">{{ $delivered_date }}</td>
                    @endif

                    @if($deliveryOrder->submit_status != '1')
                        <td class="text-center">
                            @can('do-edit')
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

                            @can('do-delete')
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
    <div class="m-10">
        <div class="row bg-white p-b-20">
            <div class="col-md-6 text-center">
                @if($deliveryOrder->received_sign != '')
                    <img src="{{ asset($deliveryOrder->received_sign) }}" class="img-responsive img-sign" height="100"/> <br>
                @else
                    <img src="{{ asset('signature/blank.png') }}" class="img-responsive img-sign" height="100"/> <br>
                @endif
                @if($deliveryOrder->submit_status != '1')
                    <div>
                        <button type="button" class="btn btn-primary btn-sm m-b-10 sign-button" data-bs-toggle="modal" data-bs-target="#receiver" id="receiverSign">
                            Add Signature
                        </button>
                        @if($deliveryOrder->received_sign != '')
                            <a href="{{ route('OfficeManagement.deliveryOrderSignRemove', ['id' => $deliveryOrder->id, 'sign' => 'received']) }}" class="btn btn-danger btn-sm remove-sign"><i class="fa fa-trash"></i></a>
                        @endif
                    </div>
                @endif
                <h6>Received By</h6>
                @if($deliveryOrder->received_name != '')
                    <h6><b>{{ $deliveryOrder->received_name }}</b></h6>
                @endif
            </div>

            <div class="col-md-6 text-center">
                @if($deliveryOrder->delivered_sign != '')
                    <img src="{{ asset($deliveryOrder->delivered_sign) }}" class="img-responsive img-sign" height="100"/> <br>
                @else
                    <img src="{{ asset('signature/blank.png') }}" class="img-responsive img-sign" height="100"/> <br>
                @endif
                @if($deliveryOrder->submit_status != '1')
                    <div>
                        <button type="button" class="btn btn-primary btn-sm m-b-10 sign-button" data-bs-toggle="modal" data-bs-target="#deliever">
                            Add Signature
                        </button>
                        @if($deliveryOrder->delivered_sign != '')
                            <a href="{{ route('OfficeManagement.deliveryOrderSignRemove', ['id' => $deliveryOrder->id, 'sign' => 'delivered']) }}" class="btn btn-danger btn-sm remove-sign"><i class="fa fa-trash"></i></a>
                        @endif
                    </div>
                @endif
                <h6>Delivered By</h6>
                @if($deliveryOrder->delivered_name != '')
                    <h6><b>{{ $deliveryOrder->delivered_name }}</b></h6>
                @endif
            </div>
        </div>
    </div>

    @if($confirmDeliverButton)
        <div class="text-center ">
            <a href="{{route('OfficeManagement.deliveryOrderConfirmDelivery',$deliveryOrder->id)}}"><p><button class="btn btn-info btn-sm btn-block w-80">Confirm Deliver</button></p></a> 
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
                                        {{-- <a href="{{ route('OfficeManagement.doPrint', ['id' => $deliveryOrder->id, 'date' => $history['date']]) }}" class="btn btn-success btn-sm m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                                            Print
                                        </a> --}}
                                        <a href="{{ route('OfficeManagement.doPrint', ['id' => $deliveryOrder->id, 'date' => $history['date'] , 'pdf' => 'kinzi']) }}" class="btn btn-success btn-sm m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                                            Print
                                        </a>
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

<!-- The receiver -->
<div class="modal" id="receiver">
    <div class="modal-dialog modal-lg">
    {!! Form::open(['route' => ['OfficeManagement.deliveryOrderSign', $deliveryOrder->id], 'method' => 'POST', 'files' => true]) !!}
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Signature for Receiver</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body align-self-center">
                <div class="form-group" style="width: 500px;">
                    <div class="form-group">
                        <label for="">Receiver Name</label>
                        {!! Form::text('received_name', null, ['placeholder' => 'Name', 'class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="" for="">Signature:</label>
                    <button class="btn btn-warning btn-sm float-right m-b-10 clear">Clear</button> <br/>
                    <div style="width: 500px; height: 300px;">
                        <div class="sig" style="width: 500px; height: 300px;"></div>
                        <textarea class="signature64" name="received_sign" style="display: none" required></textarea> 
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    {!! Form::close() !!}
    </div>
</div>

<!-- The receiver -->
<div class="modal" id="deliever">
    <div class="modal-dialog modal-lg">
        {!! Form::open(['route' => ['OfficeManagement.deliveryOrderSign', $deliveryOrder->id], 'method' => 'POST', 'files' => true]) !!}
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Signature for Deliever</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal body -->
            <div class="modal-body align-self-center">
                <div class="form-group" style="width: 500px;">
                    <div class="form-group">
                        <label for="">Deliever Name</label>
                        {!! Form::text('delivered_name', null, ['placeholder' => 'Name', 'class' => 'form-control', 'required']) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="" for="">Signature:</label>
                    <button class="btn btn-warning btn-sm float-right m-b-10 clear">Clear</button> <br/>
                    <div  style="width: 500px; height: 300px;">
                        <div class="sig" style="width: 500px; height: 300px;"></div>
                        <textarea class="signature64" name="delivered_sign" style="display: none" required></textarea> 
                    </div>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    {!! Form::close() !!}
    </div>
</div>
@endsection