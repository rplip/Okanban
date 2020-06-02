<?php

// on charge le fichier autoload de composer
// c'est un fichier généré par composer qui permet de récupérer toutes mes dépendances
require __DIR__ . '/../vendor/autoload.php';

// require __DIR__ . '/../app/Controllers/CoreController.php';
// require __DIR__ . '/../app/Controllers/MainController.php';
// require __DIR__ . '/../app/Controllers/ListController.php';
// require __DIR__ . '/../app/Controllers/ErrorController.php';

// require __DIR__ . '/../app/Models/CoreModel.php';
// require __DIR__ . '/../app/Utils/Database.php';

// // ici plutôt que d'avoir plein de code en vrac dans index.php
// // on a fait une classe réutilisable Application
// // on l'inclus ici
// require __DIR__ . '/../app/Application.php';

// // autoload à la main
// function my_autoloader($class) {
//   require __DIR__ . '/../' . str_replace(['Okanban', '\\'], ['app', '/'], $class). '.php';
//   // if($class === 'Okanban\Application') {
//   //   require __DIR__ . '/../app/Application.php';
//   // }
// }

// spl_autoload_register('my_autoloader');

// j'instancie la classe (le constructeur est appelé, je récupère un objet de type Application avec toutes ses propriétés et ses méthodes)
$app = new \Okanban\Application();

// j'execute la méthode run
$app->run();
