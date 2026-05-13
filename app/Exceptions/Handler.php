<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Symfony\Component\HttpFoundation\Response;

use Exception;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return mixed
     */
    public function render($request, Exception $exception)
    {
        if ($request->wantsJson()) {
            $response['success'] = false;

            if ($exception instanceof ModelNotFoundException) {
                $status_code = Response::HTTP_NOT_FOUND;
                $message = 'Resource Not Found';
            } else {
                $status_code = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = $exception->getMessage();
            }

            $response['error']['code'] = $status_code;
            $response['error']['message'] = $message;

            return response()->json($response, $status_code);
        }

        return parent::render($request, $exception);
    }
}
