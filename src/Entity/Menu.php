<?php

namespace App\Entity;

class Menu extends Entity
{
  protected ?int $menuId = null;
  protected ?string $titre = null;
  protected ?float $prixPersonne = null;
  protected ?int $nombrePersonneMin = null;
  protected ?string $conditions = null;
  protected ?int $stockDispo = null;
  protected ?bool $menuActif = null;

  protected array $plat = [];
  protected array $evenement = [];
  protected array $theme = [];
  protected array $regime = [];
  protected ?string $imageMenu = null;


  /**
   * Get the value of menuId
   */
  public function getMenuId(): ?int
  {
    return $this->menuId;
  }

  /**
   * Set the value of menuId
   */
  public function setMenuId(?int $menuId): self
  {
    $this->menuId = $menuId;

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
   * Get the value of nombrePersonneMin
   */
  public function getNombrePersonneMin(): ?int
  {
    return $this->nombrePersonneMin;
  }

  /**
   * Set the value of nombrePersonneMin
   */
  public function setNombrePersonneMin(?int $nombrePersonneMin): self
  {
    $this->nombrePersonneMin = $nombrePersonneMin;

    return $this;
  }

  /**
   * Get the value of conditions
   */
  public function getConditions(): ?string
  {
    return $this->conditions;
  }

  /**
   * Set the value of conditions
   */
  public function setConditions(?string $conditions): self
  {
    $this->conditions = $conditions;

    return $this;
  }

  /**
   * Get the value of stockDispo
   */
  public function getStockDispo(): ?int
  {
    return $this->stockDispo;
  }

  /**
   * Set the value of stockDispo
   */
  public function setStockDispo(?int $stockDispo): self
  {
    $this->stockDispo = $stockDispo;

    return $this;
  }

  /**
   * Get the value of menuActif
   */
  public function isMenuActif(): ?bool
  {
    return $this->menuActif;
  }

  /**
   * Set the value of menuActif
   */
  public function setMenuActif(?bool $menuActif): self
  {
    $this->menuActif = $menuActif;

    return $this;
  }

  /**
   * Get the value of evenement
   */
  public function getEvenement(): array
  {
    return $this->evenement;
  }

  /**
   * Set the value of evenement
   */
  public function setEvenement(array $evenement): self
  {
    $this->evenement = $evenement;

    return $this;
  }

  /**
   * Get the value of theme
   */
  public function getTheme(): array
  {
    return $this->theme;
  }

  /**
   * Set the value of theme
   */
  public function setTheme(array $theme): self
  {
    $this->theme = $theme;

    return $this;
  }

  /**
   * Get the value of regime
   */
  public function getRegime(): array
  {
    return $this->regime;
  }

  /**
   * Set the value of regime
   */
  public function setRegime(array $regime): self
  {
    $this->regime = $regime;

    return $this;
  }

  /**
   * Get the value of plat
   */
  public function getPlat(): array
  {
    return $this->plat;
  }

  /**
   * Set the value of plat
   */
  public function setPlat(array $plat): self
  {
    $this->plat = $plat;

    return $this;
  }

  /**
   * Get the value of imageMenu
   */
  public function getImageMenu(): ?string
  {
    return $this->imageMenu;
  }

  /**
   * Set the value of imageMenu
   */
  public function setImageMenu(?string $imageMenu): self
  {
    $this->imageMenu = $imageMenu;

    return $this;
  }
}