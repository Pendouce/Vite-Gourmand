<?php


namespace App\Routing;

use App\Controller\ErreurController;
use App\Exceptions\RouteIntrouvableException;
use App\Exceptions\ControllerIntrouvableException;
use App\Exceptions\MethodeIntrouvableException;
use Throwable;

class Router
{
  private array $route;

  public function __construct()
  {
    $this->route = require_once APP_ROOT."/config/routes.php";
  }

  public function GererRequette(string $uri):void
  {
    try{
      $path = $this->nettoyageUri($uri);

      if(!isset($this->route[$path])){
        throw new RouteIntrouvableException($path);
      }

      $route = $this->route[$path];
      $controllerPath = $route["controller"];
      $action = $route["action"];

      if(!class_exists($controllerPath)){
        throw new ControllerIntrouvableException($controllerPath);

      }
      $controller= new $controllerPath();

      if(!method_exists($controller, $action)){
        throw new MethodeIntrouvableException($controllerPath, $action);

      }
      $controller->$action();
    } catch(Throwable $e){
        $errorController = new ErreurController();
        $errorController->afficheErreur($e);
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