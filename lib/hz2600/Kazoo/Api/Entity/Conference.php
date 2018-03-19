<?php

namespace Kazoo\Api\Entity;


class Conference extends AbstractEntity
{
    public function dial($data) {
        $envelope = new stdClass();
        $envelope->action = "dial";
        $envelope->data = $data;

        $id = $this->getId();

        $this->execute($envelope)
    }

    public function execute($envelope) {
        if ($this->read_only) {
            throw new ReadOnly("The entity is read-only");
        }

        $id = $this->getId();
        $payload = json_encode($envelope);

        $this->setTokenValue($this->getEntityIdName(), $id);

        $response = $this->put($payload);

        $entity = $response->getData();
        $this->setEntity($entity);

        return $this;
    }
}
