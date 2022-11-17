<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
        $this->renderable(function (\Throwable $e) {
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 404,
                    'message' => __('messages.page_not_found'),
                ], 404);
            }
        });
    }

    protected function invalidJson($request, $exception)
    {
        return response()->json([
            'data' => [],
            'message' => 'The given data was invalid',
            'errors' => $exception->errors(),
            'status' => $exception->status
        ], $exception->status);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'data' => null,
                'status' => 401
            ], 401);
        }

        return redirect()->guest('login');
    }
}
