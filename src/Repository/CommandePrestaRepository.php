<?php

namespace App\Repository;

use App\Entity\CommandePrestation;
use App\Entity\Prestation;
use PDO;

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

  public function trouverPrestaDeLaCommande(int $commandeId)
  {
    $sql = 'SELECT commande_prestation.*, prestation.* FROM commande_prestation
    INNER JOIN prestation ON commande_prestation.prestation_id = prestation.prestation_id
    WHERE commande_prestation.commande_id = :commande_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':commande_id', $commandeId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPresta = [];

    foreach($data as $presta){
      $keyPresta = ['nom_presta', 'prix_presta', 'description_presta', 'img_presta', 'necessite_retour', 'prestation_actif', 'type_presta_id', 'contenu_presta'];
      $keyCommandePresta = ['prix_total_presta', 'adresse_presta', 'date_presta', 'date_retour_prevu', 'date_retour', 'taux_retard', 'commande_id', 'prestation_id'];

      $donneesPresta = [];
      $donneesCommandePresta = [];

      foreach($keyPresta as $key){
        $donneesPresta[$key] = $presta[$key];
      }

      foreach($keyCommandePresta as $key){
        $donneesCommandePresta[$key] = $presta[$key];
      }

      $prestaCommande = CommandePrestation::creerEtHydrate($donneesCommandePresta);
      $prestaCommande->setPrestation(Prestation::creerEtHydrate($donneesPresta));
 

      $tabPresta[] = $prestaCommande;
    }

    return $tabPresta;
  }
}

