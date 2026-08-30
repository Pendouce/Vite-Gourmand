<?php

namespace App\Controller;

use App\Factory\ContainerId;
use App\Service\InfoVgService;

class InfoVgController extends Controller
{
  private InfoVgService $infoVgService;

  public function __construct() {
    parent::__construct();
    $this->infoVgService = ContainerId::getInfoVgService();
  }

  public function afficherInfosVg()
  {
    $infos = $this->infoVgService->afficherInfosVg();
    $this->render('layouts/footer', ['infos' => $infos]);
  }
}