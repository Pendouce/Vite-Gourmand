<?php

namespace App\Entity;

class TypeDePlat extends Entity
{
  protected ?int $type_id = null;
  protected ?string $libelle = null;



  /**
   * Get the value of type_id
   */
  public function getTypeId(): ?int
  {
    return $this->type_id;
  }

  /**
   * Set the value of type_id
   */
  public function setTypeId(?int $type_id): self
  {
    $this->type_id = $type_id;

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