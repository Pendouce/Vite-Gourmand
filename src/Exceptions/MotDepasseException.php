<?php

namespace App\Exceptions;

use Exception;

class MotDepasseException extends Exception
{
  protected $message = "Mot de passe incorrect";
}