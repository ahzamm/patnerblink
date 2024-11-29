<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\model\Users\Error;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $exception)
    {
        //
        $username = NULL;
        if (Auth::user()) {
            $username = Auth::user()->username;
        }

        $data = array(
            'username' => $username, 
            'message' => $exception->getMessage(), 
            'route_name' => Route::currentRouteName(),
            'route_action' => Route::currentRouteAction(),
            'trace' => $exception->getTraceAsString(),       
        );

        // Error::create($data);
        //
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof \Illuminate\Session\TokenMismatchException) {

            return redirect('/login');

        }
        return parent::render($request, $exception);
    }
}
