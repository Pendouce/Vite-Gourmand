<?php

namespace App\Entity;

class Regime extends Entity
{
  protected ?int $regimeId = null;
  protected ?string $libelle = null;

  /**
   * Get the value of regimeId
   */
  public function getRegimeId(): ?int
  {
    return $this->regimeId;
  }

  /**
   * Set the value of regimeId
   */
  public function setRegimeId(?int $regimeId): self
  {
    $this->regimeId = $regimeId;

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