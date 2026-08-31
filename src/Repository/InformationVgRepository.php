<?php

namespace App\Repository;

use App\Entity\ImageSite;
use App\Entity\InformationVg;
use PDO;

class InformationVgRepository extends Repository
{
  public function trouverInfosVg()
  {
    $sql = 'SELECT * FROM information_vg';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $info = $statement->fetch(PDO::FETCH_ASSOC);
    
    return InformationVg::creerEtHydrate($info);
  }

  public function modifierInfosVg(array $data)
  {
    $sql = 'UPDATE information_vg SET 
    adresse = :adresse, telephone = :telephone, email = :email, 
    horaires_semaine = :horaires_semaine, horaires_weekend = :horaires_weekend
    WHERE info_id = 1';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

  public function trouverImagesSite()
  {
    $sql = 'SELECT * FROM image_site';

    $statement = $this->pdo->prepare($sql);
    $statement->execute();

    $data = $statement->fetchAll(PDO::FETCH_ASSOC);
    $tabImage = [];

    foreach($data as $image){
      $tabImage[] = ImageSite::creerEtHydrate($image);
    }
    
    return $tabImage;
  }

  public function trouverImageSiteParId(int $id)
  {
    $sql = 'SELECT * FROM image_site WHERE id = :id';

    $statement = $this->pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    $image = $statement->fetch(PDO::FETCH_ASSOC);

   return ImageSite::creerEtHydrate($image);
  }

  public function modifierImagesSite(array $data)
  {
    $sql = 'UPDATE image_site SET nom_img = :nom_img, chemin = :chemin
    WHERE id = :id';

    $statement = $this->pdo->prepare($sql);
    $statement->execute($data);
  }

}