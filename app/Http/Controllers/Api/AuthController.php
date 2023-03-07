<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DateTime;

class AuthController extends Controller
{
    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email'         => 'required',
                'password'      => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status'        => false,
                    'message'       => 'validation error',
                    'errors'        => $validateUser->errors(),
                    'error_type'    => 'validation'
                ], 401);
            }

            // check verify
            $chkUser = User::where('email', $request->email)->first();
            if(isset($chkUser) && Hash::check($request->password, $chkUser->password)){
                if(!($chkUser->email_verified_at !='')){
                    return response()->json([
                        'status'        => false,
                        'title'         => 'Verify Your Account',
                        'error_type'    => 'verify',
                        'message'       => 'Please, verify Your email address first. Check your email and then click the verification link in the mail. If you don\'t receive any mail, click Send Verify.',
                    ], 401);
                }
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status'    => false,
                    'title'     => 'Login Invalid',
                    'error_type'=> 'invaild',
                    'message'   => 'Email & Password does not match with our records.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            $token = $user->createToken("API TOKEN");

            $permission = $user->getAllPermissions();

            return response()->json([
                'status'   => true,
                'token'    => $token->plainTextToken,
                'token_id' => $token->accessToken->id,
                'user'     => $user,
                'modules'  => ['office']
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => $th->getMessage()
            ], 500);
        }
    }

    public function deleteToken(User $user, $tokenId)
    {
        return $user->tokens()->where('id', $tokenId)->delete();
    }

}
