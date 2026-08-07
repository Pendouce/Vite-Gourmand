<?php

namespace App\Test\TestService;

use PHPUnit\Framework\TestCase;
use App\Service\CalculPrixService;
use Override;

class CalculPrixTest extends TestCase
{
  private CalculPrixService $calculPrixService;

#[Override]
	protected function setUp(): void
  {
    // Mock de la methode geocoderAdresse pour qu'elle ne depande pas de l'api
    $this->calculPrixService = $this->getMockBuilder(CalculPrixService::class)->
    onlyMethods(['geocoderAdresse'])->
    getMock();
    // Configuration de geocoderAdresse pour qu'elle retourne une valeur
    // differente selon l'adresse grace a une callback
    $this->calculPrixService->method('geocoderAdresse')->
    willReturnCallback(function (string $adresse) {
      if ($adresse === 'adresse traiteur') {
        return ['coordonees' => [-0.5792, 44.8378], 'ville' => 'Bordeaux'];
      }
      if ($adresse === 'adresse client bordeaux') {
        return ['coordonees' => [-0.5800, 44.8400], 'ville' => 'Bordeaux'];
      }
      return ['coordonees' => [-1.1520, 46.1591], 'ville' => 'La Rochelle'];
    });
  }

  public function testcalculTotalCommandeSansRemise()
  {
    $total = $this->calculPrixService->calculTotalCommande(
      [],           // presta vide
      50.0,         // prixMenu
      10,           // nbPersonnesMin
      12,           // nbPersonnes (pas de remise)
      'adresse traiteur',
      'adresse client'
    );

    $this->assertEquals(695.60, $total);
  }

  public function testcalculTotalCommandeAvecRemiseEtPresta()
  {
     $total = $this->calculPrixService->calculTotalCommande(
        [12, 20, 40],     // prix prestas
        50.0,             // prixMenu
        10,               // nbPersonnesMin
        15,               // nbPersonnes (remise)
        'adresse traiteur',
        'adresse client'
    );

    $this->assertEquals(842.60, $total);
  }

  public function testcalculTotalCommandeClientBordeaux()
  {
     $total = $this->calculPrixService->calculTotalCommande(
        [12, 20, 40],     // prix prestas
        50.0,             // prixMenu
        10,               // nbPersonnesMin
        15,               // nbPersonnes (remise)
        'adresse traiteur',
        'adresse client bordeaux'
    );

    $this->assertEquals(752.00, $total);
  }

}