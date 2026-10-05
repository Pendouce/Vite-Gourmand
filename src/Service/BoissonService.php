<?php

namespace App\Service;

use App\Exceptions\AccesRefuseException;
use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Repository\BoissonRepository;
use Exception;

class BoissonService
{
  private BoissonRepository $boissonRepository;
  private CalculStockService $calculStockService;

  public function __construct(BoissonRepository $boissonRepository, CalculStockService $calculStockService) {
    $this->boissonRepository = $boissonRepository;
    $this->calculStockService = $calculStockService;
  }

  public function creerBoisson(array $data, int $role)
  {
    if(!in_array($role, [ROLE_ADMIN, ROLE_EMPLOYE])) throw new AccesRefuseException();
    
    if(empty($data['nom_boisson'])){
      throw new Exception("Veuillez entrer un titre");
    }
    
    $this->existeEnBase($data['nom_boisson']);


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

    if(!empty($data['nom_boisson'])){
      $this->existeEnBase($data['nom_boisson'], $id);
      $data['nom_boisson'] = ucfirst($data['nom_boisson']);
    }

    if(isset($data['prix_boisson'])){
      VerifService::verifNbPositif($data['prix_boisson']);
    }
      
    if(isset($data['stock_boisson'])){
      VerifService::verifNbPositif($data['stock_boisson']);
    }

    $boisson = $this->afficherBoissonParId($id);
    $anciennesDonnes = $boisson->deshydrate();

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

    if(!$this->boissonRepository->trouverBoissonParId($boissonId)){
      throw new IdInnexistantException($boissonId);

      }
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

  private function existeEnBase(string $nom, ?int $id = null)
  {
    $boisson = $this->boissonRepository->trouverBoissonParNom($nom);
    if($boisson && $boisson->getBoissonId() !== $id){
      throw new LibelleExistantException($nom);
    }
  }
}