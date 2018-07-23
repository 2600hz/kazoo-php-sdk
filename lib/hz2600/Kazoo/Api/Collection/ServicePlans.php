<?php

namespace Kazoo\Api\Collection;

class ServicePlans extends AbstractCollection
{
    public function available() {
        $response = $this->get(array(),'/available');
        $this->setCollection($response->getData());
        return $this;
    }
}
