@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="quotation page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ $quotation->Serial_No}} 's Detail</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('role-create')
                    <a href="{{ route('OfficeManagement.quotationDetail.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <hr>
        <div class="row">
            <div class="col-md-7">
                <p><b>Sub: </b> <t>{{ $quotation->Sub}}</p>
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
                    <tr>
                        <td class="text-right">
                             <a href="{{ route('OfficeManagement.quotationDetail.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
                    </a></td>
                        <td>{{ $quotation->Date }}</td>
                        <td><a href="{{route('OfficeManagement.quotation.edit',$quotation->id)}}">{{ $quotation->Serial_No }}</a></td>
                        <td>{{ $quotation->Contact_phone }}</td>
                        <td>{{ $quotation->Company_name }}</td>
                        <td>{{ $quotation->Sub }}</td>
                        <td>{{ $quotation->Refer_No }}</td>
                        <td class="text-center">
                            @can('user-edit')
                                <a class="btn btn-primary" href="{{ route('OfficeManagement.quotation.edit', $quotation->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                            @can('user-delete')
                                {!! Form::open(['method' => 'DELETE', 'route' => ['OfficeManagement.quotation.destroy', $quotation->id], 'style' => 'display:inline']) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', ['type'=>'submit','class' => 'btn btn-danger', 'id' => 'delete']) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>

                    <!-- <tr> -->
                        <td colspan="6" class="text-right"><b>Total</b></td>
                        <td><b>58083.00 USD</b></td>
                        <td><b>61,006.00 USD</b></td>
                    <!-- </tr> -->

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

							

							</p>

						</div>

					</div>



					<div class="row" style="margin-bottom: 8px; padding: 3px 5px;">

						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">

							<p><strong>Tax (5%)</strong></p>

						</div>

						

					</div>



					<div class="row" style="margin-bottom: 8px; padding: 3px 5px;">

						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">

							<p><strong>Tax Amount</strong></p>

						</div>

						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">

							
						</div>

					</div>

					<div class="row" style="margin-bottom: 8px; padding: 3px 5px;">

						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-right">

							<p><strong>Grand Total</strong></p>

						</div>

						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">

						

						</div>

					</div>

				</div>

			</div>

            <!-- <div class="clearfix"></div> -->

    <!-- Note -->
    <div class="col-md-6 m-4 float-left">
        <label for="note">Notes</label>
        <textarea name="note" id="" cols="10" rows="5" class="form-control"></textarea>
    </div>


    <!-- FIle -->
    <div class="col-md-6 form-group m-4 float-left">
        <label for="filename">LIST FILES</label>
        <input type="text" name="" id="" placeholder="Add File Name(optional)" class="form-control"><br>
        <input type="file" name="">
    </div>

</div>
    


    <!-- authorized Person -->
    <div class="col-md-3  float-right mb-3">
    <img src="{{ asset('img/author-icon.png')}}" alt="" width=100 height=100 class="text-center">
    <h6>Authorized Person</h6>
            <div class="d-flex  align-items-center">
            <select name="" id="" class="form-control form-select">
                <option value="">Ko Naing Ko Ko</option>
                <option value="">Ko Myat Ko Ko</option>
            </select>
            <a href="" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
            </div>
    </div>



<div class="text-center ">
    <p><button class="btn btn-primary btn-block w-80">Confirm</button></p>
</div>
@endsection


