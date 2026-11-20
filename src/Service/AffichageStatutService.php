<?php

namespace App\Service;

class AffichageStatutService 
{
  public function couleurStatutCommande(int $statut): string
  {
    $pastille = match($statut){
      STATUT_TRANSMISE => 'transmise',
      STATUT_ACCEPTEE => 'accepte',
      STATUT_EN_PREPARATION => 'en-prepa',
      STATUT_EN_COUR_LIV => 'en-cours-liv',
      STATUT_LIVREE => 'livre',
      STATUT_ATTEND_RETOUR => 'attente-materiel',
      STATUT_TERMINEE => 'terminee',
      default => 'inconnu'
    };

    return $pastille;
  }
}