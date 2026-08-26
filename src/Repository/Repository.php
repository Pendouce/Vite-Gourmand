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

  // Marque le début d’une transaction. Toutes les opérations suivantes en font partie.
  public function beginTransaction(): void
  {
    $this->pdo->beginTransaction();
  }

  // Valide la transaction et rend toutes les modifications permanentes.
  public function commit(): void
  {
    $this->pdo->commit();
  }

  // Annule toutes les modifications effectuées pendant la transaction et restaure l’état précédent en cas d’erreur.
  public function rollBack(): void
  {
    $this->pdo->rollBack();
  }
}