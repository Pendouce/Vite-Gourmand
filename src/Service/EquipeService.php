<?php

namespace App\Service;

use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Repository\EquipeRepository;

class EquipeService
{
  private EquipeRepository $equipeRepository;

  public function __construct(EquipeRepository $equipeRepository) {
    $this->equipeRepository = $equipeRepository;
  }

  public function creerMembre(array $data)
  {
    $this->verifSiExisteDeja($data['nom'], $data['prenom']);
    $data['actif'] = 1;
  
    return $this->equipeRepository->creerMembre($data);
  }

  public function afficherMembres(int $role)
  {
    if($role === ROLE_ADMIN){
      return $this->equipeRepository->trouverTousLesMembres();
    }else{
      return $this->equipeRepository->trouverMembresActif();
    }
  }

  public function modifierMembre(array $data)
  {
    $this->verifSiIdExisteDeja($data['membre_id']);
    if(!empty($data['nom']) && !empty($data['prenom'])){
      $this->verifSiExisteDeja($data['nom'], $data['prenom']);
    }

    $membre = $this->equipeRepository->trouverMembreParId($data['membre_id']);
    $anciennesDonnees = $membre->deshydrate();

    $data = array_filter($data, fn($value) => $value !== null);

    $nouvellesDonnees = array_merge($anciennesDonnees, $data);

    unset($nouvellesDonnees['actif']);

    $this->equipeRepository->modifierMembre($nouvellesDonnees);
  }

  public function modifierStatutMembre(int $membreId, int $statut)
  {
    $this->verifSiIdExisteDeja($membreId);

    return $this->equipeRepository->modifierStatutMembre($membreId, $statut);
  }

  public function supprimerMembre(int $membreId)
  {
    $this->verifSiIdExisteDeja($membreId);

    return $this->equipeRepository->supprimerMembre($membreId);
  }

  private function verifSiExisteDeja(string $nom, string $prenom)
  {
    if($this->equipeRepository->trouverMembreParNom($nom, $prenom)){
      throw new LibelleExistantException($prenom);
    }
  }

  private function verifSiIdExisteDeja(int $id)
  {
    $membre = $this->equipeRepository->trouverMembreParId($id);
    if(!$membre){
      throw new IdInnexistantException($membre->getPrenom());
    }
  }
}