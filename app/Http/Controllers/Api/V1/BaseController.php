<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @OA\Info(
 *    title="Tajir API Documentation",
 *    version="1.0.0",
 *     description="API documentation for Tajir e-commerce platform",
 * )
 * */
class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse(
        $result,
        string $message = "Success",
        int $code = 200,
        array $extra = [],
        array $headers = []
    ) {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message
        ];
        if (!empty($extra)) {
            $response['extra'] = $extra;
        }
        return response()->json($response, $code)->withHeaders($headers);
    }

    public function sendPaginatedResponse(
        LengthAwarePaginator $result,
        string $message = "Success",
        int $code = 200,
        array $extra = [],
        array $headers = [],
        callable|null $collectionTransformer = null
    ) {
        $paginationHeaders = [
            'X-Total-Count' => $result->total(),
            'X-Per-Page' => $result->perPage(),
            'X-Current-Page' => $result->currentPage(),
            'X-Last-Page' => $result->lastPage(),
            'X-From' => $result->firstItem(),
            'X-To' => $result->lastItem(),
        ];
        return $this->sendResponse(
            result: $collectionTransformer
                ? $collectionTransformer($result->items())
                : ($result instanceof ResourceCollection
                    ? $result
                    : $result->getCollection()),
            message: $message,
            code: $code,
            extra: array_merge($extra, [
                'pagination' => [
                    'total' => $result->total(),
                    'per_page' => $result->perPage(),
                    'current_page' => $result->currentPage(),
                    'last_page' => $result->lastPage(),
                    'from' => $result->firstItem(),
                    'to' => $result->lastItem(),
                ]
            ]),
            headers: $headers + $paginationHeaders
        );
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

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
