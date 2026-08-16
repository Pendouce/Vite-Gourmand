<?php

namespace App\Factory;

use App\Repository\AllergeneRepository;
use App\Repository\BoissonRepository;
use App\Repository\CommandeBoissonRepository;
use App\Repository\CommandeMenuRepository;
use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;
use App\Repository\EvenementRepository;
use App\Repository\MenuRepository;
use App\Repository\PlatRepository;
use App\Repository\PrestationRepository;
use App\Repository\RegimeRepository;
use App\Repository\ThemeRepository;
use App\Repository\TypeDePlatRepository;
use App\Repository\TypeDePrestaRepository;
use App\Repository\UserRepository;
use App\Service\BoissonService;
use App\Service\CalculPrixService;
use App\Service\CalculStockService;
use App\Service\CommandeService;
use App\Service\MailService;
use App\Service\MenuService;
use App\Service\PlatService;
use App\Service\PrestationService;
use App\Service\TypeDePlatService;
use App\Service\TypeDePrestaService;
use App\Service\UserService;

class ContainerId
{
  public static function getUserService(): UserService
  {
    return new UserService(
      new UserRepository(),
      new MailService()
    );
  }

  public static function getPrestationService(): PrestationService
  {
    return new PrestationService(
      new PrestationRepository(),
      new TypeDePrestaRepository()
    );
  }
  public static function getTypeDePrestaService(): TypeDePrestaService
  {
    return new TypeDePrestaService(
      new TypeDePrestaRepository()
    );
  }

  public static function getPlatService(): PlatService
  {
    return new PlatService(
      new PlatRepository(),
      new AllergeneRepository()
    );
  }

  public static function getTypeDePlatService(): TypeDePlatService
  {
    return new TypeDePlatService(
      new TypeDePlatRepository()
    );
  }
  
  public static function getMenuService(): MenuService
  {
    return new MenuService(
      new MenuRepository (),
      new RegimeRepository (),
      new EvenementRepository (),
      new ThemeRepository (),
      new PlatRepository (),
      self::getPlatService(),
      new CalculStockService ()
    );
  }

  public static function getCommandeService(): CommandeService
  {
    return new CommandeService(
      new CommandeRepository(),
      new CommandePrestaRepository(),
      new CommandeMenuRepository(),
      new CommandeBoissonRepository(),
      new MenuRepository,
      new PrestationRepository(),
      new BoissonRepository(),
      new UserRepository(),
      self::getBoissonService(),
      new CalculPrixService(),
      new MailService(),
      self::getMenuService()
    );
  }

  public static function getBoissonService(): BoissonService
  {
    return new BoissonService(
      new BoissonRepository(),
      new CalculStockService()
    );
  }
}