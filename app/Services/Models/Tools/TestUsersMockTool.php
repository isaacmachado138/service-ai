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
        \Log::info('[TestUsersMockTool] build() called', [
            'request_all' => request()->all(),
        ]);
        return (new Tool())
            ->as('get_users')
            ->for('Busca lista completa de usuários da API JSONPlaceholder')
            ->using(function (): string {
                \Log::info('[TestUsersMockTool] execute() called');
                try {
                    $response = Http::get('https://jsonplaceholder.typicode.com/users');
                    \Log::info('[TestUsersMockTool] HTTP request sent', [
                        'url' => 'https://jsonplaceholder.typicode.com/users',
                        'status' => $response->status(),
                        'successful' => $response->successful(),
                    ]);
                    if ($response->successful()) {
                        $users = $response->json();
                        \Log::info('[TestUsersMockTool] Users fetched', [
                            'count' => count($users),
                            'users' => $users,
                        ]);
                        return json_encode([
                            'success' => true,
                            'data' => $users,
                            'count' => count($users)
                        ]);
                    } else {
                        \Log::warning('[TestUsersMockTool] Failed to fetch users', [
                            'status' => $response->status(),
                            'body' => $response->body(),
                        ]);
                        return json_encode([
                            'success' => false,
                            'error' => 'Falha ao buscar usuários',
                            'status' => $response->status()
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('[TestUsersMockTool] Exception thrown', [
                        'error' => $e->getMessage(),
                    ]);
                    return json_encode([
                        'success' => false,
                        'error' => 'Erro na requisição: ' . $e->getMessage()
                    ]);
                }
            });
    }
}
 