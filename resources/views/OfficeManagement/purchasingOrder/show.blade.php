@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $po->po_code}} 's Detail</h4>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end">
                <a href="{{ route('OfficeManagement.purchasingOrder.index') }}"
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
    

    <div class="row">
        <div class="col-md-7">
            <p><b>Sub: </b>
                {{ $po->sub}}
            </p>
            <p><b>Attn: </b> {{ $po->Attn}}</p>
            <p><b>Company: </b> {{ $po->Company_name}}</p>
            <p><b>Phone No: </b> {{ $po->Contact_phone}}</p>
            <p><b>Address: </b> {{ $po->Address}}</p>
        </div>
        <div class="col-md-5">
            <p><b>Order No: </b> {{ $po->po_code }}</p>
            <p><b>Date: </b>  {{ $po->date }}</p>
            @if($po->Serial_No != '')
                <p><b>Quatation No.: </b>  {{ $po->Serial_No }}</p>
            @endif
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
                </tr>
            </thead>
            <tbody>
                @if(!empty($poDetails))
                <?php 
                    $subTotal = 0;
                ?>
                @foreach($poDetails as $row)
                <?php
                    $subTotal += $row->price * $row->qty;
                ?>
                <tr>
                    <td>{{ $row->Description }}</td>
                    <td class="text-right">{{ $row->price }} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ $row->percent }}%</td>
                    <td class="text-right">{{number_format(percent_price($row->price, 0), 2)}} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ $row->qty }}</td>
                    <td class="text-right">{{ number_format($row->price * $row->qty,2) }} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ number_format(percent_price($row->price, 0) * $row->qty,2); }} {{$currency->Currency_name}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
                </tr>
                @endif
                @if($po->submit_status != '1')
                <tr>
                    <td class="text-right">
                        <a href="{{ route('OfficeManagement.purchasingOrderDetail.create',['po_id' => $po->id]) }}"
                            class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>
                            {{ __('button.create') }}
                        </a>
                    </td> 
                    <td colspan="5"></td>
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
                        {{number_format($subTotal,2)}} {{$currency->Currency_name}}
                        </p>
                    </div>
                </div>

                @if($po->submit_status == 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Tax (5%)</strong></p>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <label><input type="checkbox" class="minimal" id="inv-tax-check" data-id="{{ $po->id; }}" data-total="{{ $subTotal }}" @if($po->tax == 5) checked @endif></label>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Tax (%)</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <p>{{$po->tax}}%</p>
                        </div>
                    </div>
                @endif

                <?php 
                    $taxAmount = $po->tax * ($subTotal)/100;
                    $grandTotal = $subTotal + $taxAmount;
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
            </div>
            <!-- end total -->

            <!-- start note & file -->
            <div class="col-md-7 float-left">
                <!-- Note -->
                @if(count($notes) > 0)
                    <label for="note">Notes</label>
                    @foreach($notes as $row)
                    <p class="bg-gray-light text-dark p-2 noteFont">
                        {{$row->note}}
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
            @if($po->file_name != "")
                <img src="{{ asset($po->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
            @else
                <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
            @endif
            <h6>Authorized Person</h6> 
            {!! Form::open(['route' => ['OfficeManagement.invoiceAuthorizer',$po->id],'method' => 'POST']) !!}
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <select name="authorizer" id="authorizer" class="form-control form-select">\
                        <option value="">Select Authorized Person</option>
                            @foreach($authorizers as $row)
                                <option value="{{ $row->id }}" <?php if($row->authorized_name == $po->sign_name) echo "selected";?> data-file="{{ asset($row->file_name) }}">{{$row->authorized_name}}</option>
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