<?php

namespace App\Controller;

use App\Exceptions\EmailException;
use App\Exceptions\MotDepasseException;
use App\Service\UserService;
use App\Repository\UserRepository;
use Exception;

class UserController extends Controller
{
  private UserService $userService;

  public function __construct() {
    // ici ou index.html ?
    session_start();
    $userRepository = new UserRepository();
    $this->userService = new UserService($userRepository);
  }
  /* 
  Inscription
  recuperer les donnees du formulaire ✅
  nettoyer les donnees ✅
  verfie mdp 8 char min avec maj, chiffre et char spec === mdp confirm ✅
  verifier les champs du formulaire filter_var() avec le filtre FILTER_VALIDATE_EMAIL.✅
  appeller le service✅
  si exception on reste sur le formulaire catch render->inscription✅
  cree la session✅
  envoyer a la vue✅
  _______________________________
  Creation d'un compte employé ✅
  recuperer les donnees du formulaire ✅
  nettoyer les donnees ✅
  verifier les champs du formulaire ✅
  appeller le service✅
  rediriger sur inscription avec message succes✅
  si exception on reste sur le formulaire catch render->inscriptionEmploye + message✅

  _______________________________
  Connexion ✅
  recuperer les donnees du formulaire 
  nettoyer les donnees 
  verifier les champs du formulaire 
  appeller le service
  lancer la session
  redirider vers acceuil✅
    ____________________
  limiter le nombre de tentatives 
   Implémentez un système de verrouillage temporaire après plusieurs tentatives échouées, 
   par exemple, après 5 tentatives, bloquez l'accès pendant 15 minutes. Cela réduit le risque d'attaques par force brute.
    ____________________
  si exception on reste sur le formulaire catch render->connexion + message

  _______________________________
  Afficher les infos utilisateur ✅
  appeller le service
  envoyer a la vue

  _______________________________
  Modifier les infos perso ✅
  recuperer les donnees du formulaire 
  nettoyer les donnees 
  verifier les champs du formulaire 
  appeller le service
  rediriger sur infos perso avec message succes

  _______________________________
  Modifier le mdp✅
  recuperer les donnees du formulaire 
  nettoyer les donnees 
  verfie mdp 8 char min avec maj, chiffre et char spec === mdp confirm 
  appeller le service
  rediriger sur infos perso avec message succes

  _______________________________
  Supression compte✅
  appeller le service
  detruire la session
  _______________________________
  Supression compte employé ✅
  appeller le service
  ———————————————————————————————
  Deconnexion✅
  session destroy

  */

  public function inscription()
  {

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'mot_de_passe' => $_POST['mot_de_passe'],
        'telephone' => $_POST['telephone'],
        'ville' => $_POST['ville'] ?? null,
        'code_postal' => $_POST['code_postal'] ?? null,
        'adresse' => $_POST['adresse'] ?? null,
      ];
      try{
        $data = $this->nettoyerDonnees($data);
        $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

        // Verification email
        if($data['email'])$this->verifEmail($data['email']);
      
        // Verification mdp
        // preg_match effectue une recherche de correspondance avec une expression rationnelle standard
        if(!preg_match($regex, $_POST['mot_de_passe'])){
          throw new MotDepasseException('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&)');
        }

        // Verification mdp === mdpConfirm
       /*  if($_POST['mot_de_passe'] !== $_POST['mdpConfirm']){
          throw new MotDepasseException();
        } */
          $this->verifMdp($data['mot_de_passe'], $_POST['mdpConfirm']);


        //Appel du service
        $nouvelUtilisateur = $this->userService->inscrirUtilisateur($data['email'], $data['mot_de_passe'], $data);
        $nouvelUtilisateurId = $nouvelUtilisateur->getUserId();
        $nouvelUtilisateurRole = $nouvelUtilisateur->getRoleId();

        $_SESSION['user_id'] = $nouvelUtilisateurId;
        $_SESSION['role_id'] = $nouvelUtilisateurRole;

        //$this->render('page/acceuil');
        header('location: /');
        exit;
      }catch(Exception $e){
      $message = $e->getMessage();
      $this->render('page/inscription', ['erreur' => $message]);
      }
    }else {
      $this->render('page/inscription');
    }

 /*      $data = [
        'nom' => 'Boug',
        'prenom' => 'Hella',
        'email' => 'fj@pk.cb',
        'mdp' => 'ehe',
        'telephone' => '01233455',
        'ville' => 'Valenton',
        'codePostal' => '94460',
        'adresse' => '3 rtr erjker',
      ];
      $dataNettoye = $this->nettoyerDonnees($data);
       var_dump($dataNettoye); */
  }

  public function inscriptionEmploye()
  {
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'telephone' => $_POST['telephone'],
        'ville' => $_POST['ville'] ?? null,
        'code_postal' => $_POST['code_postal'] ?? null,
        'adresse' => $_POST['adresse'] ?? null,
      ];
        $data = $this->nettoyerDonnees($data);

        try {
         // Verification email
          if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            throw new EmailException();
          }
          $this->userService->creationCompteEmploye($data['email'], $data);

          $_SESSION['succes'] = 'Inscription reussi !';

          header('location: /inscriptionEmploye');
          //header('location: /gestionEmploye');
          exit;
        } catch (Exception $e) {
          $message = $e->getMessage();
          $this->render('page/inscriptionEmploye', ['erreur' => $message]);
        }
      }else{
        $this->render('page/inscriptionEmploye');
      }
  }

  public function connexion()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $data = [
        'email' => $_POST['email'],
        'mot_de_passe' => $_POST['mot_de_passe'],
      ];
      $data = $this->nettoyerDonnees($data);
      try {
        // Verification email
        if($data['email'])$this->verifEmail($data['email']);

        // appel du service
        $connecte = $this->userService->connexion($data['email'], $data['mot_de_passe']);
        $nouvelUtilisateurId = $connecte->getUserId();
        $nouvelUtilisateurRole = $connecte->getRoleId();
        $_SESSION['user_id'] = $nouvelUtilisateurId;
        $_SESSION['role_id'] = $nouvelUtilisateurRole;
        header('location: /');
        exit;

      }catch(Exception $e){
        $message = $e->getMessage();
        $this->render('page/connexion', ['erreur' => $message]);
      }
    } else{
      $this->render('page/connexion');
    }
  }

  public function afficheInfos()
  {
    $id = $_SESSION['user_id'];
    $infoUtilisateur = $this->userService->afficheInfo($id);
    $this->render('page/mesInfos', ['infoUtilisateur' => $infoUtilisateur]);
  }
  
  public function afficheInfosEmploye()
  {
    $id = $_GET['id'];
    $infoEmploye = $this->userService->afficheInfo($id);
    var_dump($infoEmploye);
    $this->render('page/detailEmploye', ['infoEmploye' => $infoEmploye]);
  }

  public function afficheEmploye()
  {
    $listeEmploye = $this->userService->afficheEmploye();
    $this->render('page/gestionEmploye', ['listeEmploye' => $listeEmploye]);
  }

