<?php

namespace App\Repository;

use App\Entity\Allergene;
use App\Entity\Plat;
use PDO;

class PlatRepository extends Repository
{
  // Create
  public function creerPlat(array $data)
  {
    $sql = 'INSERT INTO plat (titre, image_plat, description_plat, prix_personne, stock_plat, type_id, plat_actif) VALUES(:titre, :image_plat, :description_plat, :prix_personne, :stock_plat, :type_id, :plat_actif)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['plat_id'] = $this->pdo->lastInsertId();

    return Plat::creerEtHydrate($data);
  }

  public function ajouterAllergeneAuxPlat(int $platId, int $allergeneId)
  {
    $sql = 'INSERT INTO plat_allergene (plat_id, allergene_id) VALUES(:plat_id, :allergene_id)';

    $statement = $this->pdo->prepare($sql);

    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
    $statement->bindValue(':allergene_id', $allergeneId, PDO::PARAM_INT);
    $statement->execute();
  }

  // Read



  public function trouverPlatByNom(string $titre)
  {
    $sql = 'SELECT * FROM plat WHERE titre = :titre';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':titre', $titre, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function trouverPlat()
  {
    $sql = 'SELECT * FROM plat
    INNER JOIN type_de_plat ON plat.type_id = type_de_plat.type_id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPlat = [];

    foreach($data as $plat){
      $tabPlat[] = Plat::creerEtHydrate($plat);
    }

    return $tabPlat;
  }

  public function trouverPlatParType(int $typeId)
  {
    $sql = 'SELECT * FROM plat
    INNER JOIN type_de_plat ON plat.type_id = type_de_plat.type_id
    WHERE plat.type_id = :type_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':type_id', $typeId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPlat = [];

    foreach($data as $plat){
      $tabPlat[] = Plat::creerEtHydrate($plat);
    }

    return $tabPlat;
  }

  public function trouverPlatParId(int $platId)
  {
    $sql = 'SELECT * FROM plat
    INNER JOIN type_de_plat ON plat.type_id = type_de_plat.type_id
    WHERE plat.plat_id = :plat_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
    $statement->execute();

    $plat = $statement->fetch(PDO::FETCH_ASSOC);

    return Plat::creerEtHydrate($plat);
  }
}