<?php

namespace App\Repository;

use App\Entity\Allergene;
use PDO;

class AllergeneRepository extends Repository
{
  // Create

    public function ajouterAllergeneAuxPlat(int $platId, int $allergeneId)
  {
    $sql = 'INSERT INTO plat_allergene (plat_id, allergene_id) VALUES(:plat_id, :allergene_id)';

    $statement = $this->pdo->prepare($sql);

    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
    $statement->bindValue(':allergene_id', $allergeneId, PDO::PARAM_INT);
    $statement->execute();
  }
  
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

  // Update

  public function supprimerAllergeneDuPlat(int $platId, int $allergeneId)
  {
    $sql = 'DELETE FROM plat_allergene
    WHERE plat_id = :plat_id
    AND allergene_id = :allergene_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
    $statement->bindValue(':allergene_id', $allergeneId, PDO::PARAM_INT);
    $statement->execute();
  }

  // Delete

  public function supprimerPlat(int $platId)
  {
    $sql = 'DELETE FROM plat_allergene WHERE plat_id = :plat_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);

    return $statement->execute();
  }
}