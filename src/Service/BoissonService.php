<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\BoissonRepository;

class BoissonService
{
  private BoissonRepository $boissonRepository;

  public function __construct(BoissonRepository $boissonRepository) {
    $this->boissonRepository = $boissonRepository;
  }

  public function creerBoisson(array $data)
  {
    if($this->boissonRepository->trouverBoissonParNom($data['nom_boisson'])){
      throw new LibelleExistantException($data['nom_boisson']);
    }

    return $this->boissonRepository->creerBoisson($data);
  }

  public function afficherBoisson()
  {
    return $this->boissonRepository->trouverBoisson();
  }

  public function afficherBoissonParId(int $id)
  {
    return $this->boissonRepository->trouverBoissonParId($id);
  }
}