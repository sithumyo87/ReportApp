@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $receipt->Receipt_No}} 's Detail</h4>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end">
                @if($type != '' || $receipt->Advance == '4' || $receipt->Advance == '5')
                    <a href="{{ route('OfficeManagement.receiptPrint', ['id' => $receipt->id, 'type' => $type]) }}"
                        class="btn btn-success btn-sm d-none d-lg-block m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                        Print
                    </a>
                @endif
                <a href="{{ route('OfficeManagement.receipt.index') }}"
                    class="btn btn-info btn-sm d-none d-lg-block m-l-15"><i class="fa fa-back"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
    <hr>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
    @endif
    
    {{-- get receipt start --}}
    <div class="m-b-10">
        @if($receipt->Advance != '6')
            @if(getReceiptCheck($receipt, $invoice))
                {!! Form::open(['method' => 'POST', 'route' => ['OfficeManagement.receiptDetail.getReceipt',
                $receipt->id], 'style' => 'display:inline']) !!}
                    {!! Form::hidden('type', getReceiptType($receipt)) !!}
                    {!! Form::hidden('amount', null) !!}
                    {!! Form::button('<i class="fa fa fa-sort"></i> GET RECEIPT', ['type'=>'submit','class' => 'btn btn-sm btn-info', 'onclick' => 'return confirm("Are you sure to get receipt?")',
                    'id' => 'delete']) !!}
                {!! Form::close() !!}
            @endif
            @if($type != '')
                <a href="{{ route('OfficeManagement.receiptDetail.show',$receipt->id)}}" class="btn btn-warning invoice-button">Back To Detail</a>
            @endif
            @if($receipt->Advance != '4' && $receipt->Advance != '5')
                @if($receipt->First_Receipt == 1)
                    <a href="{{ route('OfficeManagement.receiptDetail.show', ['receiptDetail'=>$receipt->id, 'type' => 'first'])}}" class="btn btn-info invoice-button">First Receipt ({{ getInvRecName($receipt) }})</a>
                @endif
                @if($receipt->Second_Receipt == 1)
                    <a href="{{ route('OfficeManagement.receiptDetail.show', ['receiptDetail'=>$receipt->id, 'type' => 'second'])}}" class="btn btn-info invoice-button">Second Receipt ({{ getInvRecName($receipt) }})</a>
                @endif
            @else
                @if($receipt->First_Receipt == 1 && $receipt->Second_Receipt == 1)
                    <a class="btn btn-info invoice-button">{{ getInvRecName($receipt) }}</a>
                @endif
            @endif
        @else
            @if(count($inv_advances) > 0)
                {!! Form::open(['method' => 'POST', 'route' => ['OfficeManagement.receiptDetail.getReceipt',
                $receipt->id], 'style' => 'display:inline']) !!}
                    {!! Form::hidden('type', 4) !!}
                    {!! Form::hidden('amount', null) !!}
                    {!! Form::button('<i class="fa fa fa-sort"></i> GET RECEIPT', ['type'=>'submit','class' => 'btn btn-sm btn-info', 'onclick' => 'return confirm("Are you sure to get receipt?")',
                    'id' => 'delete']) !!}
                {!! Form::close() !!}
            @endif
            @if($type != '')
                <a href="{{ route('OfficeManagement.receiptDetail.show',$receipt->id)}}" class="btn btn-warning invoice-button">Back To Detail</a>
            @endif
            @foreach ($advances as $advance )
                <a href="{{ route('OfficeManagement.receiptDetail.show', ['receiptDetail'=>$receipt->id, 'type' => $advance->nth_time])}}" class="btn btn-info invoice-button">{{ advanceName($advance->nth_time) }} RECEIPT</a>
            @endforeach
        @endif
    </div>
    {{-- get invoice end --}}

    <div class="row">
        <div class="col-md-7">
            <table class="table table-no-border">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Attn</td>
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
        <div class="col-md-5">
            <table class="table table-no-border">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Receipt No </td>
                        <td>:</td>
                        <td>{{ $receipt->Receipt_No }}</td>
                    </tr>
                    <tr>
                        <td>Receipt Date</td>
                        <td>:</td>
                        <td>{{ getReceiptDate($receipt, $type, $advance_data) }}</td>
                    </tr>
                    <tr>
                        <td>Ref Invoice No.</td>
                        <td>:</td>
                        <td>{{ @$invoice->Invoice_No }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- start detail -->
    <div class="table-responsive bg-white p-30">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th width="130">Description</th>
                    <th>Unit Price</th>
                    <th>Percent</th>
                    <th>Unit Price (With %)</th>
                    <th>Qty</th>
                    <th>SubTotal</th>
                    <th width="150">SubTotal (With %)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $subTotal = 0;
                    $subTotalWithPer = 0;
                ?>
                @if(!empty($invDetails))
                @foreach($invDetails as $row)
                <?php
                    $subTotal += $row->Unit_Price * $row->Qty;
                    $subTotalWithPer += percent_price($row->Unit_Price, $row->percent) * $row->Qty;
                ?>

                <tr>
                    <td style="min-width: 200px;">{!! $row->Description !!}</td>
                    <td class="text-right">{{ $row->Unit_Price }} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ $row->percent }}%</td>
                    <td class="text-right">{{number_format(percent_price($row->Unit_Price, $row->percent),2)}} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ $row->Qty }}</td>
                    <td class="text-right">{{ number_format($row->Unit_Price * $row->Qty,2) }} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ number_format(percent_price($row->Unit_Price, $row->percent) * $row->Qty,2); }} {{$currency->Currency_name}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
                    <td class="text-right"><b>{{ number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}</b></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <!-- end detail -->
    
    <div class="row m-t-30">
        <div>
            <!-- start total -->
            <div class="col-md-5 float-right">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Total</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <p>
                        {{number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}
                        </p>
                    </div>
                </div>

                @if(@$invoice->Discount > 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Discount</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <p>{{number_format($invoice->Discount, 2)}} {{$currency->Currency_name}}</p>
                        </div>
                    </div>
                @endif

                
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Tax (%)</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <p>{{@$invoice->tax_id}}%</p>
                    </div>
                </div>

                <?php 
                    $taxAmount = ($invoice->tax_id * ($subTotalWithPer - $invoice->Discount))/100;
                    $grandTotal = $subTotalWithPer - $invoice->Discount + $taxAmount;
                ?>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Tax Amount</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <p><span id="tax-amount">{{ number_format($taxAmount,2);}}</span> {{$currency->Currency_name}}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Grand Total</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <span id="grand-total">{{ number_format($grandTotal,2)}}</span> {{$currency->Currency_name}}
                    </div>
                </div>

                @if($type == 'first')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Advance</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span id="grand-total">{{ number_format($invoice->First_payment_amount,2)}}</span> {{$currency->Currency_name}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Balance</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span id="grand-total">{{ number_format($grandTotal-$invoice->First_payment_amount,2)}}</span> {{$currency->Currency_name}}
                        </div>
                    </div>
                @elseif($type == 'second')
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Balance</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span id="grand-total">{{ number_format($invoice->Second_payment_amount,2)}}</span> {{$currency->Currency_name}}
                        </div>
                    </div>
                @elseif(is_numeric($type))
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            @if($advance_data->Balance > 0)
                                <p><strong>Advance</strong></p>
                            @else
                                <p><strong>Balance</strong></p>
                            @endif
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <span id="grand-total">{{ number_format($advance_data->Advance_value,2)}}</span> {{$currency->Currency_name}}
                        </div>
                    </div>
                    @if($advance_data->Balance > 0)
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                                <p><strong>Balance</strong></p>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <span id="grand-total">{{ number_format($advance_data->Balance,2)}}</span> {{$currency->Currency_name}}
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <!-- end total -->
            <!-- start note & file -->
            <div class="col-md-7 float-left">

                <!-- Note -->
                @if(count($invNotes) > 0)
                    <label for="note">Notes</label>
                    @foreach($invNotes as $row)
                    <p class="bg-gray-light text-dark p-2 noteFont">
                        {{$row->Note}}
                    </p>
                    @endforeach
                @endif
                <!-- Note ENd -->
            </div>
            <!-- end note & file -->
        </div>
    </div>

    <!-- authorized Person -->
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-center">
            @if($receipt->file_name != "")
                <img src="{{ asset($receipt->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
            @else
                <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
            @endif
            <h6>Authorized Person</h6> 
            {!! Form::open(['route' => ['OfficeManagement.receiptAuthorizer',$receipt->id],'method' => 'POST']) !!}
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <select name="authorizer" id="authorizer" class="form-control form-select">
                        <option value="">Select Authorized Person</option>
                            @foreach($authorizers as $row)
                                <option value="{{ $row->id }}" <?php if($row->authorized_name == $receipt->sign_name) echo "selected";?> data-file="{{ asset($row->file_name) }}">{{$row->authorized_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-primary float-right" type="submit">Add</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection