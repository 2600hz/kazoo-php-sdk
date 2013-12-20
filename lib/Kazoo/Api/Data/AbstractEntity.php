<?php

namespace Kazoo\Api\Data;

use stdClass;

/**
 * Entity abstraction
 */
abstract class AbstractEntity {

    protected $_client;
    protected $_uri;
    protected $_data;
    protected $_state = NULL;

    const STATE_EMPTY = 'EMPTY';
    const STATE_HYDRATED = 'HYDRATED';
    
    /**
     * 
     * @param \Kazoo\Client $client
     * @param string $uri
     */
    public function __construct(\Kazoo\Client $client, $uri, $data = null) {
        $this->_client = $client;
        $this->_uri = $uri;
        $this->_data = new stdClass();
        $this->changeState(self::STATE_EMPTY);
    }

    /**
     * 
     * @param stdClass $data
     */
    private function setData(stdClass $data){
        $this->_data = array_replace_recursive($this->_data, $data);
    }
    
    /**
     * 
     * @param type $state
     */
    private function changeState($state){
        $this->_state = $state;
    }
    
    /**
     * 
     * @param stdClass $result
     * @return \Kazoo\Api\Data\AbstractEntity
     */
    public function updateFromResult(stdClass $result) {
        $this->setData($result);
        $this->changeState(self::STATE_HYDRATED);
        return $this;
    }

    /**
     * 
     * @param type $prop
     * @return type
     */
    public function __get($prop){
        if(property_exists($this->_data, $prop)){
            return $this->_data->$prop;
        }
    }
    
    /**
     * 
     * @param type $prop
     * @param type $value
     */
    public function __set($prop, $value){
        $this->_data->$prop = $value;
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
        return $this->_data;
    }

    /**
     * 
     * @return json
     */
    public function toJSON() {
        return json_encode($this->getData(), false);
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