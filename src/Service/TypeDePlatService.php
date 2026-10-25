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
      'Ce type de plat'
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
      'Ce type de plat'
    );
    
    VerifService::verifEstRatache(
      $this->typeDePlatRepository->estRattacheAUnPlat($id),
      "Ce type de plat", " à un plat"
    );

    return $this->typeDePlatRepository->supprimerTypeDePlat($id);
  }
}