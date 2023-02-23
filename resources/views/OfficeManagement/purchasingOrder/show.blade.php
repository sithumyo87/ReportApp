@extends('layouts.setting')
@section('content')
<div class="container-fluid detail-table">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $po->po_code}} 's Detail</h4>
        </div>
        <div class="col-md-7"> 
            <div class="d-flex justify-content-end">
                @if($po->submit_status == true)
                    <a href="{{ route('OfficeManagement.poPrint', $po->id) }}"
                    class="btn btn-success btn-sm d-none d-lg-block m-l-15 mr-3" target="_blank"><i class="fa fa-back"></i>
                    Print
                    </a>
                @endif
                <div class="d-flex justify-content-end">
                    <a href="{{ route('OfficeManagement.purchasingOrder.index') }}"
                        class="btn btn-info btn-sm d-none d-lg-block m-l-15"><i class="fa fa-back"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
    <hr>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">{{ $message }}</div>
    @endif
    

    <div class="row">
        <div class="col-md-7">
            <table class="table table-no-border detail-table">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Sub</td>
                        <td>:</td>
                        <td>{{ $po->sub}}</td>
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
        <div class="col-md-5">
            <table class="table table-no-border detail-table">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Order No</td>
                        <td>:</td>
                        <td>{{ $po->po_code }}</td>
                    </tr>
                    <tr>
                        <td>Date</td>
                        <td>:</td>
                        <td>{{ dateformat($po->date) }}</td>
                    </tr>
                     @if($po->Serial_No != '')
                    <tr>
                        <td>Ref Quotation No</td>
                        <td>:</td>
                        <td>{{ $po->Serial_No }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- start detail -->
    <div class="table-responsive bg-white m-t-10">
        <table class="table table-bordered detail-table">
            <thead>
                <tr class="text-center">
                    <th style="min-width:130px;">Description</th>
                    <th>Unit Price</th>
                    <th>Qty</th>
                    <th>SubTotal</th>
                    @if($po->submit_status != '1')
                        <th style="width:90px;">{{ __('label.action') }}</th>
                    @endif
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
                    <td>{!! $row->description !!}</td>
                    <td class="text-right">{{ $row->price }} {{ $currency->Currency_name }}</td>
                    <td class="text-right">{{ $row->qty }}</td>
                    <td class="text-right">{{ number_format($row->price * $row->qty,2) }} {{$currency->Currency_name}}</td>
                    @if($po->submit_status != '1')
                        <td class="text-center">
                            @can('po-edit')
                            <a class="btn btn-sm btn-primary"
                                href="{{ route('OfficeManagement.purchasingOrderDetail.edit', $row->id) }}">
                                <i class="fa fa-edit"></i></a>
                            @endcan
                            @can('po-delete')
                                @if($po->submit_status != '1')
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.purchasingOrderDetail.destroy',
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
                    <td colspan="3" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
                    @if($po->submit_status != '1')<td></td>@endif
                </tr>
                @endif
                @if($po->submit_status != '1')
                <tr>
                    <td>
                        @can('po-create')
                            <a href="{{ route('OfficeManagement.purchasingOrderDetail.create',['po_id' => $po->id]) }}"
                                class="btn btn-success btn-sm"><i class="fa fa-plus-circle"></i>
                                {{ __('button.create') }}
                            </a>
                        @endcan
                    </td> 
                    <td colspan="3"></td>
                    @if($po->submit_status != '1')<td></td>@endif
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
                <div class="float-right">
                    <table class="table table-no-border detail-table fit-content">
                        <tr>
                            <td class='text-right'><strong>Total</strong></td>
                            <td>{{number_format($subTotal,2)}} {{$currency->Currency_name}}</td>
                        </tr>

                        <tr>
                            @if($po->submit_status == 0)
                                <td class='text-right'><strong>Tax (5%)</strong></td>
                                <td><label><input type="checkbox" class="minimal" id="po-tax-check" data-id="{{ $po->id; }}" data-total="{{ $subTotal }}" @if($po->tax == 5) checked @endif></label><td>
                            @else
                                <td class='text-right'><strong>Tax (%)</strong></td>
                                <td>{{$po->tax}}%</td>
                            @endif
                        </tr>

                        <?php 
                            $taxAmount  = $po->tax * ($subTotal)/100;
                            $grandTotal = $subTotal + $taxAmount;
                        ?>

                        <tr>
                            <td class="text-right"><strong>Tax Amount </strong></td>
                            <td><span id="tax-amount">{{ number_format($taxAmount,2);}}</span> {{$currency->Currency_name}}</td>
                        </tr>

                        <tr>
                            <td class="text-right"><strong>Grand Total</strong></td>
                            <td>
                                <span id="grand-total">{{ number_format($grandTotal,2)}}</span> {{$currency->Currency_name}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- end total -->

            <!-- start note & file -->
            <div class="col-md-7 float-left">
                <!-- Note -->
                @if(count($notes) > 0 || $po->submit_status != '1')
                    <label for="note">Notes</label>
                    @foreach($notes as $row)
                    {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.purchasingOrderNote.destroy', $row->id], ]) !!} 
                        <p class="bg-gray-light text-dark p-2 noteFont">
                            {{ $row->note }}
                            {{ Form::hidden('poId', $po->id) }}
                            @if($po->submit_status == 0)
                                {{-- delete note --}}
                                {!! Form::button('<i class="fa fa-trash text-danger"></i>', ['type'=>'submit','class' => ' float-right',
                                'onclick'=>'return confirm("Are you sure to delete?")','style' => 'background:none;border:none']) !!}   

                                {{-- edit note  --}}
                                <a class="float-right me-2 edit-note" data-id="{{$row->id}}" data-note="{{ $row->note }}"><i class="fa fa-edit text-warning"></i></a>
                            @endif
                            {!! Form::close() !!}
                        </p>
                    @endforeach
                @endif

                @if($po->submit_status == 0)
                    {!! Form::open(['route' => 'OfficeManagement.purchasingOrderNote.store', 'method' => 'POST']) !!}
                    {{ Form::hidden('poId', $po->id) }}
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
            </div>
            <!-- end note & file -->
        </div>
    </div>

    <!-- authorized Person -->
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4">
            <div class="sign-wrap">
            @if($po->file_name != "")
                <img src="{{ asset($po->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
            @else
                <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
            @endif
            @if($po->submit_status == 0)
            <h6 class="auth-title">Authorized Person</h6> 
            {!! Form::open(['route' => ['OfficeManagement.poAuthorizer',$po->id],'method' => 'POST']) !!}
            <div class="row">
                <div class="col-md-10 col-sm-10 col-10">
                    <div class="form-group">
                        <select name="authorizer" id="authorizer" class="form-control form-select">\
                        <option value="">Select Authorized Person</option>
                            @foreach($authorizers as $row)
                                <option value="{{ $row->id }}" <?php if($row->authorized_name == $po->sign_name) echo "selected";?> data-file="{{ asset($row->file_name) }}">{{$row->authorized_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-sm-2 col-2">
                    <button class="btn btn-sm btn-primary float-right" type="submit">Add</button>
                </div>
            </div>
            @else
                <div class="text-center">{{ $po->sign_name }}</div>
            @endif
            {!! Form::close() !!}
            </div>
        </div>
    </div>

    @if($po->submit_status == 0)
    <div class="text-center ">
        <a href="{{route('OfficeManagement.poConfirm',$po->id)}}"><p><button class="btn btn-primary btn-block w-80">Confirm</button></p></a> 
    </div>
    @endif
</div>
@endsection