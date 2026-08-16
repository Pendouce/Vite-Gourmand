<?php

namespace App\Repository;

use App\Entity\CommandeBoisson;

class CommandeBoissonRepository extends Repository
{
  public function ajouterBoissonCommande(array $data)
  {
    $sql = 'INSERT INTO commande_boisson (commande_id, boisson_id, quantite, prix_unitaire) 
    VALUES (:commande_id, :boisson_id, :quantite, :prix_unitaire )';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    return CommandeBoisson::creerEtHydrate($data);
  }
}
