<?php

namespace App\Repository;

use App\Entity\CommandePrestation;

class CommandePrestaRepository extends Repository
{
  public function ajouterPrestaCommande(array $data)
  {
    $sql = 'INSERT INTO commande_prestation (prix_total_presta, adresse_presta, date_presta, date_retour_prevu, date_retour, taux_retard, commande_id, prestation_id)
    VALUES (:prix_total_presta, :adresse_presta, :date_presta, :date_retour_prevu, :date_retour, :taux_retard, :commande_id, :prestation_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    return CommandePrestation::creerEtHydrate($data);
  }

}

