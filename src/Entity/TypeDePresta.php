<?php

namespace App\Entity;

class TypeDePresta extends Entity
{
  protected ?int $type_presta_id = null;
  protected ?string $libelle = null;

  /**
   * Get the value of type_presta_id
   */
  public function getTypePrestaId(): ?int
  {
    return $this->type_presta_id;
  }

  /**
   * Set the value of type_presta_id
   */
  public function setTypePrestaId(?int $type_presta_id): self
  {
    $this->type_presta_id = $type_presta_id;

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