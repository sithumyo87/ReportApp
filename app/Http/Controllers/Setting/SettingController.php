<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    function __construct()
    {
       $this->middleware('permission:password-change|email-change|profile-change', ['only' => ['index']]);
       $this->middleware('permission:password-change', ['only' => ['password_form','password_store']]);
       $this->middleware('permission:email-change', ['only' => ['email_form','email_store']]);
       $this->middleware('permission:profile-change', ['only' => ['profile_form', 'profile_store']]);
    }


    public function index(){
        return view('setting.setting.index');
    }
}
