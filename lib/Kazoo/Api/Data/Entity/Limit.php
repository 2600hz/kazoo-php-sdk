<?php

namespace Kazoo\Api\Data\Entity;

use Kazoo\Api\Data\AbstractEntity;

class Limit extends AbstractEntity {

    protected static $_schema_name = "limits.json";

    public function __construct(\Kazoo\Client $client, $uri, $data = null) {
        parent::__construct($client, $uri, $data);
        $this->single_entity();
        return $this;
    }

    public function initDefaultValues() {

    }
}
