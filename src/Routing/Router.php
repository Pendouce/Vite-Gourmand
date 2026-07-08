<?php


namespace App\Routing;

use Exception;
use Throwable;

class Router
{
  private array $route;

  public function __construct()
  {
    $this->route = require_once APP_ROOT."/config/route.php";
  }

  public function GererRequette(string $uri):void
  {
    try{
      $path = $this->nettoyageUri($uri);

      if(!isset($this->route[$path])){
        // fetaure/gestion d'erreur
        throw new Exception("La route n'existe pas");
      }

      $route = $this->route[$path];
      $controllerPath = $route["controller"];
      $action = $route["action"];

      if(!class_exists($controllerPath)){
      //Remplacer par une exeption fetaure/gestion d'erreur
        throw new Exception("La classe n'existe pas");
      }
      $controller= new $controllerPath();

      if(!method_exists($controllerPath, $action)){
        //Remplacer par une exeption fetaure/gestion d'erreur
        throw new Exception("La methode n'existe pas");
      }
      $controller->$action();
    } catch(Throwable $e){
      // fetaure/gestion d'erreur
        //$errorController = new ErreurController();
        //$errorController->show($e->getMessage());
    }
  }

    public static function nettoyageUri(string $uri):string
    {
      $path = parse_url($uri, PHP_URL_PATH);
      $path = rtrim($path, "/")."/";

      return $path;
    }

    public static function routeActive(string $path):bool
    {
      self::nettoyageUri($path);
      
      return self::nettoyageUri($_SERVER["REQUEST_URI"]) === $path;
    }
}