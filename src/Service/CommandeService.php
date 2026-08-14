<?php

namespace App\Service;

use App\Repository\CommandePrestaRepository;
use App\Repository\CommandeRepository;
use App\Repository\CommandeMenuRepository;
use App\Repository\MenuRepository;
use App\Repository\PrestationRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeZone;
use Exception;

class CommandeService
{
  private CommandeRepository $commandeRepository;
  private CommandePrestaRepository $commandePrestaRepository;
  private CommandeMenuRepository $commandeMenuRepository;
  private MenuRepository $menuRepository;
  private PrestationRepository $prestationRepository;
  private UserRepository $userRepository;
  private CalculPrixService $calculPrixService;
  private MailService $mailService;
  private MenuService $menuService;

  public function __construct(CommandeRepository $commandeRepository, CommandePrestaRepository $commandePrestaRepository, 
  CommandeMenuRepository $commandeMenuRepository, MenuRepository $menuRepository, 
  PrestationRepository $prestationRepository, UserRepository $userRepository,
  CalculPrixService $calculPrixService, MailService $mailService,
  MenuService $menuService)
  {
    $this->commandeRepository = $commandeRepository;
    $this->commandePrestaRepository = $commandePrestaRepository;
    $this->commandeMenuRepository = $commandeMenuRepository;
    $this->menuRepository = $menuRepository;
    $this->prestationRepository = $prestationRepository;
    $this->calculPrixService = $calculPrixService;
    $this->userRepository = $userRepository;
    $this->mailService = $mailService;
    $this->menuService = $menuService;
  }

  public function creerCommande(array $data, array $dataMenu, array $dataPresta, float $prixTotalPresta, array $dataUser)
  {
    $user = $this->userRepository->trouveUtilisateurById($data['user_id']);
    if($dataUser['nom'] !== $user->getNom()||
        $dataUser['prenom'] !== $user->getPrenom() ||
        $dataUser['email'] !== $user->getEmail()){
      throw new Exception('Les informations ne correspondent pas');
    }

    // Je boucle sur les menus
    // Je verifie que chacun des menus selectionée a le nombre de personnes minimum requis
    $menusCommande = [];
    foreach($dataMenu as $menu){
      $menuParId = $this->menuRepository->trouverMenuParId($menu['menu_id']);
      $nbPersMin = $menuParId->getNombrePersonneMin();

      if($menu['nb_personne_menu'] < $nbPersMin){
        throw new Exception('Nombre de personnes minimum '. $nbPersMin .' pour le menu '. $menuParId->getTitre());
      }

      // Tablau des menus de ma commande
      $menusCommande[] =
        // Infos d'UN menu
        [
          'prix_personne' => $menuParId->getPrixPersonne(), 
          'nombre_personne_min' => $nbPersMin, 
          'nb_personne_menu' => $menu['nb_personne_menu']
        ];

      $this->menuService->verifStockDispo($menu['menu_id'], $menu['nb_personne_menu']);
    }

    // Je boucle sur mes prestation pour recuperer le prix de chacunes d'entres elles
    $prixPresta = [];
    foreach($dataPresta as $presta){
      $prestationParId = $this->prestationRepository->trouverPrestationparId($presta['prestation_id']);
      $prixPresta[] = $prestationParId->getPrixPresta();
    }

    $dateLivraison = new DateTimeImmutable($data['date_livraison']);
    $delaisMinimum = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))->modify("+5 days");

    if($dateLivraison < $delaisMinimum){
      throw new Exception('Le delais minimum entre la commande et la livraison est de 5 jours');
    }

    if($prixTotalPresta != $this->calculPrixService->calculerTotalpresta($menusCommande, $prixPresta)){
      throw new Exception('Erreur prix total presta '. $this->calculPrixService->calculerTotalpresta($menusCommande, $prixPresta));
    }

    if($data['prix_livraison'] != $this->calculPrixService->calculerPrixDeLivraison(ADRESSE_VG , $data['lieu_livraison'])){
      throw new Exception('Erreur de prix livraison');
    }

    if($data['prix_total'] != $this->calculPrixService->calculTotalCommande($prixPresta, $menusCommande, ADRESSE_VG, $data['lieu_livraison'])){
      throw new Exception('Erreur de prix total');
    }

    $data['date_commande'] = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'))->format('Y-m-d H:i:s');
    $data['status_id'] = 1;
    $data['nb_commande'] = $this->genererNbCommande();
    $data['nb_personne'] = $this->calculPrixService->calculerNbPersonneCommande($menusCommande);

   

    //Envoie du mail de confirmation

    return $this->commandeRepository->creerCommande($data);
  }

  public function ajouterPrestaCommande(int $commandeId, float $prixTotalPresta, array $dataPresta)
  {
    $presta = [];
    foreach($dataPresta as $data){
      $datePresta = new DateTimeImmutable($data['date_presta'], new DateTimeZone('Europe/Paris'));
  
      $data['date_presta'] = $datePresta->format('Y-m-d H:i:s');
      $data['date_retour_prevu'] = $datePresta->modify('+10 days')->format('Y-m-d H:i:s');
      $data['commande_id'] = $commandeId;
      $data['date_retour'] = null;
      $data['taux_retard'] = 600;
      $data['prix_total_presta'] = $prixTotalPresta;

      //var_dump($data);
      $presta[] = $this->commandePrestaRepository->ajouterPrestaCommande($data);
      
    }

    return $presta;
  }

  public function ajouterMenuCommande(int $commandeId, array $dataMenu)
  {
    $menu = [];
    foreach($dataMenu as $data){
      $data['commande_id'] = $commandeId;
      $menu[] = $this->commandeMenuRepository->ajouterMenuCommande($data);

      // Je decremente le stock des plats du menu commandé
      $this->menuService->stockePlat($data['menu_id'], $data['nb_personne_menu']);
    }

    return $menu;
  }

  private function genererNbCommande(){
    $nbGenere = rand(100, 9999);
    while($this->commandeRepository->trouverCommandeParNb($nbGenere)){
      $nbGenere = rand(100, 9999);
    }
    return $nbGenere;
  }

}
