@extends('layouts.setting')
@section('content')
<div class="container-fluid">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">Settings</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success m-t-30">
            {{ $message }}
        </div>
    @endif

    <div class="setting-box">
        <div class="flex-block">
            <div class="flex-item-0">
                <i class="fa-sharp fa-solid fa-user setting-icon"></i>
            </div>
            <div class="flex-item-1">
                <h6>
                    User Information
                    <a href="#" class="float-right" id="info-gear"><i class="fa-solid fa-gears"></i></a>
                </h6>
                
                <div class="form">
                    {!! Form::open(['route' => 'setting.role.store', 'method' => 'POST']) !!}

                    <table class="table table-no-border table-setting">
                        <tbody>
                            <tr>
                                <td style="width: 100px">Name</td>
                                <td style="width: 20px">:</td>
                                <td class="info-data">
                                    {{ Auth::user()->name }}
                                </td>
                                <td class="info-form hidden">
                                    {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control', 'required']) !!}
                                </td>
                            </tr>
                            <tr class="info-form hidden">
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="button" class="btn btn-secondary btn-sm" id="info-cancel">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="setting-box m-t-10">
        <div class="flex-block">
            <div class="flex-item-0">
                <i class="fa-solid fa-key setting-icon"></i>
            </div>
            <div class="flex-item-1">
                <h6>
                    Password Change
                    <a href="#" class="float-right" id="psw-gear"><i class="fa-solid fa-gears"></i></a>
                </h6>
                <div class="form">
                    {!! Form::open(['route' => 'setting.role.store', 'method' => 'POST']) !!}
                    <table class="table table-no-border table-setting psw-form hidden">
                        <tbody>
                            <tr>
                                <td style="width: 140px">Old Password</td>
                                <td style="width: 20px">:</td>
                                <td>{!! Form::text('name', null, ['placeholder' => 'Old Password', 'class' => 'form-control']) !!}</td>
                            </tr>
                            <tr>
                                <td>New Password</td>
                                <td>:</td>
                                <td>{!! Form::text('name', null, ['placeholder' => 'New Password', 'class' => 'form-control']) !!}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="button" class="btn btn-secondary btn-sm" id="psw-cancel">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="setting-box m-t-10">
        <div class="flex-block">
            <div class="flex-item-0">
                <i class="fa-solid fa-at setting-icon"></i>
            </div>
            <div class="flex-item-1">
                <h6>
                    Email Address
                    <a href="#" class="float-right" id="email-gear"><i class="fa-solid fa-gears"></i></a>
                </h6>
                <div class="form">
                    {!! Form::open(['route' => 'setting.role.store', 'method' => 'POST']) !!}
                    <table class="table table-no-border table-setting">
                        <tbody>
                            <tr>
                                <td style="width: 160px">Email Address</td>
                                <td style="width: 20px">:</td>
                                <td>{!! Form::text('name', null, ['placeholder' => 'Old Password', 'class' => 'form-control']) !!}</td>
                            </tr>
                            <tr>
                                <td> Password</td>
                                <td>:</td>
                                <td>{!! Form::text('name', null, ['placeholder' => ' Password', 'class' => 'form-control']) !!}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="button" class="btn btn-secondary btn-sm">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
