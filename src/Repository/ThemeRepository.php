<?php

namespace App\Repository;

use App\Entity\Theme;
use PDO;

class ThemeRepository extends Repository
{
  public function ajouterThemeAuMenu(int $menuId, int $themeId)
  {
    $sql = 'INSERT INTO menu_theme (menu_id, theme_id) VALUES (:menu_id, :theme_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->bindValue(':theme_id', $themeId, PDO::PARAM_INT);
    $statement->execute();
  }

  public function trouverTheme()
  {
    $sql = 'SELECT * FROM theme';
    
    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $$tabThemes = [];

    foreach($data as $theme){
      $tabThemes[] = Theme::creerEtHydrate($theme);
    }

    return $tabThemes;
  }

  public function trouverThemeDuMenu(int $menuId)
  {
    $sql = 'SELECT theme.* FROM menu_theme 
    INNER JOIN theme ON menu_theme.theme_id = theme.theme_id
    WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabThemes = [];
    
    foreach($data as $theme)
      {
        $tabThemes[] = Theme::creerEtHydrate($theme);
      }
    return $tabThemes;
  }

  public function supprimerThemeDuMenu(int $menuId, int $themeId)
  {
    $sql = 'DELETE FROM menu_theme
    WHERE menu_id = :menu_id
    AND theme_id = :theme_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->bindValue(':theme_id', $themeId, PDO::PARAM_INT);
    $statement->execute();
  }

  public function supprimerMenu(int $menuId)
  {
    $sql = 'DELETE FROM menu_theme WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);

    return $statement->execute();
  }

}