<?php

namespace App\Entity;

class User extends Entity
{

  protected ?int $user_id = null;
  protected ?string $nom = null;
  protected ?string $prenom = null;
  protected ?string $email = null;
  protected ?string $motDePasse = null;
  protected ?string $telephone = null;
  protected ?string $ville = null;
  protected ?string $codePostal = null;
  protected ?string $adresse = null;
  protected ?int $roleId = null;

  /**
   * Get the value of user_id
   */
  public function getUserId(): ?int
  {
    return $this->user_id;
  }

  /**
   * Set the value of user_id
   */
  public function setUserId(?int $user_id): self
  {
    $this->user_id = $user_id;

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
   * Get the value of email
   */
  public function getEmail(): ?string
  {
    return $this->email;
  }

  /**
   * Set the value of email
   */
  public function setEmail(?string $email): self
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get the value of motDePasse
   */
  public function getMotDePasse(): ?string
  {
    return $this->motDePasse;
  }

  /**
   * Set the value of motDePasse
   */
  public function setMotDePasse(?string $motDePasse): self
  {
    $this->motDePasse = $motDePasse;

    return $this;
  }

  /**
   * Get the value of telephone
   */
  public function getTelephone(): ?string
  {
    return $this->telephone;
  }

  /**
   * Set the value of telephone
   */
  public function setTelephone(?string $telephone): self
  {
    $this->telephone = $telephone;

    return $this;
  }

  /**
   * Get the value of ville
   */
  public function getVille(): ?string
  {
    return $this->ville;
  }

  /**
   * Set the value of ville
   */
  public function setVille(?string $ville): self
  {
    $this->ville = $ville;

    return $this;
  }

  /**
   * Get the value of codePostal
   */
  public function getCodePostal(): ?string
  {
    return $this->codePostal;
  }

  /**
   * Set the value of codePostal
   */
  public function setCodePostal(?string $codePostal): self
  {
    $this->codePostal = $codePostal;

    return $this;
  }

  /**
   * Get the value of adresse
   */
  public function getAdresse(): ?string
  {
    return $this->adresse;
  }

  /**
   * Set the value of adresse
   */
  public function setAdresse(?string $adresse): self
  {
    $this->adresse = $adresse;

    return $this;
  }

  /**
   * Get the value of roleId
   */
  public function getRoleId(): ?int
  {
    return $this->roleId;
  }

  /**
   * Set the value of roleId
   */
  public function setRoleId(?int $roleId): self
  {
    $this->roleId = $roleId;

    return $this;
  }
}