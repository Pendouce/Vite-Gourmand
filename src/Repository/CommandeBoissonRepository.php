<?php

namespace App\Repository;

use App\Entity\Boisson;
use App\Entity\Commande;
use App\Entity\CommandeBoisson;
use PDO;

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

  public function trouverBoissonDeLaCommande(int $commandeId)
  {
    $sql = 'SELECT commande_boisson.*, boisson.* FROM commande_boisson
    INNER JOIN boisson ON commande_boisson.boisson_id = boisson.boisson_id
    WHERE commande_boisson.commande_id = :commande_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':commande_id', $commandeId, PDO::PARAM_INT);
    $statement->execute();

    // Je recupere toutes les données de la jointure (boisson + commande boisson)
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabBoissonCommande = [];

    // Je boucle sur les données de la jointure
    foreach($data as $boisson){
      // Je separe les donnees (qui sont des cles) boisson de commande boisson et les stock chacunes dans un tableau 
      $keyBoisson = ['nom_boisson', 'photo_boisson', 'prix_boisson', 'alcool', 'stock_boisson', 'boisson_actif'];
      $keyCommandeBoisson = ['commande_id', 'boisson_id', 'quantite', 'prix_unitaire'];

      // Je cree un tableau qui stockera les clees et les valeurs
      $donneesBoisson = [];
      $donneesCommandeBoisson = [];

      // Je boucle sur chacunes des cles de boisson et les stock dans donnéesBoisson
      foreach($keyBoisson as $key){
        // $donneesBoisson['nom_boisson'] = $boisson['nom_boisson'];
        $donneesBoisson[$key] = $boisson[$key];
      }

      foreach($keyCommandeBoisson as $key){
        $donneesCommandeBoisson[$key] = $boisson[$key];
      }

    // J'hydrate CommandeBoisson
    $commandeBoisson = CommandeBoisson::creerEtHydrate($donneesCommandeBoisson);
    // J'hydrate Boisson et l'assigne a la propriete boisson de la classe CommandeBoisson
    $commandeBoisson->setBoisson(Boisson::creerEtHydrate($donneesBoisson));

    $tabBoissonCommande[] = $commandeBoisson;
    }

    return $tabBoissonCommande;
  }

}
