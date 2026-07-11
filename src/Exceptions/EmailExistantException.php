<?php

namespace App\Exceptions;

use Exception;

class EmailExistantException extends Exception
{
  protected $message = "Compte deja existant";

}