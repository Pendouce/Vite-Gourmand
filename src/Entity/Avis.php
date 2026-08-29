<?php

namespace App\Entity;

use DateTimeImmutable;

class Avis extends Entity
{
  protected ?int $avis_id;
  protected ?int $note;
  protected ?string $commentaire;
  protected ?DateTimeImmutable $date_publication;
  protected ?bool $publie;
  protected ?int $commande_id;

  /**
   * Get the value of avis_id
   */
  public function getAvisId(): ?int
  {
    return $this->avis_id;
  }

  /**
   * Set the value of avis_id
   */
  public function setAvisId(?int $avis_id): self
  {
    $this->avis_id = $avis_id;

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
   * Get the value of date_publication
   */
  public function getDatePublication(): ?DateTimeImmutable
  {
    return $this->date_publication;
  }

  /**
   * Set the value of date_publication
   */
  public function setDatePublication(?DateTimeImmutable $date_publication): self
  {
    $this->date_publication = $date_publication;

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
   * Get the value of commande_id
   */
  public function getCommandeId(): ?int
  {
    return $this->commande_id;
  }

  /**
   * Set the value of commande_id
   */
  public function setCommandeId(?int $commande_id): self
  {
    $this->commande_id = $commande_id;

    return $this;
  }
}