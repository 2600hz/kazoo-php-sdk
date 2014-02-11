<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class User extends AbstractEntity {

    public function __construct(\Kazoo\Client $client, $uri, $data = null) {
        $this->_callflow_module = "user";
        parent::__construct($client, $uri, $data);
    }

}