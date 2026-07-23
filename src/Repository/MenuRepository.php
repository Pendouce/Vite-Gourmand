<?php

namespace App\Repository;

use App\Entity\Menu;
use PDO;

class MenuRepository extends Repository
{
  public function creerMenu(array $data)
  {
    $sql = 'INSERT INTO menu (titre, prix_personne, nombre_personne_min, conditions, stock_dispo, menu_actif)
    VALUES(:titre, :prix_personne, :nombre_personne_min, :conditions, :stock_dispo, :menu_actif)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['menu_id'] = $this->pdo->lastInsertId();
    return Menu::creerEtHydrate($data);
  }

  public function trouverMenuParNom(string $titre)
  {
    $sql = 'SELECT * FROM menu WHERE titre = :titre';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':titre', $titre, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::PARAM_STR);
  }

  public function trouverMenu()
  {
    $sql = 'SELECT * FROM menu';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabMenus = [];

    foreach($data as $menu){
      $tabMenus[] = Menu::creerEtHydrate($menu);
    }
    return $tabMenus;
  }
}