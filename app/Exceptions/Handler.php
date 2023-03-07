<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {

                $loginToken = DB::table('personal_access_tokens')->where('id',$request->token_id)->first();

                // if($request->token_id != null){
                //     DB::table('personal_access_tokens')->where('id',$request->token_id)->delete();
                // }

                if(isset($loginToken)){
                    return response()->json(['success' => false, 'title'=> 'Unauthorized','message'=>'You don\'t have the permission to access.', 'exception' => 'AuthenticationException'], 404);
                }else{
                    return response()->json(['success' => false, 'title'=> 'Unauthorized','message'=>'Your login time is expired. Please login again.', 'exception' => 'UnauthorizedHttpException'], 404);
                }
            }
        });
        
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => false, 'title'=> 'PageNotFound','message'=>'The requested url is not found.', 'exception' => 'NotFoundHttpException'], 404);
            }
        });

        $this->renderable(function (UnauthorizedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json(['success' => false,'title'=>'Unauthorized', 'message' => 'Your login time is expired. Please login again', 'exception' => 'UnauthorizedHttpException'], 403);
            }
        });

        $this->reportable(function (Throwable $e) {
            
        });
    }
}
