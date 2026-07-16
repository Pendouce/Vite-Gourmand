<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{

  private PHPMailer $mail;

  public function __construct() {
    $this->mail = new PHPMailer(true);
    // Configuration SMTP
    $this->mail->isSMTP();
    $this->mail->Host = $_ENV['HOST_MAILER']; // Votre serveur SMTP
    $this->mail->SMTPAuth = true;
    $this->mail->Username = $_ENV['USERNAME_MAILER']; // Votre nom d'utilisateur Mailtrap
    $this->mail->Password = $_ENV['PASSWORD_MAILER']; // Votre mot de passe Mailtrap
    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $this->mail->Port = (int) $_ENV['PORT_MAILER'];
    $this->mail->setFrom('contact@viteetgourmand.com', 'Vite et Gourmand');
    $this->mail->isHTML(true); // Définir le format d'email en format html
    $this->mail->CharSet = 'UTF-8';

  }

  public function envoyer(string $email, string $objet, string $contenu): bool
  {
    try{

    $this->mail->clearAddresses();
    $this->mail->clearAttachments();

      // Paramètres du destinataire
      $this->mail->addAddress($email);
      
      // Envoi d'email en texte brut
      $this->mail->Subject = $objet;
      $this->mail->Body = $contenu;

      // Envoyer l'email
      $this->mail->send();
      return true;
    }catch (Exception $e) {
      if($_ENV['APP_ENV'] === 'dev'){
        echo $e->getMessage();
      }
      return false;
    }
  }

  public function recupererHtml(string $pages, array $data = [])
  {
    extract($data);
    $chemin = APP_ROOT . '/templates/mails/'.$pages.'.php';

    // Demarre un tampon qui garde le html en memoire
    ob_start();

    require $chemin;

    // Recupere le html qui etait en memoire sous forme de chaine de caractere et vide le tampon
    return ob_get_clean();
  }
}