<?php

namespace App\Repository;
use PDO;
use App\Entity\User;


class UserRepository extends Repository
{
  // Create

  public function creeUtilisateur(string $nom, string $prenom, string $email, string $mdp, string $telephone, string $ville, string $codePostal, string $adresse, int $role)
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

  // Read

  public function afficheUtilisateur(){
    $sql = 'SELECT * FROM user';
    $statement = $this->pdo->prepare($sql);

    $statement->execute();
    $tabUtilisateur = $statement->fetchAll(PDO::FETCH_ASSOC);
    $utilisateur = [];

    // Boucle pour recuperer les données de tous mes utilisateurs
    // $utilisateur[] = array_push($utilisateur)
    foreach($tabUtilisateur as $data){
      $utilisateur[] = User::creerEtHydrate($data);
    }

    return $utilisateur;
  }

  // By id
    public function afficheUtilisateurById(int $id)
    {
      $sql = ('SELECT * FROM user WHERE user_id = :id');

      $statement = $this->pdo->prepare($sql);
      $statement->bindValue(':id', $id, PDO::PARAM_INT);
      $statement ->execute();

      return $statement->fetch(PDO::FETCH_ASSOC);
    }

  // By email
    public function afficheUtilisateurByEmail(string $email)
    {
      $sql = ('SELECT * FROM user WHERE email = :email');

      $statement = $this->pdo->prepare($sql);
      $statement->bindValue(':email', $email, PDO::PARAM_STR);
      $statement ->execute();

      return $statement->fetch(PDO::FETCH_ASSOC);
    }

  // Update

  public function modifieUtilisateur(array $data)
  {
    $sql = ('UPDATE user SET
       nom = :nom, 
       prenom = :prenom, 
       email = :email, 
       mot_de_passe = :mot_de_passe, 
       telephone = :telephone, 
       ville = :ville,
        code_postal = :code_postal,
        adresse = :adresse
      WHERE user_id = :id');

    $statement = $this->pdo->prepare($sql);

    return $statement->execute($data);
  }

  public function supprimeUtilisateur(int $id){
    $sql = ('DELETE FROM user WHERE user_id = :id');

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    return $statement->execute();
  }
}