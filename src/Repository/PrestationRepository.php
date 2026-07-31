<?php

namespace App\Repository;

use App\Entity\Prestation;
use PDO;

class PrestationRepository extends Repository
{
  public function creerPrestation(array $data)
  {
    $sql = 'INSERT INTO prestation (nom_presta, prix_presta, description_presta, img_presta, necessite_retour, prestation_actif, type_presta_id)
    VALUES(:nom_presta, :prix_presta, :description_presta, :img_presta, :necessite_retour, :prestation_actif, :type_presta_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);


    $data['prestation_id'] = $this->pdo->lastInsertId();

    return Prestation::creerEtHydrate($data);
  }

  public function trouverPrestationParNom(string $nom)
  {
    $sql = 'SELECT * FROM prestation WHERE nom_presta = :nom_presta';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nom_presta', $nom, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }
}


