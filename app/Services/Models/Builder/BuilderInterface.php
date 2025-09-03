<?php

namespace App\Services\Models\Builder;

use Prism\Prism\Tool;

interface BuilderInterface
{
    /**
     * Constrói e retorna uma Tool do Prism
     *
     * @return Tool
     */
    public function build(): Tool;
}