<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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


    public function profileChange(Request $request){
        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        return redirect()->route('setting.setting.index')->with('success', 'Name is successfully changed.');
    }

    public function emailChange(Request $request){
        $user = Auth::user();
        if (Hash::check($request->password, $user->password)) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->save();

            $user->sendEmailVerificationNotification();
            return redirect()->route('setting.setting.index')->with('success', 'Email is successfully changed.');
        }else{
            return redirect()->route('setting.setting.index')->with('success', 'Password is invalid.');
        }
    }

    public function passwordChange(Request $request){
        $user = Auth::user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('setting.setting.index')->with('success', 'Password is successfully changed.');
        }else{
            return redirect()->route('setting.setting.index')->with('success', 'Old Password is invalid.');
        }
    }
}
