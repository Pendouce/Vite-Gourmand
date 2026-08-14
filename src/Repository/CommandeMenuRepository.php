<?php

namespace App\Repository;

use App\Entity\CommandeMenu;

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
}

