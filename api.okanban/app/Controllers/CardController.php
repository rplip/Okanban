<?php

namespace Okanban\Controllers;

use Okanban\Models\CardModel;

class CardController extends CoreController {

  public function all() 
  {
    $cards = CardModel::findAll();
    $this->showJson($cards);
  }

  public function deleteCard($params) {

    $card = CardModel::find($params);
    if($card->delete() === true){
      $result = [
        'success' => true,
        'error' => ''
      ];
    }else{
      $result = [
        'success' => false,
        'error' => 'Impossible de supprimer la carte'
      ];
    }

    //$jsonResult = json_encode($result);
    $this->showJson($result);
  }

  public function update($id) {
    // objectif : je veux mettre à jour une card identifiée par son id
    // 1. récupération du nouvel ordre, et du nouveau listId
    $listOrder = $_POST['listOrder'];
    $listId = $_POST['listId']; 

    // je convertis ce que je récupère en int, je suis plus rigoureux
    $listOrder = (int)$listOrder;
    $listId = (int)$listId;

    // ou en une seule fois
    // $listOrder = (int)$_POST['listOrder'];
    
    // 2. instanciation de la carte possédant l'id recherché
    $card = CardModel::find($id);
    // si on a rien trouvé
    if($card === false) {
      // je prépare un tableau d'erreur
      $reponse = ["success" => false, "error" => "La carte n'a pas été trouvée"];
    }
    // sinon
    else {
      // 3. modification des propriétés de la carte
      $card->setListOrder($listOrder);
      $card->setListId($listId);

      // 4. persistance en bdd
      $isUpdated = $card->save();

      if($isUpdated) {
        // je prépare un tableau de succès
        $reponse = ["success" => true, "error" => ""];
      }
      // sinon
      else {
        // je prépare un tableau d'erreur
        $reponse = ["success" => false, "error" => "La carte n'a pas été modifiée"];
      }
    }

    // 5. réponse json
    // je renvoie ce tableau en json
    $this->showJson($reponse);
  }

}