<?php
namespace App\Services\Agents;

use Prism\Prism;

class OpenAiAgent implements AgentInterface
{
    protected $prism;

    public function __construct(array $config)
    {
        $this->prism = new Prism($config);
    }

    public function ask(string $prompt, array $options = []): string
    {
        $result = $this->prism->ask($prompt, $options);
        return $result['response'] ?? '';
    }
}
