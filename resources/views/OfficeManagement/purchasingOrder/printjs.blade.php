@extends($layout)
@section('content')
<div class="description-wrap">
    <div class="flex-division bold-font">
        <div class="flex-item1">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="70">Order No</td>
                        <td>:</td>
                        <td width="100">{{ $po->po_code }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td>{{ dateformat($po->date) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex-item2">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="100">Sub</td>
                        <td>:</td>
                        <td> {{ $po->sub}}</td>
                    </tr>
                    <tr>
                        <td>Attn</td>
                        <td>:</td>
                        <td>{{ $po->Attn}}</td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>:</td>
                        <td>{{ $po->Company_name}}</td>
                    </tr>
                    <tr>
                        <td>Contact Phone</td>
                        <td>:</td>
                        <td>{{ $po->Contact_phone}}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $po->Address}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<!-- start detail -->
<div class="table-wrap" style="page-break-inside: always;" autosize="0">
    <table class="table table-bordered table-break">
        <thead>
            <tr>
                <th>Lot No</th>
                <th>Description</th>
                <th>Unit in Price </th>
                <th>QTY</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        @if(!empty($poDetails))
        <tbody>
            <?php 
                $subTotal = 0;
                $i = 0;
            ?>
           @foreach($poDetails as $row)
            <?php
                $subTotal += $row->price * $row->qty;
            ?>
            <tr style="box-decoration-break: clone;">
                <td class="text-center">{{ ++$i }}</td>
                <td>{!! $row->description !!}</td>
                <td class="text-right">{{ $row->price }} {{ $currency->Currency_name }}</td>
                <td class="text-right">{{ $row->qty }}</td>
                <td class="text-right">{{ number_format($row->price * $row->qty,2) }} {{$currency->Currency_name}}{{$currency->Currency_name}}</td>
            </tr>
            @endforeach

            <?php 
                $taxAmount  = $po->tax * ($subTotal)/100;
                $grandTotal = $subTotal + $taxAmount;
            ?>

            <tr>
                <td colspan="2" class="text-right"><b>Total</b></td>
                <td colspan="3" class="text-right"><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
            </tr>

            @if($po->tax > 0)
            <tr>
                <td colspan="2" class="text-right"><b>Tax Amount</b></td>
                <td colspan="3" class="text-right"><b>{{ number_format($taxAmount,2);}} {{$currency->Currency_name}}</b></td>
            </tr>
            @endif

            <tr>
                <td colspan="2" class="text-right"><b>Grand Total</b></td>
                <td colspan="3" class="text-right"><b>{{ number_format($grandTotal,2)}} {{$currency->Currency_name}}</b></td>
            </tr>
        </tbody>
        @endif
    </table>
</div>
<!-- end detail --> 

<div class="note_and_sign">
    <div class="note">
        {{-- Note start --}}
        @if(count($notes) > 0)
            <div class="note-title">Remarks:</div>
            <div class="note-body">
                @foreach($notes as $row)
                    - {{$row->note}}</br>
                @endforeach
            </div>
        @endif
        {{-- Note end --}}
    </div>

    <div class="sign text-center">
        @if($po->file_name != "")
            <img src="{{ public_path($po->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            <b>{{ $po->sign_name }}</b>  <br>
            Next Hop Co.,Ltd.
        @endif
    </div>
</div>

@endsection