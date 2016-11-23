<?php

namespace Kazoo\Api\Entity;

class SystemConfig extends AbstractEntity
{
    /**
     *
     *
     */
    protected function setEntity($entity = null) {
        if (isset($_ENV['DUMP_ENTITIES'])) {
            $this->getSDK()->logMessage("debug", "set entity: %s", print_r($entity, true));
        }
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
