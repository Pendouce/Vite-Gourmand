<?php

namespace App\Entity;

use function PHPUnit\Framework\isString;

class Prestation extends Entity
{
  protected ?int $prestationId = null;
  protected ?string $nomPresta = null;
  protected ?float $prixPresta = null;
  protected ?string $descriptionPresta = null;
  protected ?string $imgPresta = null;
  protected ?bool $necessiteRetour = null;
  protected ?bool $prestationActif = null;
  protected ?string $typePrestaId = null;
  protected ?array $contenuPresta = null;

  protected ?string $libelle = null;



  /**
   * Get the value of prestationId
   */
  public function getPrestationId(): ?int
  {
    return $this->prestationId;
  }

  /**
   * Set the value of prestationId
   */
  public function setPrestationId(?int $prestationId): self
  {
    $this->prestationId = $prestationId;

    return $this;
  }

  /**
   * Get the value of nomPresta
   */
  public function getNomPresta(): ?string
  {
    return $this->nomPresta;
  }

  /**
   * Set the value of nomPresta
   */
  public function setNomPresta(?string $nomPresta): self
  {
    $this->nomPresta = $nomPresta;

    return $this;
  }

  /**
   * Get the value of prixPresta
   */
  public function getPrixPresta(): ?float
  {
    return $this->prixPresta;
  }

  /**
   * Set the value of prixPresta
   */
  public function setPrixPresta(?float $prixPresta): self
  {
    $this->prixPresta = $prixPresta;

    return $this;
  }

  /**
   * Get the value of descriptionPresta
   */
  public function getDescriptionPresta(): ?string
  {
    return $this->descriptionPresta;
  }

  /**
   * Set the value of descriptionPresta
   */
  public function setDescriptionPresta(?string $descriptionPresta): self
  {
    $this->descriptionPresta = $descriptionPresta;

    return $this;
  }

  /**
   * Get the value of imgPresta
   */
  public function getImgPresta(): ?string
  {
    return $this->imgPresta;
  }

  /**
   * Set the value of imgPresta
   */
  public function setImgPresta(?string $imgPresta): self
  {
    $this->imgPresta = $imgPresta;

    return $this;
  }

  /**
   * Get the value of necessiteRetour
   */
  public function isNecessiteRetour(): ?bool
  {
    return $this->necessiteRetour;
  }

  /**
   * Set the value of necessiteRetour
   */
  public function setNecessiteRetour(?bool $necessiteRetour): self
  {
    $this->necessiteRetour = $necessiteRetour;

    return $this;
  }

  /**
   * Get the value of prestationActif
   */
  public function isPrestationActif(): ?bool
  {
    return $this->prestationActif;
  }

  /**
   * Set the value of prestationActif
   */
  public function setPrestationActif(?bool $prestationActif): self
  {
    $this->prestationActif = $prestationActif;

    return $this;
  }

    /**
   * Get the value of typePresta
   */
  public function getTypePrestaId(): ?string
  {
    return $this->typePrestaId;
  }

  /**
   * Set the value of typePrestaId
   */
  public function setTypePrestaId(?string $typePrestaId): self
  {
    $this->typePrestaId = $typePrestaId;

    return $this;
  }


  /**
   * Get the value of libelle
   */
  public function getLibelle(): ?string
  {
    return $this->libelle;
  }

  /**
   * Set the value of libelle
   */
  public function setLibelle(?string $libelle): self
  {
    $this->libelle = $libelle;

    return $this;
  }

  /**
   * Get the value of contenuPresta
   */
  public function getContenuPresta(): ?array
  {
    return $this->contenuPresta;
  }

  /**
   * Set the value of contenuPresta
   */
  public function setContenuPresta(string | array $contenuPresta): self
  {
    // ContenuPresta est une string JSON recu depuis l'hydratation
    // Si je recois une string
    // json_decode : string JSON -> tableau PHP
    if(is_string($contenuPresta)){

      $contenuPresta = json_decode($contenuPresta, true);
    }
    $this->contenuPresta = $contenuPresta;

    return $this;
  }
}
