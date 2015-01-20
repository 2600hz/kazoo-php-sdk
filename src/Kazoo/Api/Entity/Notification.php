<?php

namespace Kazoo\Api\Entity;

class Notification extends AbstractEntity
{

    public function preview() {
        $id = $this->getId();
        $payload = $this->getPayload();

        $this->setTokenValue($this->getEntityIdName(), $id);

        $this->post($payload, '/notifications/{entity_id}/preview');
        return $this;
    }
}
