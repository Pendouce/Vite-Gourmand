<?php

namespace App\Service;

use App\Exceptions\IdInnexistantException;
use App\Exceptions\LibelleExistantException;
use App\Repository\BoissonRepository;

class BoissonService
{
  private BoissonRepository $boissonRepository;
  private CalculStockService $calculStockService;

  public function __construct(BoissonRepository $boissonRepository, CalculStockService $calculStockService) {
    $this->boissonRepository = $boissonRepository;
    $this->calculStockService = $calculStockService;
  }

  public function creerBoisson(array $data)
  {
    $this->existeEnBase($data['nom_boisson']);

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

    public function modifierBoisson(int $id, array $data)
  {
    if(!empty($data['nom_boisson'])){
      $this->existeEnBase($data['nom_boisson']);
    }

    $boisson = $this->afficherBoissonParId($id);
    $anciennesDonnes = $boisson->deshydrate();

    $data = array_filter($data, fn($value) => $value !== null);
    $nouvelleDonnees = array_merge($anciennesDonnes, $data);
    //unset($nouvelleDonnees['boisson_id']);

    //var_dump($nouvelleDonnees);
    //var_dump($data['alcool']);


    $this->boissonRepository->modifierBoisson($nouvelleDonnees);
  }

  public function modifierStatusBoisson(int $boissonId, int $status)
  {
    $this->boissonRepository->modifierStatusBoisson($boissonId, $status);
  }

  public function modifierStockBoisson(int $boissonId, int $stock)
  {
    $this->boissonRepository->modifierStockBoisson($boissonId, $stock);
  }

  public function supprimerBoisson(int $boissonId)
  {
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

  private function existeEnBase(string $nom)
  {
    if($this->boissonRepository->trouverBoissonParNom($nom)){
      throw new LibelleExistantException($nom);
    }
  }
}