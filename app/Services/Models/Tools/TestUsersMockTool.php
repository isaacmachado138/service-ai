<?php

namespace App\Services\Models\Tools;

use App\Services\Models\Builder\Builder;
use Illuminate\Support\Facades\Http;
use Prism\Prism\Tool;

/**
 * Tool para buscar dados de usuários da API JSONPlaceholder
 */
class TestUsersMockTool extends Builder
{
    /**
     * Constrói e retorna uma Tool do Prism para buscar usuários
     *
     * @return Tool Ferramenta configurada para buscar usuários
     */
    public function build(): Tool
    {
        return (new Tool())
            ->as('get_users')
            ->for('Busca lista completa de usuários da API JSONPlaceholder')
            ->using(function (): string {
                try {
                    $response = Http::get('https://jsonplaceholder.typicode.com/users');
                    
                    if ($response->successful()) {
                        $users = $response->json();
                        return json_encode([
                            'success' => true,
                            'data' => $users,
                            'count' => count($users)
                        ]);
                    } else {
                        return json_encode([
                            'success' => false,
                            'error' => 'Falha ao buscar usuários',
                            'status' => $response->status()
                        ]);
                    }
                } catch (\Exception $e) {
                    return json_encode([
                        'success' => false,
                        'error' => 'Erro na requisição: ' . $e->getMessage()
                    ]);
                }
            });
    }
}
 