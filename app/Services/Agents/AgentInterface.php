<?php
namespace App\Services\Agents;

interface AgentInterface
{
    public function ask(string $prompt, array $options = []): string;
}
