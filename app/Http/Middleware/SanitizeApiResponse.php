<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SanitizeApiResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Aplica sanitização apenas para respostas JSON
        if ($response instanceof JsonResponse) {
            $data = $response->getData(true);
            $sanitizedData = $this->sanitizeData($data);
            
            $response->setData($sanitizedData);
        }

        return $response;
    }

    /**
     * Sanitiza recursivamente os dados da resposta
     */
    private function sanitizeData($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitizeData($value);
            }
            return $data;
        } elseif (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $data;
    }
}
