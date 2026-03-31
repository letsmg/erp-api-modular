<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected function success(
        mixed $data = null,
        ?string $message = null,
        int $status = 200,
        array $meta = []
    ): JsonResponse {
        $payload = ['success' => true];

        if ($message !== null) {
            $payload['message'] = $this->sanitizeApiResponse($message);
        }

        if ($data !== null) {
            $payload['data'] = $this->sanitizeApiResponse($data);
        }

        if ($meta !== []) {
            $payload['meta'] = $this->sanitizeApiResponse($meta);
        }

        return response()->json($payload, $status);
    }

    protected function created(mixed $data, string $message = 'Registro criado com sucesso.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function deleted(string $message = 'Registro removido com sucesso.'): JsonResponse
    {
        return $this->success(null, $this->sanitizeApiResponse($message));
    }

    protected function paginated(LengthAwarePaginator $paginator, ?string $message = null): JsonResponse
    {
        return $this->success(
            $paginator->items(),
            $message,
            200,
            [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ]
        );
    }
}
