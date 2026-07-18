<?php

namespace App\Entity;

class Plat
{
  protected ?int $platId = null;
  protected ?string $titre = null;
  protected ?float $prixPersonne = null;
  protected ?int $stockPlat;
  protected ?bool $platActif = null;

  /**
   * Get the value of platId
   */
  public function getPlatId(): ?int
  {
    return $this->platId;
  }

  /**
   * Set the value of platId
   */
  public function setPlatId(?int $platId): self
  {
    $this->platId = $platId;

    return $this;
  }

  /**
   * Get the value of titre
   */
  public function getTitre(): ?string
  {
    return $this->titre;
  }

  /**
   * Set the value of titre
   */
  public function setTitre(?string $titre): self
  {
    $this->titre = $titre;

    return $this;
  }

  /**
   * Get the value of prixPersonne
   */
  public function getPrixPersonne(): ?float
  {
    return $this->prixPersonne;
  }

  /**
   * Set the value of prixPersonne
   */
  public function setPrixPersonne(?float $prixPersonne): self
  {
    $this->prixPersonne = $prixPersonne;

    return $this;
  }

  /**
   * Get the value of stockPlat
   */
  public function getStockPlat(): ?int
  {
    return $this->stockPlat;
  }

  /**
   * Set the value of stockPlat
   */
  public function setStockPlat(?int $stockPlat): self
  {
    $this->stockPlat = $stockPlat;

    return $this;
  }

  /**
   * Get the value of platActif
   */
  public function isPlatActif(): ?bool
  {
    return $this->platActif;
  }

  /**
   * Set the value of platActif
   */
  public function setPlatActif(?bool $platActif): self
  {
    $this->platActif = $platActif;

    return $this;
  }
}