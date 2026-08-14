<?php

namespace App\Entity;

class CommandeBoisson extends Entity
{
  protected ?int $commandeId = null;
  protected ?int $boissonId = null;
  protected ?int $quantite = null;

  /**
   * Get the value of commandeId
   */
  public function getCommandeId(): ?int
  {
    return $this->commandeId;
  }

  /**
   * Set the value of commandeId
   */
  public function setCommandeId(?int $commandeId): self
  {
    $this->commandeId = $commandeId;

    return $this;
  }

  /**
   * Get the value of boissonId
   */
  public function getBoissonId(): ?int
  {
    return $this->boissonId;
  }

  /**
   * Set the value of boissonId
   */
  public function setBoissonId(?int $boissonId): self
  {
    $this->boissonId = $boissonId;

    return $this;
  }

  /**
   * Get the value of quantite
   */
  public function getQuantite(): ?int
  {
    return $this->quantite;
  }

  /**
   * Set the value of quantite
   */
  public function setQuantite(?int $quantite): self
  {
    $this->quantite = $quantite;

    return $this;
  }
}