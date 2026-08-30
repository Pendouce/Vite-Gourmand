<?php

namespace App\Service;

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
}