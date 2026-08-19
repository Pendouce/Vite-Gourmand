<?php

namespace App\Entity;

class Status extends Entity
{
  protected ?int $statusId = null;
  protected ?string $libelle = null;

  /**
   * Get the value of statusId
   */
  public function getStatusId(): ?int
  {
    return $this->statusId;
  }

  /**
   * Set the value of statusId
   */
  public function setStatusId(?int $statusId): self
  {
    $this->statusId = $statusId;

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