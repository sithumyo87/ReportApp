@extends('layouts.mpdf')
@section('content')

<div class="description-wrap">
    <div class="flex-division bold-font">
        <div class="flex-item1">
            <table class="table">
                <tbody>
                    <tr>
                        <td width="100">Quotation No</td>
                        <td width="10">:</td>
                        <td>{{ $quotation->Serial_No}}</td>
                    </tr>
                    @if($quotation->Refer_No != '')
                    <tr>
                        <td>Refer No</td>
                        <td>:</td>
                        <td>{{ $quotation->Refer_No }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Quotation Date</td>
                        <td>:</td>
                        <td>{{ date('d-m-Y', strtotime($quotation->Date)) }}</td>
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
                        <td>{{ $quotation->Serial_No}}</td>
                    </tr>
                    <tr>
                        <td>Company Name</td>
                        <td>:</td>
                        <td>{{ $quotation->Company_name}}</td>
                    </tr>
                    <tr>
                        <td>Contact Phone</td>
                        <td>:</td>
                        <td>{{ $quotation->Contact_phone}}</td>
                    </tr>
                    <tr>
                        <td>Sub</td>
                        <td>:</td>
                        <td>{{ $quotation->Sub}}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $quotation->Address}}</td>
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
                <th width="50">Lot No</th>
                <th style="width: 300px;">Description</th>
                <th>Unit in Price <br> ({{$currency->Currency_name}}) </th>
                <th width="50">Qty</th>
                <th>Total In Price <br> ({{$currency->Currency_name}})</th>
            </tr>
        </thead>
        <tbody>
        @if(!empty($quoDetails))
        <?php 
            $i = 0;
            $subTotal = 0;
            $subTotalWithPer = 0;
        ?>
        @foreach($quoDetails as $row)
        <?php
            $subTotal += $row->Unit_Price * $row->Qty;
            $subTotalWithPer += percent_price($row->Unit_Price, $row->percent) * $row->Qty;
        ?>
        <tr>
            <td class="text-center">{{ ++$i }}</td>
            <td>{!! $row->Description !!}</td>
            <td class="text-right">{{number_format(percent_price($row->Unit_Price, $row->percent),2)}} {{$currency->Currency_name}}</td>
            <td class="text-right">{{ $row->Qty }}</td>
            <td class="text-right">{{ number_format(percent_price($row->Unit_Price, $row->percent) * $row->Qty,2); }} {{$currency->Currency_name}}</td>
        </tr>
        @endforeach

        <?php 
            $taxAmount = ($quotation->Tax * $subTotalWithPer)/100;
            $grandTotal = $subTotalWithPer + $taxAmount;
        ?>

        <tr>
            <td colspan="2" class="text-right"><b>Total ({{$currency->Currency_name}})</b></td>
            <td colspan="3" class="text-right"><b>{{ number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}</b></td>
        </tr>
        <tr>
            <td colspan="2" class="text-right"><b>Tax Amount:</b></td>
            <td colspan="3" class="text-right"><b>{{ number_format($taxAmount,2);}} {{$currency->Currency_name}}</b></td>
        </tr>
        <tr>
            <td colspan="2" class="text-right"><b>Grand Total:</b></td>
            <td colspan="3" class="text-right"><b>{{ number_format($grandTotal,2)}} {{$currency->Currency_name}}</b></td>
        </tr>
        @endif
        </tbody>
    </table>
</div>
<!-- end detail -->

<div class="note_and_sign">
    <div class="note">
        {{-- Note start --}}
        @if(count($quoNotes) > 0)
            <label class="note-title">Remarks:</label>
            <div class="note-body">
                @foreach($quoNotes as $row)
                    - {{$row->Note}} <br>
                @endforeach
            </div>
        @endif
        {{-- Note end --}}
    </div>

    <div class="sign text-center">
        @if($quotation->file_name != "")
            <img src="{{ public_path($quotation->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img"> <br>
            <b>{{ $quotation->sign_name }}</b>  <br>
            Next Hop Co.,Ltd.
        @endif
    </div>
</div>

@endsection