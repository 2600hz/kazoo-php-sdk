<?php

namespace Kazoo\Api\Collection;

class VMBoxes extends AbstractCollection
{
    public function messages() {
        $response = $this->get(array(), '/messages');
        $this->setCollection($response->getData());
        return $this;
    }
}
