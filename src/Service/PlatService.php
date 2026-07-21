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
      $this->allergeneRepository->ajouterAllergeneAuxPlat($platId, $allergeneId);
    }
  }

  public function afficherPlats()
  {
    // Je recupere tous les plats
    $plats = $this->platRepository->trouverPlat();

    // Je retourne plat qui contient maintenant ses allergenes
     return $this->ajouterAllergenes($plats);
  }

  public function afficherParId(int $platId)
  {
    // Je recupere tous les plats
    $plat = $this->platRepository->trouverPlatParId($platId);
    $allergenes = $this->allergeneRepository->trouverAllergenesDuPlat($platId);
    $plat->setAllergenes($allergenes);

    if ($plat === false) {
      return false;
    }
    
     return $plat;
  }

  public function afficherPlatsParType(int $typeId)
  {
    $plats = $this->platRepository->trouverPlatParType($typeId);

    return $this->ajouterAllergenes($plats);
  }

  public function modifierAllergenesDuPlat(int $platId, array $allergeneId)
  {
    $nvxAllergenes = $allergeneId;
    // Je recupere les allergene du plat
    $anciensAllergenes = $this->allergeneRepository->trouverAllergenesDuPlat($platId);
    //  Je les transformes en tableau d'id
    $anciensAllergenesId = array_map(fn($allergene) => $allergene->getAllergeneId(), $anciensAllergenes);

    // Je compare les anciens allergenes au nouveaux
    // et stocke ceux qui ne sont pas dans le nouveau
    $aSupprimer = array_diff($anciensAllergenesId, $nvxAllergenes);
    // Je compare les nouveaux allergenes au anciens
    // et stocke ceux qui ne sont pas dans l'ancien
    $aAjouter = array_diff($nvxAllergenes, $anciensAllergenesId);

    foreach($aSupprimer as $supprime){
      $this->allergeneRepository->supprimerAllergeneDuPlat($platId, $supprime);
    }
    foreach($aAjouter as $ajouter){
      $this->allergeneRepository->ajouterAllergeneAuxPlat($platId, $ajouter);
    }
  }

  public function modifierPlat(int $platId, array $data)
  {
    if(key_exists('titre', $data)){
      $this->verifNom($data['titre']);
    }
    $data['plat_id'] = $platId;
    $this->platRepository->modifierPlat($data);
  }

  private function verifNom(string $nom)
  {
    if($this->platRepository->trouverPlatByNom($nom)){
      throw new LibelleExistantException($nom);
    }
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