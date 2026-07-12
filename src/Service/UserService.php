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
  Creation d'un compte employé ✅
  Envoyer un mail avec acces
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

  /* public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  } */
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

  // Methode creation d'un compte employe
  public function creationCompteEmploye(string $email, array $data)
  {
    $mdp = $this->genererMdpAleatoire();
    $compteUtilisateur = $this->creationCompte($email, $mdp, $data, self::ROLE_EMPLOYE);
    /* 
      Envoyer le mail de avec acces
    */

      return $compteUtilisateur;
  }

  // Generer un mdp aleatoir initialemment pour la creation de compte employe
  // Voir si je propose un mdp a l'inscription est la modification de mdp 
  private function genererMdpAleatoire()
  {
    $char = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@&(){}-_+%*';
    $mdpGenere = '';
    $max = strlen($char) - 1;

    for($i = 0; $i < 16; $i++){
      $mdpGenere .= $char[random_int(0, $max)];
    }
    return $mdpGenere;
  }

  // Methode hash mdp reutiliser dans plusieurs methode
  private function hashMotDePasse(string $mdp): string
  {
    return password_hash($mdp, PASSWORD_DEFAULT);
  }
}