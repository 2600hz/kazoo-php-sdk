<?php

namespace Kazoo\Api\Entity;

class Config extends AbstractEntity
{
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
