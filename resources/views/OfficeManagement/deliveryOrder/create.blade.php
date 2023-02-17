@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Delivery Order Create</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.deliveryOrder.index') }}">Delivery Order</a></li>
                    @endcan
                    <li class="breadcrumb-item active">{{ __('label.create') }}</li>
                </ol>
            </div>
        </div>
    </div>
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="bg-white p-30">
        {!! Form::open(['route' => 'OfficeManagement.deliveryOrder.store', 'method' => 'POST', 'files' => true]) !!}
        <div class="row justify-content-center p-3">
            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label class="control-label" for="date">Date</label>
                    <input class="form-control date-picker" id="date" name="date" placeholder="DD-MM-YYYY" type="text" required/>
                </div>
                <div class="inv-quo-group">
                    <div class="form-group">
                        {{ Form::label('sub', 'Subject') }}
                        <input class="form-control" name="sub" placeholder="subject" type="text" required/>
                    </div>
                    <div class="attn-form">
                        <div class="form-group">
                            <label for="">ATT Name</label>
                            <select name="customer_id" class="form-control attn-customer select2" required>
                                <option value="">Choose Customer</option>
                                @foreach($customers as $row)
                                <option value="{{ $row->id }}">{{ $row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="company">Company Name</label>
                            <select name="Company_name" class="form-control attn-company select2" required>
                                <option value="">Choose Company Name</option>
                                @foreach($customers as $row)
                                <option value="{{ $row->company }}">{{ $row->company}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Phone No</label>
                            {!! Form::text('Contact_phone', null, ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <label for="">Address</label>
                            {!! Form::textarea('Address', null, ['placeholder' => 'Address', 'class' => 'form-control','cols'=>5,'rows'=>5]) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="quo_no">
                        <input type="checkbox" id="quo-cb" class="form-check-input" value="quo_no"> <label class="form-check-label" for="quo-cb">Quotation</label>
                    </label>
                    <select name="quo_id" class="form-control select2" id="quo_no" disabled>
                        <option value="">Quotation No:</option>
                        @foreach($quotations as $row)
                        <option value="{{ $row->id }}">{{ $row->Serial_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="inv_no">
                        <input type="checkbox" id="inv-cb" class="form-check-input" value="inv_no"> <label class="form-check-label" for="inv-cb">Invoice</label>
                    </label>
                    <select name="inv_id" class="form-control select2" id="inv_no" disabled>
                        <option value="">Invoice No:</option>
                        @foreach($invoices as $row)
                        <option value="{{ $row->id }}">{{ $row->Invoice_No}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="po_no">
                        <input type="checkbox" id="po-cb" class="form-check-input" value="po_no"> <label class="form-check-label" for="po-cb">P.O No.</label>
                    </label>
                    {!! Form::text('po_no', null, ['placeholder' => 'P.O Number', 'class' => 'form-control', 'id' => 'po_no', 'readonly']) !!}
                </div>
            </div>
            <div class="text-center">
                <a href="{{ route('OfficeManagement.deliveryOrder.index') }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection