<?php

namespace App\Exceptions;

use Exception;

class RattacheActifException extends Exception
{
  public function __construct(string $typeNom, string $typeActif) {

    parent::__construct("Impossible de supprimer ce type de " .$typeNom.", ce type est encore utilisé par ".$typeActif);
  }
}