<?php

namespace Kazoo\Api\Entity;

class Connectivity extends AbstractEntity
{
    protected function getCollectionName(){
        return "connectivity";
    }

    /**
     * Saves the current entity, if it does not have an
     * id then it will be created.
     *
     */
    public function save($append_uri = null) {
        return parent::save($append_uri,"patch");
    }

}    
