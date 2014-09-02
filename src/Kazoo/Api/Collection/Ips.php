<?php

namespace Kazoo\Api\Collection;

class Ips extends AbstractCollection
{
    public function assigned() {
        $response = $this->get(array(), "/assigned");
        $this->setCollection($response->getData());
        return $this;
    }

    public function zones() {
        $response = $this->get(array(), "/zones");
        $this->setCollection($response->getData());
        return $this;
    }

    public function hosts() {
        $response = $this->get(array(), "/hosts");
        $this->setCollection($response->getData());
        return $this;
    }
}
