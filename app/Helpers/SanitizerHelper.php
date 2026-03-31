<?php

namespace App\Helpers;

class SanitizerHelper
{
    /**
     * Sanitiza um array de dados removendo tags HTML e padrões perigosos
     * 
     * @param array $data Dados a serem sanitizados
     * @param array $except Campos que não devem ser sanitizados (ex: ['schema_markup', 'google_tag_manager'])
     * @return array Dados sanitizados
     */
    public static function sanitize(array $data, array $except = []): array
    {
        return self::sanitizeArray($data, $except);
    }

    /**
     * Sanitiza recursivamente um array de dados
     * 
     * @param array $data Dados a serem sanitizados
     * @param array $except Campos que não devem ser sanitizados
     * @return array Dados sanitizados
     */
    private static function sanitizeArray(array $data, array $except = []): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::sanitizeArray($value, $except);
            } elseif (is_string($value)) {
                if (!in_array($key, $except)) {
                    $data[$key] = self::sanitizeString($value);
                }
            }
        }
        return $data;
    }

    /**
     * Sanitiza uma string individual removendo tags e padrões perigosos
     */
    public static function sanitizeString(string $value): string
    {
        // Remove tags HTML completamente (incluindo conteúdo dentro de script/style)
        $cleaned = strip_tags($value);
        
        // Remove espaços em branco no início e fim
        $cleaned = trim($cleaned);
        
        // Remove múltiplos espaços em branco internos
        $cleaned = preg_replace('/\s+/', ' ', $cleaned);
        
        // Remove espaços em branco no início e fim novamente (após a substituição)
        $cleaned = trim($cleaned);
        
        // Remove padrões javascript perigosos que possam ter escapado
        $cleaned = preg_replace('/javascript\s*:/i', '', $cleaned);
        
        // Remove eventos on* perigosos
        $cleaned = preg_replace('/on\w+\s*=/i', '', $cleaned);
        
        // Remove conteúdo de scripts que possa ter escapado
        $cleaned = preg_replace('/alert\s*\([^)]*\)/i', '', $cleaned);
        
        // NÃO APLICA htmlspecialchars aqui - será aplicado apenas na saída
        
        return $cleaned;
    }
}
