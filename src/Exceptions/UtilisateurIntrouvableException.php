<?php

namespace App\Exceptions;

use Exception;

class UtilisateurIntrouvableException extends Exception
{
  protected $message = "Utilisateur introuvable";

}