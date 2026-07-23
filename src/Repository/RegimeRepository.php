<?php

namespace App\Repository;

use App\Entity\Regime;
use PDO;

class RegimeRepository extends Repository
{
  public function ajouterRegimeAuMenu(int $menuId, int $regimeId)
  {
    $sql = 'INSERT INTO menu_regime (menu_id, regime_id) VALUES (:menu_id, :regime_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->bindValue(':regime_id', $regimeId, PDO::PARAM_INT);
    $statement->execute();
  }

  public function trouverRegime()
  {
    $sql = 'SELECT * FROM regime';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabRegimes = [];

    foreach($data as $regime){
      $tabRegimes[] = Regime::creerEtHydrate($regime);
    }

    return $tabRegimes;
  }

  public function trouverRegimeDuMenu(int $menuId)
  {
    //$sql = 'SELECT menu_regime.menu_id, menu_regime.regime_id FROM menu_regime
    $sql = 'SELECT regime.* FROM menu_regime
    INNER JOIN regime ON menu_regime.regime_id = regime.regime_id
    WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabRegimes = [];

    foreach($data as $regime){
      $tabRegimes[] = Regime::creerEtHydrate($regime);
    }
    return $tabRegimes;
  }
}