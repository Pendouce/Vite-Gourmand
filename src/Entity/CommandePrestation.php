<?php

namespace App\Entity;

use DateTimeImmutable;

class CommandePrestation extends Entity
{
  protected ?int $prixTotalPresta = null;
  protected ?DateTimeImmutable $datePresta = null;
  protected ?DateTimeImmutable $dateRetourPrevu = null;
  protected ?DateTimeImmutable $dateRetour = null;
  protected ?float $tauxRetard = null;
  protected ?int $commandeId = null;
  protected ?int $prestationId = null;

  /**
   * Get the value of prixTotalPresta
   */
  public function getPrixTotalPresta(): ?int
  {
    return $this->prixTotalPresta;
  }

  /**
   * Set the value of prixTotalPresta
   */
  public function setPrixTotalPresta(?int $prixTotalPresta): self
  {
    $this->prixTotalPresta = $prixTotalPresta;

    return $this;
  }

  /**
   * Get the value of datePresta
   */
  public function getDatePresta(): ?DateTimeImmutable
  {
    return $this->datePresta;
  }

  /**
   * Set the value of datePresta
   */
  public function setDatePresta(?DateTimeImmutable $datePresta): self
  {
    $this->datePresta = $datePresta;

    return $this;
  }

  /**
   * Get the value of dateRetourPrevu
   */
  public function getDateRetourPrevu(): ?DateTimeImmutable
  {
    return $this->dateRetourPrevu;
  }

  /**
   * Set the value of dateRetourPrevu
   */
  public function setDateRetourPrevu(?DateTimeImmutable $dateRetourPrevu): self
  {
    $this->dateRetourPrevu = $dateRetourPrevu;

    return $this;
  }

  /**
   * Get the value of dateRetour
   */
  public function getDateRetour(): ?DateTimeImmutable
  {
    return $this->dateRetour;
  }

  /**
   * Set the value of dateRetour
   */
  public function setDateRetour(?DateTimeImmutable $dateRetour): self
  {
    $this->dateRetour = $dateRetour;

    return $this;
  }

  /**
   * Get the value of tauxRetard
   */
  public function getTauxRetard(): ?float
  {
    return $this->tauxRetard;
  }

  /**
   * Set the value of tauxRetard
   */
  public function setTauxRetard(?float $tauxRetard): self
  {
    $this->tauxRetard = $tauxRetard;

    return $this;
  }

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
   * Get the value of prestationId
   */
  public function getPrestationId(): ?int
  {
    return $this->prestationId;
  }

  /**
   * Set the value of prestationId
   */
  public function setPrestationId(?int $prestationId): self
  {
    $this->prestationId = $prestationId;

    return $this;
  }
}