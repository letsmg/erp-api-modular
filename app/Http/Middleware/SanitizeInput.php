<?php

namespace App\Http\Middleware;

use App\Helpers\SanitizerHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Aplica sanitização apenas em requisições que modificam dados
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {
            $input = $request->all();
            
            // Detecta se há campos que devem preservar HTML (APENAS schema_markup e google_tag_manager)
            $htmlFields = ['schema_markup', 'google_tag_manager'];
            $hasHtmlFields = false;
            
            foreach ($htmlFields as $field) {
                if ($this->arrayHasKey($input, $field)) {
                    $hasHtmlFields = true;
                    break;
                }
            }
            
            // Se houver campos HTML, usa sanitização específica com exceções
            if ($hasHtmlFields) {
                $sanitized = SanitizerHelper::sanitize($input, $htmlFields);
            } else {
                // Sanitização completa para todos os outros campos
                $sanitized = SanitizerHelper::sanitize($input);
            }
            
            // Substitui o input da requisição com dados sanitizados
            $request->replace($sanitized);
        }

        return $next($request);
    }
    
    /**
     * Verifica recursivamente se uma chave existe em um array
     */
    private function arrayHasKey(array $array, string $key): bool
    {
        if (array_key_exists($key, $array)) {
            return true;
        }
        
        foreach ($array as $value) {
            if (is_array($value) && $this->arrayHasKey($value, $key)) {
                return true;
            }
        }
        
        return false;
    }
}
