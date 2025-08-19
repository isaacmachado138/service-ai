<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Modelo para gerenciar templates de prompt com system_prompt e user_prompt
 */
class PromptTemplate extends Model
{
    /**
     * A chave primária é 'code' em vez do padrão 'id'
     */
    protected $primaryKey = 'code';
    
    /**
     * Indica que a chave primária não é um incremento automático
     */
    public $incrementing = false;
    
    /**
     * Define o tipo da chave primária como string
     */
    protected $keyType = 'string';
    
    /**
     * Campos permitidos para atribuição em massa
     */
    protected $fillable = [
        'code',
        'name',
        'system_prompt',
        'user_prompt',
        'description',
        'is_active'
    ];

    /**
     * Conversões automáticas de tipo para campos específicos
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Inicializa o modelo e configura eventos
     * Gera automaticamente um código único baseado no nome se não fornecido
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            // Se o código não foi definido, gere automaticamente
            if (!$model->code) {
                // Gerar um código baseado no nome + string aleatória
                $model->code = Str::snake(Str::limit($model->name, 30, ''));
                
                // Verifica se já existe
                $count = 0;
                $originalCode = $model->code;
                while (self::where('code', $model->code)->exists()) {
                    $count++;
                    $model->code = $originalCode . '_' . $count;
                }
            }
        });
    }

    /**
     * Escopo de consulta para retornar apenas templates ativos
     *
     * @param \Illuminate\Database\Eloquent\Builder $query Query builder
     * @return \Illuminate\Database\Eloquent\Builder Query modificada
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Substitui as variáveis no template de prompt com os valores fornecidos
     * 
     * @param array $variables Array associativo com as variáveis a serem substituídas
     * @return string Prompt formatado com as variáveis substituídas
     */
    public function formatUserPrompt(array $variables = []): string
    {
        $prompt = $this->user_prompt;
        
        // Substitui variáveis no template (formato: {variable_name})
        foreach ($variables as $key => $value) {
            $prompt = str_replace("{{$key}}", $value, $prompt);
        }
        
        return $prompt;
    }
}
