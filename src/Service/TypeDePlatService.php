<?php

namespace App\Service;

use App\Exceptions\LibelleExistantException;
use App\Repository\TypeDePlatRepository;
use Exception;

class TypeDePlatService
{
  private TypeDePlatRepository $typeDePlatRepository;

  public function __construct(TypeDePlatRepository $typeDePlatRepository) {
    $this->typeDePlatRepository = $typeDePlatRepository;
  }

  public function creerTypeDePlat(string $libelle)
  {
    $this->existeEnBase($libelle);

    $data =['libelle' => $libelle];

    return $this->typeDePlatRepository->creerTypeDePlat($data);
  }

  public function afficheTypeDePlat()
  {
    return $this->typeDePlatRepository->trouverTypeDePlat();
  }

  public function modifieTypeDePlat(string $libelle, int $id)
  {
    $this->existeEnBase($libelle);

    $this->existePasEnBase($id);

    $data['libelle'] = $libelle;
    $data['type_id'] = $id;

    return $this->typeDePlatRepository->modifierTypeDePlat($data);
  }

  public function supprimeTypeDePlat(int $id)
  {
    $this->existePasEnBase($id);
    if($this->typeDePlatRepository->estRattacheAUnPlatActif($id)){
      throw new Exception('Impossible de supprimer ce type de plat, ce type est encore utilisé par un plat actif');
    }

    return $this->typeDePlatRepository->supprimerTypeDePlat($id);
  }

  //////////

  private function existeEnBase(string $libelle)
  {
    if($this->typeDePlatRepository->trouverTypeDePlatByNom($libelle)){
      throw new LibelleExistantException($libelle);
    }
  }

  private function existePasEnBase(int $id)
  {
    if(!$this->typeDePlatRepository->trouverTypeDePlatById($id)){
      throw new Exception('Type de plat introuvable');
    }
  }
}