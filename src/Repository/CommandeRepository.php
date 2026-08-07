<?php

namespace App\Repository;

use App\Entity\Commande;

class CommandeRepository extends Repository
{
  public function creerCommande(array $data)
  {
    $sql = 'INSERT INTO commande (
    commande_id, nb_commande, date_commande, date_prestation, nb_personne, 
    heure_Livraison, lieu_livraison, prix_livraison, prix_total, user_id, status_id)
    VALUES (
    :commande_id, :nb_commande, :date_commande, :date_prestation, :nb_personne, :
    heure_Livraison, :lieu_livraison, :prix_livraison, :prix_total, :user_id, :status_id)
    ';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['commande_id'] = $this->pdo->lastInsertId();

    return Commande::creerEtHydrate($data);
  }

}