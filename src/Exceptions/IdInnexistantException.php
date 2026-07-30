<?php

namespace App\Exceptions;

use Exception;

class IdInnexistantException extends Exception
{
  public function __construct(string $nom) {

    parent::__construct($nom." introuvable");
  }
}