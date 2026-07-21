<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\PlatRepository;
use App\Repository\AllergeneRepository;

class PlatService
{
  private PlatRepository $platRepository;
  private AllergeneRepository $allergeneRepository;

  public function __construct(PlatRepository $platRepository, AllergeneRepository $AllergeneRepository)
  {
    $this->platRepository = $platRepository;
    $this->allergeneRepository = $AllergeneRepository;
  }

  public function creerPlat(array $data)
  {
    if($this->platRepository->trouverPlatByNom($data['titre'])){
      throw new LibelleExistantException($data['titre']);
    }
    
    return $this->platRepository->creerPlat($data);
  }

  public function ajouterAllergeneAuplat(int $platId, array $allergenesId)
  {
    foreach($allergenesId as $allergeneId){
      $this->platRepository->ajouterAllergeneAuxPlat($platId, $allergeneId);
    }
  }

  public function afficherPlat()
  {
    $this->platRepository->trouverPlat();
    return $this->platRepository->trouverPlat();
  }
}