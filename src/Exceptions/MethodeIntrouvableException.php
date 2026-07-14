<?php

namespace App\Exceptions;

use Exception;

    class MethodeIntrouvableException extends Exception
{
    public function __construct(string $controllerPath = "", string $action = "")
    {
        parent::__construct("La methode ".$action. " n'existe pas dans le controller " . $controllerPath);
    }
}