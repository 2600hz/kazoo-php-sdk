<?php

namespace Kazoo\Api\Entity;

class Config extends AbstractEntity
{
    /**
     * Saves the current entity, if it does not have an
     * id then it will be created.
     *
     */
    public function save($append_uri = null) {
        return parent::save($append_uri,"patch");
    }

    /**
     * Automagic used to determine the URL snippet.
     * This should be implemented in the entity class if
     * it is an exception to the standard naming.
     *
     */
    protected function getUriSnippet() {
        $collectionName = $this->getCollectionName();
        $entityId = $this->getId();
        
        if (isset($entityId)) {
            $entityId = str_replace("configs_","",$entityId);
            return "/$collectionName/$entityId";
        } else {
            $entityIdName = $this->getEntityIdName();
        }
        return "/$collectionName/{{$entityIdName}";
    }


}    
