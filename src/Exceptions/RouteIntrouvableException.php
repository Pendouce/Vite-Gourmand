<?php

namespace App\Exceptions;

use Exception;

  class RouteIntrouvableException extends Exception
{
  // $path = "" gere le cas ou j'utilise cette exception sans donner de path
    public function __construct(string $path = "")
    {
      // Appelle le message de la classe parente et lui donne le message
        parent::__construct("La route n'existe pas : " . $path);
    }
}
