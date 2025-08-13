<?php
namespace App\Services\Models;

interface ChatModelInterface
{
    public function buildPrompt(array $input): string;
}
