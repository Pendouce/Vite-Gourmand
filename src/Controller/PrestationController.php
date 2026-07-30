<?php

namespace App\Controller;

use App\Repository\PrestationRepository;
use App\Repository\TypeDePrestaRepository;
use App\Service\PrestationService;


class PrestationController extends Controller
{
  private PrestationService $prestationService;

  public function __construct()
  {
    parent::__construct();
    $prestationRepository = new PrestationRepository();
    $typePrestaRepository = new TypeDePrestaRepository();
    $this->prestationService = new PrestationService($prestationRepository, $typePrestaRepository);
  }

  
}