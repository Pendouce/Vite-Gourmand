<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\LibelleExistantException;
use App\Repository\AllergeneRepository;
use App\Repository\PlatRepository;

class PlatService
{
  private PlatRepository $platRepository;
  private AllergeneRepository $allergeneRepository;

  public function __construct(PlatRepository $platRepository, AllergeneRepository $AllergeneRepository)
  {
    $this->platRepository = $platRepository;
    $this->allergeneRepository = $AllergeneRepository;
  }

  public function creerPlat(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

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

  public function afficherPlats(int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    // Je recupere tous les plats
    $plats = $this->platRepository->trouverPlat();

    // Je retourne plat qui contient maintenant ses allergenes
     return $this->ajouterAllergenes($plats);
  }

  public function afficherParId(int $platId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    $plat = $this->platRepository->trouverPlatParId($platId);
    
    if ($plat === false) {
      return false;
    }

    $allergenes = $this->allergeneRepository->trouverAllergenesDuPlat($platId);
    $plat->setAllergenes($allergenes);

     return $plat;
  }

  public function afficherPlatsParType(int $typeId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $plats = $this->platRepository->trouverPlatParType($typeId);

    return $this->ajouterAllergenes($plats);
  }

  public function modifierAllergenesDuPlat(int $platId, array $allergeneId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

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

  public function modifierPlat(int $platId, array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    if(!empty($data['titre'])){
      $this->verifNom($data['titre']);
    }

    $platActuel = $this->platRepository->trouverPlatParId($platId);
    $donneesActuel = $platActuel->deshydrate();

    $data = array_filter($data, fn($value) => $value !== null);
    $nouvellesDonnees = array_merge($donneesActuel, $data);
    $nouvellesDonnees['plat_id'] = $platId;
    unset($nouvellesDonnees['allergenes']);
    unset($nouvellesDonnees['libelle']);

    $this->platRepository->modifierPlat($nouvellesDonnees);
  }

  public function modifierStatusPlat(int $platId, int $status, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->platRepository->modifierStatusPlat($platId, $status);
  }

  public function modifierStockPlat(int $platId, int $stock, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->platRepository->modifierStockPlat($platId, $stock);
  }

  public function supprimerPlat(int $platId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->allergeneRepository->supprimerPlat($platId);
    $this->platRepository->supprimerPlat($platId);
  }

  private function verifNom(string $nom)
  {
    if($this->platRepository->trouverPlatByNom($nom)){
      throw new LibelleExistantException($nom);
    }
  }

  public function ajouterAllergenes(array $plats)
  {
      // Je boucle pour mettre dans la propriete allergene de l'entity plats les allergenes liés a leurs plats
    foreach($plats as $plat){
      $platId = $plat->getPlatId();
      $allergene = $this->allergeneRepository->trouverAllergenesDuPlat($platId);
      $plat->setAllergenes($allergene);
    }
    return $plats;
  }


}