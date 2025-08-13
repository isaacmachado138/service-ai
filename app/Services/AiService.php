<?php
namespace App\Services;

use Prism\Prism;

class AiService
{
    protected $prism;

    public function __construct()
    {
        $this->prism = new Prism([
            'provider' => config('prism.provider'),
            'openai_api_key' => env('OPENAI_API_KEY'),
            'model' => config('prism.model'),
        ]);
    }

    public function sendPrompt(string $prompt): string
    {
        $result = $this->prism->ask($prompt);
        return $result['response'] ?? '';
    }
}
