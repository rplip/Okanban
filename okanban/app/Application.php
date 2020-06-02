<?php

class Application {

  private $router;

  public function __construct()
  {
    $this->router = new AltoRouter();
    $this->router->setBasePath($_SERVER['BASE_URI']);
    
    // on va définir dans un fichier à part, nos routes
    // pratique comme ça Application est générique
    // et routes.php est spécifique au projet 
    require 'routes.php';

    
    $this->router->addRoutes($routes);
  }

  public function run()
  {
    $match = $this->router->match();
    if ($match) {
      $controllerToUse = $match['target']['controller'];
      $methodToUse = $match['target']['method'];
      $controller = new $controllerToUse($this->router);
      $controller->$methodToUse($match['params']);
    } else {
      exit('404 Not found');
    }
  }

}