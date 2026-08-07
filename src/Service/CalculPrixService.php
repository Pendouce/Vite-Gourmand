<?php

namespace App\Service;

use App\Exceptions\IdInnexistantException;


class CalculPrixService
{

  public function calculTotalCommande(array $presta, float $prixMenu, int $nbPersonnesMin, int $nbPersonnes, string $adresseVg, string $adresseClient)
  {
    $totalPresta = $this->calculerTotalpresta($presta);
    $totalMenu = $this->calculerTotalMenu($prixMenu, $nbPersonnesMin, $nbPersonnes);
    $prixLivraison = $this->calculerPrixDeLivraison($adresseVg, $adresseClient);

    $totalCommande = round($totalPresta + $totalMenu + $prixLivraison, 2);

    return $totalCommande;
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

  private function calculerTotalpresta(array $presta)
  {
    //$presta = [10, 5, 5];
    $result = array_reduce($presta, fn($sum, $presta) => $sum + $presta, 0);
    //echo $result;
    return $result;
  }

  protected function geocoderAdresse(string $adresse)
  {
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

    [$lon1, $lat1] = $coordoneesDepart;
    [$lon2, $lat2] = $coordoneesArrivee;

    $rayonTerre = 6371; // km

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $rayonTerre * $c;
  }

  private function calculerPrixDeLivraison(string $adresseVg, string $adresseClient)
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