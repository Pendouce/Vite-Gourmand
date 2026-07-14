<?php

namespace App\Exceptions;

use Exception;

    class PageInexistanteException extends Exception
{
    public function __construct(string $filepath )
    {
        parent::__construct("La page ".$filepath. " n'existe pas");
    }
}