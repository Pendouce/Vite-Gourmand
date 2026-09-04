<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Repository\InformationVgRepository;

class InfoVgService
{
  private InformationVgRepository $infoRepository;

  public function __construct(InformationVgRepository $infoRepository) {
    $this->infoRepository = $infoRepository;
  }

  public function afficherInfosVg()
  {
    return $this->infoRepository->trouverInfosVg();
  }

  public function modifierInfosVg(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $infos = $this->infoRepository->trouverInfosVg();

    $anciennesDonnees = $infos->deshydrate();
    $data = array_filter($data, fn ($value) => $value !== null);

    $nouvellesDonnees = array_merge($anciennesDonnees, $data);
    unset($nouvellesDonnees['info_id']);

    $this->infoRepository->modifierInfosVg($nouvellesDonnees);
  }

  public function afficherImagesSite()
  {
    return $this->infoRepository->trouverImagesSite();
  }

  public function modifierImageSite(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    
    $image = $this->infoRepository->trouverImageSiteParId($data['id']);

    $anciennesDonnees = $image->deshydrate();
    $data = array_filter($data, fn ($value) => $value !== null);

    $nouvellesDonnees = array_merge($anciennesDonnees, $data);

    $this->infoRepository->modifierImagesSite($nouvellesDonnees);
  }
}