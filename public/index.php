<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use App\Routing\Router;


/* use DateTimeImmutable;
//use DateTimeZone; */
define('APP_ROOT', dirname(__DIR__));
define('ADRESSE_VG', '27 Rue des Fauvettes, 33000 Bordeaux');

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();


$router = new Router();
$router->GererRequette($_SERVER['REQUEST_URI']);


//echo rand(100, 9999);
//$date = new DateTimeImmutable()->format('Y-m-d H:i:s');
/* $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
$dateRetour = $date->modify('+10 days');
echo $dateRetour->format('Y-m-d H:i:s');
echo $date->format('Y-m-d H:i:s'); */



//use App\Service\CalculPrixService;

/* $calcul = new CalculPrixService();
$presta = [10, 5, 5,10];
$calcul->calculTotalpresta($presta); */

/* $depart = $calcul->geocoderAdresse("10 place de la bourse bordeaux");
$arrive = $calcul->geocoderAdresse(" place gambetta bordeaux");

/* var_dump($depart);
var_dump($arrive); */

//echo $calcul->calculerDistance($depart, $arrive);