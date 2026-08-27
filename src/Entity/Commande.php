<?php

namespace App\Entity;

use DateTimeImmutable;

class Commande extends Entity
{
  protected ?int $commandeId = null;
  protected ?int $nbCommande = null;
  protected ?DateTimeImmutable $dateCommande = null;
  protected ?DateTimeImmutable $dateLivraison = null;
  protected ?int $nbPersonne = null;
  protected ?string $lieuLivraison = null;
  protected ?float $prixLivraison = null;
  protected ?float $prixTotal = null;
  protected ?int $userId = null;
  protected ?int $statusId = null;

  protected ?string $libelle = null;
  protected ?User $user = null;
  protected ?array $commandePrestations = null;
  protected ?array $commandeMenus = null;
  protected ?array $commandeBoissons = null;

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
   * Get the value of nbCommande
   */
  public function getNbCommande(): ?int
  {
    return $this->nbCommande;
  }

  /**
   * Set the value of nbCommande
   */
  public function setNbCommande(?int $nbCommande): self
  {
    $this->nbCommande = $nbCommande;

    return $this;
  }

  /**
   * Get the value of dateCommande
   */
  public function getDateCommande(): ?DateTimeImmutable
  {
    return $this->dateCommande;
  }

  /**
   * Set the value of dateCommande
   */
  public function setDateCommande(?DateTimeImmutable $dateCommande): self
  {
    $this->dateCommande = $dateCommande;

    return $this;
  }

  /**
   * Get the value of dateLivraison
   */
  public function getDateLivraison(): ?DateTimeImmutable
  {
    return $this->dateLivraison;
  }

  /**
   * Set the value of dateLivraison
   */
  public function setDateLivraison(?DateTimeImmutable $dateLivraison): self
  {
    $this->dateLivraison = $dateLivraison;

    return $this;
  }

  /**
   * Get the value of nbPersonne
   */
  public function getNbPersonne(): ?int
  {
    return $this->nbPersonne;
  }

  /**
   * Set the value of nbPersonne
   */
  public function setNbPersonne(?int $nbPersonne): self
  {
    $this->nbPersonne = $nbPersonne;

    return $this;
  }

  /**
   * Get the value of lieuLivraison
   */
  public function getLieuLivraison(): ?string
  {
    return $this->lieuLivraison;
  }

  /**
   * Set the value of lieuLivraison
   */
  public function setLieuLivraison(?string $lieuLivraison): self
  {
    $this->lieuLivraison = $lieuLivraison;

    return $this;
  }

  /**
   * Get the value of prixLivraison
   */
  public function getPrixLivraison(): ?float
  {
    return $this->prixLivraison;
  }

  /**
   * Set the value of prixLivraison
   */
  public function setPrixLivraison(?float $prixLivraison): self
  {
    $this->prixLivraison = $prixLivraison;

    return $this;
  }

  /**
   * Get the value of prixTotal
   */
  public function getPrixTotal(): ?float
  {
    return $this->prixTotal;
  }

  /**
   * Set the value of prixTotal
   */
  public function setPrixTotal(?float $prixTotal): self
  {
    $this->prixTotal = $prixTotal;

    return $this;
  }

  /**
   * Get the value of userId
   */
  public function getUserId(): ?int
  {
    return $this->userId;
  }

  /**
   * Set the value of userId
   */
  public function setUserId(?int $userId): self
  {
    $this->userId = $userId;

    return $this;
  }

  /**
   * Get the value of statusId
   */
  public function getStatusId(): ?int
  {
    return $this->statusId;
  }

  /**
   * Set the value of statusId
   */
  public function setStatusId(?int $statusId): self
  {
    $this->statusId = $statusId;

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

  /**
   * Get the value of commandePrestations
   */
  public function getCommandePrestations(): ?array
  {
    return $this->commandePrestations;
  }

  /**
   * Set the value of commandePrestations
   */
  public function setCommandePrestations(?array $commandePrestations): self
  {
    $this->commandePrestations = $commandePrestations;

    return $this;
  }

  /**
   * Get the value of commandeMenus
   */
  public function getCommandeMenus(): ?array
  {
    return $this->commandeMenus;
  }

  /**
   * Set the value of commandeMenus
   */
  public function setCommandeMenus(?array $commandeMenus): self
  {
    $this->commandeMenus = $commandeMenus;

    return $this;
  }

  /**
   * Get the value of commandeBoissons
   */
  public function getCommandeBoissons(): ?array
  {
    return $this->commandeBoissons;
  }

  /**
   * Set the value of commandeBoissons
   */
  public function setCommandeBoissons(?array $commandeBoissons): self
  {
    $this->commandeBoissons = $commandeBoissons;

    return $this;
  }

  /**
   * Get the value of user
   */
  public function getUser(): ?User
  {
    return $this->user;
  }

  /**
   * Set the value of user
   */
  public function setUser(?User $user): self
  {
    $this->user = $user;

    return $this;
  }
}