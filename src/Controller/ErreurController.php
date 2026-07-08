<?php

namespace App\Controller;

use Throwable;

class ErreurController extends Controller
{
  public function afficheErreur(Throwable $e): void
  {
     if($_ENV["APP_ENV"] === 'dev'){
      $this->render("errors/default", [
      "erreurMsg" => $e->getMessage()
    ]);
    }else{
       $this->render("errors/404", [
        "message" => "La page n'existe pas"
      ]);
    }
  }

}