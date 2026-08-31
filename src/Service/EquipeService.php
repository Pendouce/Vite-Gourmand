<?php

namespace App\Service;

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

  private function verifSiExisteDeja(string $nom, string $prenom)
  {
    if($this->equipeRepository->trouverMembreParNom($nom, $prenom)){
      throw new LibelleExistantException($prenom);
    }
  }
}