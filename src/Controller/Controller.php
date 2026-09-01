<?php

namespace App\Controller;

use App\Exceptions\PageInexistanteException;
use Exception;

class Controller
{
  protected $token;

  public function __construct() {
    session_set_cookie_params([
      'httponly' => true,
      'secure' => false,
      'samesite' => 'Lax',
    ]);
    session_start();
    // Je genere le token dans le constructeur
    $this->token = $this->genererToken();
  }
  protected function render(string $path, array $params=[]): void
  {
    $filePath = APP_ROOT."/templates/$path.php";

    if(!file_exists($filePath)){
      throw new PageInexistanteException($filePath);
    }else{
      // J'injecte automatiquement le token CSRF dans toutes les vues
      // Pour ne pas avoir à le passer manuellement dans chaque contrôleurs
      $params['csrfToken'] = $this->token;
      extract($params);
      require_once $filePath;
    }
  }

  protected function genererToken()
  {
    // Si je n'ai pas de csrf token dans ma session j'en genere un
    if(empty($_SESSION['csrf_token'])){
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
  }

  // Je hash et verifie que le token que je recois est identique a celui que j'ai attribuée
  protected function verifCsrfToken(?string $token)
  {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
      return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
  }

  // Je verifie que la requette post provient bien d'un de mes formulaires
  // Je redirige vers l'acceuil en cas de token invalide
  protected function checkCsrfToken()
  {
    if(!$this->verifCsrfToken($_POST['csrfToken'] ?? null)){
      $_SESSION['erreur'] = "Token invalide";
      header('location: /');
      exit;
    }
  }

  protected function nettoyerDonnees(array $data): array
{
    $dataNettoye = array_map(function($value) 
    {
      if ($value === null) {
        return null;
      }

      if(is_array($value)){
        return array_map(fn($item) => htmlspecialchars($item), $value);
      }
      return htmlspecialchars($value);
    }, $data);
    return $dataNettoye;
}
/*   public function nettoyerDonnees(array $data): array
{
    $dataNettoye = array_map(function($value) 
    {
      return $value === null ? null : htmlspecialchars($value);
    }, $data);
    return $dataNettoye;
} */

  protected function uploadImage(array $file, string $folder)
  {
    $nomTmpImage = $file ['tmp_name'];
    $image = "/upload/".$folder."/".$file['name'];
    $succes = move_uploaded_file($nomTmpImage, APP_ROOT."/public/".$image);

    if (!$succes) {
      throw new Exception("Echec de l'upload de l'image dans le dossier ".$folder);
    }

    return $image;
  }


}