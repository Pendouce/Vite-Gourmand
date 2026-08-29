<?php

namespace App\Repository;

use App\Entity\Avis;
use PDO;

class AvisRepository extends Repository
{
  public function creerAvis(array $data)
  {
    $sql = 'INSERT INTO avis ( note, commentaire, date_publication, publie, commande_id) 
    VALUES (:note, :commentaire, :date_publication, :publie, :commande_id)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['avis_id'] = $this->pdo->lastInsertId();

    return Avis::creerEtHydrate($data);
  }

  public function trouverAvisParCommande(int $commandeId)
  {
    $sql = 'SELECT * FROM avis WHERE commande_id = :commande_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':commande_id', $commandeId, PDO::PARAM_INT);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public function trouverAvis()
  {
    return $this->trouverAvisGenerique('SELECT * FROM avis');
  }

  public function trouverAvisAcceptes()
  {
    return $this->trouverAvisGenerique('SELECT * FROM avis WHERE publie = 1');
  }

  private function trouverAvisGenerique(string $sql)
  {
    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabAvis = [];

    foreach($data as $avis){
      $tabAvis[] = Avis::creerEtHydrate($avis);
    }

    return $tabAvis;
  }
}