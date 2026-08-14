<?php

namespace App\Entity;

class Boisson extends Entity
{
  protected ?int $boissonId = null;
  protected ?string $nomBoisson = null;
  protected ?string $photoBoisson = null;
  protected ?float $prixBoisson = null;
  protected ?bool $alcool = null;
  protected ?int $stockBoisson = null;
  protected ?bool $boissonActif = null;

  /**
   * Get the value of boissonId
   */
  public function getBoissonId(): ?int
  {
    return $this->boissonId;
  }

  /**
   * Set the value of boissonId
   */
  public function setBoissonId(?int $boissonId): self
  {
    $this->boissonId = $boissonId;

    return $this;
  }

  /**
   * Get the value of nomBoisson
   */
  public function getNomBoisson(): ?string
  {
    return $this->nomBoisson;
  }

  /**
   * Set the value of nomBoisson
   */
  public function setNomBoisson(?string $nomBoisson): self
  {
    $this->nomBoisson = $nomBoisson;

    return $this;
  }

  /**
   * Get the value of photoBoisson
   */
  public function getPhotoBoisson(): ?string
  {
    return $this->photoBoisson;
  }

  /**
   * Set the value of photoBoisson
   */
  public function setPhotoBoisson(?string $photoBoisson): self
  {
    $this->photoBoisson = $photoBoisson;

    return $this;
  }

  /**
   * Get the value of prixBoisson
   */
  public function getPrixBoisson(): ?float
  {
    return $this->prixBoisson;
  }

  /**
   * Set the value of prixBoisson
   */
  public function setPrixBoisson(?float $prixBoisson): self
  {
    $this->prixBoisson = $prixBoisson;

    return $this;
  }

  /**
   * Get the value of alcool
   */
  public function isAlcool(): ?bool
  {
    return $this->alcool;
  }

  /**
   * Set the value of alcool
   */
  public function setAlcool(?bool $alcool): self
  {
    $this->alcool = $alcool;

    return $this;
  }

  /**
   * Get the value of stockBoisson
   */
  public function getStockBoisson(): ?int
  {
    return $this->stockBoisson;
  }

  /**
   * Set the value of stockBoisson
   */
  public function setStockBoisson(?int $stockBoisson): self
  {
    $this->stockBoisson = $stockBoisson;

    return $this;
  }

  /**
   * Get the value of boissonActif
   */
  public function isBoissonActif(): ?bool
  {
    return $this->boissonActif;
  }

  /**
   * Set the value of boissonActif
   */
  public function setBoissonActif(?bool $boissonActif): self
  {
    $this->boissonActif = $boissonActif;

    return $this;
  }
}