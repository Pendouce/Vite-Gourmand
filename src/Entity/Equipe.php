<?php

namespace App\Entity;

class Equipe extends Entity
{
  protected ?int $membreId = null;
  protected ?string $nom = null;
  protected ?string $prenom = null;
  protected ?string $photo = null;
  protected ?string $poste = null;
  protected ?string $description = null;
  protected ?bool $actif = null;

  /**
   * Get the value of membreId
   */
  public function getMembreId(): ?int
  {
    return $this->membreId;
  }

  /**
   * Set the value of membreId
   */
  public function setMembreId(?int $membreId): self
  {
    $this->membreId = $membreId;

    return $this;
  }

  /**
   * Get the value of nom
   */
  public function getNom(): ?string
  {
    return $this->nom;
  }

  /**
   * Set the value of nom
   */
  public function setNom(?string $nom): self
  {
    $this->nom = $nom;

    return $this;
  }

  /**
   * Get the value of prenom
   */
  public function getPrenom(): ?string
  {
    return $this->prenom;
  }

  /**
   * Set the value of prenom
   */
  public function setPrenom(?string $prenom): self
  {
    $this->prenom = $prenom;

    return $this;
  }

  /**
   * Get the value of photo
   */
  public function getPhoto(): ?string
  {
    return $this->photo;
  }

  /**
   * Set the value of photo
   */
  public function setPhoto(?string $photo): self
  {
    $this->photo = $photo;

    return $this;
  }

  /**
   * Get the value of poste
   */
  public function getPoste(): ?string
  {
    return $this->poste;
  }

  /**
   * Set the value of poste
   */
  public function setPoste(?string $poste): self
  {
    $this->poste = $poste;

    return $this;
  }

  /**
   * Get the value of description
   */
  public function getDescription(): ?string
  {
    return $this->description;
  }

  /**
   * Set the value of description
   */
  public function setDescription(?string $description): self
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get the value of actif
   */
  public function isActif(): ?bool
  {
    return $this->actif;
  }

  /**
   * Set the value of actif
   */
  public function setActif(?bool $actif): self
  {
    $this->actif = $actif;

    return $this;
  }
}