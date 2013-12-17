<?php

namespace Kazoo\Api\Data;

use stdClass;

/**
 * Entity abstraction
 */
abstract class AbstractEntity {

    protected $_client;
    protected $_uri;

    /**
     * 
     * @param \Kazoo\Client $client
     * @param string $uri
     */
    public function __construct(\Kazoo\Client $client, $uri = null) {
        $this->_client = $client;
        $this->_uri = $uri;
    }

    /**
     * 
     * @param stdClass $result
     * @return \Kazoo\Api\Data\AbstractEntity
     */
    public function updateFromResult(stdClass $result) {

        //Hunt for id
        if (property_exists($result->data, 'id')) {
            $this->id = $result->data->id;
        }

        return $this;
    }

    /**
     * 
     * @return type
     */
    public function __toString() {
        return $this->toJSON();
    }

    /**
     * 
     * @return type
     */
    public function getData() {
        return json_decode($this->toJSON());
    }

    /**
     * 
     * @return json
     */
    public function toJSON() {
        return json_encode($this, false);
    }

    /**
     * 
     * @param string $name
     * @param null|array $arguments
     * @return \Kazoo\Api\Data\AbstractEntity
     */
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