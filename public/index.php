<<<<<<< HEAD
<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;

define('APP_ROOT', dirname(__DIR__));

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();






