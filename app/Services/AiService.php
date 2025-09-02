<?php
namespace App\Services;

use Prism\Prism\Prism;
use Prism\Prism\Enums\Provider;

/**
 * Serviço para interação com modelos de IA via Prism
 */
class AiService
{
    /**
     * Agente de IA a ser utilizado
     */
    private $agent;
    
    /**
     * Modelo de chat a ser utilizado
     */
    private $chatModel;
    
    /**
     * Instrução de sistema que define o comportamento da IA
     */
    private $systemPrompt = '';
    
    /**
     * Prompt principal a ser enviado para a IA
     */
    private $prompt = '';
    
    /**
     * Ferramentas disponíveis para a IA
     */
    private $tools = [];
    
    /**
     * Provedor de IA (ex: OpenAI)
     */
    private $provider = '';
    
    /**
     * Modelo específico de IA a ser utilizado
     */
    private $model = '';
    
    /**
     * Chave de API para autenticação
     */
    private $apiKey = '';

    /**
     * Inicializa o serviço com configurações padrão ou personalizadas
     *
     * @param string|null $apiKey Chave de API (opcional, usa .env se não fornecida)
     * @param string|null $provider Provedor de IA (opcional, usa config se não fornecido)
     * @param string|null $model Modelo de IA (opcional, usa config se não fornecido)
     */
    public function __construct($apiKey = null, $provider = null, $model = null)
    {
        $this->apiKey = $apiKey ?? env('OPENAI_API_KEY');
        $this->provider = $provider ?? env('PRISM_PROVIDER', 'openai');
        $this->model = $model ?? env('PRISM_MODEL', 'gpt-4');
    }

    /**
     * Cria uma nova instância do serviço
     *
     * @param string|null $apiKey Chave de API (opcional)
     * @param string|null $provider Provedor de IA (opcional)
     * @param string|null $model Modelo de IA (opcional)
     * 
     * @return self Nova instância do serviço
     */
    public static function make($apiKey = null, $provider = null, $model = null): self
    {
        return new self($apiKey, $provider, $model);
    }

    /**
     * Define o prompt de sistema
     *
     * @param string $systemPrompt Instruções de sistema para definir comportamento da IA
     * 
     * @return self Instância atual para encadeamento
     */
    public function with(string $systemPrompt): self
    {
        $this->systemPrompt = $systemPrompt;
        return $this;
    }

    /**
     * Define o prompt principal
     *
     * @param string $prompt Texto do prompt principal a ser enviado para a IA
     * 
     * @return self Instância atual para encadeamento
     */
    public function prompt(string $prompt): self
    {
        $this->prompt = $prompt;
        return $this;
    }

    /**
     * Define ferramentas disponíveis para a IA
     *
     * @param array $tools Array de ferramentas/ações disponíveis
     * 
     * @return self Instância atual para encadeamento
     */
    public function tools(array $tools): self
    {
        $this->tools = $tools;
        return $this;
    }

    /**
     * Executa a requisição à IA e retorna a resposta processada
     *
     * @return string Texto da resposta da IA
     */
    public function execute()
    {
        $response = Prism::text()
            ->using($this->provider, $this->model)
            ->withSystemPrompt($this->systemPrompt)
            ->withPrompt($this->prompt)
            ->withTools($this->tools)
            ->asText();

        return $response->steps[0]->text;
    }
}
