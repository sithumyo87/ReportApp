@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $quotation->Serial_No}} 's Detail</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <a href="{{ route('OfficeManagement.quotationDetail.create') }}"
                    class="btn btn-success d-none d-lg-block m-l-15 mr-3"><i class="fa fa-print"></i>
                    Print
                </a>
                <a href="{{ route('OfficeManagement.quotation.index') }}"
                    class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-back"></i>
                    Back
                </a>
            </div>
        </div>
    </div>
    <hr>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="row">
        <div class="col-md-7">
            <p><b>Sub: </b>
                <t>{{ $quotation->Sub}}
            </p>
            <p><b>Attn: </b> {{ $quotation->Serial_No}}</p>
            <p><b>Company: </b> {{ $quotation->Company_name}}</p>
            <p><b>Phone No: </b> {{ $quotation->Contact_phone}}</p>
            <p><b>Address: </b> {{ $quotation->Address}}</p>
        </div>
        <div class="col-md-5">
            <p><b>Serial No: </b> {{ $quotation->Serial_No}}</p>
            <p><b>Refer No: </b> {{ $quotation->Refer_No}}</p>
            <p><b>Date: </b> {{ $quotation->Date}}</p>
        </div>
    </div>
    <hr>

    <div class="bg-white p-30">
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
                    <th width="150">{{ __('label.action') }}</th>
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
                 $subTotalWithPer += percent_price($row['Unit_Price'], $row['percent']) * $row['Qty'];
                 ?>

                <tr>
                    <td>{{ $row->Description }}</td>
                    <td>{{ $row->Unit_Price }}</td>
                    <td>{{ $row->percent }}</td>
                    <td>{{number_format(percent_price($row['Unit_Price'], $row['percent']),2)}} </td>
                    <td>{{ $row->Qty }}</td>
                    <td>{{ number_format($row->Unit_Price * $row->Qty,2) }} {{$currency->Currency_name}}</td>
                    <td>{{ number_format(percent_price($row['Unit_Price'], $row['percent']) * $row['Qty'],2); }} {{$currency->Currency_name}}</td>
                    <td class="text-center">
                        @can('user-edit')
                        <a class="btn btn-primary"
                            href="{{ route('OfficeManagement.quotationDetail.edit', $quotation->id) }}"><i
                                class="fa fa-edit"></i></a>
                        @endcan
                        @can('user-delete')
                        {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotation.destroy',
                        $quotation->id], 'style' => 'display:inline']) !!}
                        {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger',
                        'id' => 'delete']) !!}
                        {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
                @endforeach
                 <!-- <tr> -->
                 <td colspan="5" class="text-right"><b>Total</b></td>
                <td><b>{{ number_format($subTotal,2) }} {{$currency->Currency_name}}</b></td>
                <td><b>{{ number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}</b></td>
                <!-- </tr> -->
                @endif
                @if($quotation->SubmitStatus == 0):
                <tr>
                    <td class="text-right">
                        <a href="{{ route('OfficeManagement.quotationDetailCreate',$quotation->id) }}"
                            class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>
                            {{ __('button.create') }}
                        </a>
                    </td> 
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class=" clearfix">
        <div class="col-lg-5 col-md-5 float-right">
            <div class="row" style=" padding: 0 0 10px 0;">
                <div class="row" style="margin-bottom: 8px; padding: 3px 5px;">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Total</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                      <p>
                        {{number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}
                        </p>
                    </div>
                </div>
                
                @if($quotation->SubmitStatus == 0):
                <div class="row" style="margin-bottom: 8px; padding: 3px 5px;">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Tax (5%)</strong></p>
                        </div>
                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                        @if($quotation['Tax'] != 0)
                        <label><input type="checkbox" class="minimal" id="taxcheck" data-id="{{ $quotation['Id']; }}" checked></label>
                        @else
                        <label><input type="checkbox" class="minimal" id="taxcheck" data-id="{{ $quotation['Id']; }}"></label>
                        @endif
                    </div>
                </div>
                @endif

                <div class="row" style="margin-bottom: 8px; padding: 3px 5px;">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Tax Amount</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <p>{{ number_format(($quotation['Tax'] * $subTotalWithPer)/100,2);}}{{$currency->Currency_name}}</p>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 8px; padding: 3px 5px;">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">
                        <p><strong>Grand Total</strong></p>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    {{ number_format($subTotalWithPer,2)}} {{$currency->Currency_name}}
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="clearfix"></div> -->

        <!-- Note -->
        <div class="col-md-6 m-4 float-left">
            <label for="note">Notes</label>
            @foreach($quoNotes as $row)
            
            {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotationNote.destroy', $row->id], ]) !!} 
            <p class="bg-dark text-white p-2 noteFont">
                {{$row->Note}}
                {{ Form::hidden('Quotation_Id', $quotation->id) }}
                @if($quotation->SubmitStatus == 0):
                {!! Form::button('<i class="fa fa-trash text-danger"></i>', ['type'=>'submit','class' => ' float-right',
                    'onclick'=>'return confirm("Are you sure to delete?")','style' => 'background:none;border:none']) !!}    
                    <a href="{{route('OfficeManagement.quotationDetail.getNote',['id'=>$quotation->id,'noteId'=>$row->id])}}"
                        class="float-right me-2"><i class="fa fa-edit text-white"></i></a>
                        @endif
                {!! Form::close() !!}
                
            </p>
            
            @endforeach
            @if($quotation->SubmitStatus == 0):
            @if(empty($quoNote))
            {!! Form::open(['route' => 'OfficeManagement.quotationNote.store', 'method' => 'POST']) !!}
            @else
            {!! Form::model($quotation, ['method' => 'PATCH', 'route' =>
            ['OfficeManagement.quotationNote.quotationDetail.update',
            ['quotationNote'=>$quoNote->id,'quotationDetail'=>$quotation->id]]]) !!}
            @endif
            {{ Form::hidden('quoId', $quotation->id) }}
            <textarea name="Note" id="" cols="5" rows="5" class="form-control"
                required>{{$quoNote->Note ?? null}} </textarea>
            <div class="text-right mt-3">
                <button class="btn btn-sm btn-grey" type="reset">Reset</button>
                <button class="btn btn-sm btn-primary" type="submit">Add</button>
            </div>
            @endif
        </div>
        {!! Form::close() !!}
       
        <!-- Note ENd -->

        <!-- FIle -->
        <div class="col-md-6 form-group m-4 float-left">
            <label for="note">Files</label>
            @foreach($quoFiles as $row)
            {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotationFile.destroy', $row->id], ]) !!} 
            <p class="bg-dark text-white p-2 noteFont">
            {{ Form::hidden('Quotation_Id', $quotation->id) }}
                <a href="{{asset($row->list_file)}}" class="text-white " >{{ $row->list_name != "" ? $row->list_name  : str_replace('attachments/', '', $row->list_file)}}</a>
                @if($quotation->SubmitStatus == 0):
                {!! Form::button('<i class="fa fa-trash text-danger"></i>', ['type'=>'submit','class' => ' float-right',
                    'onclick'=>'return confirm("Are you sure to delete?")','style' => 'background:none;border:none']) !!}
                @endif
            </p>
            {!! Form::close() !!}
            @endforeach
            
            @if($quotation->SubmitStatus == 0):
            {!! Form::open(['route' => 'OfficeManagement.quotationFile.store', 'method' => 'POST', 'files' => true]) !!}
            {{ Form::hidden('quoId', $quotation->id) }}
            <input type="text" name="list_name" id="" placeholder="Add File Name(optional)" class="form-control"><br>
            <input type="file" name="file" class="form-control" accept="image/jpeg,image/png,application/pdf," required>
            <div class="text-right mt-3">
                <button class="btn btn-sm btn-grey" type="reset">Reset</button>
                <button class="btn btn-sm btn-primary" type="submit">Add</button>
            </div>
        </div>
        {!! Form::close() !!}
        @endif
        <!-- FIle end -->
        

    </div>



    <!-- authorized Person -->
    @if($quotation->SubmitStatus == 0):
    <div class="col-md-3  float-right mb-3">
    @if($quotation->file_name != ""):
        <img src="{{ asset($quotation->file_name)}}" alt="" width=100 height=100 class="text-center">
    @else
        <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center">
    @endif
        <h6>Authorized Person</h6>
        <div>
        {!! Form::open(['route' => ['OfficeManagement.quotationAuthorizer',$quotation->id],'method' => 'POST']) !!}
            <select name="authorizer" id="" class="form-control form-select">
            @foreach($authorizers as $row)
                <option value="{{$row->id}}" <?php if($row->sign_name == $quotation->authorized_name) echo "selected";?>>{{$row->authorized_name}}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary float-right" type="submit">Add</button>
            {!! Form::close() !!}
        </div>
    </div>
    @else
    <div class="col-md-3  float-right mb-3">
        <img src="{{ asset($quotation->file_name)}}" alt="" width=100 height=100 class="text-center">
        <p><b>{{$quotation->sign_name}}</b></p>
    </div>
    @endif


    @if($quotation->SubmitStatus == 0):
    <div class="text-center ">
        <a href="{{route('OfficeManagement.quotationConfirm',$quotation->id)}}"><p><button class="btn btn-primary btn-block w-80">Confirm</button></p></a> 
    </div>
    @endif
    @endsection