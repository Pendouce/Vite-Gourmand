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

  public function modifierInfosVg(array $data)
  {
    $sql = 'UPDATE information_vg SET 
    adresse = :adresse, telephone = :telephone, email = :email, 
    horaires_semaine = :horaires_semaine, horaires_weekend = :horaires_weekend
    WHERE info_id = 1';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }
}