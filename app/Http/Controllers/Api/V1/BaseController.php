<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Symfony\Component\HttpFoundation\Response;

class BaseController extends Controller
{
    use AuthorizesRequests;

    /**
     * Returns a valid response.
     *
     * @param  mixed $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendValidResponse($data, $message = '', $statusCode = Response::HTTP_OK)
    {
        $response['success'] = true;
        $response['data'] = $data;

        if (!empty($message)) {
            $response['message'] = $message;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Returns an error response.
     *
     * @param  string  $errorMessage
     * @param  int  $statusCode
     * @param  array  $errorMessages
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendErrorResponse($errorMessage, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR, $errorMessages = [], $redirectUrl = '')
    {
        $response['success'] = false;
        $response['error'] = [
            'code' => $statusCode,
            'message' => $errorMessage
        ];

        if (!empty($errorMessages)) {
            $response['error']['errors'] = $errorMessages;
        }

        if (!empty($redirectUrl)) {
            $response['error']['redirect_url'] = $redirectUrl;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Returns an invalid response.
     *
     * @param  string  $errorMessage
     * @param  mixed  $errorMessages
     * @param  string  $redirectUrl
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvalidResponse($errorMessages = [], $errorMessage = 'Unprocessable Entity', $redirectUrl = '')
    {
        $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY;

        return $this->sendErrorResponse($errorMessage, $statusCode, $errorMessages, $redirectUrl);
    }

    /**
     * Returns an unauthorized response.
     *
     * @param  mixed  $errorMessages
     * @return  \Illuminate\Http\JsonResponse
     */
    public function sendUnauthorizedResponse($errorMessages = [])
    {
        $errorMessage = 'Unauthorized Access';
        $statusCode = Response::HTTP_UNAUTHORIZED;

        return $this->sendErrorResponse($errorMessage, $statusCode, $errorMessages);
    }
}
