<?php
namespace App\Services;

use App\Services\Agents\AgentInterface;
use App\Services\Agents\OpenAiAgent;
use App\Services\Models\ChatModelInterface;
use App\Services\Models\DefaultChatModel;

class AiService
{
    protected $agent;
    protected $chatModel;

    public function __construct(AgentInterface $agent, ChatModelInterface $chatModel)
    {
        $this->agent = $agent;
        $this->chatModel = $chatModel;
    }

    public static function makeFromConfig(): self
    {
        $provider = config('prism.provider', 'openai');
        $model = config('prism.model', 'gpt-4');

        // Instanciar agente conforme provider
        switch ($provider) {
            case 'openai':
            default:
                $agent = new OpenAiAgent([
                    'provider' => $provider,
                    'openai_api_key' => env('OPENAI_API_KEY'),
                    'model' => $model,
                ]);
        }

        // Instanciar modelo de chat (pode ser dinâmico)
        $chatModel = new DefaultChatModel();

        return new self($agent, $chatModel);
    }

    public function sendPrompt(array $input): string
    {
        $prompt = $this->chatModel->buildPrompt($input);
        return $this->agent->ask($prompt, $input['options'] ?? []);
    }
}
