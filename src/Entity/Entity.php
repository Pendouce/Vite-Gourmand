<?php

namespace App\Entity;

use App\Exceptions\MethodeIntrouvableException;
use DateTimeImmutable;

class Entity{

  public static function creerEtHydrate(array $data): static
  {
    $entity = new static();
    $entity->hydrate($data);

    return $entity;
  }

  public function hydrate(array $data): void
  {
    foreach($data as $key => $value){

      $methode = str_replace("_", " ",$key);
      $methode = ucwords($methode);
      $methode = str_replace(" ", "",$methode);
      $methode = "set".$methode;

      if(method_exists($this, $methode)){
        if(str_contains($key, "date")){
          $value = $value !== null ? new DateTimeImmutable($value) : null;
        }
        $this->$methode($value);
      }else{
        throw new MethodeIntrouvableException("La methode ".$methode." n'existe pas sur ". static::class);
      }
    }
  }

  // Transforme un objet en tableau associatif cle => valeur
  public function deshydrate(): array
  {
    $data = get_object_vars($this);
    $dataConverties = [];

    foreach($data as $key => $value){
      // Je recherche chaques majuscules qui n'est pas en debut de chaine et ajoute un _ avant
      // et les passe en minuscule
      $convertionKey = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
      $dataConverties[$convertionKey] = $value;
    }
    return $dataConverties;
  }
}