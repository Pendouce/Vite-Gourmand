<?php

namespace App\Exceptions;

use Exception;

class EmailException extends Exception
{
  protected $message = "Veuillez entrez un email valide";
}