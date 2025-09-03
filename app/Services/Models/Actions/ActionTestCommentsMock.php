<?php

namespace App\Services\Models\Actions;

use App\Services\Models\Builder\Builder;
use Illuminate\Support\Facades\Http;
use Prism\Prism\Tool;

/**
 * Action para buscar comentários de um post da API JSONPlaceholder
 */
class ActionTestCommentsMock extends Builder
{
    /**
     * Constrói e retorna uma Tool do Prism para buscar comentários
     *
     * @return Tool Ferramenta configurada para buscar comentários
     */
    public function build(): Tool
    {
        return (new Tool())
            ->as('get_comments')
            ->for('Busca comentários de um post específico da API JSONPlaceholder')
            ->withStringParameter('postId', 'ID do post para buscar comentários', true)
            ->using(function (string $postId): string {
                try {
                    $response = Http::get("https://jsonplaceholder.typicode.com/comments?postId={$postId}");
                    
                    if ($response->successful()) {
                        $comments = $response->json();
                        return json_encode([
                            'success' => true,
                            'data' => $comments,
                            'count' => count($comments),
                            'postId' => $postId
                        ]);
                    } else {
                        return json_encode([
                            'success' => false,
                            'error' => 'Falha ao buscar comentários',
                            'status' => $response->status(),
                            'postId' => $postId
                        ]);
                    }
                } catch (\Exception $e) {
                    return json_encode([
                        'success' => false,
                        'error' => 'Erro na requisição: ' . $e->getMessage(),
                        'postId' => $postId
                    ]);
                }
            });
    }
}
 