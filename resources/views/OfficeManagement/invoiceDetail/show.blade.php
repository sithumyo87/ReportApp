@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $invoice->Invoice_No}} 's Detail</h4>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end">
                @if($invoice->submit_status == true)
                    <a href="{{ route('OfficeManagement.quotationDetail.create') }}"
                        class="btn btn-success d-none d-lg-block m-l-15 mr-3"><i class="fa fa-print"></i>
                        Print
                    </a>
                @endif
                <a href="{{ route('OfficeManagement.quotation.index') }}"
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
    
    {{-- get invoice start --}}
    @if($invoice->submit_status == '1')
        <div class="m-b-10">
            @if($invoice->Advance != '6')
                @if(getInvoiceCheck($invoice))
                    {!! Form::open(['method' => 'POST', 'route' => ['OfficeManagement.invoiceDetail.getInvoice',
                    $invoice->id], 'style' => 'display:inline']) !!}
                        {!! Form::hidden('type', getInvoiceType($invoice)) !!}
                        {!! Form::hidden('amount', null) !!}
                        {!! Form::button('<i class="fa fa fa-sort"></i> GET INVOICE', ['type'=>'submit','class' => 'btn btn-sm btn-info', 'onclick' => 'return confirm("Are you sure to get invoice?")',
                        'id' => 'delete']) !!}
                    {!! Form::close() !!}
                @endif
                @if($type != '')
                    <a href="{{ route('OfficeManagement.invoiceDetail.show',$invoice->id)}}" class="btn btn-warning invoice-button">Back To Detail</a>
                @endif
                @if($invoice->Advance != '4' && $invoice->Advance != '5')
                    @if($invoice->FirstInvoice == 1)
                        <a href="{{ route('OfficeManagement.invoiceDetail.show', ['invoiceDetail'=>$invoice->id, 'type' => 'first'])}}" class="btn btn-info invoice-button">First Invoice ({{ getInvRecName($invoice) }})</a>
                    @endif
                    @if($invoice->SecondInvoice == 1)
                        <a href="{{ route('OfficeManagement.invoiceDetail.show', ['invoiceDetail'=>$invoice->id, 'type' => 'second'])}}" class="btn btn-info invoice-button">Second Invoice ({{ getInvRecName($invoice) }})</a>
                    @endif
                @else
                    @if($invoice->FirstInvoice == 1 && $invoice->SecondInvoice == 1)
                        <a class="btn btn-info invoice-button">{{ getInvRecName($invoice) }}</a>
                    @endif
                @endif
            @else
                <?php 
                    $remainAmt = otherPaymentRemainAmt($invoice, $advance_last, $invDetails);
                ?>
                @if($remainAmt > 0)
                    <button type="button" class="btn btn-primary invoice-button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fa fa fa-sort"></i> GET INVOICE
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        {!! Form::open(['method' => 'POST', 'route' => ['OfficeManagement.invoiceDetail.getInvoice',$invoice->id]]) !!}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">GET INVOICE</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {!! Form::hidden('amount', $remainAmt) !!}
                                    {!! Form::hidden('type', getInvoiceType($invoice)) !!}
                                    <div class="form-group">
                                        <label>Amount (max: {{ $remainAmt }})</label>
                                        {!! Form::number('other_amt', null, ['class' =>'form-control', 'required', 'min' => '0', 'max' => $remainAmt, 'step' => '0.01']) !!}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary invoice-button" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary invoice-button">Add</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                @endif
                @if($type != '')
                    <a href="{{ route('OfficeManagement.invoiceDetail.show',$invoice->id)}}" class="btn btn-warning invoice-button">Back To Detail</a>
                @endif
                @foreach ($advances as $advance )
                    <a href="{{ route('OfficeManagement.invoiceDetail.show', ['invoiceDetail'=>$invoice->id, 'type' => $advance->nth_time])}}" class="btn btn-info invoice-button">{{ advanceName($advance->nth_time) }} INVOICE</a>
                @endforeach
            @endif
        </div>
    @endif
    {{-- get invoice end --}}

    <div class="row">
        <div class="col-md-7">
            <p><b>Sub: </b>
                {{ $invoice->Sub}}
            </p>
            <p><b>Attn: </b> {{ $invoice->Attn}}</p>
            <p><b>Company: </b> {{ $invoice->Company_name}}</p>
            <p><b>Phone No: </b> {{ $invoice->Contact_phone}}</p>
            <p><b>Address: </b> {{ $invoice->Address}}</p>
        </div>
        <div class="col-md-5">
            <p><b>Invoice No: </b> {{ $invoice->Invoice_No}}</p>
            <p><b>Date: </b>  {{ getInvoiceDate($invoice, $type, $advance_data) }}</p>
            <p><b>Tax ID: </b> - </p>
            <p><b>PO No: </b> - {{ $invoice->po_no}}</p>
            <p><b>Vender ID: </b> - {{ $invoice->vender_id}}</p>
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
                    @if($invoice->submit_status != '1')
                        <th width="150">{{ __('label.action') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(!empty($invDetails))
                <?php 
                    $subTotal = 0;
                    $subTotalWithPer = 0;
                ?>
                @foreach($invDetails as $row)
                <?php
                 $subTotal += $row->Unit_Price * $row->Qty;
                 $subTotalWithPer += percent_price($row->Unit_Price, $row->percent) * $row->Qty;
                 ?>

                <tr>
                    <td>{{ $row->Description }}</td>
                    <td class="text-right">{{ $row->Unit_Price }} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ $row->percent }}%</td>
                    <td class="text-right">{{number_format(percent_price($row->Unit_Price, $row->percent),2)}} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ $row->Qty }}</td>
                    <td class="text-right">{{ number_format($row->Unit_Price * $row->Qty,2) }} {{$currency->Currency_name}}</td>
                    <td class="text-right">{{ number_format(percent_price($row->Unit_Price, $row->percent) * $row->Qty,2); }} {{$currency->Currency_name}}</td>
                    @if($invoice->submit_status != '1')
                        <td class="text-center">
                            @can('user-edit')
                            <a class="btn btn-sm btn-primary"
                                href="{{ route('OfficeManagement.invoiceDetail.edit', $row->id) }}">
                                <i class="fa fa-edit"></i></a>
                            @endcan
                            @can('user-delete')
                                @if($invoice->submit_status != '1')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.invoiceDetail.destroy',
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
                <tr>
                    <td colspan="5" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
                    <td class="text-right"><b>{{ number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}</b></td>
                    @if($invoice->submit_status != '1')<td></td>@endif
                </tr>
                @endif
                @if($invoice->submit_status != '1')
                <tr>
                    <td class="text-right">
                        <a href="{{ route('OfficeManagement.invoiceDetailCreate',$invoice->id) }}"
                            class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>
                            {{ __('button.create') }}
                        </a>
                    </td> 
                    <td colspan="6"></td>
                    @if($invoice->submit_status != '1')<td></td>@endif
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

                @if($invoice->submit_status == 0)
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Discount</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        {!! Form::open(['route' => ['OfficeManagement.invoiceDiscount', $invoice->id],'method' => 'POST']) !!}
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <input class="form-control" value="{{ $invoice->Discount}}" name="discount">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button class="btn btn-sm btn-primary float-right" type="submit">Add</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                @else
                    @if($invoice->Discount > 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Discount</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <p>{{number_format($invoice->Discount, 2)}} {{$currency->Currency_name}}</p>
                        </div>
                    </div>
                    @endif
                @endif

                
                @if($invoice->submit_status == 0)
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Tax (5%)</strong></p>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <label><input type="checkbox" class="minimal" id="inv-tax-check" data-id="{{ $invoice->id; }}" data-total="{{ $subTotalWithPer }}" @if($invoice->tax_id != 0) checked @endif></label>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                            <p><strong>Tax (%)</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                            <p>{{$invoice->tax_id}}%</p>
                        </div>
                    </div>
                @endif

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
                @endif
                @foreach($invNotes as $row)
                
                {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.invoiceNote.destroy', $row->id], ]) !!} 
                <p class="bg-gray-light text-dark p-2 noteFont">
                    {{$row->Note}}
                    {{ Form::hidden('invId', $invoice->id) }}
                    @if($invoice->submit_status == 0)
                        {{-- delete note --}}
                        {!! Form::button('<i class="fa fa-trash text-danger"></i>', ['type'=>'submit','class' => ' float-right',
                        'onclick'=>'return confirm("Are you sure to delete?")','style' => 'background:none;border:none']) !!}   

                        {{-- edit note  --}}
                        <a class="float-right me-2 edit-note" data-id="{{$row->id}}" data-note="{{$row->Note}}"><i class="fa fa-edit text-warning"></i></a>
                    @endif
                    {!! Form::close() !!}
                </p>
                @endforeach

                @if($invoice->submit_status == 0)
                    {!! Form::open(['route' => 'OfficeManagement.invoiceNote.store', 'method' => 'POST']) !!}
                    {{ Form::hidden('invId', $invoice->id) }}
                    {{ Form::hidden('noteId', null, ['id' => 'noteId']) }}
                    <div class="form-group">
                        <textarea name="Note" id="note" cols="5" rows="2" class="form-control" required></textarea>
                    </div>
                    <div class="text-right mt-3">
                        <button class="btn btn-sm btn-grey note-reset" type="reset">Reset</button>
                        <button class="btn btn-sm btn-primary" type="submit">Add</button>
                    </div>
                    {!! Form::close() !!}
                @endif
                <!-- Note ENd -->

                <!-- Bank Information ENd -->
                <?php 
                    $bankInfo = [];
                    if($invoice->bank_info != ''){
                        $bankInfo = explode(',', $invoice->bank_info);
                    }
                ?>
                <div class="bank-information">
                    @if($invoice->submit_status == 0)
                        @foreach ($bankInfos as $b)
                            <?php 
                                if (array_search($b->id, $bankInfo) === false) {
                                    $bankState = '';
                                }else{
                                    $bankState = 'checked';
                                }
                            ?>
                            <label><input type="checkbox" class="minimal bank-info-check" data-id="{{ $invoice->id; }}" data-bank="{{ $b->id }}" {{ $bankState }}> Use {{ $b->name }}</label>
                            <br/>
                        @endforeach
                    @else
                        @foreach($bankInfoDetails as $bDetail)
                            <h6 class="bank-info-title">{{ $bDetail['name'] }}</h6>
                            <table class="bank-info-table">
                                <tbody>
                                    @foreach($bDetail['details'] as $detail)
                                        <tr>
                                            <td>{{ $detail->label_name }}</td>
                                            <td>:</td>
                                            <td>{{ $detail->value_name }}</td>
                                        <tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @endif
                </div>
                <!-- Bank Information ENd -->
            </div>
            <!-- end note & file -->
        </div>
    </div>

    <!-- authorized Person -->
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-center">
            @if($invoice->submit_status == '1')
                @if($invoice->file_name != "")
                    <img src="{{ asset($invoice->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
                @else
                    <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
                @endif
                <h6>Authorized Person</h6> 
                {!! Form::open(['route' => ['OfficeManagement.invoiceAuthorizer',$invoice->id],'method' => 'POST']) !!}
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <select name="authorizer" id="authorizer" class="form-control form-select">\
                            <option value="">Select Authorized Person</option>
                                @foreach($authorizers as $row)
                                    <option value="{{ $row->id }}" <?php if($row->authorized_name == $invoice->sign_name) echo "selected";?> data-file="{{ asset($row->file_name) }}">{{$row->authorized_name}}</option>
                                @endforeach
                            </select>
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
   
    @if($invoice->submit_status == 0)
    <div class="text-center ">
        <a href="{{route('OfficeManagement.invoiceConfirm',$invoice->id)}}"><p><button class="btn btn-primary btn-block w-80">Confirm</button></p></a> 
    </div>
    @endif
</div>
@endsection