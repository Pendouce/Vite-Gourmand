<?php

namespace App\Controller;

use App\Exceptions\PageInexistanteException;

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
      return $value === null ? null : htmlspecialchars($value);
    }, $data);
    return $dataNettoye;
}


}