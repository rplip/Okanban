<?php

namespace Okanban\Utils;

use PDO;

// Retenir son utilisation  => Database::getPDO()
// Design Pattern : Singleton
class Database {
    /** @var PDO */
    private $dbh;
    private static $_instance;
    private function __construct() {
        // Récupération des données du fichier de config
        // la fonction parse_ini_file parse le fichier et retourne un array associatif
        $configData = parse_ini_file(__DIR__.'/../config.ini');
        
        try {
            $this->dbh = new PDO(
                "mysql:host={$configData['DB_HOST']};dbname={$configData['DB_NAME']};charset=utf8",
                $configData['DB_USERNAME'],
                $configData['DB_PASSWORD'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING) // Affiche les erreurs SQL à l'écran
            );
        }
        catch(\Exception $exception) {
            echo 'Erreur de connexion...<br>';
            echo $exception->getMessage().'<br>';
            echo '<pre>';
            echo $exception->getTraceAsString();
            echo '</pre>';
            exit;
        }
    }
    // the unique method you need to use
    // à l'appelle de cette méthode
    // si la propriété $_instance est vide on va la renseigner
    // sinon on la retourne directement
    // $_instance est une propriété statique, relative à la classe, une fois définie on la redéfinira plus
    // c'est à dire que cette classe va s'auto instancier une fois, et une fois seulement
    // on retournera ensuite directement la propriété de l'instance qui nous intéresse
    public static function getPDO() {
        // If no instance => create one
        if (empty(self::$_instance)) {
            self::$_instance = new Database();
        }
        // ce qu'on retourne ce n'est pas directement l'instance
        // mais sa propriété dbh, c'est à dire l'objet pdo
        return self::$_instance->dbh;
    }
}