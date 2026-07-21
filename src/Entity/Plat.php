<?php

namespace App\Entity;

class Plat extends Entity
{
  protected ?int $platId = null;
  protected ?string $titre = null;
  protected ?string $imagePlat = null;
  protected ?string $descriptionPlat = null;
  protected ?float $prixPersonne = null;
  protected ?int $stockPlat = null;
  protected ?int $typeId = null;
  protected ?bool $platActif = null;
  
  protected ?string $libelle = null;
  protected array $allergenes = [];

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
   * Get the value of imagePlat
   */
  public function getImagePlat(): ?string
  {
    return $this->imagePlat;
  }

  /**
   * Set the value of imagePlat
   */
  public function setImagePlat(?string $imagePlat): self
  {
    $this->imagePlat = $imagePlat;

    return $this;
  }

  /**
   * Get the value of descriptionPlat
   */
  public function getDescriptionPlat(): ?string
  {
    return $this->descriptionPlat;
  }

  /**
   * Set the value of descriptionPlat
   */
  public function setDescriptionPlat(?string $descriptionPlat): self
  {
    $this->descriptionPlat = $descriptionPlat;

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
   * Get the value of typeId
   */
  public function getTypeId(): ?int
  {
    return $this->typeId;
  }

  /**
   * Set the value of typeId
   */
  public function setTypeId(?int $typeId): self
  {
    $this->typeId = $typeId;

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
   * Get the value of allergene
   */
  public function getAllergenes(): array
  {
    return $this->allergenes;
  }

  /**
   * Set the value of allergene
   */
  public function setAllergenes(array $allergenes): self
  {
    $this->allergenes = $allergenes;

    return $this;
  }
}