<?php

namespace App\Service;

use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Exceptions\RattacheActifException;
use Exception;

class VerifService {

  public static function verifNbPositif($nb): void
  {
    if (!is_numeric($nb) || $nb <= 0) {
      throw new Exception("Veuillez entrer un nombre superieur à 0");
    }
  }

  public static function verifTitre(string $titre): string
  {
    $titre = trim($titre);
    if ($titre === '') throw new Exception("Veuillez entrer un titre");
    return ucfirst($titre);
  }

  public static function verifDispoTitre(string $titre, object | false $element, ?int $elementId = null, ?int $id = null)
  {
    if($element && $elementId !== $id){
      throw new LibelleExistantException($titre);
    }
  }

  public static function verifExisteEnBase(mixed $element, string $nom): void
  {
    if (!$element){
      throw new IdInnexistantException($nom);
    }
  }

  public static function verifActif(bool $element, string $nom, string $type):void
  {
    if($element){
      throw new RattacheActifException($nom, $type);
    }
  }
}