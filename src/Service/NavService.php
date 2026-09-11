<?php

namespace App\Service;

class NavService
{
  // Methode pour l'affichage des labels de la navbar
  // en fonction du role
  public function lienNav(int $role): array
  {
    $navRole = match($role){
      ROLE_UTILISATEUR => [
        ['label' => 'Mes infos', 'url' => '/mesInfos'],
        ['label' => 'Menus', 'url' => '/menu'],
        ['label' => 'Nos prestations', 'url' => '/prestations'],
        ['label' => 'Mes commandes', 'url' => '/mesCommandes'],
        ['label' => 'Contact', 'url' => '/contact'],
      ],
      ROLE_EMPLOYE => [
        ['label' => 'Mes infos', 'url' => '/mesInfos'],
        ['label' => 'Carte', 'url' => '/menu'],
        ['label' => 'Prestations', 'url' => '/prestations'],
        ['label' => 'Commandes', 'url' => '/commandes'],
        ['label' => 'Avis', 'url' => '/avis'],
      ],
      ROLE_ADMIN => [
        ['label' => 'Mes infos', 'url' => '/mesInfos'],
        ['label' => 'Carte', 'url' => '/menu'],
        ['label' => 'Prestations', 'url' => '/prestations'],
        ['label' => 'Commandes', 'url' => '/commandes'],
        ['label' => 'Avis', 'url' => '/avis'],
        ['label' => 'Employe', 'url' => '/gestionEmployes'],
        ['label' => 'Statistques', 'url' => '/stat'],
      ],
      default => [
        ['label' => 'Menus', 'url' => '/menu'],
        ['label' => 'Nos prestations', 'url' => '/prestations'],
        ['label' => 'Contact', 'url' => '/contact'],
      ],
    };

    return $navRole;
  }

  // Mehtode pour l'affichage connexion/deconnexion
  public function connexionNav(bool $estConnecte): array
  {
    return $estConnecte? ['label' => 'Déconnexion', 'url' => '/deconnexion'] : ['label' => 'Connexion', 'url' => '/connexion'];
  }

  // Methode pour l'affichage des liens de la carte
  public function lienCarte(int $role, string $page): array
  {
    $carteRole = match ($role) {
      ROLE_ADMIN, ROLE_EMPLOYE =>[
        ['label' => 'Menus', 'url' => '/menu'],
        ['label' => 'Plats', 'url' => '/plats'],
        ['label' => 'Boisson', 'url' => '/boisson'],
      ],
      default =>[
        ['label' => 'Menus', 'url' => '/menu'],
        ['label' => 'Boisson', 'url' => '/boisson'],
      ],
    };

    // J'ajoute classeActive => active a $carteRole
    // & : $carte pointe vers le vrai élément du tableau, donc le modifier modifie carteRole
    foreach($carteRole as &$carte){
      $carte['classeActive'] = $carte['url'] === $page ? 'active' : "";
    }

    // Je détruit la référence pour éviter d'écraser le dernier élément plus tard
    unset($carte);

    return $carteRole;
  }
}