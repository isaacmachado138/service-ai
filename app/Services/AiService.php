<?php
namespace App\Services;

class AiService
{
    private array $instances = [];
    private $agent;
    private $chatModel;
    private $systemPrompt = '';
    private $prompt = '';
    private $tools = [];
    private $provider = '';
    private $model = '';
    private $apiKey = '';

    public function __construct($apiKey = null, $provider = null, $model = null)
    {
        $this->apiKey = $apiKey;
        $this->provider = $provider;
        $this->model = $model;
    }

    public static function make($apiKey = null, $provider = null, $model = null): self
    {
        return new self($apiKey, $provider, $model);
    }

    public function with(string $systemPrompt): self
    {
        $this->systemPrompt = $systemPrompt;
        return $this;
    }

    public function prompt(string $prompt): self
    {
        $this->prompt = $prompt;
        return $this;
    }

    public function tools(array $tools): self
    {
        $this->tools = $tools;
        return $this;
    }

    // Exemplo de chamada:
    // AiService::make(getEnv('apikey'), GTP,)
    //     ->with('...')
    //     ->prompt('')
    //     ->tools([
    //         new ActionPickupBuild::class,
    //     ])
    //     ->execute();

    public function execute()
    {
        // Exemplo genérico:
        $response = Prism::text()
            ->using($this->provider ?? Provider::Anthropic, $this->model ?? 'claude-3-7-sonnet-latest')
            ->withSystemPrompt($this->systemPrompt)
            ->withPrompt($this->prompt)
            ->withTools($this->tools)
            ->asText();
        return $response;
    }
}
