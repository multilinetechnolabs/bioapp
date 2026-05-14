<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') && $this->shouldReport($e)) {
                app('sentry')->captureException($e);
            }
        });

        $this->renderable(function (Throwable $e, Request $request) {
            if (! $request->wantsJson()) {
                return null;
            }

            if ($e instanceof ModelNotFoundException) {
                $statusCode = Response::HTTP_NOT_FOUND;
                $message = 'Resource Not Found';
            } else {
                $statusCode = method_exists($e, 'getStatusCode')
                    ? $e->getStatusCode()
                    : Response::HTTP_INTERNAL_SERVER_ERROR;

                $message = $e->getMessage() ?: Response::$statusTexts[$statusCode] ?? 'Server Error';
            }

            return response()->json([
                'success' => false,
                'error' => [
                    'code' => $statusCode,
                    'message' => $message,
                ],
            ], $statusCode);
        });
    }
}
