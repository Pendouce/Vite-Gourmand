<?php

return[
    "/" => ["controller" => "App\Controller\PageController", "action" => "acceuil"],
    "/inscription/" => ["controller" => "App\Controller\UserController", "action" => "inscription"],
    "/inscriptionEmploye/" => ["controller" => "App\Controller\UserController", "action" => "inscriptionEmploye"],
    "/connexion/" => ["controller" => "App\Controller\UserController", "action" => "connexion"],
    "/mesInfos/" => ["controller" => "App\Controller\UserController", "action" => "afficheInfos"],
    "/modifierInfos/" => ["controller" => "App\Controller\UserController", "action" => "modifierInfos"],
    "/modificationMotDePasse/" => ["controller" => "App\Controller\UserController", "action" => "modifierMdp"],
    "/motDePasseOublie/" => ["controller" => "App\Controller\UserController", "action" => "reinitialiserMdp"],
    "/gestionEmployes/" => ["controller" => "App\Controller\UserController", "action" => "afficheEmploye"],
    "/detailEmploye/" => ["controller" => "App\Controller\UserController", "action" => "afficheInfosEmploye"],
    "/supprimerMonCmpte/" => ["controller" => "App\Controller\UserController", "action" => "supprimerCompteUtilisateur"],
    "/supprimerCompteEmploye/" => ["controller" => "App\Controller\UserController", "action" => "supprimerCompteEmploye"],
    "/creeTypeDePlats/" => ["controller" => "App\Controller\TypeDePlatController", "action" => "creerTypeDePlat"],
    "/plats/" => ["controller" => "App\Controller\TypeDePlatController", "action" => "afficherTypeDePlat"],
    "/modifierTypeDePlat/" => ["controller" => "App\Controller\TypeDePlatController", "action" => "modifierTypeDePlat"],
    "/supprimerTypeDePlat/" => ["controller" => "App\Controller\TypeDePlatController", "action" => "supprimerTypeDePlat"],
    
];