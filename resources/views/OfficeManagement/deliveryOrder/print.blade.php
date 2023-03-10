@extends($layout)
@section('content')

@if($layout == 'layouts.mpdf')
<div class="description-wrap">
    <div class="flex-division bold-font">
        <div class="flex-item1">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="100">D.O No:</td>
                        <td width="10">:</td>
                        <td>{{ $deliveryOrder->do_code }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td>{{ $date != '' ? dateformat($date) : dateformat($deliveryOrder->date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex-item2">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="100">Attn</td>
                        <td width="10">:</td>
                        <td>{{ $deliveryOrder->Attn }}</td>
                    </tr>
                    <tr>
                        <td>Sub</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->sub }} </td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Company_name}}</td>
                    </tr>
                    <tr>
                        <td>Contact Phone</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Contact_phone }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Address }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@else
<div class="description-wrap">
    <div class="flex-division bold-font">
        <div class="flex-item2">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="120">Attn</td>
                        <td width="10">:</td>
                        <td>{{ $deliveryOrder->Attn }}</td>
                    </tr>
                    <tr>
                        <td>Sub</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->sub }} </td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Company_name}}</td>
                    </tr>
                    <tr>
                        <td>Contact Phone</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Contact_phone }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $deliveryOrder->Address }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex-item1">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="120">D.O No:</td>
                        <td width="10">:</td>
                        <td>{{ $deliveryOrder->do_code }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td>{{ $date != '' ? dateformat($date) : dateformat($deliveryOrder->date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- start detail -->
<div class="table-wrap">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="50">No</th>
                <th style="min-width: 300px;">Description</th>
                <th width="50">QTY</th>
                @if($date != '')
                    <th width="100">Delivered Qty</th>
                    <th width="100">Left Qty</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @if(!empty($details))
                <?php 
                    $subTotal = 0;
                    $i = 0;
                ?>
                @foreach($details as $row)
                <?php
                    if(isset($detail_records[$row->id])){
                        $last_record        = $detail_records[$row->id];
                        $delivered_amt      = $last_record->amount;
                        $balance            = $last_record->balance;
                    }else{
                        $balance            = '-';
                        $delivered_amt      = '-';
                    }
                ?>
                <tr>
                    <td class="text-center">{{ ++$i }}</td>
                    <td>{!! $row->name !!}</td>
                    <td class="text-right">{{ $row->qty }}</td>
                    @if($date != '')
                        <td class="text-right">{{ $delivered_amt }}</td>
                        <td class="text-center">{{ $balance }}</td>
                    @endif
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
<!-- end detail -->

<div class="deliver_receiver">
    <div class="deliver text-center">
        @if($deliveryOrder->received_sign != "")
            <img src="{{ $layout == 'layouts.mpdf' ? public_path($deliveryOrder->received_sign) : asset($deliveryOrder->received_sign)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            Received By<br>
            <b>{{ $deliveryOrder->received_name }}</b>
        @else
            <img src="{{ $layout == 'layouts.mpdf' ? public_path('signature/blank.png') : asset('signature/blank.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            Received By<br>
            <b></b>
        @endif
    </div>

    <div class="receiver text-center">
        @if($deliveryOrder->delivered_sign != "")
            <img src="{{ $layout == 'layouts.mpdf' ? public_path($deliveryOrder->delivered_sign) : asset($deliveryOrder->delivered_sign)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            Delivered By<br>
            <b>{{ $deliveryOrder->delivered_name }}</b>
        @else
            <img src="{{ $layout == 'layouts.mpdf' ? public_path('signature/blank.png')  : asset('signature/blank.png') }}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            Delivered By<br>
            <b></b>
        @endif
    </div>
</div>

@endsection