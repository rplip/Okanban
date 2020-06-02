<?php

namespace Okanban\Models;

use JsonSerializable;
use Okanban\Utils\Database;
use PDO;

class CardModel extends CoreModel implements JsonSerializable{

  /**
   * title
   *
   * @var string
   */
  private $title;

  /**
   * list_order
   *
   * @var int
   */
  private $list_order;

  /**
   * list_id
   *
   * @var int
   */
  private $list_id;

  const TABLE = 'card';

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
      INSERT INTO `" . self::TABLE . "`(`title`, `list_order`, `list_id`) 
      VALUES (
        :title,
        :list_order,
        :list_id
      )
    ";

    $pdoStatement = Database::getPDO()->prepare($sql);

    $pdoStatement->bindValue(':title', $this->title, PDO::PARAM_STR);
    $pdoStatement->bindValue(':list_order', $this->list_order, PDO::PARAM_INT);
    $pdoStatement->bindValue(':list_id', $this->list_id, PDO::PARAM_INT);

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
        `title`= :title,
        `list_order`= :list_order,
        `list_id`= :list_id,
        `updated_at`= NOW()
      WHERE id = :id
    ";

    $pdoStatement = Database::getPDO()->prepare($sql);

    $pdoStatement->bindValue(':title', $this->title, PDO::PARAM_STR);
    $pdoStatement->bindValue(':list_order', $this->list_order, PDO::PARAM_INT);
    $pdoStatement->bindValue(':list_id', $this->list_id, PDO::PARAM_INT);
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
      ORDER BY `list_order`
    ";

    $pdoStatement = Database::getPDO()->query($sql);

    $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, self::class);

    return $results;
  }


  /**
   * Get title
   *
   * @return  string
   */ 
  public function getTitle() : string
  {
    return $this->title;
  }

  /**
   * Set title
   *
   * @param  string  $title  title
   *
   * @return  self
   */ 
  public function setTitle(string $title)
  {
    $this->title = $title;

    return $this;
  }

  /**
   * Get list_order
   *
   * @return  int
   */ 
  public function getListOrder() : int
  {
    return $this->list_order;
  }

  /**
   * Set list_order
   *
   * @param  int  $list_order  list_order
   *
   * @return  self
   */ 
  public function setListOrder(int $list_order)
  {
    $this->list_order = $list_order;

    return $this;
  }

  /**
   * Get list_id
   *
   * @return  int
   */ 
  public function getListId() : int
  {
    return $this->list_id;
  }

  /**
   * Set list_id
   *
   * @param  int  $list_id  list_id
   *
   * @return  self
   */ 
  public function setListId(int $list_id)
  {
    $this->list_id = $list_id;

    return $this;
  }

  public function jsonSerialize()
  {
    return [
          "id" => $this->id,
          "title" => $this->title,
          "list_id" => $this->list_id,
          "list_order" => $this->list_order,
          "created_at" => $this->created_at,
          "updated_at" => $this->updated_at
    ];
  }


}