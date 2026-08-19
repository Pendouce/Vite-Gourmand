<?php

namespace App\Repository;

use App\Entity\Commande;
use PDO;

class CommandeRepository extends Repository
{
  // Create 
  public function creerCommande(array $data)
  {
    $sql = 'INSERT INTO commande (
    nb_commande, date_commande, nb_personne, 
    date_livraison, lieu_livraison, prix_livraison, prix_total, user_id, status_id)
    VALUES (
    :nb_commande, :date_commande, :nb_personne, 
    :date_livraison, :lieu_livraison, :prix_livraison, :prix_total, :user_id, :status_id)
    ';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['commande_id'] = $this->pdo->lastInsertId();

    return Commande::creerEtHydrate($data);
  }

  // Read

  public function trouverCommandeParNb(int $nbCommande): bool
  {
    $sql = 'SELECT COUNT(*) FROM commande
    WHERE nb_commande = :nb_commande';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nb_commande', $nbCommande, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchColumn();
  }

  public function trouverCommande()
  {
    $sql = 'SELECT * FROM commande INNER JOIN status ON commande.status_id = status.status_id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabCommande = [];

    foreach($data as $commande){
      $tabCommande[] = Commande::creerEtHydrate($commande);
    }

    return $tabCommande;
  }

  public function trouverCommandeUser(int $userId)
  {
    $sql = 'SELECT * FROM commande INNER JOIN status ON commande.status_id = status.status_id
    WHERE user_id = :user_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabCommande = [];

    foreach($data as $commande){
      $tabCommande[] = Commande::creerEtHydrate($commande);
    }

    return $tabCommande;
  }


  public function trouverCommandeParId(int $id)
  {
    $sql = 'SELECT * FROM commande INNER JOIN status ON commande.status_id = status.status_id
    WHERE commande_id = :commande_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':commande_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $commande = $statement->fetch(PDO::FETCH_ASSOC);

    if($commande === false)
    {
      return false;
    }

    return Commande::creerEtHydrate($commande);
  }
}