<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\TypeDePrestaRepository;

class TypeDePrestaService
{
  private TypeDePrestaRepository $typeDePrestaRepository;

  public function __construct(TypeDePrestaRepository $typeDePrestaRepository)
  {
    $this->typeDePrestaRepository = $typeDePrestaRepository;
  }

  public function creerTypeDePresta(string $libelle)
  {
    $this->typeDePrestaExistante($libelle);
    $data['libelle'] = $libelle;
    $this->typeDePrestaRepository->creerTypeDePresta($data);
  }

  private function typeDePrestaExistante(string $libelle)
  {
    if($this->typeDePrestaRepository->trouverTypeDePrestaParNom($libelle)){
      throw new LibelleExistantException($libelle);
    }
  }
}