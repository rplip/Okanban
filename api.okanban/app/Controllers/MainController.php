<?php

namespace Okanban\Controllers;

// je dis que la classe ListModel que j'utiliserai dans ma classe fera référénce en fait à Okanban\Models\ListModel
use Okanban\Models\ListModel;
use Okanban\Models\LabelModel;
use Okanban\Models\CardModel;
use Okanban\Models\CoreModel;

class MainController extends CoreController {

  public function home() 
  {
    $this->show('home');
  }

  public function test()
  {
    /*
    echo 'début des tests';

    // j'instancie mon modèle / ou pas, on a passé find en statique donc plus besoin
    // $list = new ListModel();
    // execution de find non statique
    // $list = $list->find(2);

    // j'execute la méthode find avec l'id qui m'intéresse
    // je veux récupérer les infos de la liste possédant l'id 2
    // (c) stéphane : la différence entre :: et -> c'est la portée, liée à la classe ou liée à l'instance
    
   

    $list = ListModel::find(2);
    // je récupère une instance de liste
    echo '<p>$listModel->find(2);</p>';
    dump($list);


    // $autreList = new ListModel();
    $autreList = ListModel::find(3);
    echo '<p>ListModel::find(3)</p>';
    dump($autreList);

    $unknowList = ListModel::find(99);
    echo '<p>ListModel::find(99)</p>';
    dump($unknowList);

    $lists = ListModel::findAll();
    echo '<p> ListModel::findAll();</p>';
    dump($lists);

    // // je crée une nouvelle liste
    // $newList = new ListModel();


    // je veux supprimer la 3ème list
    // je récupère la liste
    // $liste3 = $listModel->find(2);
    // je supprime mon instance
    // dump($liste3->delete());

    // mise à jour
    // récupération
    $list = ListModel::find(1);
    // mise à jour de données
    $list->setName('Hello world');
    $list->setPageOrder(12);
    // persistance
    $list->update();

    // // on simule la récupération de données en post
    // on peut aisément imagine un utilisateur malicieux, mal intentionné
    // qui essayera d'executer ses propres requetes pour des mauvaises fins
    // $_POST['listname'] = '",5); INSERT INTO `list`(`name`, `page_order`) VALUES("JE SUIS UN PIRATE", 5);';
    // ici on fait ce qu'on appelle une injection SQL
    // $listName = $_POST['listname'];


    // $listName = 'Liste démo finale';

    // // je modifie ses propriétés avec les setter
    // $newList->setName( $listName);
    // $newList->setPageOrder(5);
    // on peut éventuellement chainer les setter, si les setter retourne bien $this
    // $newList
    //   ->setName('Découvrir Active Record')
    //   ->setPageOrder(5);

    // je veux la faire persister en bdd
    // $isInseterd = $newList->insert();
    // dump($isInseterd);
    // dump($newList);

    // instanciation
    $newList = new ListModel();
    // modifications
    $newList->setName('PHP');
    $newList->setPageOrder(5);
    // sauvegarde
    // $newList->insert();


    $label = LabelModel::find(2);
    // je récupère une instance de label
    echo '<p>LabelModel::find(2)</p>';
    dump($label);

    $labels = LabelModel::findAll();
    echo '<p>LabelModel::findAll()</p>';
    dump($labels);

    $newLabel = new LabelModel();
    $newLabel->setName('Label toto');
    // peristance des données
    // $newLabel->insert();
    $newLabel->save();


    $label5 = LabelModel::find(4);
    $label5->setName('Label truc');
    // peristance des données
    // $label5->update();
    $label5->save();
    

    // $label5->delete();

    $card = CardModel::find(2);
    $card->setTitle('Card update démo');
    $card->setListOrder(5);
    $card->setListId(1);
    $card->save();
    dump($card);
    */

    $labels = LabelModel::findAll();
    $this->showJson($labels);

  }

}