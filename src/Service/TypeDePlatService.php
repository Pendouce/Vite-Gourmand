<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Exceptions\RattacheActifException;
use App\Repository\TypeDePlatRepository;
use Exception;

class TypeDePlatService
{
  private TypeDePlatRepository $typeDePlatRepository;

  public function __construct(TypeDePlatRepository $typeDePlatRepository) {
    $this->typeDePlatRepository = $typeDePlatRepository;
  }

  public function creerTypeDePlat(string $libelle, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $libelle = VerifService::verifTitre($libelle);
  
    VerifService::verifDispoTitre($libelle, 
    $this->typeDePlatRepository->trouverTypeDePlatByNom($libelle));
    //$this->existeEnBase($libelle);


    $data =['libelle' => $libelle];

    return $this->typeDePlatRepository->creerTypeDePlat($data);
  }


  public function afficheTypeDePlat()
  {
    return $this->typeDePlatRepository->trouverTypeDePlat();
  }

  public function modifieTypeDePlat(string $libelle, int $id, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $libelle = VerifService::verifTitre($libelle);
    $typeDePlat = $this->typeDePlatRepository->trouverTypeDePlatByNom($libelle);

    VerifService::verifDispoTitre(
      $libelle,
      $typeDePlat,
      $typeDePlat ? $typeDePlat->getTypeId() : null,
      $id
    );

    VerifService::verifExisteEnBase(
      $this->typeDePlatRepository->trouverTypeDePlatById($id),
      'Type de plat'
    );

    //$this->existeEnBase($libelle);
    //$this->existePasEnBase($id);

    $data['libelle'] = $libelle;
    $data['type_id'] = $id;

    return $this->typeDePlatRepository->modifierTypeDePlat($data);
  }

  public function supprimeTypeDePlat(int $id, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    VerifService::verifExisteEnBase(
      $this->typeDePlatRepository->trouverTypeDePlatById($id),
      'Type de plat'
    );
    
    VerifService::verifActif(
      $this->typeDePlatRepository->estRattacheAUnPlat($id),
      "plat", "un plat"
    );

    //$this->existePasEnBase($id);


    /* if($this->typeDePlatRepository->estRattacheAUnPlatActif($id)){
      throw new RattacheActifException("plat", "un plat actif");
    } */

    return $this->typeDePlatRepository->supprimerTypeDePlat($id);
  }

  /* private function existeEnBase(string $libelle)
  {
    if($this->typeDePlatRepository->trouverTypeDePlatByNom($libelle)){
      throw new LibelleExistantException($libelle);
    }
  } */

  /* private function existePasEnBase(int $id)
  {
    if(!$this->typeDePlatRepository->trouverTypeDePlatById($id)){
      throw new IdInnexistantException('Type de plat');
    }
  } */
}