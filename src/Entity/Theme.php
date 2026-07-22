<?php

namespace App\Entity;

class Theme extends Entity
{
  protected ?int $themeId = null;
  protected ?string $libelle = null;

  /**
   * Get the value of themeId
   */
  public function getThemeId(): ?int
  {
    return $this->themeId;
  }

  /**
   * Set the value of themeId
   */
  public function setThemeId(?int $themeId): self
  {
    $this->themeId = $themeId;

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
}