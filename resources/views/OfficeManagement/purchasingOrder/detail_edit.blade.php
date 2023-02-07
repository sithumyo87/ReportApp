@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $po->po_code }} Detail Create</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    @can('user-index')
                    <li class="breadcrumb-item"><a href="{{ route('OfficeManagement.purchasingOrder.show', $po->id) }}">{{ $po->po_code }}</a></li>
                    @endcan
                    <li class="breadcrumb-item active">Create</li>
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
        <!-- <h3 class="text-center m-b-20"></h3> -->
        {!! Form::model($poDetail, ['route' => ['OfficeManagement.purchasingOrderDetail.update', $poDetail->id], 'method' => 'PATCH']) !!}
        {!! Form::hidden('po_id', $po->id) !!}
        <div class="row justify-content-center">
            <div class="col-xs-12 col-sm-12 col-md-8 mt-3">
                <div class="form-group">
                    <label for="">Dealer Name</label>
                    <select name="dealer_id" class="form-control select2" required>
                        <option value="">Choose Dealer Name</option>
                        @foreach($dealers as $row)
                        <option value="{{ $row->id }}" @if($poDetail->dealer_id == $row->id) checked @endif>{{ $row->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Form 31 number</label>
                    {!! Form::text('form31_no', null, ['placeholder' => 'Form 31 number', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Invoice Number</label>
                    {!! Form::text('invoice_no', null, ['placeholder' => 'Invoice Number', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Description</label>
                    {!! Form::textarea('description', null, ['placeholder' => 'value', 'class' => 'form-control tinymce-editor', 'rows' => '5']) !!}
                </div>
                <div class="form-group">
                    <label for="">Price Per Item</label>
                    {!! Form::number('price', null, ['placeholder' => 'Price (only number)', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="">Qty</label>
                    {!! Form::number('qty', null, ['placeholder' => 'qty (only number)', 'class' => 'form-control']) !!}
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="tax" name="tax" value="5" @if($poDetail->tax == 5) checked @endif>
                    <label class="form-check-label" for="tax">Tax(5%)</label>
                </div>
                <hr>
                <div class="text-center">
                    <a href="{{ route('OfficeManagement.purchasingOrderDetail.show', $po->id) }}" class="btn btn-warning">{{ __('button.cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __('button.save') }}</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection