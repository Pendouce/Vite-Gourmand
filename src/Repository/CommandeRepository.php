<?php

namespace App\Repository;

use App\Entity\Commande;
use PDO;

class CommandeRepository extends Repository
{
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

  public function trouverCommandeParNb(int $nbCommande): bool
  {
    $sql = 'SELECT COUNT(*) FROM commande
    WHERE nb_commande = :nb_commande';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nb_commande', $nbCommande, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetchColumn();
  }
}