<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, string $message = "Success", int $code = 200, array $extra = [])
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message
        ];
        if (!empty($extra)) $response['extra'] = $extra;
        return response()->json($response, $code);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError(string $error, $errorMessages = [], int $code = 404, array $extra = [])
    {
        $response = [
            'success' => false,
            'data' => [],
            'message' => $error,
            'extra' => $extra
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
