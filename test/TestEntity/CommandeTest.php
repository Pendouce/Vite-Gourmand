<?php

namespace App\Test\TestEntity;



use App\Entity\Commande;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class CommandeTest extends TestCase
{
  public function testCreerEtHydrate(): void
  {
    // Arrange
    $data = [
      'commande_id' => '2',
      'nb_commande' => '788', 
      'date_commande' => '2026-05-01', 
      'nb_personne' => '2', 
      'lieu_livraison' => 'Bordeaux', 
      'prix_livraison' => '5', 
      'prix_total' => '22.90', 
      'user_id' => '4', 
      'status_id' => '3', 
    ];

    // Act
    $commande = Commande::creerEtHydrate($data);

    // Assert
    // Je verifie que $commande est bien une instance de Commande
    $this->assertInstanceOf(Commande::class, $commande);
    // Je verifie que mes attriuts on bien etes hydratees
    $this->assertEquals(788, $commande->getNbCommande());
    $this->assertEquals('Bordeaux', $commande->getLieuLivraison());
    // Je verifie que datePrestation retourne bien un objet dateTime et pas une string
    $this->assertInstanceOf(DateTimeImmutable::class, $commande->getDateCommande());
  }

}

