<?php

namespace Kazoo\Api\Entity;

class SystemConfig extends AbstractEntity
{
    public function save($append_uri = null) {
        $id = $this->getId();
        $payload = $this->getPayload();
        $this->setTokenValue($this->getEntityIdName(), $id);
        $response = $this->post($payload, $append_uri);

        $entity = $response->getData();
        $this->setEntity($entity);

        return $this;
    }   
}
