<?php

// on charge le fichier autoload de composer
// c'est un fichier généré par composer qui permet de récupérer toutes mes dépendances
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../app/Controllers/CoreController.php';
require __DIR__ . '/../app/Controllers/MainController.php';
require __DIR__ . '/../app/Controllers/ListController.php';
require __DIR__ . '/../app/Models/CoreModel.php';
require __DIR__ . '/../app/Utils/Database.php';

require __DIR__.'/../app/Application.php';

$app = new Application();

$app->run();