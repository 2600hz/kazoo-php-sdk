<?php

namespace Kazoo\Api\Collection;

class Devices extends AbstractCollection
{
    public function status() {
        $response = $this->get(array(), '/status');
        $this->setCollection($response->getData());
        return $this;
    }
}
