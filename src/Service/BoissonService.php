<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Repository\BoissonRepository;
use App\Repository\CommandeBoissonRepository;
use Exception;

class BoissonService
{
  private BoissonRepository $boissonRepository;
  private CommandeBoissonRepository $commandeBoissonRepository;
  private CalculStockService $calculStockService;

  public function __construct(BoissonRepository $boissonRepository, CommandeBoissonRepository $commandeBoissonRepository, CalculStockService $calculStockService) {
    $this->boissonRepository = $boissonRepository;
    $this->calculStockService = $calculStockService;
  }

  public function creerBoisson(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    
    $data['nom_boisson'] = VerifService::verifTitre($data['nom_boisson']);

    VerifService::verifDispoTitre(
      $data['nom_boisson'],
      $this->boissonRepository->trouverBoissonParNom($data['nom_boisson'])
    );

    if(empty($data['photo_boisson'])){
      throw new Exception("Veuillez selectioner une image");
    }

    if(empty($data['prix_boisson'])){
      throw new Exception("Veuillez fixer un prix");
    }
    VerifService::verifNbPositif($data['prix_boisson']);
    
    if(empty($data['stock_boisson'])){
      throw new Exception("Veuillez fixer un nombre de personne minimum pour la commande");
    }
    VerifService::verifNbPositif($data['stock_boisson']);

    $data['nom_boisson'] = ucfirst($data['nom_boisson']);
    $data['description_boisson'] = ucfirst($data['description_boisson']);

    return $this->boissonRepository->creerBoisson($data);
  }

  public function afficherBoisson()
  {
    return $this->boissonRepository->trouverBoisson();
  }

  public function afficherBoissonParId(int $id)
  {
    return $this->boissonRepository->trouverBoissonParId($id);
  }

  public function modifierBoisson(int $id, array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $boissonActuelle = $this->boissonRepository->trouverBoissonParId($id);
    VerifService::verifExisteEnBase($boissonActuelle, 'Cette boisson');

    if(!empty($data['nom_boisson'])){
      $data['nom_boisson'] = VerifService::verifTitre($data['nom_boisson']);

      $boisson = $this->boissonRepository->trouverBoissonParNom($data['nom_boisson']);

      VerifService::verifDispoTitre(
        $data['nom_boisson'],
        $boisson,
        $boisson ? $boisson->getBoissonId(): null,
        $id
      );
    }

    if(isset($data['prix_boisson'])){
      VerifService::verifNbPositif($data['prix_boisson']);
    }
      
    if(isset($data['stock_boisson'])){
      VerifService::verifNbPositif($data['stock_boisson']);
    }

    $anciennesDonnes = $boissonActuelle->deshydrate();

    $data = array_filter($data, fn($value) => $value !== null);
    $nouvelleDonnees = array_merge($anciennesDonnes, $data);

    $this->boissonRepository->modifierBoisson($nouvelleDonnees);
  }

  public function modifierStatusBoisson(int $boissonId, int $status, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->boissonRepository->modifierStatusBoisson($boissonId, $status);
  }

  public function modifierStockBoisson(int $boissonId, int $stock, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $this->boissonRepository->modifierStockBoisson($boissonId, $stock);
  }

  public function supprimerBoisson(int $boissonId, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();

    $boisson = $this->boissonRepository->trouverBoissonParId($boissonId);

    VerifService::verifExisteEnBase($boisson, 'Boisson');
    VerifService::verifEstRatache(
      $this->commandeBoissonRepository->estRatacheAUneCommande($boissonId),
      'cette boisson',
      'e à une commande');

    $this->boissonRepository->supprimerBoisson($boissonId);
  }

/*   public function stockBoisson(int $boissonId, int $nbBoisson)
  {
    $boisson = $this->boissonRepository->trouverBoissonParId($boissonId);
    $stockBoisson = $boisson->getStockBoisson();

    $nouveauStock = $this->calculStockService->calculStockBoisson($stockBoisson, $nbBoisson);

    $this->boissonRepository->modifierStockBoisson($boissonId, $nouveauStock);
  } */

  public function verifStockBoisson(int $boissonId, int $nbBoisson)
  {
    $boisson = $this->boissonRepository->trouverBoissonParId($boissonId);
    $stockBoisson = $boisson->getStockBoisson();

    return $this->calculStockService->calculerStockBoisson($stockBoisson, $nbBoisson);
  }

  public function decrementerStockBoisson(int $boissonId, int $nbBoisson)
  {
    $nouveauStock = $this->verifstockBoisson($boissonId, $nbBoisson);

    $this->boissonRepository->modifierStockBoisson($boissonId, $nouveauStock);
  }

  public function incrementerStockBoisson(int $boissonId, int $nbBoisson)
  {
    $boisson = $this->boissonRepository->trouverBoissonParId($boissonId);
    $stock = $boisson->getStockBoisson();
    $nouveauStock = $this->calculStockService->calculerRetourStockBoisson($stock, $nbBoisson);

    $this->boissonRepository->modifierStockBoisson($boissonId, $nouveauStock);
  }
}