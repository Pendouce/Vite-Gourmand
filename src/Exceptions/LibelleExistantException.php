<?php

namespace App\Exceptions;

use Exception;

class LibelleExistantException extends Exception
{
  public function __construct(string $nom) {

    parent::__construct($nom." existe deja");
  }
}