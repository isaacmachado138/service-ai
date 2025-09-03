<?php

namespace App\Services\Models\Builder;

use Prism\Prism\Tool;

abstract class Builder implements BuilderInterface
{
    /**
     * Constrói e retorna uma Tool do Prism
     * Deve ser implementado pelas classes filhas
     *
     * @return Tool
     */
    abstract public function build(): Tool;
}