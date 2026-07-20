<?php

namespace App\Entity;

class Allergene extends Entity
{
  protected ?int $allergene_id = null;
  protected ?string $libelle = null;


  /**
   * Get the value of allergene_id
   */
  public function getAllergeneId(): ?int
  {
    return $this->allergene_id;
  }

  /**
   * Set the value of allergene_id
   */
  public function setAllergeneId(?int $allergene_id): self
  {
    $this->allergene_id = $allergene_id;

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