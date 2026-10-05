<?php

namespace App\Service;

use Exception;

class VerifService {

  public static function verifNbPositif($nb)
  {
    if (!is_numeric($nb) || $nb <= 0) {
      throw new Exception("Veuillez entrer un nombre superieur à 0");
    }
  }

}