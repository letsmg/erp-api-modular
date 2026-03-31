<?php

namespace App\Http\Controllers;

use App\Helpers\SanitizerHelper;
use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Sanitiza dados de resposta da API para prevenir XSS
     * 
     * @param mixed $data Dados a serem sanitizados
     * @return mixed Dados sanitizados
     */
    protected function sanitizeApiResponse($data)
    {
        if (is_array($data)) {
            return $this->sanitizeArrayRecursive($data);
        } elseif (is_object($data)) {
            return $this->sanitizeObjectRecursive($data);
        }
        
        return $data;
    }
    
    /**
     * Sanitiza recursivamente arrays
     */
    private function sanitizeArrayRecursive(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->sanitizeArrayRecursive($value);
            } elseif (is_string($value)) {
                $array[$key] = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
        
        return $array;
    }
    
    /**
     * Sanitiza recursivamente objetos
     */
    private function sanitizeObjectRecursive($object)
    {
        if (method_exists($object, 'toArray')) {
            $array = $object->toArray();
            return $this->sanitizeArrayRecursive($array);
        }
        
        return $object;
    }
    
    /**
     * Retorna resposta JSON com dados sanitizados
     */
    protected function sanitizedJson($data = null, string $message = null, int $status = 200): JsonResponse
    {
        if ($data !== null) {
            $data = $this->sanitizeApiResponse($data);
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    
    /**
     * Retorna resposta JSON de sucesso com paginação
     */
    protected function success($data = null, string $message = null, int $status = 200): JsonResponse
    {
        if ($data !== null) {
            $data = $this->sanitizeApiResponse($data);
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }
    
    /**
     * Retorna resposta JSON paginada
     */
    protected function paginated($paginator, string $message = null, int $status = 200): JsonResponse
    {
        $data = [
            'data' => $paginator->items(),
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
        
        if ($message !== null) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ], $status);
        }
        
        return response()->json($data, $status);
    }
}
