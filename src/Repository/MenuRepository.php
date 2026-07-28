<?php

namespace App\Repository;

use App\Entity\Menu;
use PDO;

class MenuRepository extends Repository
{
  // Create

  public function creerMenu(array $data)
  {
    $sql = 'INSERT INTO menu (titre, prix_personne, nombre_personne_min, conditions, stock_dispo, menu_actif)
    VALUES(:titre, :prix_personne, :nombre_personne_min, :conditions, :stock_dispo, :menu_actif)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['menu_id'] = $this->pdo->lastInsertId();
    return Menu::creerEtHydrate($data);
  }

  // Read

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

  public function trouverMenuParId(int $menuId)
  {
    $sql = 'SELECT * FROM menu WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();

    $menu = $statement->fetch(PDO::FETCH_ASSOC);

    if ($menu === false) {
      return false;
    }

    return Menu::creerEtHydrate($menu);
  }
/* 
  public function trouverMenuParEvenement(int $evenementId)
  {
    $sql = 'SELECT * FROM menu 
    INNER JOIN menu_evenement ON menu_evenement.menu_id = menu.menu_id
    INNER JOIN evenement ON menu_evenement.evenement_id = evenement.evenement_id
    WHERE menu_evenement.evenement_id = :evenement_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':evenement_id', $evenementId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabMenuEvenement = [];

    foreach($data as $menuEvenement){
      $tabMenuEvenement[] = Menu::creerEtHydrate($menuEvenement);
    }

    return $tabMenuEvenement;
  }

  public function trouverMenuParTheme(int $themeId)
  {
    $sql = 'SELECT * FROM menu
    INNER JOIN menu_theme ON menu_theme.menu_id = menu_id
    INNER JOIN theme ON menu_theme.theme_id = theme.theme_id
    WHERE theme.theme_id = :theme_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':theme_id', $themeId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabMenuTheme = [];

    foreach($data as $menuTheme){
      $tabMenuTheme[] = Menu::creerEtHydrate($menuTheme);
    }

    return $tabMenuTheme;
  }

  public function trouverMenuParRegime(int $regimeId)
  {
    $sql = 'SELECT * FROM menu
    INNER JOIN menu_regime ON menu_regime.menu_id = menu_id
    INNER JOIN regime ON menu_regime.regime_id = regime.regime_id
    WHERE regime.regime_id = :regime_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':regime_id', $regimeId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabMenuRegime = [];
    foreach($data as $menuRegime){
      $tabMenuRegime[] = Menu::creerEtHydrate($menuRegime);
    }

    return $tabMenuRegime;
  }

  public function trouverMenuParPrix(float $prixMax)
  {
    $sql = 'SELECT * FROM menu
    WHERE prix_personne <= :prix_personne';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':prix_personne', $prixMax, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPrixMenu = [];

    foreach($data as $prixmenu){
      $tabPrixMenu[] = Menu::creerEtHydrate($prixmenu);
    }

    return $tabPrixMenu;
  }

  public function trouverMenuParNbPersonneMin(int $nbPersonneMin)
  {
    $sql = 'SELECT * FROM menu 
    WHERE nombre_personne_min <= :nombre_personne_min';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nombre_personne_min', $nbPersonneMin, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabNbPersonnes = [];

    foreach($data as $nbPersonnes){
      $tabNbPersonnes[] = Menu::creerEtHydrate($nbPersonnes);
    }

    return $tabNbPersonnes;
  } */

  public function trouverMenuFiltre(array $filtres)
  {
    $sql = 'SELECT DISTINCT menu.* FROM menu ';

    $condtion = [];
    $params = [];

    if(!empty($filtres['evenement_id'])){
      // Ajoute la jointure a la requette
      $sql .= ' INNER JOIN menu_evenement ON menu_evenement.menu_id = menu.menu_id';
      // Clause WHERE
      $condtion[] = 'menu_evenement.evenement_id = :evenement_id';
      // BindValue
      $params[':evenement_id'] = $filtres['evenement_id'];
    }

    if(!empty($filtres['theme_id'])){
      $sql .= ' INNER JOIN menu_theme ON menu_theme.menu_id = menu.menu_id';
      $condtion[] = 'menu_theme.theme_id = :theme_id';
      $params[':theme_id'] = $filtres['theme_id'];
    }

    if(!empty($filtres['regime_id'])){
      $sql .= ' INNER JOIN menu_regime ON menu_regime.menu_id = menu.menu_id';
      $condtion[] = 'menu_regime.regime_id = :regime_id';
      $params[':regime_id'] = $filtres['regime_id'];
    }

    if(!empty($filtres['prix_personne'])){
      $condtion[] = 'prix_personne <= :prix_personne';
      $params[':prix_personne'] = $filtres['prix_personne'];
    }

    if(!empty($filtres['nombre_personne_min'])){
      $condtion[] = 'nombre_personne_min <= :nombre_personne_min';
      $params[':nombre_personne_min'] = $filtres['nombre_personne_min'];
    }

    if($condtion){
      $sql .= " WHERE " .implode(" AND ", $condtion);
    }

    $statement = $this->pdo->prepare($sql);
    $statement->execute($params);

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabMenuFiltre = [];

    foreach($data as $menu){
      $tabMenuFiltre[] = Menu::creerEtHydrate($menu);
    }

    return $tabMenuFiltre;
  }

  // Update

  public function modifierMenu(array $data)
  {
    $sql = 'UPDATE menu SET 
    titre = :titre, prix_personne = :prix_personne, nombre_personne_min = :nombre_personne_min,
     conditions = :conditions, stock_dispo = :stock_dispo, menu_actif = :menu_actif
    WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

  public function modifierStatusMenu(int $menuId, int $status)
  {
    $sql = 'UPDATE menu SET menu_actif = :menu_actif WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_actif', $status, PDO::PARAM_BOOL);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();
  }

  public function supprimerMenu(int $menuId)
  {
    $sql = 'DELETE FROM menu WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();
  }

}