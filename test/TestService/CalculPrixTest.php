<?php

namespace App\Test\TestService;

use App\Service\CalculPrixService;
use Exception;
use Override;
use PHPUnit\Framework\TestCase;

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

  public function testcalculTotalCommandeSansRemiseAvecBoisson()
  {

    $menuCommande = [
      [
        'prix_personne' => 50.0, 
        'nombre_personne_min' => 10, 
        'nb_personne_menu' => 12
      ]
    ];

    $boissonCommande = [
      [
        'prix_boisson' =>10,
        'quantite' => 3
      ]
    ];

    $total = $this->calculPrixService->calculTotalCommande(
      [],           // presta vide
      $menuCommande,
      $boissonCommande,
      'adresse traiteur',
      'adresse client'
    );

    $this->assertEquals(725.60, $total);
  }

  public function testcalculTotalCommandeAvecRemiseEtPresta()
  {
    $menuCommande = [
      [
        'prix_personne' => 50.0,      // prixMenu
        'nombre_personne_min' => 10,  // nbPersonnesMin
        'nb_personne_menu' => 15           // nbPersonnes (remise)
      ]
    ];

    $boissonCommande = [
      [
        'prix_boisson' =>10,
        'quantite' => 3
      ]
    ];

    $prixPresta = [
      ['prix_presta' => 12, 'seuil' => 5, 'supplement' => 20],
      ['prix_presta' => 20, 'seuil' => 5, 'supplement' => 20],
      ['prix_presta' => 40, 'seuil' => 5, 'supplement' => 20]
    ];

     $total = $this->calculPrixService->calculTotalCommande(
        $prixPresta,
        $menuCommande,
        $boissonCommande,
        'adresse traiteur',
        'adresse client'
    );

    $this->assertEquals(992.60, $total);
  }

  public function testcalculTotalCommandeClientBordeaux()
  {
  $menuCommande = [
      [
        'prix_personne' => 50.0,      // prixMenu
        'nombre_personne_min' => 10,  // nbPersonnesMin
        'nb_personne_menu' => 15           // nbPersonnes (remise)
      ]
    ];

    $boissonCommande = [
      [
        'prix_boisson' =>10,
        'quantite' => 3
      ]
    ];

    $prixPresta = [
      ['prix_presta' => 12, 'seuil' => 5, 'supplement' => 20],
      ['prix_presta' => 20, 'seuil' => 5, 'supplement' => 20],
      ['prix_presta' => 40, 'seuil' => 5, 'supplement' => 20]
    ];

     $total = $this->calculPrixService->calculTotalCommande(
        $prixPresta,     // prix prestas
        $menuCommande,
        $boissonCommande,
        'adresse traiteur',
        'adresse client bordeaux'
    );

    $this->assertEquals(902.00, $total);
  }

  public function testcalculTotalCommandeSansBoisson()
  {
  $menuCommande = [
      [
        'prix_personne' => 50.0,      // prixMenu
        'nombre_personne_min' => 10,  // nbPersonnesMin
        'nb_personne_menu' => 15           // nbPersonnes (remise)
      ]
    ];

    $prixPresta = [
      ['prix_presta' => 12, 'seuil' => 5, 'supplement' => 20],
      ['prix_presta' => 20, 'seuil' => 5, 'supplement' => 20],
      ['prix_presta' => 40, 'seuil' => 5, 'supplement' => 20]
    ];

    $boissonCommande = [];

     $total = $this->calculPrixService->calculTotalCommande(
        $prixPresta,
        $menuCommande,
        $boissonCommande,
        'adresse traiteur',
        'adresse client bordeaux'
    );

    $this->assertEquals(872.00, $total);
  }

  public function testPrestaSousLeSeuil(): void
  {
    $service = $this->createPartialMock(CalculPrixService::class, ['calculerNbPersonneCommande']);
    $service->method('calculerNbPersonneCommande')->willReturn(3);

    $this->expectException(Exception::class);
    $service->calculerTotalpresta([], [['prix_presta' => 20.0, 'seuil' => 5, 'supplement' => 20]]);
  }


}