<?php

return[
    "/" => ["controller" => "App\Controller\PageController", "action" => "acceuil"],
    "/inscription/" => ["controller" => "App\Controller\UserController", "action" => "inscription"],
    "/inscriptionEmploye/" => ["controller" => "App\Controller\UserController", "action" => "inscriptionEmploye"],
    "/connexion/" => ["controller" => "App\Controller\UserController", "action" => "connexion"],
    "/mesInfos/" => ["controller" => "App\Controller\UserController", "action" => "afficheInfos"],
    "/modifierInfos/" => ["controller" => "App\Controller\UserController", "action" => "modifierInfos"],
];