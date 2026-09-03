<?php

namespace App\Test\TestService;

use PHPUnit\Framework\TestCase;
use App\Service\UploadService;
use Exception;
use Override;

class UploadServiceTest extends TestCase
{
  private const CHEMIN_TEST = __DIR__ . '/../..';
  private string $copie;

 #[Override]
  protected function setUp(): void
  {
    $original = self::CHEMIN_TEST . "/public/upload/equipe/pexels-vitaliy-haiduk-326720599-16990994.jpg";
    $this->copie = sys_get_temp_dir() . '/test-upload.jpg';
    copy($original, $this->copie);
  }

  public function testValiderImagSucces()
  {
    $file = [
      'tmp_name' => $this->copie/* chemin vers une image existante */,
      'name' => 'photo-equipier.jpg',
      'size' => filesize($this->copie),
    ];

    $validerImage = new UploadService();
    $extension = $validerImage->validerImage($file);

    $this->assertSame('jpg', $extension);
  }

  public function testMauvaisextension()
  {
    $this->expectException(Exception::class);
    $this->expectExceptionMessageIs("Extention invalide");
    
    $file = [
      'tmp_name' => $this->copie/* chemin vers une image existante */,
      'name' => 'photo-equipier.txt',
      'size' => filesize($this->copie),
    ];

    $validerImage = new UploadService();
    $validerImage->validerImage($file);
  }

  public function testTailleTropGrande()
  {
    $this->expectException(Exception::class);
    $this->expectExceptionMessageIs("L'image est trop volumineuse");
    
    $file = [
      'tmp_name' => $this->copie/* chemin vers une image existante */,
      'name' => 'photo-equipier.jpg',
      'size' => 600000000000000000,
    ];

    $validerImage = new UploadService();
    $validerImage->validerImage($file);
  }

  public function testMauvaisMime()
  {
    $this->expectException(Exception::class);
    $this->expectExceptionMessageIs("Les extentions ne correspondent pas");
    
    $file = [
      'tmp_name' => $this->copie/* chemin vers une image existante */,
      'name' => 'photo-equipier.png',
      'size' => filesize($this->copie),
    ];

    $validerImage = new UploadService();
    $validerImage->validerImage($file);
  }
}