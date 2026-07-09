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
          $value = new DateTimeImmutable($value);
        }
        $this->$methode($value);
      }else{
        throw new MethodeIntrouvableException("La methode ".$methode." n'existe pas sur ". static::class);
      }
    }
  }
}