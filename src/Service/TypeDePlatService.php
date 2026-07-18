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

    if(!$this->typeDePlatRepository->trouverTypeDePlatById($id)){
      throw new Exception('Type de plat introuvable');
    }

    $data['libelle'] = $libelle;
    $data['type_id'] = $id;

    return $this->typeDePlatRepository->modifierTypeDePlat($data);
  }

  private function existeEnBase(string $libelle)
  {
    if($this->typeDePlatRepository->trouverTypeDePlatByNom($libelle)){
      throw new LibelleExistantException($libelle);
    }
  }
}