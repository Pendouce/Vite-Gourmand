<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\PrestationRepository;
use App\Repository\TypeDePrestaRepository;

class PrestationService
{
  private PrestationRepository $prestationRepository;
  private TypeDePrestaRepository $typePrestaRepository;

  public function __construct(PrestationRepository $prestationRepository, TypeDePrestaRepository $typePrestaRepository)
  {
    $this->prestationRepository = $prestationRepository;
    $this->typePrestaRepository = $typePrestaRepository;
  }

  public function creerPrestation(array $data)
  {
    $this->existeEnBase($data['nom_presta']);

    return $this->prestationRepository->creerPrestation($data);
  }

  public function afficherPrestation()
  {
    return $this->prestationRepository->trouverPrestation();
  }

  public function afficherPrestationParId(int $id)
  {
    return $this->prestationRepository->trouverPrestationParId($id);
  }



  private function existeEnBase(string $nom)
  {
    if($this->prestationRepository->trouverPrestationParNom($nom))
      {
        throw new LibelleExistantException($nom);
      }
  }
}