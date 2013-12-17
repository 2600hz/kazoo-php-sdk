<?php

namespace Kazoo\Api\Data;

use stdClass;

abstract class AbstractEntity {

    protected $_client;
    protected $_uri;
    protected $_data;

    public function __construct(\Kazoo\Client $client, $uri = null) {
        $this->_client = $client;
        $this->_uri = $uri;
        $this->_data = new stdClass();
    }
    
    public function setScaffolding(stdClass $data){
        $this->_data  = $data;
        return $this;
    }

    public function updateFromResults(stdClass $results) {
        //Hunt for id
        if (property_exists($results->data, 'id')) {
            $this->id = $results->data->id;
        }
        
        return $this;
    }

    private function getValueRecursive($root, $search_prop) {
        if (property_exists($root, $search_prop)) {
            return $root->$search_prop;
        } else {
            foreach ($root as $prop => $value) {
                if (is_object($value)) {
                    return $this->getValueRecursive($value, $search_prop);
                }
            }
        }
    }

    private function setValueRecursive($root, $search_prop, $val) {
        if (property_exists($root, $search_prop)) {
            $root->$search_prop = $val;
            return true;
        } else {
            foreach ($root as $prop => $value) {
                if (is_object($value)) {
                    return $this->setValueRecursive($value, $search_prop, $val);
                }
            }
        }
    }

    public function __get($prop) {
        return $this->getValueRecursive($this->_data, $prop);
    }

    public function __set($prop, $val) {
        $this->setValueRecursive($this->_data, $prop, $val);
    }

    public function __toString() {
        return $this->toJSON();
    }

    public function toJSON() {
        return json_encode($this->_data, false);
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