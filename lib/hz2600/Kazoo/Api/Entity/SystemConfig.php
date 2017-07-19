<?php

namespace Kazoo\Api\Entity;

class SystemConfig extends AbstractEntity
{
    /**
     *
     *
     */
    protected function setEntity($entity = null) {
        $this->getSDK()->logEntity("debug", "setEntity", $entity);
        $this->entity = $entity;
        if (!empty($entity->id)) {
            $this->setId($entity->id);
        } elseif (!empty($this->entity_id)){
            return;
        } else {
            $this->setId();
        }
    }
}
