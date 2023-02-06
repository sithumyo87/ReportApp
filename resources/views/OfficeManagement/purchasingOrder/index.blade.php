@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Purchasing Order</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                @can('role-create')
                    <a href="{{ route('OfficeManagement.purchasingOrder.create') }}" class="btn btn-success d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i>  {{ __('button.create') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="table-responsive bg-white p-30 m-t-30">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th width="50">{{ __('label.date') }}</th>
                    <th style="min-width: 180px">PO No:</th>
                    <th style="min-width: 180px">Attn Name</th>
                    <th style="min-width: 200px">Company Name</th>
                    <th style="min-width: 200px">Address</th>
                    <th style="min-width: 200px">Sub	</th>
                    <th style="min-width: 180px">Quotation No:</th>
                    <th style="min-width: 200px">Refer No:</th>
                    <th>{{ __('label.action') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <tr>
                        <td class="text-right">{{ ++$i }}</td>
                        <td>{{$row->date }}</td>
                        <td><a href="{{ route('OfficeManagement.purchasingOrder.show', $row->id) }}">{{$row->po_code }}</a></td>
                        <td>{{$row->Attn }}</td>
                        <td>{{$row->Company_name }}</td>
                        <td>{{$row->Address }}</td>
                        <td>{{$row->sub }}</td>
                        <td>{{$row->Serial_No ?? '-' }}</td>
                        <td>
                            {{$row->Refer_No ?? '-' }}
                            @if(isset($attachs[$row->id]))
                            @foreach ($attachs[$row->id] as $attach)
                                <a href="{{asset($attach['list_file'])}}" target="_blank" class="refer-attach">{{ $attach['list_name']}}</a>
                            @endforeach
                            @endif
                            
                        </td>
                        <td class="text-center">
                            @can('user-edit')
                                <a class="btn btn-sm btn-primary" href="{{ route('OfficeManagement.purchasingOrder.edit',$row->id) }}"><i class="fa fa-edit"></i></a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $data->render() !!}
    </div>
</div>
@endsection
