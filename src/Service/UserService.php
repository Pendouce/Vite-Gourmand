<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;

/* 
  Inscription

  verifier si l'email existe si oui erreur
  hasher le mdp
  attribuer le role utilisateur
  tout est ok créer le compte
*/

class UserService
{
  const ROLE_UTILISATEUR = 1;

  private UserRepository $userRepository;

  public function __construct(UserRepository $userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function inscrirUtilisateur(string $email, string $mdp, array $data)
  {
    $verifEmail = $this->userRepository->afficheUtilisateurByEmail($email);
    if($verifEmail != false){
      throw new Exception('Cette email est deja utilisé');
    }

    $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

    $data['mot_de_passe'] = $mdpHash;
    $data['role_id'] = self::ROLE_UTILISATEUR;

    return $this->userRepository->creeUtilisateur($data);
  }
}