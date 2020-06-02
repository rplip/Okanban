<?php

namespace Okanban\Models;

use Okanban\Utils\Database;
use PDO;
use JsonSerializable;

// ici je ne peux étendre qu'une classe, j'étends CoreModel
// il se trouve que je peux aussi implémenter des interface, on peut les voir comme des classe presque parent, des oncles
// la différence c'est que je peux implémenter plusieurs interfaces

// JsonSerializable impose la création d'une méthode jsonSerialize
// cette méthode doit retourner la forme de l'objet voulu quand on json encode
class ListModel extends CoreModel implements JsonSerializable {
  
  /**
   * le nom de la liste
   *
   * @var string
   */
  private $name;
  
  /**
   * l'ordre de la liste dans la page
   *
   * @var int
   */
  private $page_order;

  // on peut définir une propriété static, on l'appelera avec self::TABLE ou ListModel::$table
  // une propriété peut être réécrite
  // ici je veux figer ce nom une bonne fois pour toute
  // private static $table = 'list';

  // plutôt que de faire quelque chose variable je vais faire quelque chose de constant
  // une constante s'écrit avec le mot clé const, par convention en majuscule (avec des underscores eventuellement)
  // on stocke une valeur mais on pourra pas la réassigner
  const TABLE = 'list';
  
  // private static $fqcn = '\Okanban\Models\ListModel';
  // redondant car la même chose existe nativement

  /**
   * méthode pour récupérer un objet de type ListModel en fonction de son id dans la table "list"
   * permet de récupérer une liste
   *
   * @param int $id
   * @return ListModel|false
   */

  // si une propriété ou une méthode n'est pas propre à l'instance on peut la mettre en statique, elle sera propre à la classe 
  // on identifie une méthode qui peut être mise en statique facilement, on n'utilise pas $this dedans, $this représente l'objet courant === l'instance, si on  besoin d'y faire référence la méthode ne pourra en aucun cas être statique. A l'utilisation (dans le controller) on devra instancier le model puis executer les méthodes sur l'instance
  // ex: $instance = new Model();
  // $instance->method();
  // Si on n'utilise pas $this dans le méthode on peut mettre la méthode en static
  // elle est donc associée à la classe plutôt qu'aux instances
  // à l'utilisation (dans le controller) on pourra y faire référence directement sur la classe
  // ex: Model::method();
  public static function find(int $id)
  {
    // requete SQL
    // self::TABLE =
    // self = la classe dans laquelle j'écris = ListModel
    // table = la propriété static da ma classe ListModel
    // self::TABLE = list
    $sql = "
      SELECT * 
      FROM `" . self::TABLE . "`
      WHERE `id` = :id
    ";

    // on veut executer la requete
    // pour récupérer l'objet pdo j'utilise la méthode statique getPDO de la classe Database
    // je peux executer la méthode query de mon objet pdo
    // rappel : query retourne les lignes sélectionnées, exec retourne le nombre de lignes affectées
    // $pdoStatement = Database::getPDO()->query($sql);

    // /!\ chaque fois qu'on aura une donnée dynamique on utilse prepare + bindvalue + execute
    // preparation
    $pdoStatement = Database::getPDO()->prepare($sql);
  
    // affectation des jetons
    // parametre 1 = le nom du jeton
    // parametre 2 = la valeur
    // parametre 3 = le type
    $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);

    // execution
    $pdoStatement->execute();

    // on récupère 1 résultat avec fetchObject sous forme d'objet
    // avec fetchObject on doit préciser la forme de l'objet qu'on veut
    // on passe le FQCN en paramètre
    // soit on passe '\Okanban\Models\ListModel'
    // soit utilise self::class self fait référence à la classe dans laquelle on écrit / class fait au FQCN
    
    // on peut récupérer le fqcn avec la propriété statique native (fait par défaut par php) des classes class
    // il exite un identifieur de classe, plutôt que de répéter le nom de la casse on peut utiliser self === la classe dans laquelle on écrit
    // $result = $pdoStatement->fetchObject(ListModel::$fqcn);
    $result = $pdoStatement->fetchObject(self::class);
    // $result = $pdoStatement->fetchObject(self::class);
    // dump($result);

