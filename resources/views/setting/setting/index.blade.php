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
        <div class="alert alert-success m-t-20">
            {{ $message }}
        </div>
    @endif
    
    @can('profile-change')
    <div class="setting-box m-t-20">
        <div class="flex-block">
            <div class="flex-item-0">
                <i class="fa-sharp fa-solid fa-user setting-icon"></i>
            </div>
            <div class="flex-item-1">
                <h6>
                    User Information
                    <a class="float-right" id="info-gear"><i class="fa-solid fa-gears"></i></a>
                </h6>
                
                <div class="table-responsie">
                    {!! Form::open(['route' => 'setting.setting.profileChange', 'method' => 'POST']) !!}

                    <table class="table table-no-border table-setting">
                        <tbody>
                            <tr>
                                <td class="info-form hidden" style="width: 140px">Name</td>
                                <td class="info-form hidden" style="width: 20px">:</td>
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
                                    <button type="submit" class="btn btn-primary btn-sm">Change</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('email-change')
    <div class="setting-box m-t-5">
        <div class="flex-block">
            <div class="flex-item-0">
                <i class="fa-solid fa-at setting-icon"></i>
            </div>
            <div class="flex-item-1">
                <h6>
                    User Email
                    <a class="float-right" id="email-gear"><i class="fa-solid fa-gears"></i></a>
                </h6>
                <div class="table-responsive">
                    {!! Form::open(['route' => 'setting.setting.emailChange', 'method' => 'POST']) !!}
                    <table class="table table-no-border table-setting">
                        <tbody>

                            <tr class="email-form hidden">
                                <td colspan="3">
                                    <div class="alert alert-warning m-t-20">
                                        After you've changed your email address, you must have to verify your new email adress. Otherwise you can't login into the system. Notice that action can't be undoned.
                                    </div>
                                </td>
                            </tr>

                            <tr class="email-data">
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            
                            <tr class="email-form hidden">
                                <td style="width: 140px">Email Address</td>
                                <td style="width: 20px">:</td>
                                <td style="min-width: 160px">
                                {!! Form::email('email', null, ['placeholder' => 'Email Address', 'class' => 'form-control', 'required']) !!}</td>
                            </tr>
                            <tr class="email-form hidden">
                                <td>Your Password</td>
                                <td>:</td>
                                <td>{!! Form::password('password', ['placeholder' => ' Password', 'class' => 'form-control', 'required']) !!}</td>
                            </tr>
                            <tr class="email-form hidden">
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="button" class="btn btn-secondary btn-sm" id="email-cancel">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Change</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('password-change')
    <div class="setting-box m-t-5">
        <div class="flex-block">
            <div class="flex-item-0">
                <i class="fa-solid fa-key setting-icon"></i>
            </div>
            <div class="flex-item-1">
                <h6  id="psw-gear">
                    Password Change
                    <a class="float-right"><i class="fa-solid fa-gears"></i></a>
                </h6>
                <div class="table-responsive">
                    {!! Form::open(['route' => 'setting.setting.passwordChange', 'method' => 'POST']) !!}
                    <table class="table table-no-border table-setting">
                        <tbody>
                            <tr class="psw-data">
                                <td>******</td>
                            </tr>
                            <tr class="psw-form hidden">
                                <td style="width: 140px">Old Password</td>
                                <td style="width: 20px">:</td>
                                <td>{!! Form::password('old_password', ['placeholder' => 'Old Password', 'class' => 'form-control', 'required']) !!}</td>
                            </tr>
                            <tr class="psw-form hidden">
                                <td>New Password</td>
                                <td>:</td>
                                <td>{!! Form::password('password', ['placeholder' => 'New Password', 'class' => 'form-control', 'required']) !!}</td>
                            </tr>
                            <tr class="psw-form hidden">
                                <td></td>
                                <td></td>
                                <td>
                                    <button type="button" class="btn btn-secondary btn-sm" id="psw-cancel">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Change</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @endcan

</div>
@endsection
