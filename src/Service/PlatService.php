<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\PlatRepository;

class PlatService
{
  private PlatRepository $platRepository;

  public function __construct(PlatRepository $platRepository)
  {
    $this->platRepository = $platRepository;
  }

  public function creerPlat(array $data)
  {
    if($this->platRepository->trouverPlatByNom($data['titre'])){
      throw new LibelleExistantException($data['titre']);
    }
    
    return $this->platRepository->creerPlat($data);
  }

  public function afficherPlat()
  {
    return $this->platRepository->afficherPlat();
  }
}