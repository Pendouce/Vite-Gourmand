<?php 

require_once __DIR__ . "/../vendor/autoload.php";

/* if (session_status() === PHP_SESSION_NONE) {
    session_start();
} */

use Dotenv\Dotenv;
use App\Routing\Router;

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();


$router = new Router();
$router->GererRequette($_SERVER['REQUEST_URI']);

use App\Service\CalculPrixService;

$calcul = new CalculPrixService();
$presta = [10, 5, 5,10];
$calcul->calculTotalpresta($presta);

/* $depart = $calcul->geocoderAdresse("10 place de la bourse bordeaux");
$arrive = $calcul->geocoderAdresse(" place gambetta bordeaux");

/* var_dump($depart);
var_dump($arrive); */

//echo $calcul->calculerDistance($depart, $arrive);