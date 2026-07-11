<?php

namespace App\Service;

use App\Exceptions\EmailExistantException;
use App\Repository\UserRepository;

/* 
  Inscription✅
  verifier si l'email existe si oui erreur
  hasher le mdp
  attribuer le role utilisateur
  tout est ok créer le compte

  Envoyé un mail de confirmation
  _______________________________
  Creation d'un compte employé
  _______________________________
  Connexion
  _______________________________
  Afficher les infos utilisateur
  _______________________________
  Modifier les infos perso
  _______________________________
  Modifier le mdp
  _______________________________
  Supression compte
  _______________________________
  Supression compte employé
  _______________________________
  Methode hash mdp ✅
*/

class UserService
{
  const ROLE_UTILISATEUR = 1;
  const ROLE_EMPLOYE = 2;
  const ROLE_ADMIN = 3;

  private UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }
  // Methode globale creation de compte
  private function creationCompte(string $email, string $mdp, array $data, int $role)
  {
    $verifEmail = $this->userRepository->afficheUtilisateurByEmail($email);
    if($verifEmail !== false){
      throw new EmailExistantException();
    }

    $mdpHash = $this->hashMotDePasse($mdp);

    $data['mot_de_passe'] = $mdpHash;
    $data['role_id'] = $role;

    $nvlUtilisateur = $this->userRepository->creeUtilisateur($data);

    return $nvlUtilisateur;
  }
  
  // Methode creation d'un compte utilisateur
  public function inscrirUtilisateur(string $email, string $mdp, array $data)
  {
    $compteUtilisateur = $this->creationCompte($email, $mdp, $data, self::ROLE_UTILISATEUR);
    /* 
      Envoyer le mail de confirmation
    */

      return $compteUtilisateur;
  }

  // Methode hash mdp reutiliser dans plusieurs methode
  private function hashMotDePasse(string $mdp): string
  {
    return password_hash($mdp, PASSWORD_DEFAULT);
  }
}