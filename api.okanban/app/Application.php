<?php

namespace Okanban;

// dans une classe dans un namespace, c'est comme si on se déplaçait dans le dossier virtuel
// si on veut utiliser des classes de nos dépendances ou des classes natives de php, on doit  venir les chercher depuis la racine, on doit spécifier le FQCN fully qualified classname === chemin virtuel absolu
// soit dans le corps de la classe on utilise par exemple \Altorouter
// soit on écrit le use avant la classe, les use sont par défaut absolus, on est pas obligé de mettre l'antislash de début
use AltoRouter;
use Dispatcher;

class Application {

  private $router;

  public function __construct()
  {
    $this->router = new AltoRouter();
    // pour éviter une erreur avec alto router dans le cas ou base_uri est vide
    // on vérifie s'il est vide
    if (!empty($_SERVER['BASE_URI'])) {
      $this->router->setBasePath($_SERVER['BASE_URI']);
    }
    
    // on va définir dans un fichier à part, nos routes
    // pratique comme ça Application.php est générique, donc réutilisable de projet en projet
    // et routes.php est spécifique au projet 
    require 'routes.php';
    
    $this->router->addRoutes($routes);
  }

  public function run()
  {
    $match = $this->router->match();
    
    // You can optionnally add a try/catch here to handle Exceptions
    // Instanciate the dispatcher, give it the $match variable and a fallback action
    $dispatcher = new Dispatcher($match, [
      'controller' => '\Okanban\Controllers\ErrorController',
      'method' => 'error404',
    ]);
    // then run the dispatch method which will call the mapped method
    $dispatcher->dispatch();
  }

}