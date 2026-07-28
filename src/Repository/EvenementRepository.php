<?php

namespace App\Repository;

use App\Entity\Evenement;
use PDO;
class EvenementRepository extends Repository
{
  public function ajouterEvenementAuMenu(int $menuId, int $evenementId)
  {
    $sql = 'INSERT INTO menu_evenement (menu_id, evenement_id) VALUES (:menu_id, :evenement_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->bindValue(':evenement_id', $evenementId, PDO::PARAM_INT);
    $statement->execute();
  }

  public function trouverEvenement()
  {
    $sql = 'SELECT * FROM evenement';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabEvenements = [];

    foreach($data as $evenement){
      $tabEvenements[] = Evenement::creerEtHydrate($evenement);
    }

    return $tabEvenements;
  }

   public function trouverEvenementDuMenu(int $menuId)
  {
    //$sql = 'SELECT menu_evenement.menu_id, menu_evenement.evenement_id FROM menu_evenement 
    $sql = 'SELECT evenement.* FROM menu_evenement 
    INNER JOIN evenement ON menu_evenement.evenement_id = evenement.evenement_id
    WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabEvenements = [];
    
    foreach($data as $evenement)
      {
        $tabEvenements[] = Evenement::creerEtHydrate($evenement);
      }
    return $tabEvenements;
  }

  public function supprimerEvenementDuMenu(int $menuId, int $evenementId)
  {
    $sql = 'DELETE FROM menu_evenement
    WHERE menu_id = :menu_id
    AND evenement_id = :evenement_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->bindValue(':evenement_id', $evenementId, PDO::PARAM_INT);
    $statement->execute();
  }

  public function supprimerMenu(int $menuId)
  {
    $sql = 'DELETE FROM menu_evenement WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);

    return $statement->execute();
  }
}