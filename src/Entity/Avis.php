<?php

namespace App\Entity;

use DateTimeImmutable;

class Avis extends Entity
{
  protected ?int $avisId;
  protected ?int $note;
  protected ?string $commentaire;
  protected ?DateTimeImmutable $datePublication;
  protected ?bool $publie;
  protected ?int $commandeId;


  /**
   * Get the value of avisId
   */
  public function getAvisId(): ?int
  {
    return $this->avisId;
  }

  /**
   * Set the value of avisId
   */
  public function setAvisId(?int $avisId): self
  {
    $this->avisId = $avisId;

    return $this;
  }

  /**
   * Get the value of note
   */
  public function getNote(): ?int
  {
    return $this->note;
  }

  /**
   * Set the value of note
   */
  public function setNote(?int $note): self
  {
    $this->note = $note;

    return $this;
  }

  /**
   * Get the value of commentaire
   */
  public function getCommentaire(): ?string
  {
    return $this->commentaire;
  }

  /**
   * Set the value of commentaire
   */
  public function setCommentaire(?string $commentaire): self
  {
    $this->commentaire = $commentaire;

    return $this;
  }

  /**
   * Get the value of datePublication
   */
  public function getDatePublication(): ?DateTimeImmutable
  {
    return $this->datePublication;
  }

  /**
   * Set the value of datePublication
   */
  public function setDatePublication(?DateTimeImmutable $datePublication): self
  {
    $this->datePublication = $datePublication;

    return $this;
  }

  /**
   * Get the value of publie
   */
  public function isPublie(): ?bool
  {
    return $this->publie;
  }

  /**
   * Set the value of publie
   */
  public function setPublie(?bool $publie): self
  {
    $this->publie = $publie;

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
}