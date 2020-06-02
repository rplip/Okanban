<?php

class ListController extends CoreController {

  public function all() 
  {
    $lists = [
      [
        "id" => 1,
        "name" => "Perso",
      ],
      [
        "id" => 2,
        "name" => "PHP",
      ],
      [
        "id" => 4,
        "name" => "Javascript",
      ],
    ];

    $this->showJson($lists);
  }

}