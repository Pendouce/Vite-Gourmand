<?php

namespace App\Repository;

use App\Entity\Plat;
use PDO;

class PlatRepository extends Repository
{
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

    $data = $statement->fetch(PDO::FETCH_ASSOC);

    if ($data === false) {
        return false;
    }
  }

  public function trouverPlatByNom(string $titre)
  {
    $sql = 'SELECT * FROM plat WHERE titre = :titre';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':titre', $titre, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function afficherPlat()
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
}