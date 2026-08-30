<?php

namespace App\Entity;

class InformationsVg extends Entity
{
  protected ?int $infoId;
  protected ?string $adresse;
  protected ?string $telephone;
  protected ?string $email;
  protected ?string $horairesSemaine;
  protected ?string $horairesWeekend;

  /**
   * Get the value of infoId
   */
  public function getInfoId(): ?int
  {
    return $this->infoId;
  }

  /**
   * Set the value of infoId
   */
  public function setInfoId(?int $infoId): self
  {
    $this->infoId = $infoId;

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
   * Get the value of horairesSemaine
   */
  public function getHorairesSemaine(): ?string
  {
    return $this->horairesSemaine;
  }

  /**
   * Set the value of horairesSemaine
   */
  public function setHorairesSemaine(?string $horairesSemaine): self
  {
    $this->horairesSemaine = $horairesSemaine;

    return $this;
  }

  /**
   * Get the value of horairesWeekend
   */
  public function getHorairesWeekend(): ?string
  {
    return $this->horairesWeekend;
  }

  /**
   * Set the value of horairesWeekend
   */
  public function setHorairesWeekend(?string $horairesWeekend): self
  {
    $this->horairesWeekend = $horairesWeekend;

    return $this;
  }
}