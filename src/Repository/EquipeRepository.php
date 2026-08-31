<?php

namespace App\Repository;

use App\Entity\Equipe;
use PDO;

class EquipeRepository extends Repository
{
  public function creerMembre(array $data)
  {
    $sql = 'INSERT INTO equipe (nom, prenom, photo, poste, description, actif)
    VALUES(:nom, :prenom, :photo, :poste, :description, :actif)';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);

    $data['membre_id'] = $this->pdo->lastInsertId();

    return Equipe::creerEtHydrate($data);
  }

  public function trouverMembreParId(int $membreId)
  {
    $sql = 'SELECT * FROM equipe WHERE membre_id = :membre_id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':membre_id', $membreId, PDO::PARAM_INT);
    $statement->execute();

    $membre = $statement->fetch(PDO::FETCH_ASSOC);

    return Equipe::creerEtHydrate($membre);
  }

  public function trouverMembreParNom(string $nom, string $prenom)
  {
    $sql = 'SELECT * FROM equipe WHERE nom = :nom AND prenom = :prenom';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':nom', $nom, PDO::PARAM_STR);
    $statement->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetch(PDO::FETCH_ASSOC);
  }
 //:nom, :prenom, :photo, :poste, :description, :actif
}