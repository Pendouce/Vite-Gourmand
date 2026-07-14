<?php

return[
    "/" => ["controller" => "App\Controller\PageController", "action" => "acceuil"],
    "/inscription/" => ["controller" => "App\Controller\UserController", "action" => "inscription"],
    "/inscriptionEmploye/" => ["controller" => "App\Controller\UserController", "action" => "inscriptionEmploye"],
    "/connexion/" => ["controller" => "App\Controller\UserController", "action" => "connexion"],
];