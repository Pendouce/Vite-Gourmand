<?php

namespace App\Repository;

use App\Entity\TypeDePresta;
use PDO;

class TypeDePrestaRepository extends Repository
{
  // Create 

  public function creerTypeDePresta(array $data)
  {
    $sql = 'INSERT INTO type_presta (libelle) VALUES(:libelle)';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':libelle', $data['libelle'], PDO::PARAM_STR);
    $statement->execute();

    $data['type_presta_id'] = $this->pdo->lastInsertId();

    return TypeDePresta::creerEtHydrate($data);
  }

  // Read

  public function trouverTypeDePresta()
  {
    $sql = 'SELECT * FROM type_presta';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabTypeDePresta = [];

    foreach($data as $presta){
      $tabTypeDePresta[] = TypeDePresta::creerEtHydrate($presta);
    }

    return $tabTypeDePresta; 
  }

  public function trouverTypeDePrestaParNom(string $libelle)
  {
    $sql = 'SELECT * FROM type_presta WHERE libelle = :libelle';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':libelle', $libelle, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetch(PDO::FETCH_ASSOC);

    if ($data === false) {
      return false;
    }
    return TypeDePresta::creerEtHydrate($data);
  }

  public function trouverTypeDePrestaParId(string $id)
  {
    $sql = 'SELECT * FROM type_presta WHERE type_presta_id = :type_presta_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':type_presta_id', $id, PDO::PARAM_STR);
    $statement->execute();

    $data = $statement->fetch(PDO::FETCH_ASSOC);

    if ($data === false) {
      return false;
    }
    return TypeDePresta::creerEtHydrate($data);
  }


}