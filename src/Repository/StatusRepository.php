<?php

namespace App\Repository;

use App\Entity\Status;
use PDO;

class StatusRepository extends Repository
{
  public function trouverStatus()
  {
    $sql = 'SELECT * FROM status';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabStatus = [];

    foreach($data as $status){
      $tabStatus[] = Status::creerEtHydrate($status);
    }

    return $tabStatus;
  }
}