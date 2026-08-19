<?php

namespace App\Entity;

class CommandeMenu extends Entity
{
  protected ?int $nb_personneMenu = null;
  protected ?int $commandeId = null;
  protected ?int $menuId = null;

  protected ?Menu $menu = null;

  /**
   * Get the value of nb_personneMenu
   */
  public function getNbPersonneMenu(): ?int
  {
    return $this->nb_personneMenu;
  }

  /**
   * Set the value of nb_personneMenu
   */
  public function setNbPersonneMenu(?int $nb_personneMenu): self
  {
    $this->nb_personneMenu = $nb_personneMenu;

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
   * Get the value of menuId
   */
  public function getMenuId(): ?int
  {
    return $this->menuId;
  }

  /**
   * Set the value of menuId
   */
  public function setMenuId(?int $menuId): self
  {
    $this->menuId = $menuId;

    return $this;
  }

  /**
   * Get the value of menu
   */
  public function getMenu(): ?Menu
  {
    return $this->menu;
  }

  /**
   * Set the value of menu
   */
  public function setMenu(?Menu $menu): self
  {
    $this->menu = $menu;

    return $this;
  }
}