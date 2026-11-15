<?php

namespace App\Service;

use App\Exceptions\StockDispoException;
use Exception;

class CalculStockService
{
  public function calculerStockPlat(int $nbPlat, int $nbCommande): int
  {
    if ($nbPlat < $nbCommande) {
      throw new StockDispoException($nbPlat, "ce plat");
    }
    return $nbPlat - $nbCommande;
  }

  public function calculerStockMenu(array $plat, int $nbmenu, string $nom): int
  {
    $nbMenuDispo = min($plat);
   
    if($nbMenuDispo < $nbmenu)
    {
      throw new Exception('Vous ne pouvez commander que '.$nbMenuDispo.' menus pour le menu '.$nom);
      //throw new StockDispoException($nbMenuDispo, "ce menu");
    }

    return $nbMenuDispo;
  }

  public function calculerStockBoisson(int $stockBoisson, int $nbBoissonCommande, string $nom): int
  {
    if($stockBoisson < $nbBoissonCommande){
      throw new Exception('Vous ne pouvez commander que '.$stockBoisson.' boissons pour la boisson '.$nom);
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