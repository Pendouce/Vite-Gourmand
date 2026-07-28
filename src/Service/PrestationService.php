<?php

namespace App\Service;

use App\Repository\PrestationRepository;

class PrestationService
{
  private PrestationRepository $prestationRepository;

  public function __construct(PrestationRepository $prestationRepository)
  {
    $this->prestationRepository = $prestationRepository;
  }
}