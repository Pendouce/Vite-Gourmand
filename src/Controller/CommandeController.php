<?php

namespace App\Controller;

use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;
use App\Service\CalculPrixService;
use App\Service\CommandeService;

class CommandeController extends Controller
{
  private CommandeService $commandeService;

  public function __construct() {
    parent::__construct();
    $commandeRepository = new CommandeRepository();
    $commandePrestaRepository = new CommandePrestaRepository();
    $calculPrixService = new CalculPrixService();
    $this->commandeService = new CommandeService($commandeRepository, $commandePrestaRepository, $calculPrixService);
  }
}