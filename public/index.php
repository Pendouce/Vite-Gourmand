<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use App\Routing\Router;

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