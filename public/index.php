<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use App\Repository\UserRepository;
use Dotenv\Dotenv;
use App\Routing\Router;

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();


$router = new Router();
$router->GererRequette($_SERVER['REQUEST_URI']);


$affiche = new UserRepository();

//var_dump($affiche->afficheUtilisateur());
//var_dump($affiche->afficheUtilisateurById(2));