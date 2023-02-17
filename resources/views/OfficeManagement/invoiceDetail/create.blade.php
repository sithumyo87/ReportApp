@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ __('Invoice Item Form') }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center font-13">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a
                            href="{{ route('OfficeManagement.invoiceDetail.show', $inv->id) }}">{{ __('Invoice Detail') }}</a></li>
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
        <!-- <h3 class="text-center m-b-20">Ite{{ __('label.create') }}</h3> -->
        {!! Form::open(['route' => 'OfficeManagement.invoiceDetail.store', 'method' => 'POST']) !!}
        {{ Form::hidden('invId', $inv->id) }}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8">
                <div class="form-group">
                    <label for="">Dealer Name</label>
                    <select name="dealer_id" class="form-control select2">
                        <option value="">Choose Dealer Name</option>
                        @foreach($dealers as $row)
                        <option value="{{ $row->id }}">{{ $row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Invoice Number</label>
                    {!! Form::text('invoice_no', null, ['placeholder' => 'Invoice Number', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    {!! Form::textarea('Description', null, ['placeholder' => 'Description', 'class' => 'form-control tinymce-editor', 'cols'=>5,'rows'=>5, 'required']) !!}
                </div>
                <div class="form-group">
                    <label for="">PRICE PER ITEM</label>
                    {!! Form::number('Unit_Price', null, ['placeholder' => '', 'class' => 'form-control', 'required', 'min' => '0', 'step' => '0.01']) !!}
                </div>
                <div class="form-group">
                    <label for="">Qty</label>
                    {!! Form::number('Qty', null, ['placeholder' => '', 'class' => 'form-control', 'required', 'min' => '0']) !!}
                </div>
                <div class="form-group">
                    <label for="">Percentage</label>
                    {!! Form::number('percent', null, ['placeholder' => '', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="tax" name="tax" value="1">
                    <label class="form-check-label" for="tax">Tax(5%)</label>
                </div>
                <hr>
                <div class="text-center">
                    <a href="{{ route('OfficeManagement.invoiceDetail.show', $inv->id) }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection