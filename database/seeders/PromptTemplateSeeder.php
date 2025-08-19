<?php

namespace Database\Seeders;

use App\Models\PromptTemplate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeder para popular a tabela de templates de prompt com valores iniciais
 */

class PromptTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    /**
     * Executa o seeder criando templates padrão no banco de dados
     * Cada template contém um código único, nome, system_prompt e user_prompt
     */
    public function run(): void
    {
        $templates = [
            [
                'code' => 'default',
                'name' => 'Default Template',
                'system_prompt' => 'Você é um assistente útil que fornece informações precisas e baseadas em dados reais e confiáveis.',
                'user_prompt' => '{prompt}',
                'description' => 'Template padrão para prompts gerais enviados.',
            ],
            [
                'code' => 'summary_report_occupation',
                'name' => 'Resumo de relatório de ocupação',
                'system_prompt' => 'Você é supervisor de uma empresa de hotelaria e quer analisar os dados de um relatório de ocupação, deve informar os principais pontos e insights.',
                'user_prompt' => 'Resuma os seguintes dados em cerca de 100 palavras:\n\n{text}',
                'description' => 'Template para resumo de relatório',
            ],
            [
                'code' => 'pickup_compare',
                'name' => 'Comparador de Pickup',
                'system_prompt' => 'Você é um especialista em hotelaria e Revenue Management. Compare os dados no período {start} a {end} infornendo informações precisas para o hotel {hotel_name}. Leve em conta as alterações e verifique se houve algum fator externo do ambiente do hotel para que isso acontecesse.',
                'user_prompt' => 'Compare as seguintes opções de pickup:\n\n{text}',
                'description' => 'Template para comparação de pickup',
            ],
        ];

        // Insert all templates
        foreach ($templates as $template) {
            PromptTemplate::updateOrCreate(
                ['code' => $template['code']],
                $template
            );
        }
    }
}
