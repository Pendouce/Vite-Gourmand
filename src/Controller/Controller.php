<?php

namespace App\Controller;

class Controller
{
  protected function render(string $path, array $params=[]): void
  {
    $filePath = APP_ROOT."/templates/$path.php";

    if(!file_exists($filePath)){
      //Remplacer par une exeption fetaure/gestion d'erreur
      die("Le fichier n'existe pas");
    }else{
      extract($params);
      require_once $filePath;
    }
  }

  public function nettoyerDonnees(array $data): array
  {
    $dataNettoye = array_map('htmlspecialchars', $data);
    return $dataNettoye;
  }

}