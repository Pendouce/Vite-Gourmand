<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\IdInnexistantException;
use App\Repository\AvisRepository;
use App\Repository\CommandeRepository;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

class AvisService
{
  private AvisRepository $avisRepository;
  private CommandeRepository $commandeRepository;

  public function __construct(AvisRepository $avisRepository, CommandeRepository $commandeRepository)
  {
    $this->avisRepository = $avisRepository;
    $this->commandeRepository = $commandeRepository;
  }

  public function creerAvis(array $data, int $nbCommande, int $userId){

    // Je verifie si un avis n'existe pas deja sur la commande
    $commande = $this->commandeRepository->trouverCommandeParNb($nbCommande);

    // Je verifie que le numero de commande existe
    if(!$commande){
      throw new Exception('Commande Introuvable');
    }
    
    $data['commande_id'] = $commande->getCommandeId();

    if($this->avisRepository->trouverAvisParCommande($data['commande_id'])){
      throw new Exception('Vous avez deja laissez un avis pour cette commande');
    }

    

    // Je verifie que la commande appartient bien a l'utilisateur
    if($userId !== $commande->getUserId()){
      throw new Exception('Cette commande ne vous appartient pas');
    }

    // Je verifie que la commande est bein terminée
    if($commande->getStatusId() !== STATUT_TERMINEE){
      throw new Exception("Vous ne pouvez pas laisser d'avis tant que la commande n'est pas terminée");
    }

    if ($data['note'] < 1 || $data['note'] > 5) {
      throw new Exception('La note doit être comprise entre 1 et 5');
    }


    $date = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));
    //var_dump($date);
    $data['date_publication'] = $date->format('Y-m-d H:i:s');
    $data['publie'] = 0;
    $data['note'] = (int)$data['note'];

    return $this->avisRepository->creerAvis($data);
  }

  public function afficherAvis(int $role)
  {
    if($role === ROLE_ADMIN || $role === ROLE_EMPLOYE){
      return $this->avisRepository->trouverAvis();
    }else{
      return $this->avisRepository->trouverAvisAcceptes();
    }
  }

  public function modifierStatusPublie(int $avisId, int $publie, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    if(!$this->avisRepository->trouverAvisParId($avisId)){
      throw new IdInnexistantException($avisId);
    }

    return $this->avisRepository->modifierStatutPublier($avisId, $publie);
  }

}