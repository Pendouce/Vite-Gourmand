<?php

namespace App\Service;

use Exception;
use finfo;

class UploadService
{
  public function validerImage(array $file): string
  {
    $typeMime = $this->detectionMime($file);
    $extension = $this->listeBlanche($file);
    $this->coherence($typeMime, $extension);
    $this->limiteTaille($file);
    $this->validationContenu($file);
    $this->reEncoderImage($file, $typeMime);

    return $extension;
  }

  // Détection MIME côté serveur
  private function detectionMime(array $file): string
  {
    // Ouvre tmp_name et lit ses premiers octets, retourne le vrai type MIME (jpeg, png...)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $typeMime = $finfo->file($file['tmp_name']);

    return $typeMime;
  }

  // Extension sur liste blanche
  private function listeBlanche(array $file): string
  {
    $extensionsAutorise = ['jpg', 'jpeg', 'png', 'webp'];
    // J'extrait l'extension du nom de fichier fourni (ex: "photo.JPG" → "jpg")
    $extension = strtolower( pathinfo($file['name'], PATHINFO_EXTENSION));
    // Si l'extension recu ne fait pas partie de celles autorisées je leve une exception
    if (!in_array($extension, $extensionsAutorise)) {
      throw new Exception("Extention invalide");
    }
    return $extension;
  }

  // Cohérence extension / MIME
  private function coherence(string $typeMime, string $extension): bool
  {

    // Table de correspondance entre types MIME réels et extensions de fichier autorisées
    $correspondances = [
      'image/jpeg' => ['jpg', 'jpeg'],
      'image/png' => ['png'],
      'image/webp' => ['webp'],
    ];
    // Je vérifie que l'extension du fichier correspond bien au type MIME détecté
    if (!in_array($extension, $correspondances[$typeMime] ?? [])) {
      throw new Exception("Les extentions ne correspondent pas");
    }
    return true;
  }

  // Limite de taille
  private function limiteTaille(array $file): void
  {
    $tailleMax = 5 * 1024 * 1024; // 5 Mo
    if ($file['size'] > $tailleMax) {
      throw new Exception("L'image est trop volumineuse");
    }
  }

  // Validation du contenu image
  private function validationContenu(array $file): void
  {
    // je verifie que le ficher est bien une image
    if (getimagesize($file['tmp_name']) === false) {
        throw new Exception('Image invalide');
    }
  }

  // Détection de contenu malveillant
  private function reEncoderImage(array $file, string $typeMime): void
  {
    // Je decode l'image et la charge en memoire
    // élimine tout code caché ou métadonnées suspectes
    $image = match($typeMime) {
      'image/jpeg' => imagecreatefromjpeg($file['tmp_name']),
      'image/png' => imagecreatefrompng($file['tmp_name']),
      'image/webp' => imagecreatefromwebp($file['tmp_name']),
      default => throw new Exception('Type non supporté'),
    };

    // Je remplace le tmp_name recu du front par le mien ($image) qui est nettoye
    match($typeMime) {
      'image/jpeg' => imagejpeg($image, $file['tmp_name']),
      'image/png' => imagepng($image, $file['tmp_name']),
      'image/webp' => imagewebp($image, $file['tmp_name']),
    };
  }
}