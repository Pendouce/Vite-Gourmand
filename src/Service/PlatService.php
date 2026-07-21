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

  public function afficherPlats()
  {
    // Je recupere tous les plats
    $plats = $this->platRepository->trouverPlat();

    // Je retourne plat qui contient maintenant ses allergenes
     return $this->ajouterAllergenes($plats);
  }

  public function afficherPlatsParType(int $typeId)
  {
    $plats = $this->platRepository->trouverPlatParType($typeId);

    return $this->ajouterAllergenes($plats);
  }

  private function ajouterAllergenes(array $plats)
  {
        // Je boucle pour mettre dans ma propriete allergene de l'entity plats les allergenes liés a leurr plats
      foreach($plats as $plat){
        $platId = $plat->getPlatId();
        $allergene = $this->allergeneRepository->trouverAllergenesDuPlat($platId);
        $plat->setAllergenes($allergene);

      return $plats;
    }
  }


}