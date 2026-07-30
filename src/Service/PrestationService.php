<?php

namespace App\Service;

use App\Repository\PrestationRepository;
use App\Repository\TypeDePrestaRepository;

class PrestationService
{
  private PrestationRepository $prestationRepository;
  private TypeDePrestaRepository $typePrestaRepository;

  public function __construct(PrestationRepository $prestationRepository, TypeDePrestaRepository $typePrestaRepository)
  {
    $this->prestationRepository = $prestationRepository;
    $this->typePrestaRepository = $typePrestaRepository;
  }
}