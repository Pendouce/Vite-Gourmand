<?php

namespace App\Repository;

use App\Entity\Boisson;
use PDO;

class BoissonRepository extends Repository
{
  //Create

  public function creerBoisson(array $data)
  {
    $sql = 'INSERT INTO boisson (nom_boisson, photo_boisson, prix_boisson, alcool, stock_boisson, boisson_actif) 
    VALUES(:nom_boisson, :photo_boisson, :prix_boisson, :alcool, :stock_boisson, :boisson_actif)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['boisson_id'] = $this->pdo->lastInsertId();

    return Boisson::creerEtHydrate($data);
  }

  //Read

  public function trouverBoissonParNom(string$nomBoisson)
  {
    $sql = 'SELECT * FROM boisson WHERE nom_boisson = :nom_boisson';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nom_boisson', $nomBoisson, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function trouverBoisson()
  {
    $sql = 'SELECT * FROM boisson';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabBoisson = [];

    foreach($data as $boisson){
      $tabBoisson[] = Boisson::creerEtHydrate($boisson);
    }

    return $tabBoisson;
  }

  public function trouverBoissonParId(int $id)
  {
    $sql = 'SELECT * FROM boisson WHERE boisson_id = :boisson_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':boisson_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $boisson = $statement->fetch(PDO::FETCH_ASSOC);

     if ($boisson === false) {
      return false;
    }

    return Boisson::creerEtHydrate($boisson);
  }
  //Update

  public function modifierBoisson(array $data)
  {
    $sql = 'UPDATE boisson SET nom_boisson = :nom_boisson,
    photo_boisson = :photo_boisson,
    prix_boisson = :prix_boisson,
    alcool = :alcool,
    stock_boisson = :stock_boisson,
    boisson_actif = :boisson_actif
    WHERE boisson_id = :boisson_id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

  public function modifierStatusBoisson(int $boissonId, int $status)
  {
    $sql = 'UPDATE boisson SET boisson_actif = :boisson_actif
    WHERE boisson_id = :boisson_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':boisson_id', $boissonId, PDO::PARAM_INT);
    $statement->bindValue(':boisson_actif', $status, PDO::PARAM_BOOL);
    $statement->execute();
  }


  public function modifierStockBoisson(int $boissonId, int $stock)
  {
    $sql = 'UPDATE boisson SET stock_boisson = :stock_boisson
    WHERE boisson_id = :boisson_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':stock_boisson', $stock, PDO::PARAM_INT);
    $statement->bindValue(':boisson_id', $boissonId, PDO::PARAM_INT);
    $statement->execute();
  }
  //Delete

  public function supprimerBoisson(int $boissonId)
  {
    $sql = 'DELETE FROM boisson WHERE boisson_id = :boisson_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':boisson_id', $boissonId, PDO::PARAM_INT);
    $statement->execute();
  }
}