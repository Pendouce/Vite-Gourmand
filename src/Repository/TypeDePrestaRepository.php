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

  public function trouverTypeDePrestaParNom(string $libelle)
  {
    $sql = 'SELECT * FROM type_presta WHERE libelle = :libelle';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':libelle', $libelle, PDO::PARAM_STR);
    $statement->execute();
  }
}