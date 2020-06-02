<?php

namespace Okanban\Controllers;


use Okanban\Models\ListModel;

class AddController extends CoreController {


    public function add() 
    {

    $newList = new ListModel();
    $listName = $_POST['listName'];
    
    $newList->setName($listName);
    $newList->setPageOrder(0);
    $isInseterd = $newList->insert();

    $this->showJson($isInseterd);

    }


}