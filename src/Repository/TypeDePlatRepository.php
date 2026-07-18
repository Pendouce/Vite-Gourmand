<?php

namespace App\Repository;

use PDO;
use App\Entity\TypeDePlat;

class TypeDePlatRepository extends Repository
{
  // Create
  public function creerTypeDePlat(array $data)
  {
    $sql = 'INSERT INTO type_de_plat (libelle) VALUES(:libelle)';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':libelle', $data['libelle'], PDO::PARAM_STR);
    $statement->execute();

    $data['type_id'] = $this->pdo->lastInsertId();

    return TypeDePlat::creerEtHydrate($data);
  }

  // Read
  public function trouverTypeDePlat()
  {
    $sql = 'SELECT * FROM type_de_plat';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
  }

  public function trouverTypeDePlatByNom(string $libelle)
  {
    $sql = 'SELECT * FROM type_de_plat WHERE libelle = :libelle';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':libelle', $libelle, PDO::PARAM_STR);
    $statement->execute();
    
    $data = $statement->fetch(PDO::FETCH_ASSOC);

    if ($data === false) {
      return false;
    }
    return TypeDePlat::creerEtHydrate($data);
  }

  // Update
  // Delete
}