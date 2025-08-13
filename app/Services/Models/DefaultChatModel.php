<?php
namespace App\Services\Models;

class DefaultChatModel implements ChatModelInterface
{
    public function buildPrompt(array $input): string
    {
        return $input['prompt'];
    }
}
