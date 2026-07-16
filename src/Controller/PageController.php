<?php

namespace App\Controller;

/* 
  Controlleur de page "static" a propos, mentions legal ...
*/

class PageController extends Controller{

  public function acceuil(): void
  {
    $this->render("pages/acceuil");
  }

}