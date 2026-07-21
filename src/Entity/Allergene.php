<?php

namespace App\Entity;

class Allergene extends Entity
{
  protected ?int $allergeneId = null;
  protected ?string $libelle = null;


  /**
   * Get the value of allergeneId
   */
  public function getAllergeneId(): ?int
  {
    return $this->allergeneId;
  }

  /**
   * Set the value of allergeneId
   */
  public function setAllergeneId(?int $allergeneId): self
  {
    $this->allergeneId = $allergeneId;

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