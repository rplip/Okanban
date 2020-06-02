<?php

namespace Okanban\Models;

use Okanban\Utils\Database;
use PDO;
use JsonSerializable;

class LabelModel extends CoreModel implements JsonSerializable  {

  /**
   * name
   *
   * @var string
   */
  private $name;

  const TABLE = 'label';


  public static function find(int $id)
  {
    $sql = "
      SELECT * 
      FROM `" . self::TABLE . "`
      WHERE `id` = :id
    ";

    $pdoStatement = Database::getPDO()->prepare($sql);
    $pdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
    $pdoStatement->execute();

    $result = $pdoStatement->fetchObject(self::class);

    return $result;
  }

  public function insert() : bool
  {
    $sql = "
      INSERT INTO `" . self::TABLE . "`(`name`) 
      VALUES (
        :name
      )
    ";

    $pdoStatement = Database::getPDO()->prepare($sql);

    $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);

    $pdoStatement->execute();

    $this->id = Database::getPDO()->lastInsertId();

    $numRows = $pdoStatement->rowCount();

    return $numRows > 0;
  }

  public function update() : bool
  {
    $sql = "
      UPDATE `" . self::TABLE . "`
      SET
        `name`= :name,
        `updated_at`= NOW()
      WHERE id = :id
    ";

    $pdoStatement = Database::getPDO()->prepare($sql);

    $pdoStatement->bindValue(':name', $this->name, PDO::PARAM_STR);
    $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);

    $pdoStatement->execute();

    $numRows = $pdoStatement->rowCount();

    return $numRows > 0;
  }

  public function delete() : bool 
  {
    $sql = "
      DELETE
      FROM `" . self::TABLE . "`
      WHERE id = :id
    ";

    $pdoStatement = Database::getPDO()->prepare($sql);
    $pdoStatement->bindValue(':id', $this->id, PDO::PARAM_INT);
    $pdoStatement->execute();

    $numRows = $pdoStatement->rowCount();

    return $numRows > 0;
  }

  public static function findAll() : array
  {
    $sql = "
      SELECT * 
      FROM `" . self::TABLE . "`
    ";

    $pdoStatement = Database::getPDO()->query($sql);

    $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

    return $results;
  }


  /**
   * Get name
   *
   * @return  string
   */ 
  public function getName() : string
  {
    return $this->name;
  }

  /**
   * Set name
   *
   * @param  string  $name  name
   *
   * @return  self
   */ 
  public function setName(string $name)
  {
    $this->name = $name;

    return $this;
  }

  public function jsonSerialize() 
  {
    return [
      "name" => $this->name,
      "id" => $this->id,
      "created_at" => $this->created_at,
      "updated_at" => $this->updated_at,
    ];
  }
  
}