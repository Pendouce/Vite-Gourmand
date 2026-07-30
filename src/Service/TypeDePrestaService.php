<?php

namespace App\Service;

use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Exceptions\RattacheActifException;
use App\Repository\TypeDePrestaRepository;

class TypeDePrestaService
{
  private TypeDePrestaRepository $typeDePrestaRepository;

  public function __construct(TypeDePrestaRepository $typeDePrestaRepository)
  {
    $this->typeDePrestaRepository = $typeDePrestaRepository;
  }

  public function creerTypeDePresta(string $libelle)
  {
    $this->typeDePrestaExistante($libelle);
    $data['libelle'] = $libelle;
    $this->typeDePrestaRepository->creerTypeDePresta($data);
  }

  public function afficherTypeDePresta()
  {
    return $this->typeDePrestaRepository->trouverTypeDePresta();
  }

  public function modifierTypeDePresta(array $data)
  {
    $this->typeDePrestaExistante($data['libelle']);
    $this->typeDePrestaRepository->modifierTypeDePresta($data);
  }

  public function supprimerTypeDePresta(int $id)
  {
    if (!$this->typeDePrestaRepository->trouverTypeDePrestaParId($id)){
      throw new IdInnexistantException('Type de prestaion');
    }

   /*  if($this->typeDePrestaRepository->estRattacheAUnTypeDePrestaActif($id)){
      throw new RattacheActifException('prestation', 'une prestation active');
    } */

    return $this->typeDePrestaRepository->supprimertypeDePresta($id);
  }


  private function typeDePrestaExistante(string $libelle)
  {
    if($this->typeDePrestaRepository->trouverTypeDePrestaParNom($libelle)){
      throw new LibelleExistantException($libelle);
    }
  }
}