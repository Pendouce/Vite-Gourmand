<?php

namespace App\Exceptions;

use Exception;

class AccesRefuseException extends Exception
{
  protected $message = "Acces refusé";
}