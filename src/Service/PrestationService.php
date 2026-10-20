<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Repository\PrestationRepository;
use App\Repository\TypeDePrestaRepository;
use Exception;

class PrestationService
{
  private PrestationRepository $prestationRepository;
  private TypeDePrestaRepository $typePrestaRepository;

  public function __construct(PrestationRepository $prestationRepository, TypeDePrestaRepository $typePrestaRepository)
  {
    $this->prestationRepository = $prestationRepository;
    $this->typePrestaRepository = $typePrestaRepository;
  }

  public function creerPrestation(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    
    if(empty($data['nom_presta'])){
      throw new Exception("Veuillez entrer un titre");
    }
    $this->existeEnBase($data['nom_presta']);

    if(empty($data['img_presta'])){
      throw new Exception("Veuillez selectioner une image");
    }
    
    if(empty($data['description_presta'])){
      throw new Exception("Veuillez entrer une description");
    }

    $contenuPresta = [];

    // Je boucle sur le contenue de mes prestations et ne garde que ceux qui sont remplis
    foreach($data['contenu_presta'] as $contenu){
      $input = trim($contenu);
      if ($input !== "") {
        $contenuPresta[] = $input;
      }
    }

    // contenu_presta est un tableau PHP
    // json_encode : tableau PHP -> string JSON

    if(empty($contenuPresta)){
      throw new Exception("Veuillez entrer au moins un contenu de prestation");
    }
    $data['contenu_presta'] = json_encode($contenuPresta);

    if(empty($data['prix_presta'])){
      throw new Exception("Veuillez fixer un prix");
    }
    VerifService::verifNbPositif($data['prix_presta']);

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

  public function afficherPrestationParType()
  {
    $prestations = $this->prestationRepository->trouverPrestationParType();
    $prestaParType = [];

    foreach ($prestations as $presta) {
      $prestaParType[$presta->getLibelle()][] = $presta;
    }

    return $prestaParType;
  }

  public function modifierPrestation(int $prestaId, array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    if(!empty($data['nom_prestation'])){
      $this->existeEnBase($data['nom_prestation'], $prestaId);
    }

    $prestaActuels = $this->afficherPrestationParId($prestaId);
    $donneesActuels = $prestaActuels->deshydrate();

    $data = array_filter($data, fn ($value) => $value !== null);
    $nouvellesDonnees = array_merge($donneesActuels, $data);

    $nouvellesDonnees['prestation_id'] = $prestaId;
    unset($nouvellesDonnees['libelle']);

    // j'encode pour envoyer a la bdd un format JSON
    if(!empty($nouvellesDonnees['contenu_presta'])){
      $nouvellesDonnees['contenu_presta'] = json_encode($nouvellesDonnees['contenu_presta']);
    }

    $this->prestationRepository->modifierPrestation($nouvellesDonnees);
  }

  public function modifierStatusPrestation(int $prestaId, int $status, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    return $this->prestationRepository->modifierStatusPrestation($prestaId, $status);
  }

  public function supprimerPrestation(int $prestaId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    if(!$this->afficherPrestationParId($prestaId)){
      throw new IdInnexistantException('Prestation');
    }

    return $this->prestationRepository->supprimerPrestation($prestaId);
  }

  private function existeEnBase(string $nom, ?int $id = null)
  {
    $presta = $this->prestationRepository->trouverPrestationParNom($nom);
    if($presta && $presta->getPrestationId() !== $id)
    {
      throw new LibelleExistantException($nom);
    }
  }
}