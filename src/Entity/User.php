<?php

namespace App\Entity;

class User extends Entity
{

  private ?int $user_id = null;
  private ?string $nom = null;
  private ?string $prenom = null;
  private ?string $email = null;
  private ?string $mot_de_passe = null;
  private ?string $telephone = null;
  private ?string $ville = null;
  private ?string $code_postal = null;
  private ?string $adresse = null;
  private ?int $role_id = null;

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
   * Get the value of mot_de_passe
   */
  public function getMotDePasse(): ?string
  {
    return $this->mot_de_passe;
  }

  /**
   * Set the value of mot_de_passe
   */
  public function setMotDePasse(?string $mot_de_passe): self
  {
    $this->mot_de_passe = $mot_de_passe;

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
   * Get the value of code_postal
   */
  public function getCodePostal(): ?string
  {
    return $this->code_postal;
  }

  /**
   * Set the value of code_postal
   */
  public function setCodePostal(?string $code_postal): self
  {
    $this->code_postal = $code_postal;

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
   * Get the value of role_id
   */
  public function getRoleId(): ?int
  {
    return $this->role_id;
  }

  /**
   * Set the value of role_id
   */
  public function setRoleId(?int $role_id): self
  {
    $this->role_id = $role_id;

    return $this;
  }
}