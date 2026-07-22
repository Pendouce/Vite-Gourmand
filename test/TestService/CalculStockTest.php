<?php

namespace App\Test\TestService;

use App\Exceptions\StockDispoException;
use App\Service\CalculStockService;
use PHPUnit\Framework\TestCase;

class CalculStockTest extends TestCase
{
    public function testStockPlat()
    {
      // Arrange
      $nbPlat = 16;
      $nbCommande = 12;

      // Act
      $calculStock = new CalculStockService;
      $result = $calculStock->calculerStockPlat($nbPlat, $nbCommande);

      // Assert
      $this->assertEquals(4, $result);
    }

    public function testStockPlatEgalZero()
    {
      // Arrange
      $nbPlat = 16;
      $nbCommande = 16;

      // Act
      $calculStock = new CalculStockService;
      $result = $calculStock->calculerStockPlat($nbPlat, $nbCommande);

      // Assert
      $this->assertEquals(0, $result);
    }

    public function testExceptionStockPlat()
    {
      // Arrange
      $nbPlat = 16;
      $nbCommande = 19;

      // Expect
      $this->expectException(StockDispoException::class);

      // Act
      $calculStock = new CalculStockService;
      $calculStock->calculerStockPlat($nbPlat, $nbCommande);
    }


    public function testCalculStockMenuDispo()
    {
      $plat = [16, 12, 20];
      $nbMenu = 10;

      $calculStock = new CalculStockService;
      $result = $calculStock->calculerStockMenu($plat, $nbMenu);

      $this->assertEquals(12, $result);
    }

    public function testCalculStockMenuEgalZero()
    {
      $plat = [16, 12, 20];
      $nbMenu = 12;

      $calculStock = new CalculStockService;
      $result = $calculStock->calculerStockMenu($plat, $nbMenu);

      $this->assertEquals(12, $result);
    }

    public function testExceptionStockMenu()
    {
      $plat = [16, 12, 20];
      $nbMenu = 16;

      $this->expectException(StockDispoException::class);

      $calculStock = new CalculStockService;
      $calculStock->calculerStockMenu($plat, $nbMenu);
    }

}