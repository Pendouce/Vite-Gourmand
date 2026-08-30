<?php

namespace App\Repository;

use App\Entity\InformationVg;
use PDO;

class InformationVgRepository extends Repository
{
  public function trouverInfosVg()
  {
    $sql = 'SELECT * FROM information_vg';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $info = $statement->fetch(PDO::FETCH_ASSOC);
    
    return InformationVg::creerEtHydrate($info);
  }
}