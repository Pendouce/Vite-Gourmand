<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\EmailException;
use App\Exceptions\EmailExistantException;
use App\Exceptions\EmailMdpException;
use App\Exceptions\MotDepasseException;
use App\Exceptions\UtilisateurIntrouvableException;
use App\Repository\UserRepository;

class UserService
{
  private UserRepository $userRepository;
  private MailService $mailService;

  public function __construct(UserRepository $userRepository, MailService $mailService)
  {
    $this->userRepository = $userRepository;
    $this->mailService = $mailService;
  } 

  // Methode globale creation de compte
  private function creationCompte(array $data, int $role)
  {
    // Verification email
    $this->verifEmail($data['email']);

    $verifEmail = $this->userRepository->trouveUtilisateurByEmail($data['email']);
    if($verifEmail !== false){
      throw new EmailExistantException();
    }

    $mdpHash = $this->hashMotDePasse($data['mot_de_passe']);
    $data['mot_de_passe'] = $mdpHash;
   
    $data['role_id'] = $role;

    $nvlUtilisateur = $this->userRepository->creeUtilisateur($data);

    return $nvlUtilisateur;
  }
  
  // Methode creation d'un compte utilisateur
  public function inscrirUtilisateur(array $data)
  {
      $this->verifMdp($data['mot_de_passe'], $data['mdpConfirm']);
      unset($data['mdpConfirm']);

    $compteUtilisateur = $this->creationCompte($data, ROLE_UTILISATEUR);
  
    //Envoye du mail de confirmation
    $html = $this->mailService->recupererHtml('inscriptionMail', ['prenom' => $data['prenom']]);
    $objet = 'Bienvenue chez vite et Gourmand';
    file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
    //$this->mailService->envoyer($data['email'], $objet, $html);

    return $compteUtilisateur;
  }

  // Methode creation d'un compte employe
  public function creationCompteEmploye(array $data, int $role)
  {
    if($role !== ROLE_ADMIN) throw new AccesRefuseException();

    // Generation mdp
    $mdp = $this->genererMdpAleatoire();
    $data['mot_de_passe'] = $mdp;
    var_dump($data);

    $compteUtilisateur = $this->creationCompte($data, ROLE_EMPLOYE);
    
    // Envoie mail avec nouveau mdp
    $html = $this->mailService->recupererHtml('inscriptionEmployeMail', ['prenom' => $data['prenom'], 'mdp' => $mdp]);
    $objet = 'Identifiant Employe';
    file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
    //$this->mailService->envoyer($data['email'], $objet, $html);
    var_dump($mdp);
      return $compteUtilisateur;
  }

  // Connexion
  public function connexion(string $email, string $mdp)
  {
    $verifEmail = $this->userRepository->trouveUtilisateurByEmail($email);
    if($verifEmail === false){
      throw new EmailMdpException();
    }
     // Verification email
    $this->verifEmail($email);

    $verifMdp = password_verify($mdp, $verifEmail->getMotDePasse());
    if(!$verifMdp){
      throw new EmailMdpException();
    }
    return $verifEmail;
  }

  // Afficher les infos utilisateur
  public function afficheInfo(int $id)
  {
    $utilisateur = $this->userRepository->trouveUtilisateurById($id);
    if($utilisateur === false){
      throw new UtilisateurIntrouvableException();
    }

    return $utilisateur;
  }

  public function afficheEmploye()
  {
    return $this->userRepository->trouveEmployeByRole(ROLE_EMPLOYE);
  }

  // Modifier les infos perso
  public function modifieInfo( array $data)
  {
    $infoActuel = $this->afficheInfo($data['id']);
    $donneesActuel = $infoActuel->deshydrate();
    if($data['email'])$this->verifEmail($data['email']);
    // Parcourt chaques valeur du tableau et ne garde que celles qui ne sont pas null
    $data = array_filter($data, fn($value) => $value !== null);
    $nouvellesDonnees = array_merge($donneesActuel, $data);

    unset($nouvellesDonnees['user_id']);
    unset($nouvellesDonnees['role_id']);
    $this->userRepository->modifieUtilisateur($nouvellesDonnees);
  }

  // Reinitialiser le mdp
  public function reinitialiseMdp(array $data)
  {
    $verifEmail = $this->userRepository->trouveUtilisateurByEmail($data['email']);
    if(!$verifEmail){
      throw new UtilisateurIntrouvableException();
    }
    $this->verifEmail($data['email']);

    $genereMdp = $this->genererMdpAleatoire();
    $nouveauMdp = $this->hashMotDePasse($genereMdp);
    $data = $verifEmail->deshydrate();
    $data['mot_de_passe'] = $nouveauMdp;
    $data['id'] = $verifEmail->getUserId();
    $this->modifieInfo($data);

    // Envoie mail avec nouveau mdp
    $html = $this->mailService->recupererHtml('reinitialisationMdpMail', ['prenom' => $data['prenom'], 'nouveauMdp' => $genereMdp]);
    $objet = 'Reinitialisation de mot de passe';
    file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
    //$this->mailService->envoyer($data['email'], $objet, $html);
  }

  // Modifier le mdp
  public function modifieMdp(string $ancienMdp, string $nouveauMdp,int $id, array $data)
  {
    $utilisateur = $this->userRepository->trouveUtilisateurById($id);
    if(!$utilisateur){
      throw new UtilisateurIntrouvableException();
    }

    $verifMdp = password_verify($ancienMdp, $utilisateur->getMotDePasse());
    if(!$verifMdp){
      throw new MotDepasseException();
    }
    $this->verifMdp($data['mot_de_passe'], $data['mdpConfirm']);

    $nouveauMdp = $this->hashMotDePasse($nouveauMdp);
    $data['mot_de_passe'] = $nouveauMdp;
    unset($data['ancienMdp']);
    unset($data['mdpConfirm']);

    $this->userRepository->modifieMdp($data);
    return $data;
  }

  // Supression compte
  public function supprimeCompte(int $id)
  {
    $this->afficheInfo($id);
    $compte = $this->userRepository->supprimeUtilisateur($id);

    return $compte;
  }

  private function verifEmail(string $email)
  {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      throw new EmailException();
    }
  }

  private function verifMdp(string $mdp, string $mdpConfirm){
    $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

    // preg_match effectue une recherche de correspondance avec une expression rationnelle standard
    if(!preg_match($regex, $mdp)){
      throw new MotDepasseException('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&)');
    }

      // Verification mdp === mdpConfirm
      if($mdp !== $mdpConfirm){
        throw new MotDepasseException('Le mot de passes et le mot de passe de confirmation doivent êtres identique');
      }
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