<?php

namespace Okanban\Controllers;

use Okanban\Models\ListModel;

class ListController extends CoreController {

  public function all() 
  {
    // $list = ListModel::find(1);
    // $this->showJson($list);

    // problème je veux faire getAllInfos sur tous les objets de mon tableau retourné par findAll
    // solution envisagée 1 : foreach, ok bonne idée
    // solution envisagée 2 : découvrir JsonSerializable
    // https://www.php.net/manual/fr/class.jsonserializable.php
    // à priori en utilisant ça, on peut décrire comment notre objet (par exemple une instance de ListModel) doit être représenté lorsqu'on le passe dans json_encode

    // une interface ressemble beaucoup a une classe abstraite
    // sauf que on ne va pas créer d'enfant, on ne pas les étendre
    // on va les implémenter
    // on ne peut étendre qu'une seule classe

    $lists = ListModel::findAll();
    $this->showJson($lists);
  }

  public function updateList ($params) {

    $list = ListModel::find($params);
    if(isset($list)) {
      $updateList = new ListModel();
      $listName = $_POST['listName'];
  
      $updateList->setName($listName);
      $updateList->setPageOrder(0);
      $isUpdated = $updateList->update();
    }
    

    $this->showJson($isUpdated);
  }

}