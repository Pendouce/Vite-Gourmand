<?php

namespace App\Controller;

use App\Exceptions\PageInexistanteException;
use Exception;

class Controller
{
  public function __construct() {
    session_start();
  }
  protected function render(string $path, array $params=[]): void
  {
    $filePath = APP_ROOT."/templates/$path.php";

    if(!file_exists($filePath)){
      throw new PageInexistanteException($filePath);
    }else{
      extract($params);
      require_once $filePath;
    }
  }

  public function nettoyerDonnees(array $data): array
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