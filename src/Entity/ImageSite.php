<?php

namespace App\Entity;

class ImageSite extends Entity
{
  protected ?int $id = null;
  protected ?string $nomImg = null;
  protected ?string $chemin = null;

  /**
   * Get the value of id
   */
  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * Set the value of id
   */
  public function setId(?int $id): self
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of nomImg
   */
  public function getNomImg(): ?string
  {
    return $this->nomImg;
  }

  /**
   * Set the value of nomImg
   */
  public function setNomImg(?string $nomImg): self
  {
    $this->nomImg = $nomImg;

    return $this;
  }

  /**
   * Get the value of chemin
   */
  public function getChemin(): ?string
  {
    return $this->chemin;
  }

  /**
   * Set the value of chemin
   */
  public function setChemin(?string $chemin): self
  {
    $this->chemin = $chemin;

    return $this;
  }
}