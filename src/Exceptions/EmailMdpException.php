<?php

namespace App\Exceptions;

use Exception;

class EmailMdpException extends Exception
{
  protected $message = "Email ou mot de passe incorrect";

}