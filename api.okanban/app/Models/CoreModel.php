<?php

namespace Okanban\Models;

/*
Une classe peut être abstraite, c'est à dire qu'elle n'a pas pour vocation d'être instanciée directement, elle a pour vocation d'être étendue, on instanciera plutôt ses enfants
C'était déjà le cas du CoreModel, mais si on veut vérouiller, cad obtenir une erreur si on essaye de le faire malgré tout on indique abstract devant class
*/
abstract class CoreModel {
    protected $id;
    protected $created_at;
    protected $updated_at;
  
    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
    /**
     * Get the value of created_at
     */ 
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    /**
     * Get the value of updated_at
     */ 
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * methode pour décidier si on update ou si on insert
     * pratique à l'utilisation on save sans reflechir
     */
    public function save() {
        // si l'id existe sur l'objet courant
        if (isset($this->id)) {
            // je mets à jour et on retourne la valeur de retour du update
            return $this->update();
        }
        // sinon
        else {
            // j'insère
            return $this->insert();
        }
    }

    
    /**
     * On peut définir des méthodes abstraites, dans une classe parent, ainsi les enfants devront impérativement définir ces méthodes
     * on impose des choses aux classes enfants
     * ici chaque classe qui étend CoreModel DEVRA définir une méthode insert sinon on aura une erreur
     */
    abstract public function insert();
    abstract public function update();
    abstract public function delete();
    abstract public static function find(int $id);
    abstract public static function findAll();
}