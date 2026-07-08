<?php 

require_once __DIR__ . "/../vendor/autoload.php";

use Dotenv\Dotenv;
use App\db\Mysql;

$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

$instance = Mysql::getInstance();
$instance->getPDO();