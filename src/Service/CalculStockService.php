<?php

namespace App\Service;

use App\Exceptions\StockDispoException;

class CalculStockService
{
  public function calculerStockPlat(int $nbPlat, int $nbCommande): int
  {
    if ($nbPlat < $nbCommande) {
      throw new StockDispoException($nbPlat, "ce plat");
    }
    return $nbPlat - $nbCommande;
  }

  public function calculerStockMenu(array $plat, int $nbmenu): int
  {
    $nbMenuDispo = min($plat);
   
    if($nbMenuDispo < $nbmenu)
    {
      throw new StockDispoException($nbMenuDispo, "ce menu");
    }

    return $nbMenuDispo;
  }

  public function calculerStockBoisson(int $stockBoisson, int $nbBoissonCommande): int
  {
    if($stockBoisson < $nbBoissonCommande){
      throw new StockDispoException($stockBoisson, "cette boisson");
    }

    return $stockBoisson - $nbBoissonCommande;
  }

  public function calculerRetourStockPlat(int $nbPlatCommande, int $stockPlat): int
  {
    return $nbPlatCommande + $stockPlat;
  }


  public function calculerRetourStockBoisson(int $stockBoisson, int $nbBoissonCommande): int
  {
    return $stockBoisson + $nbBoissonCommande;
  }
}