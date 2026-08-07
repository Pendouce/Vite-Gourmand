<?php

namespace App\Service;

use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;

class CommandeService
{
  private CommandeRepository $commandeRepository;
  private CommandePrestaRepository $commandPrestaRepository;
  private CalculPrixService $calculPrixService;

  public function __construct(CommandeRepository $commandeRepository, CommandePrestaRepository $commandPrestaRepository, CalculPrixService $calculPrixService)
  {
    $this->commandeRepository = $commandeRepository;
    $this->commandPrestaRepository = $commandPrestaRepository;
    $this->calculPrixService = $calculPrixService;
  }

  public function creerCommande()
  {
    
  }
}
