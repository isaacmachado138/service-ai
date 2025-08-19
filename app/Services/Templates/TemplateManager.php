<?php

namespace App\Services\Templates;

use App\Models\PromptTemplate;
use Illuminate\Support\Facades\Cache;

/**
 * Gerenciador de templates de prompt com suporte a cache
 */
class TemplateManager
{
    /**
     * Tempo de cache em segundos (1 hora por padrão)
     */
    const CACHE_TTL = 3600;

    /**
     * Obtém um template completo pelo código com suporte a cache
     *
     * @param string $code Código identificador único do template
     * 
     * @return PromptTemplate|null O template encontrado ou null caso não exista
     */
    public static function getTemplate(string $code): ?PromptTemplate
    {
        return Cache::remember('template_' . $code, self::CACHE_TTL, function () use ($code) {
            $template = PromptTemplate::active()
                ->where('code', $code)
                ->first();
                
            return $template ?: null;
        });
    }

    /**
     * Obtém e formata um template com as variáveis fornecidas
     *
     * @param string $code Código identificador único do template
     * @param array $variables Array associativo com as variáveis a serem substituídas
     * 
     * @return array Array contendo system_prompt e user_prompt formatados
     * 
     * @throws \InvalidArgumentException Se o template não for encontrado e não houver prompt direto
     * @throws \InvalidArgumentException Se variáveis obrigatórias não forem fornecidas
     */
    public static function getFormattedTemplate(string $code, array $variables = []): ?array
    {
        $template = self::getTemplate($code);
        
        if (!$template) {
            // Se não tem template e não tem prompt, lança exceção
            if (!isset($variables['prompt']) || empty($variables['prompt'])) {
                throw new \InvalidArgumentException('O prompt não pode estar vazio quando o template não é encontrado');
            }
            
            return [
                'system_prompt' => 'Você é um assistente útil.',
                'user_prompt' => $variables['prompt']
            ];
        }

        // Se for um template com variáveis, valida que as variáveis necessárias existem
        $userPrompt = $template->formatUserPrompt($variables);

        // Verifica se ainda existem placeholders não substituídos no prompt formatado
        if (preg_match('/{[a-zA-Z0-9_]+}/', $userPrompt)) {
            throw new \InvalidArgumentException('Variáveis obrigatórias não foram fornecidas para o template');
        }

        return [
            'system_prompt' => $template->system_prompt,
            'user_prompt' => $userPrompt
        ];
    }

    /**
     * Limpa o cache de um template específico
     *
     * @param string $code Código identificador único do template
     */
    public static function clearCache(string $code): void
    {
        Cache::forget('template_' . $code);
    }

    /**
     * Limpa o cache de todos os templates
     */
    public static function clearAllCache(): void
    {
        $templates = PromptTemplate::all();

        foreach ($templates as $template) {
            self::clearCache($template->code);
        }
    }
}
