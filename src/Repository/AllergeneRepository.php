<?php

namespace App\Repository;

use App\Entity\Allergene;
use PDO;

class AllergeneRepository extends Repository
{
  // Read
  public function trouverAllergenes()
  {
    $sql = 'SELECT * FROM allergene';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $tabAllergene = $statement->fetchAll(PDO::FETCH_ASSOC);
    $allergenes = [];

    foreach($tabAllergene as $data){
      $allergenes[] = Allergene::creerEtHydrate($data);
    }

    return $allergenes;
  }

  public function trouverAllergenesDuPlat(int $platId)
  {
    $sql = 'SELECT plat_allergene.allergene_id, allergene.libelle FROM plat_allergene 
    INNER JOIN allergene ON plat_allergene.allergene_id = allergene.allergene_id
    WHERE plat_id = :plat_id ';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabAllergenes = [];

    foreach($data as $allergene){
      $tabAllergenes[] = Allergene::creerEtHydrate($allergene);
    }

    return $tabAllergenes;
  }
}