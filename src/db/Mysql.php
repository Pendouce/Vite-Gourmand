<?php

namespace App\db;


use PDO;




class Mysql{

  private string $dbHost;
  private string $dbName;
  private string $dbUser;
  private string $dbPassword;

  private ?PDO $pdo = null;
  private static ?self $_instance = null;

  private function __construct()
  {
    $this->dbHost = $_ENV["DB_HOST"];
    $this->dbName = $_ENV["MYSQL_DATABASE"];
    $this->dbUser = $_ENV["MYSQL_USER"];
    $this->dbPassword = $_ENV["MYSQL_PASSWORD"];
  }

  public static function getInstance():self
  {
    if(is_null(self::$_instance)){
      self::$_instance = new Mysql();
    }
    return self::$_instance;
  }

  public function getPDO():PDO
  {
    if(is_null($this->pdo)){
      $this->pdo = new PDO("mysql:host={$this->dbHost};dbname={$this->dbName}", $this->dbUser, $this->dbPassword);
    }
    return $this->pdo;
  }
}




