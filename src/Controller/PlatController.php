<?php

namespace App\Controller;

use App\Repository\PlatRepository;
use App\Service\PlatService;

class PlatController extends Controller
{
  private PlatService $platService;
  
  public function __construct() {
    parent::__construct();
    $platRepository = new PlatRepository();
    $this->platService = new PlatService($platRepository);
  }
}