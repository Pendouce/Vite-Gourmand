<?php

namespace App\Service;

use App\Exceptions\IdInnexistantException;


class CalculPrixService
{

  public function calculTotalCommande(array $presta, array $menuCommande, array $boissonCommande, string $adresseVg, string $adresseClient)
  {
    $totalPresta = $this->calculerTotalpresta($menuCommande, $presta);
    $totalMenu = 0;
    foreach($menuCommande as $menu){
      $totalMenu += $this->calculerTotalMenu(
        $menu['prix_personne'], 
        $menu['nombre_personne_min'], 
        $menu['nb_personne_menu']);
    }
    $totalBoisson = $this->calculerTotalBoisson($boissonCommande);
    $prixLivraison = $this->calculerPrixDeLivraison($adresseVg, $adresseClient);

    $totalCommande = round($totalPresta + $totalMenu + $totalBoisson + $prixLivraison, 2);

    return $totalCommande;
  }

  private const SEUIL_DE_BASE = 5; // Prix presta fixe commence a partir de 5 prsn
  private const PALIER = 5; // Calcul par 5 personnes (si nbPersonne = 7, presta pour 10)
  private const SUPPLEMENT = 20; // 20€ ajouté par pallier

  public function calculerTotalpresta(array $menuCommande, array $presta)
  {
    $nbPersonnes = $this->calculerNbPersonneCommande($menuCommande);
    $seuil = self::SEUIL_DE_BASE;
    $supplement = 0;

    while($seuil < $nbPersonnes){
      $seuil += self::PALIER;
      $supplement += self::SUPPLEMENT;
    }

    // Je calcul le prix total de chaques prestations (map)
    // Je les additionnent entres eux (sum)
    $result = array_sum(
      array_map(fn($prixPresta) => $prixPresta + $supplement, $presta)
    );

    return $result;
  }

  public function calculerNbPersonneCommande(array $menuCommande)
  {
    // J'additionne le nombre de personnes de chacun des menus de ma commande
    // Puis j'additinne les totaux entre eux pour recuperer le nombre total de personnes de tous les menus
    $totalNbPersonnes = array_sum(
      array_map(fn($menu) => $menu['nb_personne_menu'], $menuCommande)
    );

    return $totalNbPersonnes;
  }

  public function calculerTotalBoisson(array $boissonCommande)
  {

    $result = array_sum(
      array_map(fn($boisson) => $boisson['prix_boisson'] * $boisson['quantite'], $boissonCommande)
    ) ;

    return $result;
  }

  private function calculerTotalMenu(float $prixMenu, int $nbPersonnesMin, int $nbPersonnes)
  {
    $remise = 0.10;
    $prixTotalMenu = $prixMenu * $nbPersonnes;

    if($nbPersonnes >= $nbPersonnesMin + 5){
      $prixTotalMenu = $prixTotalMenu - $prixTotalMenu * $remise;
    }

    return $prixTotalMenu;
  }

  protected function geocoderAdresse(string $adresse)
  {
    // Je recupere une api geocode url encode pour avoir le bon format d'adresse
    $json = file_get_contents('https://data.geopf.fr/geocodage/search?q='.urlencode($adresse));
    $parse = json_decode($json);

    if(empty($parse->features)){
      throw new IdInnexistantException('Adresse');
    }
    
    return ['coordonees' => $parse->features[0]->geometry->coordinates,
     'ville' => $parse->features[0]->properties->city];
  }

  private function calculerDistance(array $coordoneesDepart, array $coordoneesArrivee)
  {
    // Formule de Haversine calcule la distance en km a vol d'oiseau

    [$lon1, $lat1] = $coordoneesDepart;
    [$lon2, $lat2] = $coordoneesArrivee;

    $rayonTerre = 6371; // km

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $rayonTerre * $c;
  }

  public function calculerPrixDeLivraison(string $adresseVg, string $adresseClient)
  {
    $fraisLivraison = 5;
    $geoVg = $this->geocoderAdresse($adresseVg);
    $geoClient = $this->geocoderAdresse($adresseClient);

    if (strtolower($geoClient['ville']) === 'bordeaux') {
        return $fraisLivraison;
    }

    $distanceKm = $this->calculerDistance($geoVg['coordonees'], $geoClient['coordonees']);

    return $fraisLivraison + $distanceKm * 0.59;
  }
}