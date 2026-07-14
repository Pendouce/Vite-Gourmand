<?php

namespace App\Repository;
use PDO;
use App\Entity\User;


class UserRepository extends Repository
{
  // Create

  public function creeUtilisateur(array $data)
  {
    $sql = 'INSERT INTO user(nom, prenom, email, mot_de_passe, telephone, ville, code_postal, adresse, role_id)
      VALUES(
        :nom, :prenom, :email, :mot_de_passe, :telephone, :ville, :code_postal, :adresse, :role_id
      )';
    $statment = $this->pdo->prepare($sql);
    $statment->execute($data);

    $data['user_id'] = $this->pdo->lastInsertId();

    return User::creerEtHydrate($data);
  }

  // Read

  public function afficheUtilisateur()
  {
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
    public function trouveUtilisateurById(int $id) 
    {
      $sql = ('SELECT * FROM user WHERE user_id = :id');

      $statement = $this->pdo->prepare($sql);
      $statement->bindValue(':id', $id, PDO::PARAM_INT);
      $statement ->execute();

      $data = $statement->fetch(PDO::FETCH_ASSOC);

      if ($data === false) {
        return false;
      }
      
      return User::creerEtHydrate($data);
    }

  // By email
    public function trouveUtilisateurByEmail(string $email)
    {
      $sql = ('SELECT * FROM user WHERE email = :email');

      $statement = $this->pdo->prepare($sql);
      $statement->bindValue(':email', $email, PDO::PARAM_STR);
      $statement ->execute();

      $data =  $statement->fetch(PDO::FETCH_ASSOC);

      if ($data === false) {
        return false;
      }

      return User::creerEtHydrate($data);
    }

  // Update

  public function modifieUtilisateur(array $data): void
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
    $statement->execute($data);
    //return User::creerEtHydrate($data);
  }

  public function modifieMdp(array $data): void
  {
    $sql = ('UPDATE user SET
       mot_de_passe = :mot_de_passe
      WHERE user_id = :id');

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

  public function supprimeUtilisateur(int $id):bool
  {
    $sql = ('DELETE FROM user WHERE user_id = :id');

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);

    return $statement->execute();
  }
}