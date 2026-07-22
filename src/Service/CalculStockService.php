<?php

namespace App\Service;

use App\Exceptions\StockDispoException;

class CalculStockService
{
  public function calculerStockPlat(int $nbPlat, int $nbCommande): int
  {
    if ($nbPlat < $nbCommande) {
      throw new StockDispoException($nbPlat, "plat");
    }
    return $nbPlat - $nbCommande;
  }

  public function calculerStockMenu(array $plat, int $nbmenu): int
  {
    $nbMenuDispo = min($plat);
   
    if($nbMenuDispo < $nbmenu)
    {
      throw new StockDispoException($nbMenuDispo, "menu");
    }

    return $nbMenuDispo;
  }
}