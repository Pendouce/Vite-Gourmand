<?php

namespace App\Repository;
use PDO;
use App\db\Mysql;

class Repository
{
  protected PDO $pdo;
  
  public function __construct() {
    $mysql = Mysql::getInstance();
    $this->pdo = $mysql->getPDO();
  }
}