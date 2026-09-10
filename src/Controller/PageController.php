<?php

namespace App\Controller;

use App\Exceptions\EmailException;
use App\Factory\ContainerId;
use App\Service\MailService;
use Exception;

/* 
  Controlleur de page "static" a propos, mentions legal ...
*/

class PageController extends Controller{
private MailService $mailService;

  public function __construct() {
    parent::__construct();
    $this->mailService = ContainerId::getMailService();
  }

  public function acceuil(): void
  {
    $this->render("pages/acceuil", ['titre' => 'acceuil']);
  }

  public function contact()
  {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $this->checkCsrfToken();
      $data = [
        'titre' => $_POST['titre'],
        'nom' => $_POST['nom'],
        'prenom' => $_POST['prenom'],
        'email' => $_POST['email'],
        'description' => $_POST['description'],
      ];
      $data = $this->nettoyerDonnees($data);

      try {
        if(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
          throw new EmailException();
        }

        $html = $this->mailService->recupererHtml('contactMail', 
        ['nom' => $data['nom'],
          'prenom' => $data['prenom'],
          'email' => $data['email'],
          'description' => $data['description'],
        ]);
        $objet = $data['titre'];
        file_put_contents(APP_ROOT . '/public/afficheMail.php', $html);
        //$this->mailService->envoyer('contact@viteetgourmand.com', $objet, $html);

        $_SESSION['succes'] = 'Message envoyé avec succès';
        header('location: /contact');
        exit;
      } catch(Exception $e) {
        $_SESSION['erreur'] = $e->getMessage();
        header('location: /contact');
        exit;
      }
    } else {
      $this->render('pages/client/contact', ['titre' => 'contact']);
    }
  }

}