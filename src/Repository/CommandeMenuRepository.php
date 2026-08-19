<?php

namespace App\Repository;

use App\Entity\CommandeMenu;
use App\Entity\Menu;
use PDO;

class CommandeMenuRepository extends Repository
{
  public function ajouterMenuCommande(array $data)
  {
    $sql = 'INSERT INTO commande_menu(nb_personne_menu, commande_id, menu_id)
    VALUES (:nb_personne_menu, :commande_id, :menu_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    return CommandeMenu::creerEtHydrate($data);
  }

  public function trouverMenuDeLaCommande(int $commandeId)
  {
    $sql = 'SELECT commande_menu.*, menu.* FROM commande_menu
    INNER JOIN menu ON commande_menu.menu_id = menu.menu_id
    WHERE commande_menu.commande_id = :commande_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':commande_id', $commandeId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabMenuCommande = [];

    foreach($data as $menu){
      $keyMenu = ['titre', 'prix_personne', 'nombre_personne_min', 'conditions', 'stock_dispo', 'menu_actif'];
      $keyCommandeMenu = ['nb_personne_menu', 'commande_id', 'menu_id'];

      $donneesMenu = [];
      $donneesCommandeMenu = [];

      foreach($keyMenu as $key){
        $donneesMenu[$key] = $menu[$key];
      }
      foreach($keyCommandeMenu as $key){
        $donneesCommandeMenu[$key] = $menu[$key];
      }

      $menuCommande = CommandeMenu::creerEtHydrate($donneesCommandeMenu);
      $menuCommande->setMenu(Menu::creerEtHydrate($donneesMenu));

      $tabMenuCommande[] = $menuCommande;
    }

    return $tabMenuCommande;
  }
}

