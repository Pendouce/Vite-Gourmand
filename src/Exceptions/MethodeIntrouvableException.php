<?php

namespace App\Exceptions;

use Exception;

class MethodeIntrouvableException extends Exception
{
  protected $message = "La methode n'existe pas";
}