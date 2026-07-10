<?php

namespace App\Repository;
use PDO;
//use App\Entity\User;


class UserRepository extends Repository
{
  // Create

  public function creationUtilisateur(string $nom, string $prenom, string $email, string $mdp, string $telephone, string $ville, string $codePostal, string $adresse, int $role)
  //public function creationUtilisateur(array $data)
  {
    $sql = 'INSERT INTO user(nom, prenom, email, mot_de_passe, telephone, ville, code_postal, adresse, role_id)
      VALUES(
        :nom, :prenom, :email, :mot_de_passe, :telephone, :ville, :code_postal, :adresse, :role_id
      )';
    $statment = $this->pdo->prepare($sql);

    $statment->bindValue(':nom', $nom, PDO::PARAM_STR);
    $statment->bindValue(':prenom', $prenom, PDO::PARAM_STR);
    $statment->bindValue(':email', $email, PDO::PARAM_STR);
    $statment->bindValue(':mot_de_passe', $mdp, PDO::PARAM_STR);
    $statment->bindValue(':telephone', $telephone, PDO::PARAM_STR);
    $statment->bindValue(':ville', $ville, PDO::PARAM_STR);
    $statment->bindValue(':code_postal', $codePostal, PDO::PARAM_STR);
    $statment->bindValue(':adresse', $adresse, PDO::PARAM_STR);
    $statment->bindValue(':role_id', $role, PDO::PARAM_INT);

    //return $statment->execute($data);
    return $statment->execute();
  }
}