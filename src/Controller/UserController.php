<?php

namespace App\Controller;

use App\Exceptions\EmailException;
use App\Factory\ContainerId;
use App\Service\UserService;
use Exception;

class UserController extends Controller
{
  private UserService $userService;

  public function __construct() {
    parent::__construct();
    $this->userService = ContainerId::getUserService();
  }

  public function inscription()
  {

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();
      $data = [
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'mot_de_passe' => $_POST['mot_de_passe'],
        'mdpConfirm' => $_POST['mdpConfirm'],
        'telephone' => $_POST['telephone'],
        'ville' => $_POST['ville'] ?? null,
        'code_postal' => $_POST['code_postal'] ?? null,
        'adresse' => $_POST['adresse'] ?? null,
      ];
      $data = $this->nettoyerDonnees($data);

      try{
        //Appel du service
        $nouvelUtilisateur = $this->userService->inscrirUtilisateur($data);
        $nouvelUtilisateurId = $nouvelUtilisateur->getUserId();
        $nouvelUtilisateurRole = $nouvelUtilisateur->getRoleId();

        $_SESSION['user_id'] = $nouvelUtilisateurId;
        $_SESSION['role_id'] = $nouvelUtilisateurRole;

        header('location: /');
        exit;
      }catch(Exception $e){
      $message = $e->getMessage();
      $this->render('pages/client/inscription', ['erreur' => $message, 'titre' => 'inscription']);
      }
    }else {
      $this->render('pages/client/inscription', ['titre' => 'inscription']);
    }
  }

  public function inscriptionEmploye()
  {
    $this->accesPage([ROLE_ADMIN]);
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $this->checkCsrfToken();
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

        $role = $_SESSION['role_id'];

        try {
          $this->userService->creationCompteEmploye($data, $role);

          $_SESSION['succes'] = 'Inscription reussi !';

          header('location: /gestionEmployes');
          //header('location: /gestionEmploye');
          exit;
        } catch (Exception $e) {
          $message = $e->getMessage();
          $this->render('pages/admin/inscriptionEmploye', ['erreur' => $message, 'titre' => 'inscription employe']);
        }
      }else{
        $this->render('pages/admin/inscriptionEmploye', ['titre' => 'inscription employe']);
      }
  }

  public function connexion()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();
      $data = [
        'email' => $_POST['email'],
        'mot_de_passe' => $_POST['mot_de_passe'],
      ];
      $data = $this->nettoyerDonnees($data);
      try {
        // appel du service
        $connecte = $this->userService->connexion($data['email'], $data['mot_de_passe']);
        $nouvelUtilisateurId = $connecte->getUserId();
        $nouvelUtilisateurRole = $connecte->getRoleId();
        $_SESSION['user_id'] = $nouvelUtilisateurId;
        $_SESSION['role_id'] = $nouvelUtilisateurRole;
        // Je regenere l'id de session apres connexion
        session_regenerate_id(true);
        header('location: /');
        exit;

      }catch(Exception $e){
        $message = $e->getMessage();
        $this->render('pages/auth/connexion', ['erreur' => $message, 'titre' => 'connexion']);
      }
    } else{
      $this->render('pages/auth/connexion', ['titre' => 'connexion']);
    }
  }

  public function afficheInfos()
  {
    $id = $_SESSION['user_id'];
    $infoUtilisateur = $this->userService->afficheInfo($id);
    $this->render('pages/client/mesInfos', ['infoUtilisateur' => $infoUtilisateur,'titre' => 'mes infos']);
  }
  
  public function afficheInfosEmploye()
  {
    $this->accesPage([ROLE_ADMIN]);
    $id = $_GET['id'];
    $infoEmploye = $this->userService->afficheInfo($id);
    $this->render('pages/admin/detailEmploye', ['infoEmploye' => $infoEmploye, 'titre' => 'info employe']);
  }

  public function afficheEmploye()
  {
    $this->accesPage([ROLE_ADMIN]);
    $listeEmploye = $this->userService->afficheEmploye();
    $this->render('pages/admin/gestionEmployes', ['listeEmploye' => $listeEmploye, 'titre' => 'gestion employe']);
  }

  public function modifierInfos()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    $data = [
      'nom' => $_POST['nom'] ?? null,
      'prenom' => $_POST['prenom'] ?? null,
      'email' => $_POST['email'] ?? null,
      'telephone' => $_POST['telephone'] ?? null,
      'ville' => $_POST['ville'] ?? null,
      'code_postal' => $_POST['code_postal'] ?? null,
      'adresse' => $_POST['adresse'] ?? null,
    ];
    $data = $this->nettoyerDonnees($data);
    $data['id'] = $_SESSION['user_id'];

    try{
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
      $this->checkCsrfToken();
      $data = [
        'ancienMdp' => $_POST['ancienMdp'],
        'mot_de_passe' => $_POST['mot_de_passe'],
        'mdpConfirm' => $_POST['mdpConfirm'],
      ];
      $data = $this->nettoyerDonnees($data);

      $data['id'] = $_SESSION['user_id'];
      try{
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
      $this->render('pages/auth/modificationMdp', ['titre' => 'modification du mot de passe']);
    }
  }

  public function reinitialiserMdp()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();
      $data = [
        'email' => $_POST['email'],
      ];
      $data = $this->nettoyerDonnees($data);

      try{
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
      $this->render('pages/auth/reinitilisationMdp');
    }
  }
    
    public function deconnexion()
    {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('location: /');
        exit;
      }
    $this->checkCsrfToken();
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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    try{
      $role = $_SESSION['role_id'];
      $id = $_SESSION['user_id'];

      if($role === ROLE_UTILISATEUR){
        $this->userService->supprimeCompte($id);
        $this->deconnexion();
      }else{
          $this->render('page/mesInfos');
        }
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /mesInfos');
      exit;
    }
  }

  public function supprimerCompteEmploye()
  {
    $this->accesPage([ROLE_ADMIN]);
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      header('location: /');
      exit;
    }
    $this->checkCsrfToken();

    try{
      $role = $_SESSION['role_id'];
      $id = $_POST['id'];
      if($role === ROLE_ADMIN){
          $this->userService->supprimeCompte($id);
          $_SESSION['succes'] = "Compte supprimé avec succes";
          header('location: /gestionEmployes');
          exit;
        }else{
          $this->render('page/gestionEmployes');
        }
    }catch(Exception $e){
      $_SESSION['erreur'] = $e->getMessage();
      header('location: /gestionEmployes');
      exit;
    }
  }


}