<?php

namespace App\Repository;

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

    public function ajouterPlatAuMenu(int $menuId, int $platId)
  {
    $sql = 'INSERT INTO menu_plat (menu_id, plat_id) VALUES (:menu_id, :plat_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
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
    
    if ($plat === false) {
      return false;
    }

    return Plat::creerEtHydrate($plat);
  }

   public function trouverPlatDuMenu(int $menuId)
  {
    //$sql = 'SELECT menu_plat.menu_id, menu_plat.plat_id FROM menu_plat 
    $sql = 'SELECT plat.* FROM menu_plat 
    INNER JOIN plat ON menu_plat.plat_id = plat.plat_id
    WHERE menu_id = :menu_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabPlat = [];
    
    foreach($data as $plat)
      {
        $tabPlat[] = Plat::creerEtHydrate($plat);
      }
    return $tabPlat;
  }

  // Update

  public function modifierPlat(array $data)
  {
    $sql = 'UPDATE plat SET 
    titre = :titre, image_plat = :image_plat, description_plat = :description_plat, 
    prix_personne = :prix_personne, stock_plat = :stock_plat, type_id = :type_id, plat_actif = :plat_actif
    WHERE plat_id = :plat_id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

  public function modifierStatusPlat(int $platId, int $status)
  {
    $sql = 'UPDATE plat SET plat_actif = :plat_actif WHERE plat_id = :plat_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':plat_actif', $status, PDO::PARAM_BOOL);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);
    $statement->execute();
  }

  // Delete

  public function supprimerPlat(int $platId)
  {
    $sql = 'DELETE FROM plat WHERE plat_id = :plat_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':plat_id', $platId, PDO::PARAM_INT);

    return $statement->execute();
  }
}