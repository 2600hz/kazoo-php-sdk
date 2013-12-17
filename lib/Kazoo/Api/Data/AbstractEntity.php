<?php

namespace Kazoo\Api\Data;

use stdClass;

abstract class AbstractEntity {

    protected $_client;
    protected $_uri;
    

    public function __construct(\Kazoo\Client $client, $uri = null) {
        $this->_client = $client;
        $this->_uri = $uri;
    }

    public function updateFromResult(stdClass $result) {
        
        //Hunt for id
        if (property_exists($result->data, 'id')) {
            $this->id = $result->data->id;
        }

        return $this;
    }

    public function __toString() {
        return $this->toJSON();
    }

    public function getData() {
        return json_decode($this->toJSON());
    }

    public function toJSON() {
        return json_encode($this, false);
    }

    public function __call($name, $arguments) {
        switch (strtolower($name)) {
            case 'save':
                break;
            case 'delete':
                break;
        }

        return $this;
    }

}