<?php

namespace App\Entity;

use DateTimeImmutable;

class Commande extends Entity
{
  private ?int $commande_id = null;
  private ?int $nb_commande = null;
  private ?DateTimeImmutable $date_commande = null;
  private ?DateTimeImmutable $date_prestation = null;
  private ?int $nb_personne = null;
  private ?string $heure_Livraison = null;
  private ?string $lieu_livraison = null;
  private ?float $prix_livraison = null;
  private ?float $prix_total = null;
  private ?int $user_id = null;
  private ?int $status_id = null;

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

  /**
   * Get the value of nb_commande
   */
  public function getNbCommande(): ?int
  {
    return $this->nb_commande;
  }

  /**
   * Set the value of nb_commande
   */
  public function setNbCommande(?int $nb_commande): self
  {
    $this->nb_commande = $nb_commande;

    return $this;
  }

  /**
   * Get the value of date_commande
   */
  public function getDateCommande(): ?DateTimeImmutable
  {
    return $this->date_commande;
  }

  /**
   * Set the value of date_commande
   */
  public function setDateCommande(?DateTimeImmutable $date_commande): self
  {
    $this->date_commande = $date_commande;

    return $this;
  }

  /**
   * Get the value of date_prestation
   */
  public function getDatePrestation(): ?DateTimeImmutable
  {
    return $this->date_prestation;
  }

  /**
   * Set the value of date_prestation
   */
  public function setDatePrestation(?DateTimeImmutable $date_prestation): self
  {
    $this->date_prestation = $date_prestation;

    return $this;
  }

  /**
   * Get the value of nb_personne
   */
  public function getNbPersonne(): ?int
  {
    return $this->nb_personne;
  }

  /**
   * Set the value of nb_personne
   */
  public function setNbPersonne(?int $nb_personne): self
  {
    $this->nb_personne = $nb_personne;

    return $this;
  }

  /**
   * Get the value of heure_Livraison
   */
  public function getHeureLivraison(): ?string
  {
    return $this->heure_Livraison;
  }

  /**
   * Set the value of heure_Livraison
   */
  public function setHeureLivraison(?string $heure_Livraison): self
  {
    $this->heure_Livraison = $heure_Livraison;

    return $this;
  }

  /**
   * Get the value of lieu_livraison
   */
  public function getLieuLivraison(): ?string
  {
    return $this->lieu_livraison;
  }

  /**
   * Set the value of lieu_livraison
   */
  public function setLieuLivraison(?string $lieu_livraison): self
  {
    $this->lieu_livraison = $lieu_livraison;

    return $this;
  }

  /**
   * Get the value of prix_livraison
   */
  public function getPrixLivraison(): ?float
  {
    return $this->prix_livraison;
  }

  /**
   * Set the value of prix_livraison
   */
  public function setPrixLivraison(?float $prix_livraison): self
  {
    $this->prix_livraison = $prix_livraison;

    return $this;
  }

  /**
   * Get the value of prix_total
   */
  public function getPrixTotal(): ?float
  {
    return $this->prix_total;
  }

  /**
   * Set the value of prix_total
   */
  public function setPrixTotal(?float $prix_total): self
  {
    $this->prix_total = $prix_total;

    return $this;
  }

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
   * Get the value of status_id
   */
  public function getStatusId(): ?int
  {
    return $this->status_id;
  }

  /**
   * Set the value of status_id
   */
  public function setStatusId(?int $status_id): self
  {
    $this->status_id = $status_id;

    return $this;
  }
}