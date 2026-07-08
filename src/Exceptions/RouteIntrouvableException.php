<?php

namespace App\Exceptions;

use Exception;

class RouteIntrouvableException extends Exception
{
  protected $message = "La route n'existe pas";
}