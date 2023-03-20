<?php

namespace App\Exceptions;

use ErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\UnauthorizedException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $exception, $request) {
            if ($request->expectsJson()) {
                if ($exception instanceof AuthorizationException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode(Response::HTTP_UNAUTHORIZED)
                        ->withMessage($exception->getMessage())
                        ->build();
                }

                if ($exception instanceof AuthenticationException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode(Response::HTTP_FORBIDDEN)
                        ->withMessage($exception->getMessage() ?: 'Unauthenticated')
                        ->build();
                }

                if ($exception instanceof ModelNotFoundException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode(Response::HTTP_NOT_FOUND)
                        ->withMessage('The requested resource does not exist')
                        ->build();
                }

                if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode($exception->getStatusCode())
                        ->withMessage($exception->getMessage() ?: 'Route not found')
                        ->build();
                }

                if ($exception instanceof UnauthorizedException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode(Response::HTTP_UNAUTHORIZED)
                        ->withMessage($exception->getMessage() ?: 'Unauthorized')
                        ->build();
                }

                if ($exception instanceof HttpException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode($exception->getStatusCode())
                        ->withMessage($exception->getMessage())
                        ->build();
                }

                if ($exception instanceof ErrorException) {
                    return ResponseBuilder::asError(100)
                        ->withHttpCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                        ->withMessage('Server Error')
                        ->build();
                }

                // if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
                //     return response()->json([
                //         'responseMessage' => 'You do not have the required authorization.',
                //         'responseStatus'  => 403,
                //     ]);
                // }
                // return parent::render($request, $exception);
            }
        });
    }
}
