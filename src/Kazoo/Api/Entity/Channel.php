<?php

namespace Kazoo\Api\Entity;

class Channel extends AbstractEntity
{
    public function execute($data) {
        $id = $this->getId();
        $this->setTokenValue($this->getEntityIdName(), $id);
        $this->post($data);
        return $this;
    }
}
