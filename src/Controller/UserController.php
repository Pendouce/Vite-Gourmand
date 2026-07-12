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
   // session_start();
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
  si exception on reste sur le formulaire catch render->inscription
  cree la session
  envoyer a la vue
  _______________________________
  Creation d'un compte employé 
  Envoyer un mail avec acces
  _______________________________
  Connexion 
  limiter le nombre de tentatives 
   Implémentez un système de verrouillage temporaire après plusieurs tentatives échouées, 
   par exemple, après 5 tentatives, bloquez l'accès pendant 15 minutes. Cela réduit le risque d'attaques par force brute.
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
  ———————————————————————————————
  Deconnexion
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
        'ville' => $_POST['ville'],
        'code_postal' => $_POST['code_postal'],
        'adresse' => $_POST['adresse'],
      ];
      try{
        $data = $this->nettoyerDonnees($data);
        $regex = "^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$";

        // Verification email
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
          throw new EmailException();
        }
      
        // Verification mdp
        // preg_match effectue une recherche de correspondance avec une expression rationnelle standard
        if(!preg_match($regex, $_POST['mot_de_passe'])){
          throw new MotDepasseException('Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial (@$!%*?&)');
        }

        // Verification mdp === mdpConfirm
        if($_POST['mot_de_passe'] !== $_POST['mdpConfirm']){
          throw new MotDepasseException();
        }


        //Appel du service
        $this->userService->inscrirUtilisateur($data['email'], $data['mot_de_passe'], $data);
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

}