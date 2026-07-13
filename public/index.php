<?php 

require_once __DIR__ . "/../vendor/autoload.php";

/* if (session_status() === PHP_SESSION_NONE) {
    session_start();
} */
use App\Repository\UserRepository;
use Dotenv\Dotenv;
use App\Routing\Router;

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();


$router = new Router();
$router->GererRequette($_SERVER['REQUEST_URI']);


/* $affiche = new UserRepository();

//var_dump($affiche->afficheUtilisateur());
$aff = $affiche->afficheUtilisateur();

foreach($aff as $utilisateur){
  echo '<pre>';
  print_r($utilisateur);
  echo '</pre>';
};

//var_dump($affiche->afficheUtilisateurById(2));
use App\Controller\UserController;

$us = new UserController;
$us->inscription(); 
*/
