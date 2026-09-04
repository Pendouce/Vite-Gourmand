<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Repository\TypeDePrestaRepository;

class TypeDePrestaService
{
  private TypeDePrestaRepository $typeDePrestaRepository;

  public function __construct(TypeDePrestaRepository $typeDePrestaRepository)
  {
    $this->typeDePrestaRepository = $typeDePrestaRepository;
  }

  public function creerTypeDePresta(string $libelle, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    $this->typeDePrestaExistante($libelle);
    $data['libelle'] = $libelle;
    $this->typeDePrestaRepository->creerTypeDePresta($data);
  }

  public function afficherTypeDePresta()
  {
    return $this->typeDePrestaRepository->trouverTypeDePresta();
  }

  public function modifierTypeDePresta(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    $this->typeDePrestaExistante($data['libelle']);
    $this->typeDePrestaRepository->modifierTypeDePresta($data);
  }

  public function supprimerTypeDePresta(int $id, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
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