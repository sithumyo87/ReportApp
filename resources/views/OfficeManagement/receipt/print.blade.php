@extends('layouts.pdf')
@section('content')

<div class="description-wrap">
    <div class="flex-division bold-font">
        <div class="flex-item1">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="70">Receipt No </td>
                        <td>:</td>
                        <td width="100">{{ $receipt->Receipt_No }}</td>
                    </tr>
                    <tr>
                        <td>Receipt Date</td>
                        <td>:</td>
                        <td>{{ getReceiptDate($receipt, $type, $advance_data) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex-item2">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="70">Attn</td>
                        <td>:</td>
                        <td>{{ $receipt->Attn }}</td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>:</td>
                        <td>{{ $receipt->Company_name }}</td>
                    </tr>
                    <tr>
                        <td>Contact Phone</td>
                        <td>:</td>
                        <td>{{ $receipt->Contact_phone }}</td>
                    </tr>
                    <tr>
                        <td>Sub</td>
                        <td>:</td>
                        <td>{{ $receipt->Sub }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $receipt->Address }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </div>
</div>

<!-- start detail -->
<div class="table-wrap">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="20">Lot No</th>
                <th>Service Description</th>
                <th>Unit in Price <br> ({{$currency->Currency_name}}) </th>
                <th>QTY</th>
                <th>Total In Price <br> ({{$currency->Currency_name}})</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($invDetails))
            <?php 
                $subTotal = 0;
                $subTotalWithPer = 0;
                $i = 0;
            ?>
            @foreach($invDetails as $row)
            <?php
                $subTotal += $row->Unit_Price * $row->Qty;
                $subTotalWithPer += percent_price($row->Unit_Price, $row->percent) * $row->Qty;
            ?>
            <tr>
                <td width="10%"  class="text-center">{{ ++$i }}</td>
                <td width="40%" width="40%" >{!! $row->Description !!}</td>
                <td width="20%" class="text-right">{{number_format(percent_price($row->Unit_Price, $row->percent),2)}} {{$currency->Currency_name}}</td>
                <td width="10%" class="text-right">{{ $row->Qty }}</td>
                <td width="20%" class="text-right">{{ number_format(percent_price($row->Unit_Price, $row->percent) * $row->Qty,2); }} {{$currency->Currency_name}}</td>
            </tr>
            @endforeach

            <?php 
                $taxAmount = ($invoice->tax_id * ($subTotalWithPer - $invoice->Discount))/100;
                $grandTotal = $subTotalWithPer - $invoice->Discount + $taxAmount;
            ?>

            <tr>
                <td colspan="2" class="text-right"><b>Total ({{$currency->Currency_name}})</b></td>
                <td colspan="3" class="text-right"><b>{{number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}</b></td>
            </tr>

            @if($invoice->Discount > 0)
                <tr>
                    <td colspan="2" class="text-right"><b>Discount ({{$currency->Currency_name}})</b></td>
                    <td colspan="3" class="text-right"><b>{{number_format($invoice->Discount, 2)}} {{$currency->Currency_name}}</b></td>
                </tr>
            @endif

            <tr>
                <td colspan="2" class="text-right"><b>Tax Amount</b></td>
                <td colspan="3" class="text-right"><b>{{ number_format($taxAmount,2);}} {{$currency->Currency_name}}</b></td>
            </tr>
            <tr>
                <td colspan="2" class="text-right"><b>Grand Total</b></td>
                <td colspan="3" class="text-right"><b>{{ number_format($grandTotal,2)}} {{$currency->Currency_name}}</b></td>
            </tr>
            
            @if($type == 'first')
                <tr>
                    <td colspan="2" class="text-right"><b>Advance</b></td>
                    <td colspan="3" class="text-right"><b>{{ number_format($invoice->First_payment_amount,2)}}{{$currency->Currency_name}}</b></td>
                </tr>
                <tr>
                    <td colspan="2" class="text-right"><b>Balance</b></td>
                    <td colspan="3" class="text-right"><b>{{ number_format($grandTotal-$invoice->First_payment_amount,2)}}{{$currency->Currency_name}}</b></td>
                </tr>
            @elseif($type == 'second')
                <tr>
                    <td colspan="2" class="text-right"><b>Balance</b></td>
                    <td colspan="3" class="text-right"><b>{{ number_format($invoice->Second_payment_amount,2)}} {{$currency->Currency_name}}</b></td>
                </tr>
            @elseif(is_numeric($type))
                <tr>
                    <td colspan="2" class="text-right">
                        @if($advance_data->Balance > 0)
                            <strong>Advance</strong>
                        @else
                            <strong>Balance</strong>
                        @endif
                    </td>
                    <td colspan="3" class="text-right"><b>{{ number_format($advance_data->Advance_value,2)}} {{$currency->Currency_name}}</b></td>
                </tr>
                @if($advance_data->Balance > 0)
                    <tr>
                        <td colspan="2" class="text-right"><b>Balance</b></td>
                        <td colspan="3" class="text-right"><b>{{ number_format($advance_data->Balance,2)}} {{$currency->Currency_name}}</b></td>
                    </tr>
                @endif
            @endif
        @endif
        </tbody>
    </table>
</div>
<!-- end detail -->

<div class="note_and_sign">
    <div class="note">
        {{-- Note start --}}
        @if(count($invNotes) > 0)
            <label class="note-title">Remarks:</label>
            <div class="note-body">
                @foreach($invNotes as $row)
                    - {{$row->Note}} <br>
                @endforeach
            </div>
        @endif
        {{-- Note end --}}
    </div>

    <div class="sign text-center">
        @if($receipt->file_name != "")
            <img src="{{ asset($receipt->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            <b>{{ $receipt->sign_name }}</b>  <br>
            Next Hop Co.,Ltd.
        @endif
    </div>
</div>

@endsection