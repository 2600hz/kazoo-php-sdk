<?php

namespace Kazoo\Api\Collection;

class Devices extends AbstractCollection
{
    public function status() {
        $response = $this->get(array(), '/status');
        $this->setCollection($response->getData());
        return $this;
    }

    public function ownedBy($userId) {
        $response = $this->get(array(), '/owned_by/'.$userId);
        $this->setCollection($response->getData());
        return $this;
    }
}