/*           Modifier les infos perso 
  recuperer les donnees du formulaire 
  nettoyer les donnees 
  verifier les champs du formulaire 
  appeller le service
  rediriger sur infos perso avec message succes */

  public function modifierInfos()
  {
      $data = [
          'nom' => $_POST['nom'] ?? null,
          'prenom' => $_POST['prenom'] ?? null,
          'email' => $_POST['email'] ?? null,
          'telephone' => $_POST['telephone'] ?? null,
          'ville' => $_POST['ville'] ?? null,
          'code_postal' => $_POST['code_postal'] ?? null,
          'adresse' => $_POST['adresse'] ?? null,
        ];
        try{
        $data = $this->nettoyerDonnees($data);
        if($data['email'])$this->verifEmail($data['email']);
        $data['id'] = $_SESSION['user_id'];
        $this->userService->modifieInfo($data);
        $_SESSION['succes'] = 'Information personnel modifier !';
        header('location: /mesInfos');
        exit;

       }catch(Exception $e){
        $message = $e->getMessage();
        $_SESSION['erreur'] = $message;
        header('location: /mesInfos');
        exit;
      }
  }

  public function modifierMdp(){
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $data = [
        'ancienMdp' => $_POST['ancienMdp'],
        'mot_de_passe' => $_POST['mot_de_passe'],
      ];
      $data = $this->nettoyerDonnees($data);
      try{
          $this->verifMdp($data['mot_de_passe'], $_POST['mdpConfirm']);
          $data['id'] = $_SESSION['user_id'];
          $this->userService->modifieMdp($data['ancienMdp'], $data['mot_de_passe'], $data['id'], $data);
          $_SESSION['succes'] = 'Mot de passe modifié';
          header('location: /mesInfos');
          exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /modificationMotDePasse');
        exit;
      }
    }else{
      $this->render('page/modificationMdp');
    }
  }

  public function reinitialiserMdp()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $data = [
        'email' => $_POST['email'],
      ];
      try{
        $data = $this->nettoyerDonnees($data);
        $this->verifEmail($data['email']);
        $this->userService->reinitialiseMdp($data);
        $_SESSION['succes'] = 'Votre mot de passe été reinitialiser votre nouveaux mot de passe vous a ete envoyé par mail';
        header('location: /connexion');
        exit;
      }catch(Exception $e){
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /reinitilisationMdp');
        exit;
      }

    }else{
      $this->render('page/reinitilisationMdp');
    }
  }
    // Si role = utilisateur supprime , deconnecte, redirige acceuil
    // Si role = utilisateur supprime , redirige acceuil

    public function deconnexion()
    {
      $_SESSION = [];

      if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]);
      }
      session_destroy();
      header('location: /');
      exit;
    }

  public function supprimerCompteUtilisateur()
  {
    try{
      $role = $_SESSION['role_id'];
      $id = $_SESSION['user_id'];

      if($role === 1){
        $this->userService->supprimeCompte($id);
        $this->deconnexion();
        $_SESSION['succes'] = "Compte supprimé avec succes";
        header('location: /');
        exit;
      }
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /mesInfos');
      exit;
    }
  }

  public function supprimerCompteEmploye()
  {
    try{
      $role = $_SESSION['role_id'];
      $id = $_GET['id'];
      if($role === 3){
          $this->userService->supprimeCompte($id);
          $_SESSION['succes'] = "Compte supprimé avec succes";
          header('location: /gestionEmployes');
          exit;
        }else{
          $this->render('page/gestionEmploye');
        }
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /gestionEmployes');
      exit;
    }
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
        throw new MotDepasseException();
      }
  }
}