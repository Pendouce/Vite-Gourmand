<?php

namespace App\Service;

use App\Repository\AvisRepository;

class AvisService
{
  private AvisRepository $avisRepository;

  public function __construct(AvisRepository $avisRepository) {
    $this->avisRepository;
  }

  
}