    return $result;
  }

  /**
   * méthode permettant de faire persister une liste dans la bdd
   * on va prendre l'objet courant et le mettre en bdd
   *
   * @return bool
   */
  public function insert() : bool
  {
    // j'ai accès à l'objet courant
    // dump($this);

    // requete sql
    // on déclare une requete, pour persister => INSERT INTO, on précise la table cible cad list
    // on précise entre () les champs qu'on va insérer
    // on spéficie les valeurs avec VALUES()
    // $sql = '
    //   INSERT INTO `list`(`name`, `page_order`) 
    //   VALUES (
    //     "' . $this->name . '",
    //     ' . $this->page_order . '
    //   )
    // ';
    
    // execution
    // exec va retourner le nombre de ligne insérées
    // exececuter une requete sql dans laquelle on concataine des valeurs qui viendront des données saisie par l'utilisateur, c'est très très dangereux
    // on s'expose à d'énormes failles de sécurités
    // NTUI === never trust user input
    /// pour se protéger on utlisera prepare chaque fois qu'on devra concaténer des données dans une requete sql
    // $numRows = Database::getPDO()->exec($sql);

    // je veux récupérer l'id de la ligne insérée
    // j'execute la méthode lastInsertId de PDO,
    // https://www.php.net/manual/fr/pdo.lastinsertid.php
    // $id = Database::getPDO()->lastInsertId();

    // je stocke l'id nouvellement créé sur l'objet courant
    // $this->id = $id;

    // retour
    // on veut retourner true s'il y a au moins une ligne insérée
    // sinon false
    // un test retourne un booléen
    // return $numRows > 0;

    // nouvelle version avec prepare === verison sécurisée

    // requete sql
    // ici je ne concatène rien, je place des jetons (des valeurs avec :) ces jetons pourront être remplacés
    $sql = "
      INSERT INTO `" . self::TABLE . "`(`name`, `page_order`) 
      VALUES (
        :name,
        :page_order
      )
    ";

    // https://www.php.net/manual/fr/pdostatement.bindvalue.php#refsect1-pdostatement.bindvalue-examples
    // on prépare la requête avant de l'executer
    // plutôt qu'exec j'utilise prepare

    // 2 avantages aux requetes préparées :
    // - facile à sécuriser
    // - réutilisable
    $pdoStatement = Database::getPDO()->prepare($sql);

    // on doit remplacer les jetons par leurs valeurs
    // pour ça on utilise bindValue
    // je précise en premier le jeton, ensuite la valeur à prendre, en dernier le type
    $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $pdoStatement->bindValue(':page_order', $this->page_order, PDO::PARAM_INT);

    // on execute
    $pdoStatement->execute();

    // après l'execution, on modifie l'objet courant avec l'id nouvelement inséré
    $this->id = Database::getPDO()->lastInsertId();

    // il faut récupérer le nombre de lignes modifiées
    // https://www.php.net/manual/fr/pdostatement.rowcount.php
    // la méthode rowCount des pdoStatement retourne exactement ça
    $numRows = $pdoStatement->rowCount();

    // retour
    return $numRows > 0;
  }

  /**
   * méthode permettant de mettre à jour en bdd une liste
   * on va prendre l'objet courant
   *
   * @return bool
   */
  public function update() : bool
  {
    // objectif : mettre à jour un enregistrement selon son id (connu sur l'objet courant)
    // définition de la requete
    $sql = "
      UPDATE `" . self::TABLE . "`
      SET
        `name`= :name,
        `page_order`= :page_order,
        `updated_at`= NOW()
      WHERE id = :id
    ";

    // préparation
    $pdoStatement = Database::getPDO()->prepare($sql);

    // assignation des jetons
    $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $pdoStatement->bindValue(':page_order', $this->page_order, PDO::PARAM_INT);
    $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

    // execution
    $pdoStatement->execute();

    // recuparation du nombre de lignes affectées
    $numRows = $pdoStatement->rowCount();

    // retour
    return $numRows > 0;
  }


  /**
   * supprime une liste en bdd
   *
   * @return bool
   */
  public function delete() : bool 
  {
    // objectif : supprimer un enregistrement selon son id
    // définition de la requete
    $sql = "
      DELETE
      FROM `" . self::TABLE . "`
      WHERE id = :id
    ";

    // selon son id === qqch de dynamique === requete préparée
    // préparation
    $pdoStatement = Database::getPDO()->prepare($sql);

    // assignation des jetons
    $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

    // execution
    $pdoStatement->execute();

    // on peut récupérer le nombre de ligne affectées par le dernier execute de l'objet pdoStatement avec rowCount
    $numRows = $pdoStatement->rowCount();

    // retour true ou false selon le nombre de lignes impactées
    return $numRows > 0;
  }

  /**
   * retourne un tableau avec toutes les listes
   *
   * @return array
   */
  public static function findAll() : array
  {
    // objectif : recupérer tous les enregistrements en bdd de la table list
    // déclaration de la requete SQL
    $sql = "
      SELECT * 
      FROM `" . self::TABLE . "`
    ";

    // execution
    $pdoStatement = Database::getPDO()->query($sql);

    // recupération des résultats selon le model ListModel
    // fetchAll permet de récupérer un tableau de résultats
    // PDO::FETCH_CLASS en premier argument permet de dire qu'on veut récupérer un tableau d'objets, ce sera pratique pour avoir accès aux méthodes des objets
    // si on utilise PDO::FETCH_CLASS en 1er argument, il faut préciser en 2ème argument le model selon lequel on veut formatter les résultats, attention il faut le FQCN (chemin virtuel absolu)
    $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

    // retour
    return $results;
  }

  /**
   * Récupère le nom de la liste
   *
   * @return  string
   */ 
  // ici on type le retour, php retournera une erreur si jamais on retourne autre chose q'une chaîne de caractères
  public function getName() : string
  {
    return $this->name;
  }

  /**
   * ce qui suit n'est rien d'autre qu'un commentaire
   * il est là juste pour informer
   * 
   * Modifie le nom de la liste le nom de la liste
   *
   * @param  string  $name  le nom de la liste
   *
   * @return  self
   */ 
  // on type le paramètre, on aura une erreur si on appelle setName avec autre chose q'une string
  public function setName(string $name)
  {
    $this->name = $name;

    // si on retourne l'objet courant modifié, on pourra chaîner les setter
    // sinon si on le fait pas il faudra créer une instruction par setter
    return $this;
  }

  /**
   * Get l'ordre de la liste dans la page
   *
   * @return  int
   */ 
  public function getPageOrder() : int
  {
    return $this->page_order;
  }

  /**
   * Set l'ordre de la liste dans la page
   *
   * @param  int  $page_order  l'ordre de la liste dans la page
   *
   * @return  self
   */ 
  public function setPageOrder(int $page_order)
  {
    $this->page_order = $page_order;

    return $this;
  }

  public function jsonSerialize() : array
  {
    return [
      "page_order" => $this->page_order,
      "name" => $this->name,
      "id" => $this->id,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at
    ];
  }
}