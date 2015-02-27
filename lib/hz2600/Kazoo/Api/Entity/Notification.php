<?php

namespace Kazoo\Api\Entity;

class Notification extends AbstractEntity
{

    public function preview($data = array()) {
        $id = $this->getId();

        $this->setTokenValue($this->getEntityIdName(), $id);

        $this->post($data, '/preview');
        return $this;
    }
}
