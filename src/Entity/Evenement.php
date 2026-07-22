<?php

namespace App\Entity;

class Evenement extends Entity
{
  protected ?int $evenementId = null;
  protected ?string $libelle = null;

  /**
   * Get the value of evenementId
   */
  public function getEvenementId(): ?int
  {
    return $this->evenementId;
  }

  /**
   * Set the value of evenementId
   */
  public function setEvenementId(?int $evenementId): self
  {
    $this->evenementId = $evenementId;

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