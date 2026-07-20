<?php

namespace App\Repository;

use App\Entity\Allergene;
use PDO;

class AllergeneRepository extends Repository
{
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
}