<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use App\Routing\Router;


/* use DateTimeImmutable;
//use DateTimeZone; */
define('APP_ROOT', dirname(__DIR__));

define('ADRESSE_VG', '27 Rue des Fauvettes, 33000 Bordeaux');
define('BASE_URL', 'http://localhost');

define('ROLE_UTILISATEUR', 1);
define('ROLE_EMPLOYE', 2);
define('ROLE_ADMIN', 3);

define('STATUT_TRANSMISE', 1);
define('STATUT_ACCEPTEE', 2);
define('STATUT_EN_PREPARATION', 3);
define('STATUT_EN_COUR_LIV', 4);
define('STATUT_LIVREE', 5);
define('STATUT_ATTEND_RETOUR', 6);
define('STATUT_TERMINEE', 7);
define('STATUT_ANNULEE', 8);


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



/* use App\Service\CalculPrixService;
$totalBoisson = [];
//$boissons = [$boisson1[], $boisson2[]];
$boissons = [

$boisson1 = [
  'prix_boisson' => 10,
  'quantite' => 5,
],
$boisson2 = [
  'prix_boisson' => 10,
  'quantite' => 10,
]
];
$calcul = new CalculPrixService();

foreach($boissons as $boisson){
  $totalBoisson[] = $calcul->calculerTotalBoisson($boissons);
}

 var_dump($totalBoisson); */
/*
$presta = [10, 5, 5,10];
$calcul->calculTotalpresta($presta); */

/* $depart = $calcul->geocoderAdresse("10 place de la bourse bordeaux");
$arrive = $calcul->geocoderAdresse(" place gambetta bordeaux");

/* var_dump($depart);
var_dump($arrive); */

//echo $calcul->calculerDistance($depart, $arrive);