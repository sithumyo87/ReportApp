@extends('layouts.setting')
@section('content')
<div class="container-fluid  detail-table">
    <div class="row quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $quotation->Serial_No}} 's Detail</h4>
        </div>
        <div class="col-md-7">
            <div class="d-flex justify-content-end">
                @if($quotation->SubmitStatus == true)
                    <a href="{{ route('OfficeManagement.quotationPrint', $quotation->id) }}" class="btn btn-success btn-sm d-none d-lg-block m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                        Print
                    </a>
                    <a href="{{ route('OfficeManagement.quotationPrint', ['id'=>$quotation->id, 'pdf' => 'kinzi']) }}" class="btn btn-success btn-sm d-none d-lg-block m-l-15 mr-3" target="_blank"><i class="fa fa-print"></i>
                        Print-2
                    </a>
                @endif
                <a href="{{ route('OfficeManagement.quotation.index') }}"
                    class="btn btn-info btn-sm d-none d-lg-block m-l-15"><i class="fa fa-back"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
    <hr>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    <div class="row">
        <div class="col-md-7">
            <table class="table table-no-border">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Sub</td>
                        <td>:</td>
                        <td>{{ $quotation->Sub}}</td>
                    </tr>
                    <tr>
                        <td>Attn</td>
                        <td>:</td>
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
                        <td>Address</td>
                        <td>:</td>
                        <td>{{ $quotation->Address}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <table class="table table-no-border">
                <tbody>
                    <tr>
                        <td style="min-width: 150px">Quotation No</td>
                        <td>:</td>
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
    </div>

    <!-- start detail -->
    <div class="table-responsive bg-white p-10">
        <table class="table table-bordered m-0">
            <thead>
                <tr class="text-center">
                    <th style="min-width:300px;">Description</th>
                    <th>Unit Price</th>
                    <th>Percent</th>
                    <th>Unit Price <br> (With %)</th>
                    <th>Qty</th>
                    <th>SubTotal</th>
                    <th width="150">SubTotal <br> (With %)</th>
                    @if($quotation->SubmitStatus != '1')
                        <th width="100">{{ __('label.action') }}</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if(!empty($quoDetails))
                <?php 
                    $subTotal = 0;
                    $subTotalWithPer = 0;
                ?>
                @foreach($quoDetails as $row)
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
                    @if($quotation->SubmitStatus != '1')
                        <td class="text-center">
                            @can('quotation-edit')
                            <a class="btn btn-sm btn-primary"
                                href="{{ route('OfficeManagement.quotationDetail.edit', $row->id) }}">
                                <i class="fa fa-edit"></i></a>
                            @endcan
                            @can('quotation-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotationDetail.destroy',
                                $row->id], 'style' => 'display:inline']) !!}
                                {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-sm btn-danger', 'onclick' => 'return confirm("Are you sure to delete?")',
                                'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    @endif
                </tr>
                @endforeach

                <tr>
                    <td colspan="5" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
                    <td class="text-right"><b>{{ number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}</b></td>
                    @if($quotation->SubmitStatus != '1')<td></td>@endif
                </tr>
              
                @endif
                @if($quotation->SubmitStatus != '1')
                <tr>
                    <td class="text-center">
                        @can('quotation-create')
                            <a href="{{ route('OfficeManagement.quotationDetailCreate',$quotation->id) }}"
                                class="btn btn-success m-l-15"><i class="fa fa-plus-circle"></i>
                                {{ __('button.create') }}
                            </a>
                        @endcan
                    </td> 
                    <td colspan="6"></td>
                    @if($quotation->SubmitStatus != '1')<td></td>@endif
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
                
                @if($quotation->SubmitStatus == 0)
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Tax (5%)</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        <label><input type="checkbox" class="minimal" id="quo-tax-check" data-id="{{ $quotation->id; }}" data-total="{{ $subTotalWithPer }}" @if($quotation->Tax != 0) checked @endif></label>
                    </div>
                </div>
                @endif

                <?php 
                    $taxAmount = ($quotation->Tax * $subTotalWithPer)/100;
                    $grandTotal = $subTotalWithPer + $taxAmount;
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

                @if(count($quoNotes) > 0 || $quotation->SubmitStatus == 0)
                    <!-- Note -->
                    <label for="note">Notes</label>
                    @foreach($quoNotes as $row)
                        {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotationNote.destroy', $row->id], ]) !!} 
                        <p class="bg-gray-light text-dark p-2 noteFont">
                            {{$row->Note}}
                            {{ Form::hidden('Quotation_Id', $quotation->id) }}
                            @if($quotation->SubmitStatus == 0)
                                {{-- delete note --}}
                                {!! Form::button('<i class="fa fa-trash text-danger"></i>', ['type'=>'submit','class' => ' float-right',
                                'onclick'=>'return confirm("Are you sure to delete?")','style' => 'background:none;border:none']) !!}   

                                {{-- edit note  --}}
                                <a class="float-right me-2 edit-note" data-id="{{$row->id}}" data-note="{{$row->Note}}"><i class="fa fa-edit text-warning"></i></a>
                            @endif
                        </p>
                        {!! Form::close() !!}
                    @endforeach
                @endif

                @if($quotation->SubmitStatus == 0)
                    {!! Form::open(['route' => 'OfficeManagement.quotationNote.store', 'method' => 'POST']) !!}
                    {{ Form::hidden('quoId', $quotation->id) }}
                    {{ Form::hidden('noteId', null, ['id' => 'noteId']) }}
                    <div class="form-group">
                        <textarea name="Note" id="note" cols="5" rows="2" class="form-control" required>{{$quoNote->Note ?? null}} </textarea>
                    </div>
                    <div class="text-right mt-3">
                        <button class="btn btn-sm btn-grey note-reset" type="reset">Reset</button>
                        <button class="btn btn-sm btn-primary" type="submit">Add</button>
                    </div>
                    {!! Form::close() !!}
                @endif
                <!-- Note ENd -->

                <!-- FIle start -->
                <div class="">
                    @if(count($quoFiles) > 0 || $quotation->SubmitStatus == 0)
                        <label for="note">Files</label>
                        @foreach($quoFiles as $row)
                        {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotationFile.destroy', $row->id], ]) !!} 
                        <p class="bg-dark text-white p-2 noteFont">
                        {{ Form::hidden('Quotation_Id', $quotation->id) }}
                            <a href="{{ asset($row->list_file) }} " class="text-white " target="_blank">{{ $row->list_name != "" ? $row->list_name  : str_replace('attachments/', '', $row->list_file)}}</a>
                            @if($quotation->SubmitStatus == 0)
                            {!! Form::button('<i class="fa fa-trash text-danger"></i>', ['type'=>'submit','class' => ' float-right',
                                'onclick'=>'return confirm("Are you sure to delete?")','style' => 'background:none;border:none']) !!}
                            @endif
                        </p>
                        {!! Form::close() !!}
                        @endforeach
                    @endif
                    
                    @if($quotation->SubmitStatus == 0)
                    {!! Form::open(['route' => 'OfficeManagement.quotationFile.store', 'method' => 'POST', 'files' => true]) !!}
                        {{ Form::hidden('quoId', $quotation->id) }}
                        <input type="text" name="list_name" id="" placeholder="Add File Name(optional)" class="form-control"><br>
                        <input type="file" name="file" class="form-control" accept="image/jpeg,image/png,application/pdf," required>
                        <div class="text-right mt-3">
                            <button class="btn btn-sm btn-grey" type="reset">Reset</button>
                            <button class="btn btn-sm btn-primary" type="submit">Add</button>
                        </div>
                    {!! Form::close() !!}
                    @endif
                </div>
                <!-- FIle end -->

            </div>
            <!-- end note & file -->
        </div>
    </div>

    <!-- authorized Person -->
    <div class="row">
        <div class="col-md-8"></div>
        <div class="col-md-4 text-center">
            @if($quotation->SubmitStatus == 0)
                @if($quotation->file_name != "")
                    <img src="{{ asset($quotation->file_name)}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
                @else
                    <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center" id="authorizer-img">
                @endif
                <h6>Authorized Person</h6> 
                {!! Form::open(['route' => ['OfficeManagement.quotationAuthorizer',$quotation->id],'method' => 'POST']) !!}
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <select name="authorizer" id="authorizer" class="form-control form-select" required>
                            <option value="">Select Authorized Person</option>
                                @foreach($authorizers as $row)
                                    <option value="{{ $row->id }}" <?php if($row->authorized_name == $quotation->sign_name) echo "selected";?> data-file="{{ asset($row->file_name) }}">{{$row->authorized_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-sm btn-primary float-right" type="submit">Add</button>
                    </div>
                </div>
                {!! Form::close() !!}
            @elseif($quotation->file_name != '')
                <img src="{{ asset($quotation->file_name)}}" alt="" width=100 height=100 class="text-center">
                <p><b>{{$quotation->sign_name}}</b></p>
            @endif
        </div>
    </div>
   
    @if($quotation->SubmitStatus == 0)
    <div class="text-center ">
        <a href="{{route('OfficeManagement.quotationConfirm',$quotation->id)}}"><p><button class="btn btn-primary btn-block w-80">Confirm</button></p></a> 
    </div>
    @endif
</div>
@endsection