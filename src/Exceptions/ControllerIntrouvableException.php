<?php

namespace App\Exceptions;

use Exception;

class ControllerIntrouvableException extends Exception
{
  protected $message = "La classe n'existe pas";
}