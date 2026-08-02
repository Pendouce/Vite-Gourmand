<?php

namespace App\Repository;

use App\Entity\Prestation;
use PDO;

class PrestationRepository extends Repository
{
  // Create 

  public function creerPrestation(array $data)
  {
    $sql = 'INSERT INTO prestation (nom_presta, prix_presta, description_presta, img_presta, necessite_retour, prestation_actif, type_presta_id)
    VALUES(:nom_presta, :prix_presta, :description_presta, :img_presta, :necessite_retour, :prestation_actif, :type_presta_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);


    $data['prestation_id'] = $this->pdo->lastInsertId();

    return Prestation::creerEtHydrate($data);
  }

  //Read

  public function trouverPrestation()
  {
    $sql = 'SELECT * FROM prestation INNER JOIN type_presta ON prestation.type_presta_id = type_presta.type_presta_id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPresta = [];

    foreach($data as $presta){
      $tabPresta[] = Prestation::creerEtHydrate($presta);
    }

    return $tabPresta;
  }

  public function trouverPrestationparId(int $id)
  {
    $sql = 'SELECT * FROM prestation INNER JOIN type_presta ON prestation.type_presta_id = type_presta.type_presta_id
    WHERE prestation_id = :prestation_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':prestation_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPresta = [];

    foreach($data as $presta){
      $tabPresta[] = Prestation::creerEtHydrate($presta);
    }

    return $tabPresta;
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


