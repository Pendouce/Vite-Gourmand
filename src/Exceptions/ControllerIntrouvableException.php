<?php

namespace App\Exceptions;

use Exception;
    class ControllerIntrouvableException extends Exception
{
    public function __construct(string $controller = "")
    {
        parent::__construct("Le controller " . $controller . " n'existe pas");
    }
}